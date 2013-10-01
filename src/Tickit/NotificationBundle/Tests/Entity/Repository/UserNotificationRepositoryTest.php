<?php

namespace Tickit\NotificationBundle\Tests\Entity\Repository;

use Tickit\CoreBundle\Tests\AbstractOrmTest;
use Tickit\NotificationBundle\Entity\Repository\UserNotificationRepository;
use Tickit\UserBundle\Entity\User;

/**
 * UserNotificationRepository tests
 *
 * @package Tickit\NotificationBundle\Tests\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserNotificationRepositoryTest extends AbstractOrmTest
{
    /**
     * The repo under test
     *
     * @var UserNotificationRepository
     */
    private $repo;

    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();

        $em = $this->getEntityManager(
            'Tickit\\NotificationBundle\\Entity',
            array('TickitNotificationBundle' => 'Tickit\\NotificationBundle\\Entity')
        );

        $this->repo = $em->getRepository('TickitNotificationBundle:UserNotification');
    }

    /**
     * Tests the findGetFindUnreadForUserQueryBuilder() method
     */
    public function testGetFindUnreadForUserQueryBuilderBuildsQuery()
    {
        $user = new User();
        $user->setId(5);

        $builder = $this->repo->getFindUnreadForUserQueryBuilder($user);

        $from = $builder->getDQLPart('from');
        $this->assertNotEmpty($from);
        $this->assertEquals('TickitNotificationBundle:UserNotification', $from[0]->getFrom());

        $where = $builder->getDQLPart('where');
        $whereParts = $where->getParts();
        $this->assertCount(2, $whereParts);

        $firstWhere = array_shift($whereParts);
        $secondWhere = array_shift($whereParts);

        $this->assertEquals('n.recipient = :user_id', $firstWhere);
        $this->assertEquals('n.readAt IS NULL', $secondWhere);
    }
}
