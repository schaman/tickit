<?php

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
