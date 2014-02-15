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

namespace Tickit\Component\Model\User;

/**
 * User session object.
 *
 * Represents a user's PHP session in the system but does not store any session data.
 * It is used for determining concurrent session activity
 *
 * @package Tickit\Component\Model\User
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserSession
{
    /**
     * The unique identifier
     *
     * @var integer
     */
    protected $id;

    /**
     * The user that this session entry belongs to
     *
     * @var User
     */
    protected $user;

    /**
     * The IP address where this session was created
     *
     * @var string
     */
    protected $ip;

    /**
     * The PHP session ID token
     *
     * @var string
     */
    protected $sessionToken;

    /**
     * The date and time that this session was created
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * The date and time that this session was last updated
     *
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * Sets the user object on this session
     *
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Sets the IP address for this session
     *
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * Sets the PHP session token for this session
     *
     * @param string $sessionToken
     */
    public function setSessionToken($sessionToken)
    {
        $this->sessionToken = $sessionToken;
    }
}
