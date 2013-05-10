<?php

namespace Tickit\UserBundle\Entity;

use FOS\UserBundle\Entity\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * The Group entity represents an available user group in the application
 *
 * @package Tickit\UserBundle\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="groups")
 */
class Group extends BaseGroup
{
    /**
     * The unique identifier for this group
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Permissions associated with this group
     *
     * @ORM\OneToMany(targetEntity="Tickit\PermissionBundle\Entity\GroupPermissionValue", mappedBy="group")
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
}
