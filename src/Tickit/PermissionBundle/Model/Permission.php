<?php

namespace Tickit\PermissionBundle\Model;

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
    protected $id;

    protected $name;

    protected $userValue;

    protected $groupValue;

    protected $user;

    public function setGroupValue($groupValue)
    {
        $this->groupValue = $groupValue;
    }

    public function getGroupValue()
    {
        return $this->groupValue;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setUserValue($userValue)
    {
        $this->userValue = $userValue;
    }

    public function getUserValue()
    {
        return $this->userValue;
    }
}
