<?php

namespace Tickit\UserBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;

/**
 * API Controller tests.
 *
 * @package Tickit\UserBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiControllerTest extends AbstractFunctionalTest
{
    /**
     * Tests the fetchAction() method
     *
     * @return void
     */
    public function testFetchActionReturnsCorrectUserDetailsForValidId()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $requestedUser = static::$developer;

        $client->request('get', $this->generateRoute('api_user_fetch', array('id' => $requestedUser->getId())));

        $response = json_decode($client->getResponse()->getContent());
        $this->assertInstanceOf('\stdClass', $response);
        $this->assertEquals($requestedUser->getId(), $response->id);
        $this->assertEquals($requestedUser->getUsername(), $response->username);
        $this->assertEquals($requestedUser->getEmail(), $response->email);
        $this->assertEquals($requestedUser->getForename(), $response->forename);
        $this->assertEquals($requestedUser->getSurname(), $response->surname);
        $this->assertNotEmpty($response->avatarUrl);
    }

    /**
     * Tests the fetchAction()
     *
     * @return void
     */
    public function testFetchActionReturnsCurrentlyLoggedInUserWhenInvalidIdIsProvided()
    {
        $client = $this->getAuthenticatedClient(static::$developer);
        $client->request('get', $this->generateRoute('api_user_fetch', array('id' => 3489689208)));

        $response = json_decode($client->getResponse()->getContent());
        $this->assertInstanceOf('\stdClass', $response);
        $this->assertEquals(static::$developer->getId(), $response->id);
        $this->assertEquals(static::$developer->getUsername(), $response->username);
        $this->assertEquals(static::$developer->getEmail(), $response->email);
        $this->assertEquals(static::$developer->getForename(), $response->forename);
        $this->assertEquals(static::$developer->getSurname(), $response->surname);
        $this->assertNotEmpty($response->avatarUrl);
    }

    /**
     * Tests the fetchAction()
     *
     * @return void
     */
    public function testFetchActionReturnsCurrentlyLoggedInUserWhenNoIdIsProvided()
    {
        $client = $this->getAuthenticatedClient(static::$developer);
        $client->request('get', $this->generateRoute('api_user_fetch'));

        $response = json_decode($client->getResponse()->getContent());
        $this->assertInstanceOf('\stdClass', $response);
        $this->assertEquals(static::$developer->getId(), $response->id);
        $this->assertEquals(static::$developer->getUsername(), $response->username);
        $this->assertEquals(static::$developer->getEmail(), $response->email);
        $this->assertEquals(static::$developer->getForename(), $response->forename);
        $this->assertEquals(static::$developer->getSurname(), $response->surname);
        $this->assertNotEmpty($response->avatarUrl);
    }
}
