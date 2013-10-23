<?php

namespace Tickit\PermissionBundle\Evaluator;

/**
 * Evaluator interface.
 *
 * Permission evaluators determine permission levels within the application.
 *
 * @package Tickit\PermissionBundle\Evaluator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface EvaluatorInterface
{
    /**
     * Evaluates whether the current user has a permission
     *
     * @param string $systemName The system name of the permission that is being evaluated
     *
     * @return boolean
     */
    public function hasPermission($systemName);
}
