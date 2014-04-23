<?php

namespace Tickit\Component\Issue\Tests\Listener;

use Tickit\Component\Entity\Event\EntityEvent;
use Tickit\Component\Issue\Listener\IssueCreatedByListener;
use Tickit\Component\Model\Issue\Issue;
use Tickit\Component\Model\User\User;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * IssueCreatedByListenerTest
 *
 * @package Tickit\Component\Issue\Tests\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueCreatedByListenerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $securityContext;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->securityContext = $this->getMockSecurityContext();
    }

    /**
     * @dataProvider getOnIssueCreateFixtures
     */
    public function testOnIssueCreate($issue, $loggedInUser, $expectedIssue)
    {
        $token = $this->getMockUsernamePasswordToken();
        $token->expects($this->once())
              ->method('getUser')
              ->will($this->returnValue($loggedInUser));

        $this->securityContext->expects($this->once())
                              ->method('getToken')
                              ->will($this->returnValue($token));

        $this->getListener()->onIssueCreate(new EntityEvent($issue));
        $this->assertEquals($expectedIssue, $issue);
    }

    /**
     * @return array
     */
    public function getOnIssueCreateFixtures()
    {
        $user = new User();
        $user->setForename('Joe')
             ->setSurname('Bloggs');

        $issue = new Issue();
        $expectedIssue = new Issue();
        $expectedIssue->setCreatedBy($user);

        return [
            [$issue, $user, $expectedIssue]
        ];
    }

    /**
     * @return IssueCreatedByListener
     */
    private function getListener()
    {
        return new IssueCreatedByListener($this->securityContext);
    }
}
