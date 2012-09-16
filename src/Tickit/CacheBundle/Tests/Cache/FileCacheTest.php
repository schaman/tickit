<?php

namespace Tickit\CacheBundle\Tests\Cache;

use PHPUnit_Framework_TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tickit\CacheBundle\Cache\CacheFactory;
use Tickit\CacheBundle\Engine\Exception\NotCacheableException;
use stdClass;

/**
 * Test suite for file caching behaviour
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class FileCacheTest extends WebTestCase
{
    /**
     * Makes sure that a valid cache write/read sequence works as expected
     * for string data
     */
    public function testValidReadWriteString()
    {
        $client = static::createClient();

        $cacheFactory = new CacheFactory($client->getContainer());
        $cache = $cacheFactory->factory('file', array());

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
        $cache = $cacheFactory->factory('file', array());

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
     * Makes sure that when trying to write an object to the cache and
     * auto serialize is disabled, the correct exception is thrown
     */
    public function testAutoSerializeDisabledWithObjectWrite()
    {
        $client = static::createClient();

        $cacheFactory = new CacheFactory($client->getContainer());
        $cache = $cacheFactory->factory('file', array('auto_serialize' => false));

        $simpleObject = $this->buildSimpleObject();
        $caught = false;

        try {
            $cache->write(3, $simpleObject);
        } catch (NotCacheableException $e) {
            $caught = true;
        }

        $this->assertTrue($caught);
    }


    /**
     * Makes sure that cache purging behaves as expected
     */
    public function testCachePurge()
    {
        $client = static::createClient();

        $cacheFactory = new CacheFactory($client->getContainer());
        $cacheOne = $cacheFactory->factory('file', array('namespace' => 'one'));
        $cacheTwo = $cacheFactory->factory('file', array('namespace' => 'two'));

        $cacheOne->write(1, 'some data');
        $cacheTwo->write(1, 'some other data');

        $this->assertEquals('some data', $cacheOne->read(1));
        $this->assertEquals('some other data', $cacheTwo->read(1));

        $cacheOne->purge('one');
        $this->assertEquals(null, $cacheOne->read(1));
        $this->assertEquals('some other data', $cacheTwo->read(1));

        $cacheTwo->purge();
        $this->assertEquals(null, $cacheTwo->read(1));
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
