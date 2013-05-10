<?php

namespace Tickit\PermissionBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Tickit\PermissionBundle\Interfaces\PermissionInterface;

/**
 * Represents a permission in the system.
 *
 * For this entity to make any real functional sense it needs to be used in conjunction with
 * UserPermissionValue or GroupPermissionValue to map a permission and value to a user/group
 *
 * @package Tickit\PermissionBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity(repositoryClass="Tickit\PermissionBundle\Entity\Repository\PermissionRepository")
 * @ORM\Table(name="permissions")
 */
class Permission implements PermissionInterface
{
    /**
     * The unique identifier for the permission
     *
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The name of the permission
     *
     * @var string
     * @ORM\Column(type="string", length=120)
     */
    protected $name;

    /**
     * The system friendly name of the permission
     *
     * @var string
     * @ORM\Column(name="system_name", type="string", length=120)
     */
    protected $systemName;

    /**
     * The users that have a value associated with this permission
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="UserPermissionValue", mappedBy="permission")
     */
    protected $users;

    /**
     * The groups that have a value associated with this permission
     *
     * @var Collection
     * @ORM\OneToMany(targetEntity="GroupPermissionValue", mappedBy="permission")
     */
    protected $groups;

    /**
     * Gets the ID for this permission
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     *
     * @return Permission
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getSystemName()
    {
        return $this->systemName;
    }

    /**
     * {@inheritDoc}
     *
     * @return Permission
     */
    public function setSystemName($systemName)
    {
        $this->systemName = $systemName;

        return $this;
    }

    /**
     * __toString() method
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
