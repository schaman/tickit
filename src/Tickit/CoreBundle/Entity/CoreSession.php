<?php

/*
 * Tickit, an source web based bug management tool.
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

namespace Tickit\CoreBundle\Entity;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;

/**
 * Core session object.
 *
 * Overrides the default session class and provides convenience methods for writing
 * and reading data to the session.
 *
 * @package Tickit\CoreBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class CoreSession extends Session
{
    /**
     * Session storage
     *
     * @var NativeSessionStorage
     */
    protected $storage;

    /**
     * Constructor.
     *
     * @param SessionStorageInterface $storage The session storage object
     */
    public function __construct(SessionStorageInterface $storage)
    {
        $this->storage = $storage;
        parent::__construct($storage);
    }
}
