<?php

namespace Tickit\PermissionBundle\Form\Type\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Permissions field type.
 *
 * Field used for rendering permissions data
 *
 * @package Tickit\PermissionBundle\Form\Type\Field
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\PermissionBundle\Form\UserPermissionFormType
 * @see     Tickit\PermissionBundle\Mode\Permission
 */
class PermissionsType extends AbstractType
{
    /**
     * Builds the form type
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options Form options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $prototype = $builder->create(
            $options['prototype_name'],
            $options['type'],
            array_replace(array('label' => $options['prototype_name'] . 'label__',), $options['options'])
        );
        $builder->setAttribute('prototype', $prototype->getForm());
    }

    /**
     * Sets default form options
     *
     * @param OptionsResolverInterface $resolver The options resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('prototype' => true, 'allow_add' => true));
    }

    /**
     * Builds the form view
     *
     * @param FormView      $view    The form view
     * @param FormInterface $form    The form
     * @param array         $options Form options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['allow_add'] = true;
        $view->vars['prototype'] = $form->getConfig()->getAttribute('prototype')->createView($view);
    }

    /**
     * Gets the field that this field extends
     *
     * @return string
     */
    public function getParent()
    {
        return 'collection';
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'permissions';
    }
}
