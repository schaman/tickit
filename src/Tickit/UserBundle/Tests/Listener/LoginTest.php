<?php

namespace Tickit\UserBundle\Tests\Listener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\UserBundle\Entity\User;

/**
 * Login listener tests.
 *
 * @package Tickit\UserBundle\Tests\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class LoginTest extends AbstractFunctionalTest
{
    /**
     * Tests the onSecurityInteractiveLogin() method
     *
     * @return void
     */
    public function testOnSecurityInteractiveLoginCreatesSessionForUser()
    {
        $container = $this->createClient()->getContainer();
        $doctrine = $container->get('doctrine');
        $manager = $container->get('tickit_user.manager');

        $group = $doctrine->getRepository('TickitUserBundle:Group')->findOneByName('Administrators');

        $user = $this->createNewUser();
        $user->setGroup($group);
        $manager->create($user);

        $event = $this->getLoginEvent($user);
        $listener = $container->get('tickit_user.listener.login');
        $listener->onSecurityInteractiveLogin($event);

        $sessions = $doctrine->getRepository('TickitUserBundle:UserSession')->findByUser($user->getId());
        $this->assertCount(1, $sessions);
    }

    /**
     * Gets a new interactive login event
     *
     * @param User $user The user to create a login event for
     *
     * @return InteractiveLoginEvent
     */
    private function getLoginEvent(User $user = null)
    {
        $request = new Request();
        $token = new UsernamePasswordToken($user, $user->getPlainPassword(), 'main', array());
        $event = new InteractiveLoginEvent($request, $token);

        return $event;
    }
}
