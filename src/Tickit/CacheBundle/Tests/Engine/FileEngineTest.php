<?php

namespace Tickit\CacheBundle\Tests\Engine;

use Tickit\CacheBundle\Engine\FileEngine;
use Tickit\CacheBundle\Cache\Cache;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test suite for the file cache engine
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class FileEngineTest extends WebTestCase
{
    private $container;

    /**
     * Makes sure that the file engine is working together with the options resolver
     * to correctly configure valid and invalid values the cache directory option
     */
    public function testCacheDirectoryOption()
    {
        $validOptions = array(
            'cache_dir' => '/tmp'
        );

        $engine = $this->_getEngineInstance($validOptions);
        $this->assertEquals('/tmp', $engine->getOptions()->getCacheDir());

        $invalidOptions = array(
            'cache_dir' => '/etc'
        );

        $kernelCacheDir = $this->_getContainer()->getParameter('kernel.cache_dir');
        $engine = $this->_getEngineInstance($invalidOptions);
        $this->assertEquals($kernelCacheDir, $engine->getOptions()->getCacheDir());
    }

    /**
     * Makes sure that the auto_serialize option is properly configured
     */
    public function testAutoSerializeOption()
    {
        $validOptions = array(
            'auto_serialize' => false
        );

        $engine = $this->_getEngineInstance($validOptions);
        $this->assertEquals(false, $engine->getOptions()->getAutoSerialize());

        $invalidOptions = array(
            'auto_serialize' => 'notBoolean'
        );

        $engine = $this->_getEngineInstance($invalidOptions);
        $defaultAutoSerialize = $this->_getContainer()->getParameter('tickit_cache.file.auto_serialize');
        $this->assertEquals($defaultAutoSerialize, $engine->getOptions()->getAutoSerialize());
    }

    /**
     * Instantiates and returns a new FileEngine
     *
     * @param array $options An array of options to inject into the engine
     *
     * @return \Tickit\CacheBundle\Engine\FileEngine
     */
    private function _getEngineInstance(array $options)
    {
        $client = static::createClient();

        return new FileEngine($client->getContainer(), $options);
    }

    /**
     * Instantiates and returns the service container
     *
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private function _getContainer()
    {
        if (null === $this->container) {
            $client = static::createClient();
            $this->container = $client->getContainer();
        }

        return $this->container;
    }

}