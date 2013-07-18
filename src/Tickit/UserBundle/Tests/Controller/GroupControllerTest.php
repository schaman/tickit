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
        $faker = $this->getFakerGenerator();
        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');

        $crawler = $client->request('get', $router->generate('group_create'));

        $this->assertEquals('Create User Group', $crawler->filter('h2')->text());
        $form = $crawler->selectButton('Save User Group')->form(
            array(
                'tickit_group[name]' => 'Group-' . $faker->sha1
            )
        );

        $client->submit($form);
        $crawler = $client->followRedirect();

        $count = $crawler->filter('div.flash-notice:contains("The group has been created successfully")')->count();
        $this->assertGreaterThan(0, $count);
    }

    /**
     * Tests the createAction() method
     *
     * @return void
     */
    public function testCreateActionShowsErrorsForInvalidDetails()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');

        $crawler = $client->request('get', $router->generate('group_create'));

        $this->assertEquals('Create User Group', $crawler->filter('h2')->text());
        $form = $crawler->selectButton('Save User Group')->form(
            array(
                'tickit_group[name]' => ''
            )
        );

        $crawler = $client->submit($form);

        // the request should not redirect
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('Create User Group', $crawler->filter('h2')->text());
    }

    /**
     * Tests the editAction() method
     *
     * @return void
     */
    public function testEditActionUpdatesExistingGroupWithValidDetails()
    {
        $this->markTestSkipped('Needs refactoring to new API format');

        $faker = $this->getFakerGenerator();
        $client = $this->getAuthenticatedClient(static::$admin);
        $container = $client->getContainer();
        $em = $container->get('doctrine')->getManager();
        $router = $container->get('router');

        $group = new Group('Group-' . $faker->sha1);
        $em->persist($group);
        $em->flush();

        $crawler = $client->request('get', $router->generate('group_edit', array('id' => $group->getId())));

        $form = $crawler->selectButton('Save Changes')->form(
            array(
                'tickit_group[name]' => 'Group-' . $faker->sha1
            )
        );

        $client->submit($form);
        $crawler  = $client->followRedirect();

        $count = $crawler->filter('div.flash-notice:contains("The group has been updated successfully")')->count();
        $this->assertGreaterThan(0, $count);
    }

    /**
     * Tests the editAction() method
     *
     * @return void
     */
    public function testEditActionShowsErrorsForInvalidDetails()
    {
        $faker = $this->getFakerGenerator();
        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');
        $em = $client->getContainer()->get('doctrine')->getManager();

        $group = new Group('Group-' . $faker->sha1);
        $em->persist($group);
        $em->flush();

        $crawler = $client->request('get', $router->generate('group_edit', array('id' => $group->getId())));

        $form = $crawler->selectButton('Save Changes')->form(
            array(
                'tickit_group[name]' => ''
            )
        );

        $crawler = $client->submit($form);

        // the request should not redirect
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('Edit User Group', $crawler->filter('h2')->text());
    }
}
