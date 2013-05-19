<?php

namespace Tickit\PermissionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tickit\UserBundle\Entity\Group;
use Tickit\PermissionBundle\Interfaces\PermissionValueInterface;

/**
 * Represents the value of a permission against a specific group
 *
 * @package Tickit\PermissionBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity(repositoryClass="Tickit\PermissionBundle\Entity\Repository\GroupPermissionValueRepository")
 * @ORM\Table(name="groups_permission_values")
 */
class GroupPermissionValue implements PermissionValueInterface
{
    /**
     * The group for this value
     *
     * @var Group
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Tickit\UserBundle\Entity\Group", inversedBy="permissions")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    protected $group;

    /**
     * The permission that this value relates to
     *
     * @var Permission
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Permission", inversedBy="groups")
     * @ORM\JoinColumn(name="permission_id", referencedColumnName="id")
     */
    protected $permission;

    /**
     * The value of the permission
     *
     * @var boolean
     * @ORM\Column(name="value", type="boolean")
     */
    protected $value;

    /**
     * Sets the group on this permission-value pair
     *
     * @param Group $group The group associated with this permission value
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;
    }

    /**
     * Sets the permission on this permission-value pair
     *
     * @param Permission $permission The permission object associated with this permission value
     */
    public function setPermission(Permission $permission)
    {
        $this->permission = $permission;
    }

    /**
     * Sets the value for this permission-value pair
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
