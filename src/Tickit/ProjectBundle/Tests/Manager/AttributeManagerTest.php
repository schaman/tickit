<?php

namespace Tickit\ProjectBundle\Tests\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\ProjectBundle\Entity\AbstractAttributeValue;
use Tickit\ProjectBundle\Entity\ChoiceAttribute;
use Tickit\ProjectBundle\Entity\ChoiceAttributeChoice;
use Tickit\ProjectBundle\Entity\EntityAttribute;
use Tickit\ProjectBundle\Entity\LiteralAttribute;
use Tickit\ProjectBundle\Entity\Project;
use Tickit\ProjectBundle\Manager\AttributeManager;

/**
 * AttributeManager tests
 *
 * @package Tickit\ProjectBundle\Tests\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttributeManagerTest extends AbstractUnitTest
{
    /**
     * Tests the getRepository() method
     */
    public function testGetRepositoryReturnsCorrectInstance()
    {
        $entityManager = $this->getMockEntityManager();
        $attributeRepository = $this->getMockAttributeRepository();
        $choiceRepository = $this->getMockChoiceAttributeChoiceRepository();

        $manager = new AttributeManager($attributeRepository, $choiceRepository, $entityManager);

        $this->assertSame($attributeRepository, $manager->getRepository());
    }

    /**
     * Tests the delete() method
     */
    public function testDeleteRemovesAttributeFromEntityManager()
    {
        $entityManager = $this->getMockEntityManager();
        $attributeRepo = $this->getMockAttributeRepository();
        $choiceRepo = $this->getMockChoiceAttributeChoiceRepository();

        $attribute = $this->getMockForAbstractClass('Tickit\ProjectBundle\Entity\AbstractAttribute');

        $entityManager->expects($this->once())
                      ->method('remove')
                      ->with($attribute);

        $entityManager->expects($this->once())
                      ->method('flush');

        $manager = new AttributeManager($attributeRepo, $choiceRepo, $entityManager);
        $manager->delete($attribute);
    }

    /**
     * Tests the create() method
     */
    public function testCreatePersistsLiteralAttributeToEntityManager()
    {
        $entityManager = $this->getMockEntityManager();
        $attributeRepo = $this->getMockAttributeRepository();
        $choiceRepo = $this->getMockChoiceAttributeChoiceRepository();

        $attribute = new LiteralAttribute();

        $entityManager->expects($this->once())
                      ->method('persist')
                      ->with($attribute);

        $entityManager->expects($this->once())
                      ->method('flush');

        $manager = new AttributeManager($attributeRepo, $choiceRepo, $entityManager);
        $manager->create($attribute);
    }

    /**
     * Tests the create() method
     */
    public function testCreatePersistsChoiceAttributeToEntityManager()
    {
        $entityManager = $this->getMockEntityManager();
        $attributeRepo = $this->getMockAttributeRepository();
        $choiceRepo = $this->getMockChoiceAttributeChoiceRepository();

        $attribute = new ChoiceAttribute();
        $choices = array(new ChoiceAttributeChoice(), new ChoiceAttributeChoice());
        $attribute->setChoices(new ArrayCollection($choices));

        $entityManager->expects($this->never())
                      ->method('remove');

        // it should persist both of the new choices and the attribute itself
        $entityManager->expects($this->exactly(3))
                      ->method('persist');

        $entityManager->expects($this->exactly(2))
                      ->method('flush');

        $choiceRepo->expects($this->once())
                   ->method('findBy')
                   ->with(array('attribute' => $attribute))
                   ->will($this->returnValue(array()));

        $manager = new AttributeManager($attributeRepo, $choiceRepo, $entityManager);
        $manager->create($attribute);
    }

    /**
     * Tests the create() method
     */
    public function testCreatePersistsEntityAttributeToEntityManager()
    {
        $entityManager = $this->getMockEntityManager();
        $attributeRepo = $this->getMockAttributeRepository();
        $choiceRepo = $this->getMockChoiceAttributeChoiceRepository();

        $attribute = new EntityAttribute();

        $entityManager->expects($this->once())
                      ->method('persist')
                      ->with($attribute);

        $entityManager->expects($this->once())
                      ->method('flush');

        $manager = new AttributeManager($attributeRepo, $choiceRepo, $entityManager);
        $manager->create($attribute);
    }

    /**
     * Tests the getAttributeValuesForProject() method
     */
    public function testGetAttributeValuesForProjectReturnsCorrectCollection()
    {
        $entityManager = $this->getMockEntityManager();
        $attributeRepo = $this->getMockAttributeRepository();
        $choiceRepo = $this->getMockChoiceAttributeChoiceRepository();

        $project = new Project();
        $attributes = array(new LiteralAttribute(), new ChoiceAttribute(), new EntityAttribute());

        $attributeRepo->expects($this->once())
                      ->method('findAllAttributes')
                      ->will($this->returnValue($attributes));

        $manager = new AttributeManager($attributeRepo, $choiceRepo, $entityManager);
        $collection = $manager->getAttributeValuesForProject($project);

        $this->assertInstanceOf('\Doctrine\Common\Collections\Collection', $collection);
        $items = $collection->toArray();
        $this->assertContainsOnlyInstancesOf('Tickit\ProjectBundle\Entity\AbstractAttributeValue', $items);
    }

    /**
     * Tests the update() method
     *
     * We only test the update() for choice attributes, because their logic varies between
     * create and update.
     */
    public function testUpdatePersistsChoiceAttributeToEntityManager()
    {
        $entityManager = $this->getMockEntityManager();
        $attributeRepo = $this->getMockAttributeRepository();
        $choiceRepo = $this->getMockChoiceAttributeChoiceRepository();

        $existingAttribute = new ChoiceAttribute();
        $existingAttribute->setId(1);
        $choices = array(new ChoiceAttributeChoice(), new ChoiceAttributeChoice());
        $existingAttribute->setChoices(new ArrayCollection($choices));

        // choices that already exist in the repository
        $existingChoices = array(new ChoiceAttributeChoice(), new ChoiceAttributeChoice());

        // it should remove the existing choices, there are 2 of them
        $entityManager->expects($this->exactly(2))
                      ->method('remove');

        // it should persist both the choices and the attribute itself
        $entityManager->expects($this->exactly(2))
                      ->method('persist');

        $entityManager->expects($this->exactly(1))
                      ->method('flush');

        $choiceRepo->expects($this->once())
                   ->method('findBy')
                   ->with(array('attribute' => $existingAttribute))
                   ->will($this->returnValue($existingChoices));

        $manager = new AttributeManager($attributeRepo, $choiceRepo, $entityManager);
        $manager->update($existingAttribute);
    }

    /**
     * Gets mock instance of ChoiceAttributeChoiceRepository
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockChoiceAttributeChoiceRepository()
    {
        return $this->getMockBuilder('Tickit\ProjectBundle\Entity\Repository\ChoiceAttributeChoiceRepository')
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Gets mock instance of AttributeRepository
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockAttributeRepository()
    {
        return $this->getMockBuilder('Tickit\ProjectBundle\Entity\Repository\AttributeRepository')
                    ->disableOriginalConstructor()
                    ->getMock();
    }
}
