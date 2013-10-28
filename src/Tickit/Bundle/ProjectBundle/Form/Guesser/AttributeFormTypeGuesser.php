<?php

/*
 * Tickit, an open source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
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

namespace Tickit\Bundle\ProjectBundle\Form\Guesser;

use Symfony\Component\Form\AbstractType;
use Tickit\Bundle\ProjectBundle\Entity\AbstractAttribute;
use Tickit\Bundle\ProjectBundle\Form\Type\ChoiceAttributeFormType;
use Tickit\Bundle\ProjectBundle\Form\Type\EntityAttributeFormType;
use Tickit\Bundle\ProjectBundle\Form\Type\LiteralAttributeFormType;

/**
 * Attribute form type guesser.
 *
 * @package Tickit\Bundle\ProjectBundle\Form\Guesser
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttributeFormTypeGuesser
{
    /**
     * An instance of the entity attribute form type
     *
     * @var EntityAttributeFormType
     */
    protected $entityAttributeFormType;

    /**
     * Constructor.
     *
     * @param EntityAttributeFormType $entityAttributeFormType An entity attribute form type instance
     */
    public function __construct(EntityAttributeFormType $entityAttributeFormType)
    {
        $this->entityAttributeFormType = $entityAttributeFormType;
    }

    /**
     * Guesses a form type based off an attribute type.
     *
     * @param string $attributeType The attribute type to guess for
     *
     * @throws \InvalidArgumentException If an invalid attribute type is specified
     *
     * @return AbstractType
     */
    public function guessByAttributeType($attributeType)
    {
        if (!in_array($attributeType, AbstractAttribute::getAvailableTypes())) {
            throw new \InvalidArgumentException(
                sprintf('Attribute type (%s) is not recognised', $attributeType)
            );
        }

        switch ($attributeType) {
            case AbstractAttribute::TYPE_CHOICE:
                $formType = new ChoiceAttributeFormType();
                break;
            case AbstractAttribute::TYPE_ENTITY:
                $formType = $this->entityAttributeFormType;
                break;
            default:
                $formType = new LiteralAttributeFormType();
        }

        return $formType;
    }
}
