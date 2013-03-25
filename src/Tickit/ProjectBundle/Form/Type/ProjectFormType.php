<?php

namespace Tickit\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

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
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options Form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
    }

    /**
     * Gets default options for this form type
     *
     * @param array $options Current options
     *
     * @return array
     */
    public function getDefaultOptions(array $options)
    {
        $options = array('data_class' => 'Tickit\ProjectBundle\Entity\Project');

        return $options;
    }

    /**
     * Returns the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return 'tickit_project_edit';
    }
}
