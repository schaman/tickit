<?php

namespace Tickit\PermissionBundle\Entity;

/**
 * Interface for permissions
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
interface PermissionInterface
{
    /**
     * Gets the name of the permission
     *
     * @abstract
     * @return string
     */
    public function getName();

    /**
     * Gets the system friendly name of the permission
     *
     * @abstract
     * @return string
     */
    public function getSystemName();

}
