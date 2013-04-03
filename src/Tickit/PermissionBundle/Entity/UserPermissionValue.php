<?php

namespace Tickit\PermissionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tickit\UserBundle\Entity\User;
use Tickit\PermissionBundle\Interfaces\PermissionValueInterface;

/**
 * Represents the value of a permission against a specific user
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity(repositoryClass="Tickit\PermissionBundle\Entity\Repository\UserPermissionValueRepository")
 * @ORM\Table(name="user_permission_values")
 */
class UserPermissionValue implements PermissionValueInterface
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Tickit\UserBundle\Entity\User", inversedBy="permissions")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Permission", inversedBy="users")
     * @ORM\JoinColumn(name="permission_id", referencedColumnName="id")
     */
    protected $permission;

    /**
     * @ORM\Column(name="value", type="boolean")
     */
    protected $value;

    /**
     * Sets the user on this permission-value pair
     *
     * @param User $user The user that this permission value relates to
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Sets the permission object on this permission-value pair
     *
     * @param Permission $permission The permission object associated with this value
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
