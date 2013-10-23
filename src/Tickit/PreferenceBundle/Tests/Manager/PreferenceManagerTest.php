<?php

namespace Tickit\PreferenceBundle\Tests\Manager;

use Tickit\PreferenceBundle\Manager\PreferenceManager;

/**
 * PreferenceManagerTest tests
 *
 * @package Tickit\PreferenceBundle\Tests\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PreferenceManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The manager under test
     *
     * @var PreferenceManager
     */
    private $manager;

    /**
     * Setup
     */
    protected function setUp()
    {
        $repository = $this->getMockBuilder('Tickit\PreferenceBundle\Entity\Repository\PreferenceRepository')
                           ->disableOriginalConstructor()
                           ->getMock();

        $this->manager = new PreferenceManager($repository);
    }

    /**
     * Tests the getRepository() method
     */
    public function testGetRepositoryReturnsCorrectInstance()
    {
        $repository = $this->manager->getRepository();

        $this->assertInstanceOf('Tickit\PreferenceBundle\Entity\Repository\PreferenceRepository', $repository);
    }
}
