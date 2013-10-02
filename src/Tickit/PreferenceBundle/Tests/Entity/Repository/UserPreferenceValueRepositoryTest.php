<?php

namespace Tickit\PreferenceBundle\Tests\Entity\Repository;

use Tickit\CoreBundle\Tests\AbstractOrmTest;
use Tickit\PreferenceBundle\Entity\Repository\UserPreferenceValueRepository;
use Tickit\UserBundle\Entity\User;

/**
 * UserPreferenceValueRepository tests
 *
 * @package Tickit\PreferenceBundle\Tests\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserPreferenceValueRepositoryTest extends AbstractOrmTest
{
    /**
     * The repo under test
     *
     * @var UserPreferenceValueRepository
     */
    private $repo;

    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();

        $em = $this->getEntityManager(
            'Tickit\\PreferenceBundle\\Entity',
            array('TickitPreferenceBundle' => 'Tickit\\PreferenceBundle\\Entity')
        );

        $this->repo = $em->getRepository('TickitPreferenceBundle:UserPreferenceValue');
    }

    /**
     * Tests the findAllForUserQueryBuilder() method
     */
    public function testGetFindAllForUserQueryBuilderBuildsQuery()
    {
        $user = new User();
        $user->setId(1);

        $builder = $this->repo->getFindAllForUserQueryBuilder($user);

        $from = $builder->getDQLPart('from');
        $this->assertNotEmpty($from);
        $this->assertEquals('TickitPreferenceBundle:UserPreferenceValue', $from[0]->getFrom());

        $joins = $builder->getDQLPart('join');
        $join = array_shift($joins['upv']);
        $this->assertEquals('INNER', $join->getJoinType());
        $this->assertEquals('upv.preference', $join->getJoin());

        $where = $builder->getDQLPart('where');
        $whereParts = $where->getParts();

        $this->assertCount(1, $whereParts);
        $expression = array_shift($whereParts);
        $this->assertEquals('upv.user = :user_id', $expression);

        $this->assertEquals(1, $builder->getParameter('user_id')->getValue());
    }
}
