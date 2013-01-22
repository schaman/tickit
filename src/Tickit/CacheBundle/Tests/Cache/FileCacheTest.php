<?php

namespace Tickit\CacheBundle\Tests\Cache;

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
        $cache = $cacheFactory->factory(CacheFactory::FILE_ENGINE, array());

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
        $cache = $cacheFactory->factory(CacheFactory::FILE_ENGINE, array());

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
        $cache = $cacheFactory->factory(CacheFactory::FILE_ENGINE, array('auto_serialize' => false));

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
        $cacheOne = $cacheFactory->factory(CacheFactory::FILE_ENGINE, array('namespace' => 'one'));
        $cacheTwo = $cacheFactory->factory(CacheFactory::FILE_ENGINE, array('namespace' => 'two'));

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
     * Makes sure deleting from the cache completes correctly
     */
    public function testDeletingCacheData()
    {
        $client = static::createClient();

        $cacheFactory = new CacheFactory($client->getContainer());
        $cache = $cacheFactory->factory(CacheFactory::FILE_ENGINE, array('namespace' => 'one'));

        $data = $this->buildSimpleObject();

        $id = $cache->write('deleteme', $data);
        $this->assertEquals('deleteme', $id);

        $cache->delete($id);

        $this->assertNull($cache->read($id));
    }

    /**
     * Makes sure that reading/writing data with tags behaves as expected
     */
    public function testTaggableCacheReadWrite()
    {
        $client = static::createClient();

        $cacheFactory = new CacheFactory($client->getContainer());
        $cache = $cacheFactory->factory(CacheFactory::FILE_ENGINE, array('namespace' => 'tagging'));
        $cache->purge();

        $data = $this->buildSimpleObject();
        $id = $cache->write('test', $data);

        $success = $cache->addTags($id, array('tag1', 'tag2'));
        $this->assertTrue($success, 'Adding tags to cached data successful');

        $tagOneData = $cache->findByTags('tag1');
        $tagTwoData = $cache->findByTags('tag2');

        $this->assertEquals(array('test' => $data), $tagOneData);
        $this->assertEquals(array('test' => $data), $tagTwoData);
    }

    /**
     * Makes sure that deleting a single item from the cache based on its tags behaves as expected
     */
    public function testDeletingSingleTaggedCacheDataItem()
    {
        $client = static::createClient();

        $cacheFactory = new CacheFactory($client->getContainer());
        $cache = $cacheFactory->factory(CacheFactory::FILE_ENGINE, array('namespace' => 'tagging'));
        $cache->purge();

        $data = $this->buildSimpleObject();
        $id = $cache->write('test2', $data);

        $cache->addTags($id, 'tag-delete');

        $tagData = $cache->findByTags('tag-delete');
        $this->assertEquals(array('test2' => $data), $tagData, 'Tagged data successfully stored');

        $success = $cache->removeByTags('tag-delete'); //todo: fix this test assertion
        $this->assertTrue($success, 'removeByTags correctly returned boolean success');

        $postDeleteData = $cache->findByTags('tag-delete');
        $this->assertEquals(null, $postDeleteData, 'findByTags correctly returned null after data was deleted');
    }

    /**
     * Makes sure that deleting multiple items from the cache based on their tag behaves as expected
     */
    public function testDeletingMultipleTaggedCacheDataItems()
    {
        $client = static::createClient();

        $cacheFactory = new CacheFactory($client->getContainer());
        $cache = $cacheFactory->factory(CacheFactory::FILE_ENGINE, array('namespace' => 'tagging'));
        $cache->purge();

        $data1 = $this->buildSimpleObject();
        $data2 = (array) $data1;
        $data3 = 'string value';
        $data4 = 200;

        $data1Id = $cache->write('data1', $data1);
        $data2Id = $cache->write('data2', $data2);
        $data3Id = $cache->write('data3', $data3);
        $data4Id = $cache->write('data4', $data4);

        $cache->addTags($data1Id, 'tag-delete');
        $cache->addTags($data2Id, 'tag-delete');
        $cache->addTags($data3Id, 'tag-delete');
        $cache->addTags($data4Id, 'tag-delete');

        $expectedFetch = array(
            'data1' => $data1,
            'data2' => $data2,
            'data3' => $data3,
            'data4' => $data4
        );

        $actualFetch = $cache->findByTags('tag-delete');
        $this->assertEquals($expectedFetch, $actualFetch, 'Multiple tagged items correctly read from cache');

        $deleteSuccess = $cache->removeByTags('tag-delete');
        $this->assertTrue($deleteSuccess, 'removeByTags correctly returned boolean success');

        $postDeleteData = $cache->findByTags('tag-delete');
        $this->assertEquals(null, $postDeleteData, 'findByTags correctly returned null after all tagged data was data');
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
