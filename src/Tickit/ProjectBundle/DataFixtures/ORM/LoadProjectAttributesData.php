<?php

namespace Tickit\ProjectBundle\DataFixtures\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tickit\ProjectBundle\Entity\ChoiceAttribute;
use Tickit\ProjectBundle\Entity\ChoiceAttributeChoice;
use Tickit\ProjectBundle\Entity\ChoiceAttributeValue;
use Tickit\ProjectBundle\Entity\EntityAttribute;
use Tickit\ProjectBundle\Entity\EntityAttributeValue;
use Tickit\ProjectBundle\Entity\LiteralAttribute;
use Tickit\ProjectBundle\Entity\LiteralAttributeValue;

/**
 * Loads project attribute data
 *
 * @package Tickit\ProjectBundle\DataFixtures\ORM
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
        $entity->setEntity('Tickit\UserBundle\Entity\User')
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
