<?php

namespace Tickit\NotificationBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\NotificationBundle\Controller\NotificationController;

/**
 * NotificationController tests
 *
 * @package Tickit\NotificationBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class NotificationControllerTest extends AbstractUnitTest
{
    /**
     * Tests the markAsReadAction() method
     */
    public function testMarkAsReadActionBuildsCorrectResponse()
    {
        $expectedData = ['success' => false];

        $response = $this->getController()->markAsReadAction();
        $this->assertEquals($expectedData, json_decode($response->getContent(), true));
    }

    /**
     * Gets a new controller instance
     *
     * @return NotificationController
     */
    private function getController()
    {
        return new NotificationController();
    }
}
