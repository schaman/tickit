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
