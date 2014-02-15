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

namespace Tickit\Bundle\ProjectBundle\Form\EventListener;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Tickit\Component\Model\Project\AbstractAttribute;
use Tickit\Component\Model\Project\ChoiceAttribute;
use Tickit\Component\Model\Project\EntityAttribute;
use Tickit\Component\Model\Project\LiteralAttribute;
use Tickit\Bundle\ProjectBundle\Doctrine\Repository\ChoiceAttributeChoiceRepository;

/**
 * AttributeValueForm event subscriber.
 *
 * Responsible for hooking into the PRE_SET_DATA event and building the
 * attribute value form based on the type of attribute that the value is for.
 *
 * @package Tickit\Bundle\ProjectBundle\Form\EventListener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\Bundle\ProjectBundle\Form\Type\AttributeValueFormType
 */
class AttributeValueFormSubscriber implements EventSubscriberInterface
{
    /**
     * Gets subscribed events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'buildFields');
    }

    /**
     * Builds fields for the AttributeValueForm subscriber.
     *
     * Looks at the type of attribute that the value is associated with and
     * builds form fields relevant for that type.
     *
     * @param FormEvent $event The form event
     *
     * @return void
     */
    public function buildFields(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        switch (get_class($data)) {
            case 'Tickit\Component\Model\Project\ChoiceAttributeValue':
                $this->buildChoiceValueFields($form, $data->getAttribute());
                break;
            case 'Tickit\Component\Model\Project\EntityAttributeValue':
                $this->buildEntityValueFields($form, $data->getAttribute());
                break;
            case 'Tickit\Component\Model\Project\LiteralAttributeValue':
                $this->buildLiteralValueFields($form, $data->getAttribute());
                break;
        }
    }

    /**
     * Builds fields on the form for a literal attribute
     *
     * @param FormInterface    $form      The form to build fields on
     * @param LiteralAttribute $attribute The attribute that fields need adding for
     *
     * @return void
     */
    protected function buildLiteralValueFields(FormInterface $form, LiteralAttribute $attribute)
    {
        $assert = $this->buildAssertionsForAttribute($attribute);

        switch ($attribute->getValidationType()) {
            case 'date':
                $assert->add(new Assert\Date());
                $fieldType = 'date';
                break;
            case 'datetime':
                $assert->add(new Assert\DateTime());
                $fieldType = 'datetime';
                break;
            case 'file':
                $assert->add(new Assert\File());
                $fieldType = 'file';
                break;
            case 'email':
                $assert->add(new Assert\Email());
                $fieldType = 'email';
                break;
            case 'number':
                $assert->add(new Assert\Type(array('type' => 'integer')));
                $fieldType = 'number';
                break;
            case 'url':
                $assert->add(new Assert\Url());
                $fieldType = 'url';
                break;
            case 'ip':
                $assert->add(new Assert\Ip());
                $fieldType = 'text';
                break;
            default:
                $assert->add(new Assert\Type(array('type' => 'string')));
                $fieldType = 'text';
        }

        $form->add('value', $fieldType, array('constraints' => $assert->toArray(), 'label' => $attribute->getName()));
    }

    /**
     * Builds fields on the form for a choice attribute
     *
     * @param FormInterface   $form      The form to build fields on
     * @param EntityAttribute $attribute The attribute that fields need adding for
     *
     * @return void
     */
    protected function buildEntityValueFields(FormInterface $form, EntityAttribute $attribute)
    {
        $form->add('value', 'entity', array('class' => $attribute->getEntity(), 'label' => $attribute->getName()));
    }

    /**
     * Builds fields on the form for a choice attribute
     *
     * @param FormInterface   $form      The form to build fields on
     * @param ChoiceAttribute $attribute The attribute that fields need adding for
     *
     * @return void
     */
    protected function buildChoiceValueFields(FormInterface $form, ChoiceAttribute $attribute)
    {
        $form->add(
            'value',
            'entity',
            array(
                'class' => 'Tickit\Component\Model\Project\ChoiceAttributeChoice',
                'query_builder' => function (ChoiceAttributeChoiceRepository $repo) use ($attribute) {
                    return $repo->getFindAllForAttributeQueryBuilder($attribute);
                },
                'expanded' => $attribute->getExpanded(),
                'multiple' => $attribute->getAllowMultiple(),
                'label' => $attribute->getName()
            )
        );
    }

    /**
     * Builds base assertions for all attribute types
     *
     * @param AbstractAttribute $attribute The attribute
     *
     * @return ArrayCollection
     */
    private function buildAssertionsForAttribute(AbstractAttribute $attribute)
    {
        $collection = new ArrayCollection();

        if (false === $attribute->getAllowBlank()) {
            $collection->add(new Assert\NotBlank());
        }

        return $collection;
    }
}
