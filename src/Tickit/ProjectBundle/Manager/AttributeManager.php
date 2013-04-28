<?php

namespace Tickit\ProjectBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Tickit\ProjectBundle\Entity\AbstractAttribute;
use Tickit\ProjectBundle\Entity\AbstractAttributeValue;
use Tickit\ProjectBundle\Entity\ChoiceAttribute;
use Tickit\ProjectBundle\Entity\ChoiceAttributeChoice;
use Tickit\ProjectBundle\Entity\Project;
use Tickit\ProjectBundle\Entity\Repository\AttributeRepository;

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
     * @var EntityManager
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
     * @return AttributeRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('TickitProjectBundle:AbstractAttribute');
    }

    /**
     * Creates an Attribute entity by persisting it and flushing changes to the entity manager
     *
     * @param AbstractAttribute $attribute The Attribute entity to persist
     *
     * @return void
     */
    public function create(AbstractAttribute $attribute)
    {
        $this->persistEntity($attribute);
        $this->em->flush();
    }

    /**
     * Updates an Attribute entity by persisting and flushing changes to the entity manager
     *
     * @param AbstractAttribute $attribute The Attribute entity to update
     *
     * @return void
     */
    public function update(AbstractAttribute $attribute)
    {
        $this->persistEntity($attribute);
        $this->em->flush();
    }

    /**
     * Deletes an Attribute entity from the entity manager
     *
     * @param AbstractAttribute $attribute The attribute to delete
     *
     * @return void
     */
    public function delete(AbstractAttribute $attribute)
    {
        $this->em->remove($attribute);
        $this->em->flush();
    }

    /**
     * Fetches a collection of attribute values.
     *
     * This method is used when creating a new entity that is associated with
     * attribute values and needs a collection ready for attaching to the entity
     * (for example, Project entities).
     *
     * @param Project $project The project to fetch AttributeValue entities for
     *
     * @return ArrayCollection
     */
    public function getAttributeValuesForProject(Project $project)
    {
        $collection = new ArrayCollection();
        $attributes = $this->getRepository()->findAllAttributes();

        foreach ($attributes as $attribute) {
            $value = AbstractAttributeValue::factory($attribute->getType());
            $value->setProject($project);
            $value->setAttribute($attribute);
            $collection->add($value);
        }

        return $collection;
    }

    /**
     * Persists an attribute
     *
     * @param AbstractAttribute $attribute The attribute that needs persisting
     *
     * @return void
     */
    protected function persistEntity(AbstractAttribute $attribute)
    {
        if (null === $attribute->getDefaultValue()) {
            $attribute->setDefaultValue('');
        }

        switch ($attribute->getType()) {
            case AbstractAttribute::TYPE_LITERAL:
                $this->em->persist($attribute);
                break;
            case AbstractAttribute::TYPE_CHOICE:
                $this->persistChoiceEntity($attribute);
                break;
            case AbstractAttribute::TYPE_ENTITY:
                $this->em->persist($attribute);
                break;
        }
    }

    /**
     * Persists a choice entity for creation/updating
     *
     * @param ChoiceAttribute $attribute
     *
     * @return void
     */
    private function persistChoiceEntity(ChoiceAttribute $attribute)
    {
        $id = $attribute->getId();
        $choices = $attribute->getChoices();

        // save the attribute to get an internal identifier
        if (empty($id)) {
            $this->em->persist($attribute);
            $this->em->flush();
        }

        /** @var ArrayCollection $choices */
        foreach ($choices as $key => $choice) {
            if (is_array($choice)) {
                $newChoice = new ChoiceAttributeChoice();
                $newChoice->setName($choice['name'])
                          ->setAttribute($attribute);
                $this->em->persist($newChoice);

                $choices->remove($key);
                $choices->add($newChoice);
            }
        }

        $attribute->setChoices($choices);
        $this->em->flush();
    }
}
