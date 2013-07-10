<?php


namespace Tickit\ProjectBundle\Tests\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\ProjectBundle\Controller\AttributeController;
use Tickit\ProjectBundle\Entity\AbstractAttribute;
use Tickit\ProjectBundle\Entity\ChoiceAttribute;
use Tickit\ProjectBundle\Entity\ChoiceAttributeChoice;
use Tickit\ProjectBundle\Entity\EntityAttribute;
use Tickit\ProjectBundle\Entity\LiteralAttribute;

/**
 * AttributeController tests
 *
 * @package Tickit\ProjectBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @group   functional
 */
class AttributeControllerTest extends AbstractFunctionalTest
{
    /**
     * Dummy literal attribute entity
     *
     * @var LiteralAttribute
     */
    protected static $literalAttribute;

    /**
     * Dummy entity attribute entity
     *
     * @var EntityAttribute
     */
    protected static $entityAttribute;

    /**
     * Sets up entities
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $literal = new LiteralAttribute();
        $literal->setName('sample literal' . uniqid())
                ->setAllowBlank(1)
                ->setDefaultValue('n/a');

        static::$literalAttribute = $literal;

        $entity = new EntityAttribute();
        $entity->setEntity('Tickit\ProjectBundle\Entity\Project')
               ->setName('sample entity' . uniqid());

        static::$entityAttribute = $entity;
    }

    /**
     * Tests the createAction()
     *
     * Ensures that a 404 response is thrown for invalid type in URL
     *
     * @return void
     */
    public function testCreateActionThrows404ForInvalidAttributeType()
    {
        $client = $this->getAuthenticatedClient(static::$admin);

        $client->request('post', $this->generateRoute('project_attribute_create', array('type' => 'aaaaaa')));
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * Tests the createAction()
     *
     * Makes sure that the creation process works for LiteralAttribute types
     *
     * @return void
     */
    public function testCreateActionForLiteralAttributeCreatesAttribute()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $doctrine = $client->getContainer()->get('doctrine');
        $attributeRepo = $client->getContainer()->get('tickit_project.attribute_manager')->getRepository();

        $totalAttributes = count($attributeRepo->findAll());

        $createRoute = $this->generateRoute(
            'project_attribute_create_form',
            array('type' => AbstractAttribute::TYPE_LITERAL)
        );
        $crawler = $client->request('get', $createRoute);

        $newAttributeName = 'Test Attribute ' . uniqid();
        $form = $crawler->selectButton('Save Project Attribute')->form(
            array(
                'tickit_project_attribute_literal[type]' => AbstractAttribute::TYPE_LITERAL,
                'tickit_project_attribute_literal[name]' => $newAttributeName,
                'tickit_project_attribute_literal[default_value]' => 'n/a',
                'tickit_project_attribute_literal[allow_blank]' => 1,
                'tickit_project_attribute_literal[validation_type]' => LiteralAttribute::VALIDATION_EMAIL
            )
        );
        $client->submit($form);

        $this->assertEquals(++$totalAttributes, count($attributeRepo->findAll()));

        /** @var LiteralAttribute $newAttribute */
        $newAttribute = $attributeRepo->findOneByName($newAttributeName);
        $this->assertInstanceOf('Tickit\ProjectBundle\Entity\LiteralAttribute', $newAttribute);
        $this->assertEquals(LiteralAttribute::VALIDATION_EMAIL, $newAttribute->getValidationType());

        // clean up new attribute
        $em = $doctrine->getManager();
        $em->remove($newAttribute);
        $em->flush();
    }

    /**
     * Tests the createAction()
     *
     * @return void
     */
    public function testCreateActionForEntityAttributeCreatesAttribute()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $doctrine = $client->getContainer()->get('doctrine');
        $attributeRepo = $client->getContainer()->get('tickit_project.attribute_manager')->getRepository();

        $totalAttributes = count($attributeRepo->findAll());

        $route = $this->generateRoute('project_attribute_create_form', array('type' => AbstractAttribute::TYPE_ENTITY));
        $crawler = $client->request('get', $route);

