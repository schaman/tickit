<?php

/*
 * Tickit, an open source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\NotificationBundle\Tests\Controller;

use Tickit\Bundle\CoreBundle\Tests\AbstractUnitTest;
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
