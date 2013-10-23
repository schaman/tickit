<?php

namespace Tickit\CoreBundle\Form\Type\Picker;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Abstract Picker field type.
 *
 * This field type is used to create a picker component for entities in
 * the application.
 *
 * @package Tickit\CoreBundle\Form\Type
 * @author  Mark Wilson <mark@89allport.co.uk>
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractPickerType extends AbstractType
{
    const NO_RESTRICTION = 'none';

    /**
     * An entity converter
     *
     * @var EntityConverterInterface
     */
    protected $converter;

    /**
     * Constructor.
     *
     * @param EntityConverterInterface $entityConverter An entity converter
     */
    public function __construct(EntityConverterInterface $entityConverter)
    {
        $this->converter = $entityConverter;
    }

    /**
     * Build the form
     *
     * @param FormBuilderInterface $builder Form builder
     * @param array                $options Form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: move attribute generation to a separate attribute builder class
        //       use values available in $builder to get the current content value to translate into display values

        // initialise text field's attributes
        $attributes = array(
            'data-restriction' => $options['picker_restriction']
        );

        // if there is a restriction, set the foreign ID in on the input field
        if ($options['picker_restriction'] !== self::NO_RESTRICTION) {
            $attributes['data-foreign-id'] = $options['foreign_id'];
        }

        $builder->add(
            $this->getFieldName(),
            'text',
            array(
                'attr' => $attributes
            )
        );
    }

    /**
     * Build form's view
     *
     * @param FormView      $view    Form view
     * @param FormInterface $form    Form to build
     * @param array         $options Form options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        /** @var Form $element */
        $element = $form->get($this->getFieldName());
        $value   = $element->getData();

        if (!is_array($value)) {
            $value = explode(',', $value);
        }

        $value = array_map(
            function ($id) {
                try {
                    return $this->converter->convert($id);
                } catch (EntityNotFoundException $e) {
                    return false;
                }
            },
            $value
        );

        $value = array_filter($value);

        $value               = implode(',', $value);
        $view->displayValues = $value;
    }

    /**
     * Set default field options
     *
     * @param OptionsResolverInterface $resolver An options resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // foreign Id is only relevant when there is a restriction set
        $resolver->setDefaults(
            array(
                'picker_restriction' => $this->getRestriction(),
                'foreign_id'         => null,
                'compound'           => true
            )
        );
    }

    /**
     * Gets the name of the field that stores the picker IDs
     *
     * @return mixed
     */
    abstract public function getFieldName();

    /**
     * Gets the restriction type (if any)
     *
     * @return string|null
     */
    abstract public function getRestriction();
}
 