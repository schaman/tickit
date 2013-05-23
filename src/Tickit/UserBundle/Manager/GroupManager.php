<?php

namespace Tickit\UserBundle\Manager;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Tickit\UserBundle\Entity\Group;

/**
 * Group manager.
 *
 * Responsible for managing anything group related in the application.
 *
 * @package Tickit\UserBundle\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class GroupManager
{
    /**
     * The doctrine registry
     *
     * @var Registry
     */
    protected $doctrine;

    /**
     * Constructor.
     *
     * @param Registry $doctrine The doctrine registry
      */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Finds a user group by ID
     *
     * @param integer $id The group ID to find by
     *
     * @return Group
     */
    public function findGroup($id)
    {
        $group = $this->doctrine
                      ->getRepository('TickitUserBundle:Group')
                      ->find($id);

        return $group;
    }
}
