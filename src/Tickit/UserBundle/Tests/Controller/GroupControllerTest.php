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
        $this->markTestIncomplete();
    }

    /**
     * Tests the editAction() method
     *
     * @return void
     */
    public function testEditActionUpdatesExistingGroupWithValidDetails()
    {
        $this->markTestIncomplete();
    }

    /**
     * Tests the editAction() method
     *
     * @return void
     */
    public function testEditActionReturnsFormContentsForInvalidDetails()
    {
        $this->markTestIncomplete();
    }
}
