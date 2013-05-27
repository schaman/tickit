<?php

namespace Tickit\UserBundle\Tests\Controller;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;

/**
 * GroupController tests.
 *
 * @package Tickit\UserBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class GroupControllerTest extends AbstractFunctionalTest
{
    /**
     * Tests the indexAction() method
     *
     * @return void
     */
    public function testIndexActionRendersGroups()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');

        $crawler = $client->request('get', $router->generate('group_index'));

        $this->assertEquals('Manage Groups', $crawler->filter('h2')->text());
        $this->assertGreaterThan(0, $crawler->filter('div.data-list table tbody tr')->count());
    }

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



        $count = $crawler->filter('div.flash-notice:contains("The user has been created successfully")')->count();
        $this->assertGreaterThan(0, $count);
    }

    /**
     * Tests the createAction() method
     *
     * @return void
     */
    public function testCreateActionShowsErrorsForInvalidDetails()
    {

    }

    /**
     * Tests the editAction() method
     *
     * @return void
     */
    public function testEditActionUpdatesExistingGroupWithValidDetails()
    {

    }

    /**
     * Tests the editAction() method
     *
     * @return void
     */
    public function testEditActionShowsErrorsForInvalidDetails()
    {

    }
}
