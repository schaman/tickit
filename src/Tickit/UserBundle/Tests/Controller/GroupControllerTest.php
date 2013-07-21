<?php

namespace Tickit\UserBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\UserBundle\Entity\Group;

/**
 * GroupController tests.
 *
 * @package Tickit\UserBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class GroupControllerTest extends AbstractFunctionalTest
{
    /**
     * Tests the createAction() method
     *
     * @return void
     */
    public function testCreateActionCreatesGroupWithValidDetails()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $createRoute = $this->generateRoute('group_create_form');
        $crawler = $client->request('get', $createRoute);

        $groupName = __FUNCTION__ . time();
        $form = $crawler->selectButton('Save User Group')->form(
            array('tickit_group[name]' => $groupName)
        );
        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertTrue($response->success);
        $this->assertFalse(isset($response->form));
    }

    /**
     * Tests the createAction() method
     *
     * @return void
     */
    public function testCreateActionReturnsFormContentsForInvalidDetails()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $createRoute = $this->generateRoute('group_create_form');
        $crawler = $client->request('get', $createRoute);

        $form = $crawler->selectButton('Save User Group')->form(
            array('tickit_group[name]' => '')
        );
        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertFalse($response->success);
        $this->assertTrue(isset($response->form));
    }

    /**
     * Tests the editAction() method
     *
     * @return void
     */
    public function testEditActionUpdatesExistingGroupWithValidDetails()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $container = $client->getContainer();
        $doctrine = $container->get('doctrine');
        $group = new Group(__FUNCTION__ . time());
        $container->get('tickit_user.group_manager')->create($group);

        $editRoute = $this->generateRoute('group_edit_form', ['id' => $group->getId()]);
        $crawler = $client->request('get', $editRoute);

        $newName = __FUNCTION__ . uniqid();
        $form = $crawler->selectButton('Save Changes')->form(['tickit_group[name]' => $newName]);
        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertTrue($response->success);
        $this->assertFalse(isset($response->form));

        $doctrine->getManager()->refresh($group);
        $this->assertEquals($newName, $group->getName());
    }

    /**
     * Tests the editAction() method
     *
     * @return void
     */
    public function testEditActionReturnsFormContentsForInvalidDetails()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $container = $client->getContainer();
        $group = new Group(__FUNCTION__ . time());
        $container->get('tickit_user.group_manager')->create($group);

        $editRoute = $this->generateRoute('group_edit_form', ['id' => $group->getId()]);
        $crawler = $client->request('get', $editRoute);

        $form = $crawler->selectButton('Save Changes')->form(['tickit_group[name]' => '']);
        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertFalse($response->success);
        $this->assertTrue(isset($response->form));
    }
}
