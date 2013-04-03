<?php

namespace Tickit\PermissionBundle\Entity;

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
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=120)
     */
    protected $name;

    /**
     * @ORM\Column(name="system_name", type="string", length=120)
     */
    protected $systemName;

    /**
     * @ORM\OneToMany(targetEntity="UserPermissionValue", mappedBy="permission")
     */
    protected $users;

    /**
     * @ORM\OneToMany(targetEntity="GroupPermissionValue", mappedBy="permission")
     */
    protected $groups;

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
}
