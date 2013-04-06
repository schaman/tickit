<?php

namespace Tickit\PermissionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Tickit\PermissionBundle\Interfaces\PermissionInterface;

/**
 * Represents a permission in the system.
 *
 * For this entity to make any real functional sense it needs to be used
 * in conjunction with UserPermissionValue or GroupPermissionValue to map a permission and value to a user/group
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
     * The unique identifier for this permission
     *
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The permission name
     *
     * @var string
     * @ORM\Column(type="string", length=120)
     */
    protected $name;

    /**
     * The system friendly name
     *
     * @var string
     * @ORM\Column(name="system_name", type="string", length=120)
     */
    protected $systemName;

    /**
     * Users that own this permission
     *
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="UserPermissionValue", mappedBy="permission")
     */
    protected $users;

    /**
     * Groups that own this permission
     *
     * @var ArrayCollection
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
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function getSystemName()
    {
        return $this->systemName;
    }

    /**
     * {@inheritDoc}
     */
    public function setSystemName($systemName)
    {
        $this->systemName = $systemName;
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
