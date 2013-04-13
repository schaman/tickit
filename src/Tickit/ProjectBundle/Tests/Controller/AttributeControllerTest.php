<?php


namespace Tickit\ProjectBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\ProjectBundle\Entity\AbstractAttribute;
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

        $form = $crawler->selectButton('Save Project Attribute')->form(array(
            'tickit_project_attribute_literal[type]' => AbstractAttribute::TYPE_LITERAL,
            'tickit_project_attribute_literal[name]' => 'Test Attribute ' . uniqid(), //needs to be unique
            'tickit_project_attribute_literal[default_value]' => 'n/a',
            'tickit_project_attribute_literal[allow_blank]' => 1,
            'tickit_project_attribute_literal[validation_type]' => LiteralAttribute::VALIDATION_EMAIL
        ));
        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertGreaterThan(0, $crawler->filter('div.flash-notice:contains("The attribute has been created successfully")')->count());
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

        $crawler = $client->request('get', $router->generate('project_attribute_create', array('type' => AbstractAttribute::TYPE_LITERAL)));
        $form = $crawler->selectButton('Save Project')->form();
        $crawler = $client->submit($form, array(
            'tickit_project_attribute_literal[type]' =>  AbstractAttribute::TYPE_LITERAL,
            'tickit_project_attribute_literal[name]' => '',
            'tickit_project_attribute_literal[default_value]' => '',
            'tickit_project_attribute_literal[allow_blank]' => 1,
            'tickit_project_attribute_literal[validation_type]' => LiteralAttribute::VALIDATION_DATE
        ));
        $this->assertGreaterThan(0, $crawler->filter('div#tickit_project_attribute_literal ul li')->count());
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

        $client->request('get', $router->generate('project_attribute_edit', array('id' => 99999999, 'type' => AbstractAttribute::TYPE_LITERAL)));
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

        $client->request('get', $router->generate('project_attribute_edit', array('type' => 'aaaaaa', 'id' => $attribute->getId())));
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * Tests the editAction()
     *
     * Ensures that the editAction() updates literal attribute with valid details
     *
     * @return void
     */
    public function testEditActionUpdatesLiteralAttribute()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');

        $crawler = $client->request('get', $router->generate('project_attribute_index'));
        $currentAttributeName = $crawler->filter('div.data-list tbody tr td')->eq(1)->text();
        $link = $crawler->filter('div.data-list a:contains("Edit")')->first()->link();
        $crawler = $client->click($link);

        $newAttributeName = strrev($currentAttributeName);
        $form = $crawler->selectButton('Save Changes')->form();
        $crawler = $client->submit($form, array(
            'tickit_project_attribute_literal[type]' => AbstractAttribute::TYPE_LITERAL,
            'tickit_project_attribute_literal[name]' => $newAttributeName,
            'tickit_project_attribute_literal[default_value]' => '',
            'tickit_project_attribute_literal[allow_blank]' => 1,
            'tickit_project_attribute_literal[validation_type]' => LiteralAttribute::VALIDATION_IP
        ));

        $this->assertGreaterThan(0, $crawler->filter('div.flash-notice:contains("The attribute has been updated successfully")')->count());
        $this->assertEquals($newAttributeName, $crawler->filter('input[name="tickit_project_attribute_literal[name]"]')->attr('value'));
        $this->assertEquals(LiteralAttribute::VALIDATION_IP, $crawler->filter('select[name="tickit_project_attribute_literal[validation_type]"] option[selected="selected"]')->attr('value'));
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
        $this->assertGreaterThan(0, $crawler->filter('div.flash-notice:contains("The attribute has been successfully deleted")')->count());
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
