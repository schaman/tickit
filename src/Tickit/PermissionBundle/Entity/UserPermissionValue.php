<?php

namespace Tickit\PermissionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tickit\UserBundle\Entity\User;
use Tickit\PermissionBundle\Interfaces\PermissionValueInterface;

/**
 * Represents the value of a permission against a specific user
 *
 * @package Tickit\PermissionBundle\Entity
 * @author James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity(repositoryClass="Tickit\PermissionBundle\Entity\Repository\UserPermissionValueRepository")
 * @ORM\Table(name="user_permission_values")
 */
class UserPermissionValue implements PermissionValueInterface
{
    /**
     * The user that this value is for
     *
     * @var User
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Tickit\UserBundle\Entity\User", inversedBy="permissions")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * The permission that this value correlates to
     *
     * @var Permission
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Permission", inversedBy="users")
     * @ORM\JoinColumn(name="permission_id", referencedColumnName="id")
     */
    protected $permission;

    /**
     * The boolean value for this permission
     *
     * @var boolean
     * @ORM\Column(name="value", type="boolean")
     */
    protected $value;

    /**
     * Sets the user on this permission-value pair
     *
     * @param User $user The user that this permission value relates to
     *
     * @return UserPermissionValue
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Sets the permission object on this permission-value pair
     *
     * @param Permission $permission The permission object associated with this value
     *
     * @return UserPermissionValue
     */
    public function setPermission(Permission $permission)
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * Sets the value for this permission-value pair
     *
     * @param bool $value The new value
     *
     * @return UserPermissionValue
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
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

    /**
     * Gets the permission name
     *
     * @return string
     */
    public function getPermissionName()
    {
        return $this->permission->getName();
    }
}
