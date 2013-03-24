<?php

namespace Tickit\PermissionBundle\Interfaces;

/**
 * Interface file for all classes representing a permission value
 *
 * @package Tickit\PermissionBundle\Interfaces
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface PermissionValueInterface
{
    /**
     * Sets the value of the permission
     *
     * @param mixed $value The new value of the permission
     */
    public function setValue($value);

    /**
     * Gets the value of this permission
     *
     * @return mixed
     */
    public function getValue();
}
