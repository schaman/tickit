<?php

namespace Tickit\NavigationBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;

/**
 * ApiController tests
 *
 * @package Tickit\NavigationBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiControllerTest extends AbstractFunctionalTest
{
    /**
     * Tests the navItemsAction() method
     *
     * @return void
     */
    public function testNavItemsActionReturnsItems()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $client->request('get', $this->generateRoute('api_navigation_items'));
        $response = json_decode($client->getResponse()->getContent());

        $this->assertInternalType('array', $response);
        $this->assertCount(4, $response);
    }
}
