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
            [IssueUserSubscription::MASK_ASSIGNEE_CHANGES]
        ];
    }

    /**
     * @dataProvider getAddFixtures
     */
    public function testAdd(array $masks, $expectedException = null)
    {
        if (null !== $expectedException) {
            $this->setExpectedException($expectedException);
        }

        $subscription = new IssueUserSubscription();
        $expectedMask = 0;
        foreach ($masks as $mask) {
            $subscription->add($mask);
            $expectedMask |= $mask;
        }

        $this->assertEquals($expectedMask, $subscription->getSubscriptionMask());
    }

    /**
     * @return array
     */
    public function getAddFixtures()
    {
        return [
            [['invalid'], '\InvalidArgumentException'],
            [[37585748], '\InvalidArgumentException'],
            [[IssueUserSubscription::MASK_ASSIGNEE_CHANGES, IssueUserSubscription::MASK_NEW_COMMENTS]],
            [[IssueUserSubscription::MASK_STATUS_CHANGES]]
        ];
    }

    /**
     * @dataProvider getRemoveFixtures
     */
    public function testRemove(IssueUserSubscription $subscription, $maskToRemove, $expectedMask, $expectedException = null)
    {
        if (null !== $expectedException) {
            $this->setExpectedException($expectedException);
        }

        $subscription->remove($maskToRemove);
        $this->assertEquals($expectedMask, $subscription->getSubscriptionMask());
    }

    /**
     * @return array
     */
    public function getRemoveFixtures()
    {
        $subscriptionWithCommentsMask = new IssueUserSubscription(IssueUserSubscription::MASK_NEW_COMMENTS);
        $subscriptionWithCommentsMask2 = clone $subscriptionWithCommentsMask;

        $subscriptionWithMultipleMasks = new IssueUserSubscription(
            IssueUserSubscription::MASK_NEW_COMMENTS | IssueUserSubscription::MASK_ASSIGNEE_CHANGES
        );
        $subscriptionWithNoMasks = new IssueUserSubscription();

        return [
            [$subscriptionWithCommentsMask, IssueUserSubscription::MASK_NEW_COMMENTS, 0],
            [$subscriptionWithMultipleMasks, IssueUserSubscription::MASK_ASSIGNEE_CHANGES, IssueUserSubscription::MASK_NEW_COMMENTS],
            [$subscriptionWithNoMasks, IssueUserSubscription::MASK_ASSIGNEE_CHANGES, 0],
            [$subscriptionWithCommentsMask2, IssueUserSubscription::MASK_STATUS_CHANGES, $subscriptionWithCommentsMask->getSubscriptionMask()],
            [$subscriptionWithMultipleMasks, 'invalid', null, '\InvalidArgumentException']
        ];
    }
}
