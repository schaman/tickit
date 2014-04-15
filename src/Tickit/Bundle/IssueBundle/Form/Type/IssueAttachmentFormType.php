<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\Bundle\IssueBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tickit\Bundle\IssueBundle\Form\EventListener\IssueAttachmentMimeTypeFormSubscriber;

/**
 * Issue attachment form type.
 *
 * Used for managing IssueAttachment instances
 *
 * @package Tickit\Bundle\IssueBundle\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueAttachmentFormType extends AbstractType
{
    /**
     * Builds the form
     *
     * @param FormBuilderInterface $builder A form builder
     * @param array                $options An array of form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden')
                ->add('file', 'file', ['attr' => ['multiple' => true]])
                ->add('mimeType', 'hidden')
                ->addEventSubscriber(new IssueAttachmentMimeTypeFormSubscriber());
    }

    /**
     * Sets default form options
     *
     * @param OptionsResolverInterface $resolver An options resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => 'Tickit\Component\Model\Issue\IssueAttachment']);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tickit_issue_attachment';
    }
}
