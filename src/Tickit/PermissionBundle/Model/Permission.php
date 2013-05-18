<?php

namespace Tickit\PermissionBundle\Model;
use Tickit\UserBundle\Entity\User;

/**
 * Permission model.
 *
 * Represents some permission data as a whole.
 *
 * @package Tickit\PermissionBundle\Model
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class Permission
{
    /**
     * The identifier for the permission
     *
     * @var integer
     */
    protected $id;

    /**
     * The name of the permission
     *
     * @var string
     */
    protected $name;

    /**
     * Boolean value for the user override on this permission.
     *
     * If this value is null, then there is no user override for the permission and
     * it is being inherited from the user group.
     *
     * @var boolean|null
     */
    protected $userValue;

    /**
     * Boolean value for the group value on this permission
     *
     * @var boolean
     */
    protected $groupValue;

    /**
     * The user that this permission relates to
     *
     * @var User
     */
    protected $user;

    /**
     * Sets the group value for this permission
     *
     * @param boolean $groupValue The group value
     *
     * @return void
     */
    public function setGroupValue($groupValue)
    {
        $this->groupValue = $groupValue;
    }

    /**
     * Gets the group value for this permission
     *
     * @return boolean
     */
    public function getGroupValue()
    {
        return $this->groupValue;
    }

    /**
     * Sets the permission ID
     *
     * @param integer $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Gets the permission ID
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the name for this permission
     *
     * @param string $name The permission name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Gets the permission name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the user value for this permission
     *
     * @param boolean $userValue
     *
     * @return void
     */
    public function setUserValue($userValue)
    {
        $this->userValue = $userValue;
    }

    /**
     * Gets the user value for this permission
     *
     * @return boolean|null
     */
    public function getUserValue()
    {
        return $this->userValue;
    }
}
