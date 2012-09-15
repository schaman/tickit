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
    /**
     * Makes sure that the file engine is working together with the options resolver
     * to correctly configure valid options for the file cache engine
     */
    public function testValidCacheDirectoryOption()
    {
        $client = static::createClient();

        $options = array(
            'cache_dir' => '/tmp'
        );

        $cache = new FileEngine($client->getContainer(), $options);
        $this->assertEquals('/tmp', $cache->getOptions()->getCacheDir());
    }

    /**
     * Makes sure that an illegal file cache (unwritable) option is correctly
     * rejected and the default is used instead
     */
    public function testIllegalCacheDirectoryOption()
    {
        $client = static::createClient();

        $options = array(
            'cache_dir' => '/etc'
        );

        $cache = new FileEngine($client->getContainer(), $options);
        $this->assertEquals('/tmp', $cache->getOptions()->getCacheDir());
    }

    /**
     * Makes sure that the auto_serialize option is properly configured
     */
    public function testValidAutoSerializeOption()
    {
        $client = static::createClient();

        $options = array(
            'auto_serialize' => false
        );

        $cache = new FileEngine($client->getContainer(), $options);
        $this->assertEquals(false, $cache->getOptions()->getAutoSerialize());
    }

}