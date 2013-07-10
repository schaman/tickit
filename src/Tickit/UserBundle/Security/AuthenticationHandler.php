<?php

namespace Tickit\UserBundle\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * Authentication handler.
 *
 * Responsible for dealing with post-authentication attempts.
 *
 * @package Tickit\UserBundle\Security
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
    /**
     * This is called when an interactive authentication attempt fails.
     *
     * This is called by authentication listeners inheriting from AbstractAuthenticationListener.
     *
     * @param Request                 $request   The request that triggered the authentication attempt
     * @param AuthenticationException $exception The authentication exception that was thrown
     *
     * @return JsonResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = array(
            'success' => false,
            'error' => $exception->getMessage()
        );

        return new JsonResponse($data);
    }

    /**
     * This is called when an interactive authentication attempt succeeds.
     *
     * This is called by authentication listeners inheriting from AbstractAuthenticationListener.
     *
     * @param Request        $request The request that triggered the authentication attempt.
     * @param TokenInterface $token   The token that was granted during authentication
     *
     * @return JsonResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $targetPath = $request->getSession()->get('_security.target_path', '/dashboard');

        $data = array(
            'success' => true,
            'userId' => $token->getUser()->getId(),
            'sessionId' => $request->getSession()->getId(),
            'url' => $targetPath
        );

        return new JsonResponse($data);
    }
}
