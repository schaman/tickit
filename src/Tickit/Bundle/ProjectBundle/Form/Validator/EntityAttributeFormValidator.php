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

namespace Tickit\Bundle\ProjectBundle\Form\Validator;

use Symfony\Component\Validator\ExecutionContextInterface;
use Tickit\Component\Model\Project\EntityAttribute;

/**
 * Validator helper for EntityAttributeFormType
 *
 * @package Tickit\Bundle\ProjectBundle\Form\Validator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class EntityAttributeFormValidator
{
    /**
     * Ensures that the entity selected on the form is valid
     *
     * @param EntityAttribute           $attribute The entity attribute to validate
     * @param ExecutionContextInterface $context   The execution context
     *
     * @return void
     */
    public static function isEntityAttributeValid(EntityAttribute $attribute, ExecutionContextInterface $context)
    {
        if (false === class_exists($attribute->getEntity())) {
            $context->addViolationAt('entity', 'Oops, looks like that entity doesn\'t exist!');
        }
    }
}
