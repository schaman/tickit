<?php

namespace Tickit\PermissionBundle\Evaluator;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Permission evaluator.
 *
 * Evaluates permissions for the currently logged in user.
 *
 * @package Tickit\PermissionBundle\Evaluator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PermissionEvaluator implements EvaluatorInterface
{
    /**
     * The current session object
     *
     * @var SessionInterface
     */
    protected $session;

    /**
     * Constructor.
     *
     * @param SessionInterface $session The current session.
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Evaluates whether the current user has a permission
     *
     * @param string $systemName The system name of the permission that is being evaluated
     *
     * @return boolean
     */
    public function hasPermission($systemName)
    {
        // TODO: implement the permission evaluator logic
        return true;
    }
}
