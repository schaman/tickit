<?php

namespace Tickit\NotificationBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\NotificationBundle\Entity\UserNotification;

/**
 * ApiController tests.
 *
 * @package Tickit\NotificationBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiControllerTest extends AbstractFunctionalTest
{
    /**
     * Tests the listAction() method
     *
     * @return void
     */
    public function testListActionListsNotificationsWhenNotRead()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $route = $this->generateRoute('api_notification_list');
        $client->request('get', $route);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertInternalType('array', $response);
        $this->assertNotEmpty($response);
    }

    /**
     * Tests the listAction() method
     *
     * @return void
     */
    public function testListActionListsNoNotificationsWhenNoneAvailable()
    {
        $client = $this->getAuthenticatedClient(static::$developer);
        $route = $this->generateRoute('api_notification_list');
        $client->request('get', $route);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertInternalType('array', $response);
        $this->assertEmpty($response);
    }

    /**
     * Tests the listAction() method
     *
     * @return void
     */
    public function testListActionDoesNotListReadNotifications()
    {
        $client = $this->getAuthenticatedClient(static::$developer);
        $doctrine = $client->getContainer()->get('doctrine');
        $em = $doctrine->getManager();
        $user = $doctrine->getRepository('TickitUserBundle:User')->find(static::$developer->getId());

        $userNotification = new UserNotification();
        $userNotification->setRecipient($user)
                         ->setMessage('test')
                         ->setActionUri('/');

        $em->persist($userNotification);
        $em->flush();

        $route = $this->generateRoute('api_notification_list');
        $client->request('get', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertInternalType('array', $response);
        $this->assertCount(1, $response);

        $userNotification->setReadAt(new \DateTime());
        $em->flush();

        $client->request('get', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertInternalType('array', $response);
        $this->assertEmpty($response);

        // cleanup
        $em->remove($userNotification);
        $em->flush();
    }
}
