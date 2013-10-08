<?php

namespace Tickit\CoreBundle\Controller\Helper;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Csrf\CsrfTokenGeneratorInterface;

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
     * A CSRF token generator
     *
     * @var CsrfTokenGeneratorInterface
     */
    protected $tokenGenerator;

    /**
     * Constructor.
     *
     * @param CsrfTokenGeneratorInterface    $tokenGenerator  A token generator
     */
    public function __construct(CsrfTokenGeneratorInterface $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * Checks a CSRF token for validity.
     *
     * @param string $token  The CSRF token to check
     * @param string $intent The intent of the token
     *
     * @throws NotFoundHttpException If the token is not valid
     *
     * @return void
     */
    public function checkCsrfToken($token, $intent)
    {
        $tokenProvider = $this->tokenGenerator;

        if (!$tokenProvider->isCsrfTokenValid($intent, $token)) {
            throw new NotFoundHttpException('Invalid CSRF token');
        }
    }

    /**
     * Generates a new CSRF token for the given intent
     *
     * @param string $intent The intent of the CSRF token
     *
     * @return string
     */
    public function generateCsrfToken($intent)
    {
        return $this->tokenGenerator->generateCsrfToken($intent);
    }
}
