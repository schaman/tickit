<?php

namespace Tickit\ProjectBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Tickit\ProjectBundle\Entity\AbstractAttribute;

/**
 * Attribute manager.
 *
 * Responsible for project attribute entities in the application.
 *
 * @package Tickit\ProjectBundle\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttributeManager
{
    /**
     * The entity manager
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

    /**
     * Gets the repository for attributes
     *
     * @return ObjectRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('TickitProjectBundle:AbstractAttribute');
    }

    /**
     * Creates an Attribute entity by persisting it and flushing changes to the entity manager
     *
     * @param AbstractAttribute $attribute The Attribute entity to persist
     * @param boolean           $flush     False to prevent the changes being flushed, defaults to true
     */
    public function create(AbstractAttribute $attribute, $flush = true)
    {
        switch ($attribute->getType()) {
            case AbstractAttribute::TYPE_LITERAL:
                break;
            case AbstractAttribute::TYPE_CHOICE:
                break;
            case AbstractAttribute::TYPE_ENTITY:
                break;
        }

        $this->em->persist($attribute);

        if (false !== $flush) {
            $this->em->flush();
        }
    }
}
