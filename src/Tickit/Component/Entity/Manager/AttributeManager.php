<?php

/*
 * Tickit, an open source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\Component\Entity\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Tickit\Bundle\ProjectBundle\Entity\AbstractAttribute;
use Tickit\Bundle\ProjectBundle\Entity\AbstractAttributeValue;
use Tickit\Bundle\ProjectBundle\Entity\ChoiceAttribute;
use Tickit\Bundle\ProjectBundle\Entity\ChoiceAttributeChoice;
use Tickit\Bundle\ProjectBundle\Entity\Project;
use Tickit\Bundle\ProjectBundle\Doctrine\Repository\AttributeRepository;
use Tickit\Bundle\ProjectBundle\Doctrine\Repository\ChoiceAttributeChoiceRepository;

/**
 * Attribute manager.
 *
 * Responsible for project attribute entities in the application.
 *
 * @package Tickit\Component\Entity\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttributeManager
{
    /**
     * Attribute repo
     *
     * @var AttributeRepository
     */
    protected $attributeRepository;

    /**
     * The choice attribute choice repo
     *
     * @var ChoiceAttributeChoiceRepository
     */
    protected $choiceAttributeChoiceRepository;

    /**
     * An entity manager
     *
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * Constructor.
     *
     * @param AttributeRepository             $attributeRepository The attribute repo
     * @param ChoiceAttributeChoiceRepository $choiceRepository    The choice attribute choice repo
     * @param EntityManagerInterface          $em                  An entity manager
     */
    public function __construct(AttributeRepository $attributeRepository, ChoiceAttributeChoiceRepository $choiceRepository, EntityManagerInterface $em)
    {
        $this->attributeRepository = $attributeRepository;
        $this->choiceAttributeChoiceRepository = $choiceRepository;
        $this->em = $em;
    }

    /**
     * Gets the repository for attributes
     *
     * @return AttributeRepository
     */
    public function getRepository()
    {
        return $this->attributeRepository;
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

        $existingChoices  = $this->choiceAttributeChoiceRepository->findBy(array('attribute' => $attribute));

        foreach ($existingChoices as $existingChoice) {
            $this->em->remove($existingChoice);
        }

        /** @var ChoiceAttributeChoice $choice */
        foreach ($choices as $choice) {
            $choice->setAttribute($attribute);
            $this->em->persist($choice);
        }

        $attribute->setChoices($choices);
    }
}
