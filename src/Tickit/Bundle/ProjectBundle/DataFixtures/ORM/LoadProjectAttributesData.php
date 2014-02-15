<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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

namespace Tickit\Bundle\ProjectBundle\DataFixtures\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tickit\Component\Model\Project\ChoiceAttribute;
use Tickit\Component\Model\Project\ChoiceAttributeChoice;
use Tickit\Component\Model\Project\ChoiceAttributeValue;
use Tickit\Component\Model\Project\EntityAttribute;
use Tickit\Component\Model\Project\EntityAttributeValue;
use Tickit\Component\Model\Project\LiteralAttribute;
use Tickit\Component\Model\Project\LiteralAttributeValue;

/**
 * Loads project attribute data
 *
 * @package Tickit\Bundle\ProjectBundle\DataFixtures\ORM
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LoadProjectAttributesData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager The object manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $project = $this->getReference('project-1');

        $literal = new LiteralAttribute();
        $literal->setValidationType(LiteralAttribute::VALIDATION_DATE)
                ->setName('Due Date')
                ->setAllowBlank(false)
                ->setDefaultValue('N/A');

        $entity = new EntityAttribute();
        $entity->setEntity('Tickit\Component\Model\User\User')
               ->setDefaultValue('')
               ->setName('Project Manager')
               ->setAllowBlank(false);

        $choice = new ChoiceAttribute();

        $persistedChoices = array();
        $choices = array('iPhone App', 'Android App', 'Web App', 'Website');
        foreach ($choices as $key => $choiceName) {
            $choiceObject = new ChoiceAttributeChoice();
            $choiceObject->setName($choiceName);

            $manager->persist($choiceObject);
            $persistedChoices[$key] = $choiceObject;
        }

        $choice->setAllowMultiple(true)
               ->setExpanded(true)
               ->setChoices(new ArrayCollection($persistedChoices))
               ->setName('Type');

        $manager->persist($choice);
        $manager->persist($literal);
        $manager->persist($entity);

        $manager->flush();

        $literalValue = new LiteralAttributeValue();
        $literalValue->setAttribute($literal)
                     ->setValue(new \DateTime('+1 year'))
                     ->setProject($project);

        $entityValue = new EntityAttributeValue();
        $entityValue->setValue($this->getReference('admin-james')->getId())
                    ->setAttribute($entity)
                    ->setProject($project);

        $selectedChoices = new ArrayCollection(array($persistedChoices[0], $persistedChoices[1]));
        $choiceValue = new ChoiceAttributeValue();
        $choiceValue->setValue($selectedChoices)
                    ->setAttribute($choice)
                    ->setProject($project);

        $manager->persist($literalValue);
        $manager->persist($entityValue);
        $manager->persist($choiceValue);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 13;
    }
}
