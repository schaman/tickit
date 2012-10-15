<?php

namespace Tickit\CoreBundle\Entity;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;

/**
 * Overrides the default session class and provides convenience methods for writing and reading data to the session
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class CoreSession extends Session
{
    /* @var \Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage */
    protected $storage;
    /* @var \Tickit\PermissionBundle\Service\PermissionServiceInterface */
    protected $permissions;

    /**
     * Class constructor, sets up dependencies
     *
     * @param \Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface $storage The session storage object
     */
    public function __construct(SessionStorageInterface $storage)
    {
        $this->storage = $storage;
        parent::__construct($storage);
    }

}