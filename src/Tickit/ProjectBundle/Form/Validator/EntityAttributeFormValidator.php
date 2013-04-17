<?php

namespace Tickit\ProjectBundle\Form\Validator;

use Symfony\Component\Validator\ExecutionContextInterface;
use Tickit\ProjectBundle\Entity\EntityAttribute;

/**
 * Validator helper for EntityAttributeFormType
 *
 * @package Tickit\ProjectBundle\Form\Validator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class EntityAttributeFormValidator
{
    /**
     * Ensures that the entity selected on the form is valid
     *
     * @param EntityAttribute            $attribute The entity attribute to validate
     * @param ExecutionContextInterface  $context   The execution context
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
