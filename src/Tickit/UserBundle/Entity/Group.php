<?php

namespace Tickit\UserBundle\Entity;

use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Entity\Group as BaseGroup;
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
     * @param Collection $permissions The permissions collection
     *
     * @return Group
     */
    public function setPermissions(Collection $permissions)
    {
        $this->permissions = $permissions;

        return $this;
    }
}
