<?php

namespace Tickit\PermissionBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Permissions form.
 *
 * This form provides the ability to select permissions when embedding inside another form.
 *
 * @package Tickit\PermissionBundle\Form\Type
 * @author  James Halsall <jhalsall@rippleffect.com>
 * @see     Tickit\UserBundle\Form\Type\UserFormType
 */
class PermissionsFormType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options An array of options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'permission', 'entity', array(
                'class' => 'Tickit\PermissionBundle\Entity\Permission',
                'query_builder' => function($repo) {
                    /** @var EntityRepository $repo */
                    return $repo->createQueryBuilder('permissions')
                                ->select('p')
                                ->from('TickitPermissionBundle:Permission', 'p');
                },
                'expanded' => true,
                'multiple' => true
            )
        );
    }

    /**
     * Sets default options on the form
     *
     * @param OptionsResolverInterface $resolver Options resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $options = array('data_class' => 'Tickit\PermissionBundle\Entity\Permission');

        $resolver->setDefaults($options);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tickit_permissions';
    }
}
