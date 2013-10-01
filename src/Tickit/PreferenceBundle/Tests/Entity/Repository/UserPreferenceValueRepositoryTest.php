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
        $this->markTestIncomplete('Needs finishing');

        $user = new User();
        $user->setId(1);

        $builder = $this->repo->getFindAllForUserQueryBuilder($user);
    }
}
