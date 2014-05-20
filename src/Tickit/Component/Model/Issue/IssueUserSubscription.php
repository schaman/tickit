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

namespace Tickit\Component\Model\Issue;

use Tickit\Component\Model\User\User;

/**
 * The IssueUserSubscription entity represents a user's subscription settings for a issue
 *
 * @package Tickit\Component\Model\Issue
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueUserSubscription
{
    const MASK_STATUS_CHANGES = 1;
    const MASK_NEW_COMMENTS = 2;
    const MASK_ASSIGNEE_CHANGES = 4;

    /**
     * The user that this subscription is for
     *
     * @var User
     */
    private $user;

    /**
     * The issue that this subscription is against
     *
     * @var Issue
     */
    private $issue;

    /**
     * The subscription bit mask
     *
     * @var integer
     */
    private $subscriptionMask;

    /**
     * Constructor.
     *
     * @param integer $subscriptionMask The mask value identifying what is subscribed to
     *
     * @throws \InvalidArgumentException If the $subscriptionMask value is not an integer
     */
    public function __construct($subscriptionMask = 0)
    {
        if (!is_int($subscriptionMask)) {
            throw new \InvalidArgumentException('The $subscriptionMask value must be an integer');
        }

        $this->subscriptionMask = $subscriptionMask;
    }

    /**
     * Gets subscription bit mask
     *
     * @return integer
     */
    public function getSubscriptionMask()
    {
        return $this->subscriptionMask;
    }

    /**
     * Adds a subscription mask to the subscription
     *
     * @param integer $subscriptionMask The subscription mask
     *
     * @throws \InvalidArgumentException If the mask is not valid
     */
    public function add($subscriptionMask)
    {
        if (false === $this->isMaskValid($subscriptionMask)) {
            throw new \InvalidArgumentException('The $subscriptionMask property is invalid');
        }

        $this->subscriptionMask = $this->subscriptionMask |= $subscriptionMask;
    }

    /**
     * Removes a subscription mask from the subscription
     *
     * @param integer $subscriptionMask The subscription mask
     *
     * @throws \InvalidArgumentException If the mask is not valid
     */
    public function remove($subscriptionMask)
    {
        if (false === $this->isMaskValid($subscriptionMask)) {
            throw new \InvalidArgumentException('The $subscriptionMask property is invalid');
        }

        $this->subscriptionMask &= ~$subscriptionMask;
    }

    /**
     * Returns true if the mask is valid, otherwise returns false
     *
     * @param integer $mask The mask to validate
     *
     * @return boolean
     */
    private function isMaskValid($mask)
    {
        return in_array(
            $mask,
            [static::MASK_STATUS_CHANGES, static::MASK_NEW_COMMENTS, static::MASK_ASSIGNEE_CHANGES]
        );
    }
}
