<?php

namespace Tickit\UserBundle\Tests\Event;

use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Event\UpdateEvent;

/**
 * UpdateEventTest tests
 *
 * @package Tickit\UserBundle\Tests\Event
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UpdateEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Dummy user
     *
     * @var User
     */
    private $user;

    /**
     * Dummy user
     *
     * @var User
     */
    private $originalUser;

    /**
     * Event under test
     *
     * @var UpdateEvent
     */
    private $event;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->user = new User();
        $this->originalUser = new User();
        $this->event = new UpdateEvent($this->user, $this->originalUser);
    }

    /**
     * Tests the __construct() method
     */
    public function testConstructSetsUpEventCorrectly()
    {
        $this->assertSame($this->user, $this->event->getUser());
        $this->assertSame($this->originalUser, $this->event->getOriginalEntity());
    }

    /**
     * Tests the setUser() method
     */
    public function testSetUserSetsCorrectValue()
    {
        $anotherUser = new User();
        $this->event->setUser($anotherUser);

        $this->assertSame($anotherUser, $this->event->getUser());
    }
}
