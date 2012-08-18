<?php

namespace Tickit\PermissionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Represents the default value of a permission against a specific group. This is used when new users are
 * created and the system needs to know what value to assign against each permission for the new user.
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="default_group_permission_values")
 */
class DefaultGroupPermissionValue
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

}