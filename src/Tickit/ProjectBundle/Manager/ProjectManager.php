<?php

namespace Tickit\ProjectBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Project Manager
 *
 * Responsible for the management of project entities and their interaction
 * with the rest of the application
 *
 * @package Tickit\ProjectBundle\Manager
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class ProjectManager
{
    /**
     * Entity manager
     *
     * @var ObjectManager
     */
    protected $em;

    /**
     * Constructor.
     *
     * @param Registry $doctrine The doctrine registry service
     */
    public function __construct(Registry $doctrine)
    {
        $this->em = $doctrine->getManager();
    }
}
