<?php

namespace Tickit\CoreBundle\Tests\Flash;

use Symfony\Component\HttpFoundation\Session\Session;
use Tickit\CoreBundle\Flash\Provider;
use Tickit\CoreBundle\Flash\ProviderInterface;
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
        $generator = $this->getProvider();
        $this->assertInstanceOf('Tickit\CoreBundle\Flash\ProviderInterface', $generator);
    }

    /**
     * Test to ensure the correct exception is thrown when no replacement value is provided
     *
     * @return void
     */
    public function testCorrectExceptionThrownForEmptyReplacementValue()
    {
        $generator = $this->getProvider();
        $this->setExpectedException('\RuntimeException');

        $generator->addEntityCreatedMessage('');
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
        $provider = $this->getProvider();
        $provider->addEntityCreatedMessage('team');

        $container = static::createClient()->getContainer();
        $messages = $container->get('session')->getFlashBag()->get('notice');
        var_dump($messages); die;
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
        $provider = $this->getProvider();
        $provider->addEntityUpdatedMessage('user');

        $container = static::createClient()->getContainer();
        $message = $container->get('session')->getFlashBag()->get('notice');

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
        $provider = $this->getProvider();
        $provider->addEntityDeletedMessage('project');

        $container = static::createClient()->getContainer();
        $message = $container->get('session')->getFlashBag()->get('notice');

        $this->assertEquals('The project has been successfully deleted', $message);
    }

    /**
     * Gets a new flash message generator
     *
     * @return ProviderInterface
     */
    protected function getProvider()
    {
        $container = static::createClient()->getContainer();
        $provider = $container->get('tickit.flash_messages');

        return $provider;
    }
}
