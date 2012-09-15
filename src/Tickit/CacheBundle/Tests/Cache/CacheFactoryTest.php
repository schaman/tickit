<?php

namespace Tickit\CacheBundle\Tests\Cache;

use PHPUnit_Framework_TestCase;
use Tickit\CacheBundle\Cache\CacheFactory;
use Tickit\CacheBundle\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test suite for the core cache factory file
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class CacheFactoryTest extends WebTestCase
{
    /**
     * Makes sure that the factory method of the Cache core file returns the correct engines
     */
    public function testValidEnginesInFactory()
    {
        $client = static::createClient();
        $cacheFactory = new CacheFactory($client->getContainer());

        $apcCache = $cacheFactory->factory(CacheFactory::APC_ENGINE, array());
        $apcEngine = $apcCache->getEngine();
        $this->assertEquals('Tickit\CacheBundle\Engine\ApcEngine', get_class($apcEngine));

        $memcachedCache = $cacheFactory->factory(CacheFactory::MEMCACHED_ENGINE, array());
        $memcachedEngine = $memcachedCache->getEngine();
        $this->assertEquals('Tickit\CacheBundle\Engine\MemcachedEngine', get_class($memcachedEngine));

        $fileCache = $cacheFactory->factory(CacheFactory::FILE_ENGINE, array());
        $fileEngine = $fileCache->getEngine();
        $this->assertEquals('Tickit\CacheBundle\Engine\FileEngine', get_class($fileEngine));
    }

    /**
     * Makes sure that the factory method of the Cache core file correctly validates the engine name
     */
    public function testInvalidEngineInFactory()
    {
        $client = static::createClient();
        $cacheFactory = new CacheFactory($client->getContainer());

        $invalidEngineName = 'BlahBlah';

        try {
            $invalidEngine = $cacheFactory->factory($invalidEngineName, array());
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(true, true);
        }

        $this->assertTrue(!isset($invalidEngine));
    }

}