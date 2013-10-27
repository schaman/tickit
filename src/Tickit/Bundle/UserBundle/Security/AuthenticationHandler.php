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

namespace Tickit\Bundle\UserBundle\Security;

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
 * @package Tickit\Bundle\UserBundle\Security
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