        $newAttributeName = 'Test Attribute ' . uniqid();
        $form = $crawler->selectButton('Save Project Attribute')->form(
            array(
                'tickit_project_attribute_entity[type]' => AbstractAttribute::TYPE_ENTITY,
                'tickit_project_attribute_entity[name]' => $newAttributeName,
                'tickit_project_attribute_entity[default_value]' => 'n/a',
                'tickit_project_attribute_entity[allow_blank]' => 1,
                'tickit_project_attribute_entity[entity]' => 'Tickit\ProjectBundle\Entity\Project'
            )
        );
        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent());
        $this->assertTrue($response->success);
        $this->assertEquals(++$totalAttributes, count($attributeRepo->findAll()));

        $newAttribute = $attributeRepo->findOneByName($newAttributeName);
        $this->assertInstanceOf('\Tickit\ProjectBundle\Entity\EntityAttribute', $newAttribute);
        $em = $doctrine->getManager();

        $em->remove($newAttribute);
        $em->flush();
    }

    /**
     * Tests the createAction()
     *
     * @return void
     */
    public function testCreateActionForChoiceAttributeCreatesAttribute()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $doctrine = $client->getContainer()->get('doctrine');
        $attributeRepo = $client->getContainer()->get('tickit_project.attribute_manager')->getRepository();

        $totalAttributes = count($attributeRepo->findAll());

        $route = $this->generateRoute('project_attribute_create_form', array('type' => AbstractAttribute::TYPE_CHOICE));
        $crawler = $client->request('get', $route);

        $newAttributeName = 'Test Attribute' . uniqid();
        $form = $crawler->selectButton('Save Project Attribute')->form();
        $values = array(
            'tickit_project_attribute_choice' => array(
                'type' => AbstractAttribute::TYPE_CHOICE,
                '_token' => $form['tickit_project_attribute_choice[_token]']->getValue(),
                'name' => $newAttributeName,
                'default_value' => 'Off',
                'allow_blank' => 1,
                'expanded' => 1,
                'allow_multiple' => 0,
                'choices' => array(
                    0 => array(
                        'name' => 'Choice 1'
                    ),
                    1 => array(
                        'name' => 'Choice 2'
                    )
                )
            )
        );
        $client->request($form->getMethod(), $form->getUri(), $values);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());

        $this->assertTrue($response->success);
        $this->assertFalse(isset($response->form));

        $this->assertEquals($totalAttributes + 1, count($attributeRepo->findAll()));

        $attribute = $attributeRepo->findOneByName($newAttributeName);
        $this->assertInstanceOf('Tickit\ProjectBundle\Entity\ChoiceAttribute', $attribute);

        // clean up new attribute
        $em = $doctrine->getManager();
        $em->remove($attribute);
        $em->flush();
    }

    /**
     * Tests the createAction()
     *
     * @return void
     */
    public function testCreateActionForAttributeReturnsFormForInvalidDetails()
    {
        $client = $this->getAuthenticatedClient(static::$admin);

        $route = $this->generateRoute(
            'project_attribute_create_form',
            array('type' => AbstractAttribute::TYPE_LITERAL)
        );
        $crawler = $client->request('get', $route);
        $form = $crawler->selectButton('Save Project Attribute')->form();
        $client->submit(
            $form,
            array(
                'tickit_project_attribute_literal[type]' =>  AbstractAttribute::TYPE_LITERAL,
                'tickit_project_attribute_literal[name]' => '',
                'tickit_project_attribute_literal[default_value]' => '',
                'tickit_project_attribute_literal[allow_blank]' => 1,
                'tickit_project_attribute_literal[validation_type]' => ''
            )
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent());

        $this->assertFalse($response->success);
        $this->assertTrue(isset($response->form));
        $this->assertNotEmpty($response->form);
    }

    /**
     * Tests the editAction()
     *
     * Ensures that the editAction() updates literal attribute with valid details
     *
     * @return void
     */
    public function testEditActionForLiteralAttributeUpdatesAttribute()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $doctrine = $client->getContainer()->get('doctrine');
        $manager = $client->getContainer()->get('tickit_project.attribute_manager');

        $attribute = clone static::$literalAttribute;
        $attribute->setName(__FUNCTION__ . uniqid());
        $manager->create($attribute);

        $editRoute = $this->generateRoute('project_attribute_edit_form', array('id' => $attribute->getId()));
        $crawler = $client->request('get', $editRoute);

        $newAttributeName = strrev($attribute->getName());
        $form = $crawler->selectButton('Save Changes')->form();
        $client->submit(
            $form,
            array(
                'tickit_project_attribute_literal[type]' => AbstractAttribute::TYPE_LITERAL,
                'tickit_project_attribute_literal[name]' => $newAttributeName,
                'tickit_project_attribute_literal[default_value]' => '',
                'tickit_project_attribute_literal[allow_blank]' => 1,
                'tickit_project_attribute_literal[validation_type]' => LiteralAttribute::VALIDATION_IP
            )
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());

        $this->assertTrue($response->success);
        $this->assertFalse(isset($response->form));

        $doctrine->getManager()->refresh($attribute);
        $this->assertEquals($newAttributeName, $attribute->getName());
        $this->assertEquals(AbstractAttribute::TYPE_LITERAL, $attribute->getType());

        // clean up new attribute
        $doctrine->getManager()->remove($attribute);
        $doctrine->getManager()->flush();
    }

    /**
     * Tests the editAction()
     *
     * @return void
     */
    public function testEditActionForEntityAttributeUpdatesAttribute()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $doctrine = $client->getContainer()->get('doctrine');
        $manager = $client->getContainer()->get('tickit_project.attribute_manager');

        $attribute = clone static::$entityAttribute;
        $attribute->setName(__FUNCTION__ . uniqid());
        $manager->create($attribute);

        $editRoute = $this->generateRoute('project_attribute_edit_form', array('id' => $attribute->getId()));
        $crawler = $client->request('get', $editRoute);

        $newAttributeName = strrev($attribute->getName());
        $form = $crawler->selectButton('Save Changes')->form();
        $client->submit(
            $form,
            array(
                'tickit_project_attribute_entity[type]' => AbstractAttribute::TYPE_ENTITY,
                'tickit_project_attribute_entity[name]' => $newAttributeName,
                'tickit_project_attribute_entity[default_value]' => '1',
                'tickit_project_attribute_entity[allow_blank]' => 1,
                'tickit_project_attribute_entity[entity]' => $attribute->getEntity()
            )
        );

        $doctrine->getManager()->refresh($attribute);

        $this->assertEquals(AbstractAttribute::TYPE_ENTITY, $attribute->getType());
        $this->assertEquals($newAttributeName, $attribute->getName());
        $this->assertTrue($attribute->getAllowBlank());
        $this->assertEquals('1', $attribute->getDefaultValue());

        // clean up new attribute
        $doctrine->getManager()->remove($attribute);
        $doctrine->getManager()->flush();
    }

    /**
     * Tests the editAction()
     *
     * @return void
     */
    public function testEditActionForChoiceAttributeUpdatesAttribute()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $container = $client->getContainer();
        $manager = $container->get('tickit_project.attribute_manager');
        $doctrine = $container->get('doctrine');

        $choices = new ArrayCollection();
        $i = 3;
        while ($i--) {
            $choice = new ChoiceAttributeChoice();
            $choice->setName('choice ' . $i);
            $choices->add($choice);
        }

        /** @var ChoiceAttribute $newAttribute */
        $newAttribute = AbstractAttribute::factory(AbstractAttribute::TYPE_CHOICE);
        $newAttribute->setName(__FUNCTION__ . time());
        $newAttribute->setChoices($choices);

        $manager->create($newAttribute);

        $editRoute = $this->generateRoute('project_attribute_edit_form', array('id' => $newAttribute->getId()));
        $crawler = $client->request('get', $editRoute);
        $form = $crawler->selectButton('Save Changes')->form();

        $newName = strrev($newAttribute->getName());
        $values = array(
            'tickit_project_attribute_choice' => array(
                'type' => AbstractAttribute::TYPE_CHOICE,
                '_token' => $form['tickit_project_attribute_choice[_token]']->getValue(),
                'name' => $newName,
                'default_value' => 'off',
                'allow_blank' => 1,
                'expanded' => 0,
                'choices' => array(
                    0 => array(
                        'name' => 'Choice 3',
                    ),
                    1 => array(
                        'name' => 'Choice 4'
                    )
                )
            )
        );
        $client->request($form->getMethod(), $form->getUri(), $values);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertTrue($response->success);
        $this->assertFalse(isset($response->form));

        $doctrine->getManager()->refresh($newAttribute);

        $this->assertEquals($newName, $newAttribute->getName());
        $this->assertEquals(AbstractAttribute::TYPE_CHOICE, $newAttribute->getType());
        $this->assertEquals('off', $newAttribute->getDefaultValue());
        $this->assertFalse($newAttribute->getExpanded());
        $this->assertCount(2, $newAttribute->getChoicesAsArray());

        // clean up created object
        $doctrine->getManager()->remove($newAttribute);
        $doctrine->getManager()->flush();
    }

    /**
     * Tests the deleteAction()
     *
     * Ensures that the deleteAction() removes an attribute
     *
     * @return void
     */
    public function testDeleteActionDeletesAttribute()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $container = $client->getContainer();
        $manager = $container->get('tickit_project.attribute_manager');

        $newAttribute = AbstractAttribute::factory(AbstractAttribute::TYPE_LITERAL);
        $newAttribute->setName(__FUNCTION__ . time());
        $manager->create($newAttribute);

        $totalAttributes = count($manager->getRepository()->findAll());

        $token = $container->get('form.csrf_provider')->generateCsrfToken(AttributeController::CSRF_DELETE_INTENTION);

        $deleteRoute = $this->generateRoute(
            'project_attribute_delete',
            array(
                'id' => $newAttribute->getId(),
                'token' => $token
            )
        );
        $client->request('post', $deleteRoute);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $response = json_decode($client->getResponse()->getContent());
        $this->assertTrue($response->success);
        $this->assertEquals(--$totalAttributes, count($manager->getRepository()->findAll()));

        $nonExistentAttribute = $container->get('doctrine')
                                          ->getRepository('TickitProjectBundle:LiteralAttribute')
                                          ->findOneByName($newAttribute->getName());

        $this->assertNull($nonExistentAttribute);
    }

    /**
     * Tests the deleteAction()
     *
     * @return void
     */
    public function testDeleteActionReturns404ForInvalidToken()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $container = $client->getContainer();
        $manager = $container->get('tickit_project.attribute_manager');

        $newAttribute = AbstractAttribute::factory(AbstractAttribute::TYPE_LITERAL);
        $newAttribute->setName(__FUNCTION__ . time());
        $manager->create($newAttribute);

        $totalAttributes = count($manager->getRepository()->findAll());

        $deleteRoute = $this->generateRoute(
            'project_attribute_delete',
            array(
                'id' => $newAttribute->getId(),
                'token' => 'djwoajdowad'
            )
        );
        $client->request('post', $deleteRoute);

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals($totalAttributes, count($manager->getRepository()->findAll()));

        $manager->delete($newAttribute);
    }
}
