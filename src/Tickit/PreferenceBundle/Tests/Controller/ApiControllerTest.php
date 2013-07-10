<?php

namespace Tickit\PreferenceBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\PreferenceBundle\Manager\PreferenceManager;

/**
 * ApiController tests
 *
 * @package Tickit\PreferenceBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiControllerTest extends AbstractFunctionalTest
{
    /**
     * Tests the listAction() method
     *
     * @return void
     */
    public function testListActionDisplaysCorrectNumberOfPreferences()
    {
        $client = $this->getAuthenticatedClient(static::$developer);

        $client->request('get', $this->generateRoute('api_preference_list'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $preferenceManager = $client->getContainer()->get('tickit_preference.manager');
        $repository = $preferenceManager->getRepository();
        $total = count($repository->findAll());

        $response = json_decode($client->getResponse()->getContent());
        $this->assertInternalType('array', $response);
        $this->assertCount($total, $response);
    }
}
