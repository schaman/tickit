<?php

namespace Tickit\CacheBundle\Tests\Cache;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tickit\CacheBundle\Cache\CacheFactory;
use stdClass;

/**
 * Test suite for Memcached caching behaviour
 *
 * @author Mark Wilson <mark@enasni.co.uk>
 */
class MemcachedCacheTest extends WebTestCase
{
    /**
     * Makes sure that a valid cache write/read sequence works as expected
     * for string data
     */
    public function testValidReadWriteString()
    {
        $client = static::createClient();

        $cacheFactory = new CacheFactory($client->getContainer());
        $cache = $cacheFactory->factory(CacheFactory::MEMCACHED_ENGINE, array());

        $id = $cache->write(1, 'sample data');
        $this->assertEquals(1, $id);

        $data = $cache->read($id);
        $this->assertEquals('sample data', $data);
    }

    /**
     * Makes sure that simple objects are correctly written to the
     * cache and are read correctly
     */
    public function testValidReadWriteSimpleObject()
    {
        $client = static::createClient();

        $cacheFactory = new CacheFactory($client->getContainer());
        $cache = $cacheFactory->factory(CacheFactory::MEMCACHED_ENGINE, array());

        $simpleObject = $this->buildSimpleObject();

        $id = $cache->write(2, $simpleObject);
        $this->assertEquals(2, $id);

        $cachedObject = $cache->read($id);

        $this->assertEquals('stdClass', get_class($cachedObject));
        $this->assertEquals(true, $cachedObject->a);
        $this->assertEquals(false, $cachedObject->b);
        $this->assertTrue(is_array($cachedObject->c));
    }

    /**
     * Makes sure deleting from the cache completes correctly
     */
    public function testDeletingCacheData()
    {
        $client = static::createClient();

        $cacheFactory = new CacheFactory($client->getContainer());
        $cache = $cacheFactory->factory(CacheFactory::MEMCACHED_ENGINE, array());

        $data = $this->buildSimpleObject();

        $id = $cache->write('deleteme', $data);
        $this->assertEquals('deleteme', $id);

        $cache->delete($id);

        $this->assertNull($cache->read($id));
    }

    /**
     * Makes sure that purging the cache behaves as expected
     */
    public function testPurgeCacheData()
    {
        $client = static::createClient();

        $cacheFactory = new CacheFactory($client->getContainer());
        $cache = $cacheFactory->factory(CacheFactory::MEMCACHED_ENGINE, array());

        $object = $this->buildSimpleObject();
        $cache->write('key1', $object);
        $cache->write('key2', 'string value');

        $prePurgeObjectFetch = $cache->read('key1');
        $prePurgeStringFetch = $cache->read('key2');

        $this->assertEquals($object, $prePurgeObjectFetch, 'Object correctly fetched from cache before purge');
        $this->assertEquals('string value', $prePurgeStringFetch, 'String correctly fetched from cache before purge');

        $success = $cache->purge();
        $this->assertTrue($success, 'Memcached purge operation completed successfully');

        $objectFetch = $cache->read('key1');
        $stringFetch = $cache->read('key2');

        $this->assertEquals(null, $objectFetch, 'Fetching object after purge correctly returned null');
        $this->assertEquals(null, $stringFetch, 'Fetching string after purge correctly returned null');
    }

    /**
     * Builds a simple object for testing in cache writes/reads
     *
     * @return stdClass
     */
    protected function buildSimpleObject()
    {
        $simpleObject = new stdClass();
        $simpleObject->a = true;
        $simpleObject->b = false;
        $simpleObject->c = array(1, 2, 3);

        return $simpleObject;
    }

}
