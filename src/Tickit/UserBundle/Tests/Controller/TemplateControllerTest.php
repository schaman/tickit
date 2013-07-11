<?php

namespace Tickit\UserBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;

/**
 * TemplateController tests
 *
 * @package Tickit\UserBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TemplateControllerTest extends AbstractFunctionalTest
{
    /**
     * Tests the createUserFormAction() method
     *
     * @return void
     */
    public function testCreateUserFormActionServesFormMarkup()
    {
        $client = $this->getAuthenticatedClient(static::$admin);

        $createRoute = $this->generateRoute('user_create_form');
        $crawler = $client->request('get', $createRoute);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals($this->generateRoute('user_create'), $crawler->filter('form')->attr('action'));
    }

    /**
     * Tests the createUserFormAction() method
     *
     * @return void
     */
    public function testCreateUserFormActionRendersEmptyPermissionsData()
    {
        $client = $this->getAuthenticatedClient(static::$admin);

        $crawler = $client->request('get', $this->generateRoute('user_create_form'));
        $this->assertEquals(2, $crawler->filter('div.data-list table tr')->count());
        $expectedMessage = 'You need to select a group for this user before you can edit permissions';
        $this->assertContains($expectedMessage, $client->getResponse()->getContent());
    }

    /**
     * Tests the editUserFormAction() method
     *
     * @return void
     */
    public function testEditUserFormActionServesFormMarkupForExistingUser()
    {
        $client = $this->getAuthenticatedClient(static::$admin);

        $editRoute = $this->generateRoute('user_edit_form', array('id' => static::$developer->getId()));
        $crawler = $client->request('get', $editRoute);

        $expectedRoute = $this->generateRoute('user_edit', array('id' => static::$developer->getId()));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals($expectedRoute, $crawler->filter('form')->attr('action'));
    }

    /**
     * Tests the editUserFormAction() method
     *
     * @return void
     */
    public function testEditUserFormActionRendersPermissionsData()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $route = $this->generateRoute('user_edit_form', array('id' => static::$developer->getId()));
        $crawler = $client->request('get', $route);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(2, $crawler->filter('div.data-list table tr')->count());
    }

    /**
     * Tests the editUserFormAction() method
     *
     * @return void
     */
    public function testEditUserFormActionReturns404ForNonExistentUser()
    {
        $client = $this->getAuthenticatedClient(static::$admin);

        $editRoute = $this->generateRoute('user_edit_form', array('id' => 9999999999));
        $client->request('get', $editRoute);

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}