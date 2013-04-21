<?php


namespace Tickit\ProjectBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\ProjectBundle\Entity\AbstractAttribute;
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
     * Makes sure project attribute actions are behind firewall
     *
     * @return void
     */
    public function testAttributeActionsAreBehindFirewall()
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');

        $client->request('get', $router->generate('project_attribute_index'));

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertEquals('Login', $crawler->filter('h2')->text());
    }

    /**
     * Tests the indexAction()
     *
     * @return void
     */
    public function testIndexActionLayout()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');

        $crawler = $client->request('get', $router->generate('project_attribute_index'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('Manage Project Attributes', $crawler->filter('h2')->text());
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
        $router = $client->getContainer()->get('router');

        $client->request('get', $router->generate('project_attribute_create', array('type' => 'aaaaaa')));
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * Tests the indexAction()
     *
     * @return void
     */
    public function testIndexActionDisplaysCorrectNumberOfAttributes()
    {
        $client = $this->getAuthenticatedClient(static::$developer);
        $router = $client->getContainer()->get('router');

        $crawler = $client->request('get', $router->generate('project_attribute_index'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $manager = $client->getContainer()->get('tickit_project.attribute_manager');
        $repository = $manager->getRepository();
        $total = count($repository->findAll());

        $this->assertEquals($total, $crawler->filter('.data-list table tbody tr')->count());
    }

    /**
     * Tests the createAction()
     *
     * Makes sure that the creation process works for LiteralAttribute types
     *
     * @todo Improve this when templating is finalised
     *
     * @return void
     */
    public function testCreateActionForLiteralAttributeCreatesAttribute()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');

        $crawler = $client->request('get', $router->generate('project_attribute_index'));
        $totalAttributes = $crawler->filter('div.data-list table tbody tr')->count();

        $createRoute = $router->generate('project_attribute_create', array('type' => AbstractAttribute::TYPE_LITERAL));
        $crawler = $client->request('get', $createRoute);

        $form = $crawler->selectButton('Save Project Attribute')->form(
            array(
                'tickit_project_attribute_literal[type]' => AbstractAttribute::TYPE_LITERAL,
                'tickit_project_attribute_literal[name]' => 'Test Attribute ' . uniqid(), //needs to be unique
                'tickit_project_attribute_literal[default_value]' => 'n/a',
                'tickit_project_attribute_literal[allow_blank]' => 1,
                'tickit_project_attribute_literal[validation_type]' => LiteralAttribute::VALIDATION_EMAIL
            )
        );
        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('div.flash-notice:contains("The attribute has been created successfully")')->count()
        );
        $this->assertEquals($totalAttributes + 1, $crawler->filter('div.data-list table tbody tr')->count());
    }

    /**
     * Tests the createAction()
     *
     * @return void
     */
    public function testCreateActionForEntityAttributeCreatesAttribute()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');

        $crawler = $client->request('get', $router->generate('project_attribute_index'));
        $totalAttributes = $crawler->filter('div.data-list table tbody tr')->count();

        $createRoute = $router->generate('project_attribute_create', array('type' => AbstractAttribute::TYPE_ENTITY));
        $crawler = $client->request('get', $createRoute);

        $form = $crawler->selectButton('Save Project Attribute')->form(
            array(
                'tickit_project_attribute_entity[type]' => AbstractAttribute::TYPE_ENTITY,
                'tickit_project_attribute_entity[name]' => 'Test Attribute ' . uniqid(), //needs to be unique
                'tickit_project_attribute_entity[default_value]' => 'n/a',
                'tickit_project_attribute_entity[allow_blank]' => 1,
                'tickit_project_attribute_entity[entity]' => 'Tickit\ProjectBundle\Entity\Project'
            )
        );
        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('div.flash-notice:contains("The attribute has been created successfully")')->count()
        );
        $this->assertEquals($totalAttributes + 1, $crawler->filter('div.data-list table tbody tr')->count());
    }

    /**
     * Tests the createAction()
     *
     * @return void
     */
    public function testCreateActionForChoiceAttributeCreatesAttribute()
    {
        $this->markTestSkipped('Testing this form is not possible due to its use of JS');

        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');

        $crawler = $client->request('get', $router->generate('project_attribute_index'));
        $totalAttributes = $crawler->filter('div.data-list table tbody tr')->count();

        $createRoute = $router->generate('project_attribute_create', array('type' => AbstractAttribute::TYPE_CHOICE));
        $crawler = $client->request('get', $createRoute);

        $form = $crawler->selectButton('Save Project Attribute')->form();
        $form->setValues(
            array(
                'tickit_project_attribute_choice[type]' => AbstractAttribute::TYPE_CHOICE,
                'tickit_project_attribute_choice[name]' => 'Test Attribute' . uniqid(), //needs to be unique
                'tickit_project_attribute_choice[default_value]' => 'Off',
                'tickit_project_attribute_choice[allow_blank]' => 1,
                'tickit_project_attribute_choice[expanded]' => 1,
                'tickit_project_attribute_choice[allow_multiple]' => 0
            )
        );
        $values = $form->getPhpValues();
        $client->request($form->getMethod(), $form->getUri(), $values);
        $crawler = $client->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('div.flash-notice:contains("The attribute has been created successfully")')->count()
        );
        $this->assertEquals($totalAttributes + 1, $crawler->filter('div.data-list table tbody tr')->count());
    }

    /**
     * Tests the createAction()
     *
     * Ensures that the createAction() displays validation messages for invalid details
     *
     * @todo Improve this when templating is finalised
     *
     * @return void
     */
    public function testCreateActionForLiteralAttributeDisplaysErrorsForInvalidDetails()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');

        $createRoute = $router->generate('project_attribute_create', array('type' => AbstractAttribute::TYPE_LITERAL));
        $crawler = $client->request('get', $createRoute);
        $form = $crawler->selectButton('Save Project Attribute')->form();
        $crawler = $client->submit(
            $form,
            array(
                'tickit_project_attribute_literal[type]' =>  AbstractAttribute::TYPE_LITERAL,
                'tickit_project_attribute_literal[name]' => '',
                'tickit_project_attribute_literal[default_value]' => '',
                'tickit_project_attribute_literal[allow_blank]' => 1,
                'tickit_project_attribute_literal[validation_type]' => LiteralAttribute::VALIDATION_DATE
            )
        );
        $this->assertGreaterThan(0, $crawler->filter('form div ul li')->count());
    }

    /**
     * Tests the createAction()
     *
     * @return void
     */
    public function testCreateActionForEntityAttributeDisplaysErrorsForInvalidDetails()
    {
        $this->markTestSkipped('Needs fixing');

        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');

        $createRoute = $router->generate('project_attribute_create', array('type' => AbstractAttribute::TYPE_ENTITY));
        $crawler = $client->request('get', $createRoute);
        $form = $crawler->selectButton('Save Project Attribute')->form();
        $crawler = $client->submit(
            $form,
            array(
                'tickit_project_attribute_entity[type]' =>  AbstractAttribute::TYPE_LITERAL,
                'tickit_project_attribute_entity[name]' => '',
                'tickit_project_attribute_entity[default_value]' => '',
                'tickit_project_attribute_entity[allow_blank]' => 1,
                'tickit_project_attribute_entity[entity]' => 'Tickit\ProjectBundle\Entity\Project'
            )
        );

        $this->assertGreaterThan(0, $crawler->filter('form div ul li')->count());
    }

    /**
     * Tests the createAction()
     *
     * @return void
     */
    public function testCreateActionForChoiceAttributeDisplaysErrorsForInvalidDetails()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');

        $createRoute = $router->generate('project_attribute_create', array('type' => AbstractAttribute::TYPE_CHOICE));
        $crawler = $client->request('get', $createRoute);
        $form = $crawler->selectButton('Save Project Attribute')->form();
        $crawler = $client->submit(
            $form,
            array(
                'tickit_project_attribute_choice[type]' =>  AbstractAttribute::TYPE_CHOICE,
                'tickit_project_attribute_choice[name]' => '',
                'tickit_project_attribute_choice[default_value]' => '',
                'tickit_project_attribute_choice[expanded]' => 0,
                'tickit_project_attribute_choice[allow_multiple]' => 0,
                'tickit_project_attribute_choice[allow_blank]' => 1
            )
        );
        $this->assertGreaterThan(0, $crawler->filter('form ul li')->count());
    }

    /**
     * Tests the editAction()
     *
     * Ensures that a 404 response is returned for an invalid attribute ID
     */
    public function testEditActionThrows404ForInvalidAttributeId()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');

        $editRoute = $router->generate(
            'project_attribute_edit',
            array('id' => 99999999, 'type' => AbstractAttribute::TYPE_LITERAL)
        );
        $client->request('get', $editRoute);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * Tests the editAction()
     *
     * Ensures that a 404 response is returned for an invalid type
     */
    public function testEditActionThrows404ForInvalidAttributeType()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');
        $manager = $client->getContainer()->get('tickit_project.attribute_manager');
        $attribute = clone static::$literalAttribute;
        $manager->create($attribute);

        $editRoute = $router->generate(
            'project_attribute_edit',
            array('type' => 'aaaaaa', 'id' => $attribute->getId())
        );
        $client->request('get', $editRoute);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
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
        $router = $client->getContainer()->get('router');
        $manager = $client->getContainer()->get('tickit_project.attribute_manager');

        $attribute = clone static::$literalAttribute;
        $attribute->setName(__FUNCTION__ . uniqid());
        $manager->create($attribute);

        $editRoute = $router->generate('project_attribute_edit', array('id' => $attribute->getId(), 'type' => AbstractAttribute::TYPE_LITERAL));
        $crawler = $client->request('get', $editRoute);

        $newAttributeName = strrev($attribute->getName());
        $form = $crawler->selectButton('Save Changes')->form();
        $crawler = $client->submit(
            $form,
            array(
                'tickit_project_attribute_literal[type]' => AbstractAttribute::TYPE_LITERAL,
                'tickit_project_attribute_literal[name]' => $newAttributeName,
                'tickit_project_attribute_literal[default_value]' => '',
                'tickit_project_attribute_literal[allow_blank]' => 1,
                'tickit_project_attribute_literal[validation_type]' => LiteralAttribute::VALIDATION_IP
            )
        );

        $this->assertGreaterThan(
            0,
            $crawler->filter('div.flash-notice:contains("The attribute has been updated successfully")')->count()
        );
        $this->assertEquals(
            $newAttributeName,
            $crawler->filter('input[name="tickit_project_attribute_literal[name]"]')->attr('value')
        );

        $matcher = 'select[name="tickit_project_attribute_literal[validation_type]"] option[selected="selected"]';
        $this->assertEquals(LiteralAttribute::VALIDATION_IP, $crawler->filter($matcher)->attr('value'));
    }

    /**
     * Tests the editAction()
     *
     * @return void
     */
    public function testEditActionForEntityAttributeUpdatesAttribute()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');
        $manager = $client->getContainer()->get('tickit_project.attribute_manager');
        $attribute = clone static::$entityAttribute;
        $attribute->setName(__FUNCTION__ . uniqid());
        $manager->create($attribute);

        $editRoute = $router->generate(
            'project_attribute_edit',
            array('id' => $attribute->getId(), 'type' => AbstractAttribute::TYPE_ENTITY)
        );
        $crawler = $client->request('get', $editRoute);

        $newAttributeName = strrev($attribute->getName());
        $form = $crawler->selectButton('Save Changes')->form();
        $crawler = $client->submit(
            $form,
            array(
                'tickit_project_attribute_entity[type]' => AbstractAttribute::TYPE_ENTITY,
                'tickit_project_attribute_entity[name]' => $newAttributeName,
                'tickit_project_attribute_entity[default_value]' => '1',
                'tickit_project_attribute_entity[allow_blank]' => 1,
                'tickit_project_attribute_entity[entity]' => $attribute->getEntity()
            )
        );

        $this->assertGreaterThan(
            0,
            $crawler->filter('div.flash-notice:contains("The attribute has been updated successfully")')->count()
        );
        $this->assertEquals(
            $newAttributeName,
            $crawler->filter('input[name="tickit_project_attribute_entity[name]"]')->attr('value')
        );
        $this->assertEquals(
            1,
            $crawler->filter('input[name="tickit_project_attribute_entity[default_value]"]')->attr('value')
        );
    }

    /**
     * Tests the editAction()
     *
     * @return void
     */
    public function testEditActionForChoiceAttributeUpdatesAttribute()
    {
        $this->markTestSkipped('Testing this form is not possible due to its use of JS');
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
        $router = $client->getContainer()->get('router');

        $crawler = $client->request('get', $router->generate('project_attribute_index'));
        $totalAttributes = $crawler->filter('div.data-list table tbody tr')->count();
        $link = $crawler->filter('div.data-list a:contains("Delete")')->first()->link();
        $client->click($link);

        $crawler = $client->followRedirect();
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.flash-notice:contains("The attribute has been successfully deleted")')->count()
        );
        $this->assertEquals(--$totalAttributes, $crawler->filter('div.data-list table tbody tr')->count());
    }

    /**
     * Tests the deleteAction()
     *
     * @return void
     */
    public function testDeleteActionReturns404ForInvalidToken()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');

        $crawler = $client->request('get', $router->generate('project_attribute_index'));
        $linkHref = $crawler->filter('div.data-list a:contains("Delete")')->first()->attr('href');
        $linkHref .= 'dkwoadkowadawd';

        $client->request('get', $linkHref);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
