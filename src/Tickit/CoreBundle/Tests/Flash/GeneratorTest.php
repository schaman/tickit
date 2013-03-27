<?php

namespace Tickit\CoreBundle\Tests\Flash;

use Tickit\CoreBundle\Flash\GeneratorInterface;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;

/**
 * Tests for the flash message generator
 *
 * @package Tickit\CoreBundle\Tests\Flash
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class GeneratorTest extends AbstractFunctionalTest
{
    /**
     * Test to ensure that the generator service exists
     *
     * @return void
     */
    public function testServiceExists()
    {
        $generator = $this->getGenerator();
        $this->assertInstanceOf('Tickit\CoreBundle\Flash\GeneratorInterface', $generator);
    }

    /**
     * Test to ensure the correct exception is thrown when no replacement value is provided
     *
     * @return void
     */
    public function testCorrectExceptionThrownForEmptyReplacementValue()
    {
        $generator = $this->getGenerator();
        $this->setExpectedException('\RuntimeException');

        $generator->getEntityCreatedMessage('');
    }

    /**
     * Tests the getEntityCreatedMessage() method
     *
     * Ensures that a valid message string is returned
     *
     * @return void
     */
    public function testGetEntityCreatedMessage()
    {
        $generator = $this->getGenerator();
        $message = $generator->getEntityCreatedMessage('team');

        $this->assertEquals('The team has been created successfully', $message);
    }

    /**
     * Tests the getEntityUpdatedMessage() method
     *
     * Ensures that a valid message string is returned
     *
     * @return void
     */
    public function testGetEntityUpdatedMessage()
    {
        $generator = $this->getGenerator();
        $message = $generator->getEntityUpdatedMessage('user');

        $this->assertEquals('The user has been updated successfully', $message);
    }

    /**
     * Tests the getEntityDeletedMessage() method
     *
     * Ensures that a valid message string is returned
     *
     * @return void
     */
    public function testGetEntityDeletedMessage()
    {
        $generator = $this->getGenerator();
        $message = $generator->getEntityDeletedMessage('project');

        $this->assertEquals('The project has been successfully deleted', $message);
    }

    /**
     * Gets a new flash message generator
     *
     * @return GeneratorInterface
     */
    protected function getGenerator()
    {
        $container = static::createClient()->getContainer();
        $generator = $container->get('tickit.flash_messages');

        return $generator;
    }
}
