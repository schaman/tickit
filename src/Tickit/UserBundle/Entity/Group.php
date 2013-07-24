<?php

namespace Tickit\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * The Group entity represents an available user group in the application
 *
 * @package Tickit\UserBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity(repositoryClass="Tickit\UserBundle\Entity\Repository\GroupRepository")
 * @ORM\Table(name="groups")
 */
class Group extends BaseGroup
{
    /**
     * The unique identifier for this group
     *
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Permissions associated with this group
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="Tickit\PermissionBundle\Entity\GroupPermissionValue", mappedBy="group", cascade={"persist"})
     */
    protected $permissions;

    /**
     * Constructor.
     *
     * @param string $name  The name for the group
     * @param array  $roles An array of user roles
     */
    public function __construct($name, $roles = array())
    {
        parent::__construct($name, $roles);
        $this->permissions = new ArrayCollection();
    }

    /**
     * __toString method
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * Gets associated permissions for this group.
     *
     * @return Collection
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * Sets permissions on the group
     *
     * @param array|Collection $permissions The permissions collection
     *
     * @return Group
     */
    public function setPermissions($permissions)
    {
        if (is_array($permissions)) {
            $permissions = new ArrayCollection($permissions);
        }

        $this->permissions = $permissions;

        return $this;
    }

    /**
     * Clears permissions on the current user.
     *
     * @return User
     */
    public function clearPermissions()
    {
        $this->permissions = null;

        return $this;
    }
}
