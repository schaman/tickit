<?php

namespace Tickit\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Add/edit project form.
 *
 * Provides functionality for adding/editing project entities.
 *
 * @package Tickit\ProjectBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectFormType extends AbstractType
{
    /**
     * The attribute value form
     *
     * @var AttributeValueFormType
     */
    protected $attributeValueForm;

    /**
     * Constructor.
     *
     * @param AttributeValueFormType $attributeValueForm The attribute value form that this form depends on
     */
    public function __construct(AttributeValueFormType $attributeValueForm)
    {
        $this->attributeValueForm = $attributeValueForm;
    }

    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options Form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text')
                ->add(
                    'attributes',
                    'collection',
                    array(
                        'type' => $this->attributeValueForm,
                        'label' => ''
                    )
                );
    }

    /**
     * Sets default options for this form type
     *
     * @param OptionsResolverInterface $resolver The options resolver
     *
     * @return array
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'Tickit\ProjectBundle\Entity\Project'));
    }

    /**
     * Returns the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return 'tickit_project';
    }
}
