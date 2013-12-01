<?php

namespace Tickit\Component\Model\Tests\Project;
use Tickit\Component\Model\Project\Project;

/**
 * Project tests
 *
 * @package Tickit\Component\Model\Tests\Project
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The project being tested
     *
     * @var Project
     */
    protected $sut;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->sut = new Project();
    }

    /**
     * Tests the constructor
     */
    public function testProjectInitialisesWithActiveStatus()
    {
        $this->assertEquals(Project::STATUS_ACTIVE, $this->sut->getStatus());
    }

    /**
     * Tests the setStatus() method
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetStatusThrowsExceptionForInvalidStatus()
    {
        $this->sut->setStatus('invalid');
    }

    /**
     * Tests the setStatus() method
     */
    public function testSetStatusAcceptsValidValue()
    {
        $this->sut->setStatus(Project::STATUS_ARCHIVED);

        $this->assertEquals(Project::STATUS_ARCHIVED, $this->sut->getStatus());
    }

    /**
     * Tests the getStatusTypes() method
     */
    public function testGetStatusTypesReturnsValidArray()
    {
        $statuses = Project::getStatusTypes();

        $this->assertContains(Project::STATUS_ARCHIVED, $statuses);
        $this->assertContains(Project::STATUS_ACTIVE, $statuses);
    }
}
 