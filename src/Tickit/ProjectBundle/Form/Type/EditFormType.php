<?php

namespace Tickit\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Tickit\ProjectBundle\Entity\Project;

/**
 * Edit project form.
 *
 * Provides functionality for editing project entities.
 *
 * @package Tickit\ProjectBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class EditFormType extends AbstractType
{
    /**
     * The project entity being edited
     *
     * @var Project
     */
    protected $project;

    /**
     * Constructor.
     *
     * @param Project $project The project entity to edit
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options Form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array('data' => $this->project->getName()));
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