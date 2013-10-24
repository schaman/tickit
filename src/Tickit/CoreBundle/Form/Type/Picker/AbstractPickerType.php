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
    /**
     * No restrictions.
     *
     * This indicates that multiple selections can be made.
     */
    const RESTRICTION_NONE = 'none';

    /**
     * Single selection restriction.
     *
     * This indicates that only one selection can be made.
     */
    const RESTRICTION_SINGLE = 'single';

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

        // initialise text field's attributes
        $attributes = array(
            'data-restriction' => $options['picker_restriction']
        );

        // we set the restriction as a data-* attribute, this lets the JS do the
        // client side restriction and our field validator do the server-side work
        if ($options['picker_restriction'] !== self::RESTRICTION_NONE) {
            $attributes['data-restriction'] = $options['picker_restriction'];
        }

        $builder->add($this->getFieldName(), 'text', ['attr' => $attributes]);
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
     *
     * @throws \InvalidArgumentException If an invalid restriction type is specified
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $restriction = $this->getRestriction();

        if (!in_array($restriction, [static::RESTRICTION_SINGLE, static::RESTRICTION_NONE])) {
            throw new \InvalidArgumentException(
                sprintf('An invalid restriction type was specified (%s)', $restriction)
            );
        }

        $resolver->setDefaults(
            array(
                'picker_restriction' => $restriction,
                'compound'           => true
            )
        );
    }

    /**
     * Get extended field type
     *
     * @return string
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * Gets the restriction type.
     *
     * By default pickers have no restriction, meaning multiple selections
     * can be made.
     *
     * @return string
     */
    public function getRestriction()
    {
        return static::RESTRICTION_NONE;
    }

    /**
     * Gets the name of the field that stores the picker IDs
     *
     * @return mixed
     */
    abstract public function getFieldName();
}
 