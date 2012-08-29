<?php

namespace Tickit\CacheBundle\Tests\Cache;

use PHPUnit_Framework_TestCase;
use Tickit\CacheBundle\Cache\Cache;
use Tickit\CacheBundle\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test suite for the core cache file
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class CacheTest extends WebTestCase
{
    /**
     * Makes sure that the factory method of the Cache core file returns the correct engines
     */
    public function testValidEnginesInFactory()
    {
        $client = static::createClient();
        $cacheFactory = new Cache($client->getContainer());

        $apcEngine = $cacheFactory->factory(Cache::APC_ENGINE, array());
        $this->assertEquals('Tickit\CacheBundle\Engine\ApcEngine', get_class($apcEngine));

        $memcachedEngine = $cacheFactory->factory(Cache::MEMCACHED_ENGINE, array());
        $this->assertEquals('Tickit\CacheBundle\Engine\MemcachedEngine', get_class($memcachedEngine));

        $fileEngine = $cacheFactory->factory(Cache::FILE_ENGINE, array());
        $this->assertEquals('Tickit\CacheBundle\Engine\FileEngine', get_class($fileEngine));
    }

    /**
     * Makes sure that the factory method of the Cache core file correctly validates the engine name
     */
    public function testInvalidEngineInFactory()
    {
        $client = static::createClient();
        $cacheFactory = new Cache($client->getContainer());

        $invalidEngineName = 'BlahBlah';

        try {
            $invalidEngine = $cacheFactory->factory($invalidEngineName, array());
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(true, true);
        }

        $this->assertTrue(!isset($invalidEngine));
    }

}