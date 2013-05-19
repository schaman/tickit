<?php

namespace Tickit\PermissionBundle\Interfaces;

/**
 * Interface for permissions
 *
 * @package Tickit\PermissionBundle\Interfaces
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface PermissionInterface
{
    /**
     * Gets the name of the permission
     *
     * @return string
     */
    public function getName();

    /**
     * Gets the system friendly name of the permission
     *
     * @return string
     */
    public function getSystemName();

    /**
     * Sets the name of the permission
     *
     * @param string $name The name for the permission
     *
     * @return mixed
     */
    public function setName($name);

    /**
     * Sets the system friendly name of the permission
     *
     * @param string $systemName The system name for the permission
     *
     * @return mixed
     */
    public function setSystemName($systemName);
}
