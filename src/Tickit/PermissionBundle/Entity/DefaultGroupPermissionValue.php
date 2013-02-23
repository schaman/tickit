<?php

namespace Tickit\PermissionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tickit\UserBundle\Entity\Group;
use Tickit\PermissionBundle\Interfaces\PermissionValueInterface;

/**
 * Represents the default value of a permission against a specific group. This is used when new users are
 * created and the system needs to know what value to assign against each permission for the new user.
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="default_group_permission_values")
 */
class DefaultGroupPermissionValue implements PermissionValueInterface
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Tickit\UserBundle\Entity\Group")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    protected $group;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Permission")
     * @ORM\JoinColumn(name="permission_id", referencedColumnName="id")
     */
    protected $permission;

    /**
     * @ORM\Column(name="value", type="boolean")
     */
    protected $value;

    /**
     * Sets the group on this object
     *
     * @param Group $group The group that this permission value relates to
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;
    }

    /**
     * Sets the permission on this object
     *
     * @param Permission $permission The permission object associated with this value
     */
    public function setPermission(Permission $permission)
    {
        $this->permission = $permission;
    }

    /**
     * Sets the boolean value for this permission value pair
     *
     * @param bool $value The new value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Gets the value of this permission
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}