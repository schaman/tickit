<?php

namespace Tickit\CoreBundle\Tests\Flash;

use Symfony\Component\HttpFoundation\Session\Session;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;

/**
 * Tests for the flash message provider
 *
 * @package Tickit\CoreBundle\Tests\Flash
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProviderTest extends AbstractFunctionalTest
{
    /**
     * The session object
     *
     * @var Session
     */
    protected static $session;

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        static::$session = new Session();

        parent::setUpBeforeClass();
    }

    /**
     * Test to ensure that the provider service exists
     *
     * @return void
     */
    public function testServiceExists()
    {
        $container = static::createClient()->getContainer();
        $provider = $container->get('tickit.flash_messages');
        $this->assertInstanceOf('Tickit\CoreBundle\Flash\ProviderInterface', $provider);
    }

    /**
     * Test to ensure the correct exception is thrown when no replacement value is provided
     *
     * @return void
     */
    public function testCorrectExceptionThrownForEmptyReplacementValue()
    {
        $container = static::createClient()->getContainer();
        $provider = $container->get('tickit.flash_messages');
        $this->setExpectedException('\RuntimeException');

        $provider->addEntityCreatedMessage('');
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
        $container = static::createClient()->getContainer();
        $provider = $container->get('tickit.flash_messages');
        $provider->addEntityCreatedMessage('team');

        $messages = $container->get('session')->getFlashBag()->get('notice');
        $message = array_pop($messages);

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
        $container = static::createClient()->getContainer();
        $provider = $container->get('tickit.flash_messages');
        $provider->addEntityUpdatedMessage('user');

        $messages = $container->get('session')->getFlashBag()->get('notice');
        $message = array_pop($messages);

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
        $container = static::createClient()->getContainer();
        $provider = $container->get('tickit.flash_messages');
        $provider->addEntityDeletedMessage('project');

        $messages = $container->get('session')->getFlashBag()->get('notice');
        $message = array_pop($messages);

        $this->assertEquals('The project has been successfully deleted', $message);
    }
}
