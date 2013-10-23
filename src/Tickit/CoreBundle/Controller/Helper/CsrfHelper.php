<?php

namespace Tickit\CoreBundle\Controller\Helper;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * CSRF controller helper.
 *
 * Provides helper methods for handling CSRF tokens in controllers
 *
 * @package Tickit\CoreBundle\Controller\Helper
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class CsrfHelper
{
    /**
     * A CSRF token manager
     *
     * @var CsrfTokenManagerInterface
     */
    protected $tokenManager;

    /**
     * Constructor.
     *
     * @param CsrfTokenManagerInterface $tokenManager A token manager
     */
    public function __construct(CsrfTokenManagerInterface $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    /**
     * Checks a CSRF token for validity.
     *
     * @param string $tokenValue The CSRF token to check
     * @param string $id         The ID of the token
     *
     * @throws NotFoundHttpException If the token is not valid
     *
     * @return void
     */
    public function checkCsrfToken($tokenValue, $id)
    {
        $tokenProvider = $this->tokenManager;
        $token = new CsrfToken($id, $tokenValue);

        if (false === $tokenProvider->isTokenValid($token)) {
            throw new NotFoundHttpException('Invalid CSRF token');
        }
    }

    /**
     * Generates a new CSRF token for the given ID
     *
     * @param string $id The ID of the CSRF token
     *
     * @return CsrfToken
     */
    public function generateCsrfToken($id)
    {
        return $this->tokenManager->getToken($id);
    }
}
