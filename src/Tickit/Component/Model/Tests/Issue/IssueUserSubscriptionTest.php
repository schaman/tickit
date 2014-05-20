<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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

namespace Tickit\Component\Model\Tests\Issue;

use Tickit\Component\Model\Issue\IssueUserSubscription;

/**
 * IssueUserSubscription tests
 *
 * @package Tickit\Component\Model\Tests\Issue
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueUserSubscriptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getConstructorFixtures
     */
    public function testConstructor($bitmask, $expectedException = null)
    {
        if (null !== $expectedException) {
            $this->setExpectedException($expectedException);
        }

        $subscription = new IssueUserSubscription($bitmask);

        $this->assertEquals($bitmask, $subscription->getSubscriptionMask());
    }

    /**
     * @return array
     */
    public function getConstructorFixtures()
    {
        return [
            ['not an integer', '\InvalidArgumentException'],
            [IssueUserSubscription::ASSIGNEE_CHANGES]
        ];
    }
}
