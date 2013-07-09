<?php

namespace Tickit\ProjectBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\ProjectBundle\Entity\AbstractAttribute;
use Tickit\ProjectBundle\Entity\Project;

/**
 * TemplateController tests
 *
 * @package Tickit\ProjectBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class TemplateControllerTest extends AbstractFunctionalTest
{
    /**
     * Sample project entity
     *
     * @var Project
     */
    protected static $project;

    /**
     * Sample attribute entity
     *
     * @var AbstractAttribute
     */
    protected static $attribute;

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $doctrine = static::createClient()->getContainer()->get('doctrine');

        static::$project = $doctrine->getRepository('TickitProjectBundle:Project')
                                    ->findOneByName('Test Project 1');

        static::$attribute = $doctrine->getRepository('TickitProjectBundle:LiteralAttribute')
                                      ->findOneByName('Due Date');
    }

    /**
     * Tests the createProjectFormAction() method
     *
     * @return void
     */
    public function testCreateProjectFormActionServesCorrectMarkup()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $crawler = $client->request('get', $this->generateRoute('project_create_form'));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('input')->count());

        $formActionRoute = $this->generateRoute('project_create');
        $this->assertEquals($formActionRoute, $crawler->filter('form')->attr('action'));
    }

    /**
     * Tests the editProjectFormAction() method
     *
     * @return void
     */
    public function testEditProjectFormActionServesCorrectMarkup()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $route = $this->generateRoute('project_edit_form', array('id' => static::$project->getId()));
        $crawler = $client->request('get', $route);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('input')->count());

        $formActionRoute = $this->generateRoute('project_edit', array('id' => static::$project->getId()));
        $this->assertEquals($formActionRoute, $crawler->filter('form')->attr('action'));
    }

    /**
     * Tests the createProjectAttributeFormAction() method
     *
     * @return void
     */
    public function testCreateProjectAttributeFormActionThrowsExceptionForInvalidType()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $route = $this->generateRoute('project_attribute_create', array('type' => 'invalid'));

        $client->request('get', $route);

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * Tests the createProjectAttributeFormAction() method
     *
     * @return void
     */
    public function testCreateProjectAttributeFormActionServesCorrectMarkup()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $route = $this->generateRoute('project_attribute_create_form', array('type' => AbstractAttribute::TYPE_LITERAL));
        $crawler = $client->request('get', $route);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('input')->count());
        $expectedRoute = $this->generateRoute(
            'project_attribute_create',
            array('type' => AbstractAttribute::TYPE_LITERAL)
        );
        $this->assertEquals($expectedRoute, $crawler->filter('form')->attr('action'));
    }

    /**
     * Tests the editProjectAttributeFormAction() method
     *
     * @return void
     */
    public function testEditProjectAttributeFormActionServesCorrectMarkup()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $route = $this->generateRoute('project_attribute_edit_form', array('id' => static::$attribute->getId()));
        $crawler = $client->request('get', $route);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('input')->count());
        $expectedRoute = $this->generateRoute('project_attribute_edit', array('id' => static::$attribute->getId()));

        $this->assertEquals($expectedRoute, $crawler->filter('form')->attr('action'));
    }
}
