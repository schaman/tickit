<?php

namespace Tickit\CacheBundle\Tests\Engine;

use Tickit\CacheBundle\Engine\MemcachedEngine;
use Tickit\CacheBundle\Exception\MemcachedCacheUnavailableException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test suite for the Memcached caching engine
 *
 * @author Mark Wilson <mark@enasni.co.uk>
 */
class MemcachedEngineTest extends WebTestCase
{
    /**
     * Makes sure that the Memcached availability detection works as expected.
     */
    public function testMemcachedAvailability()
    {
        $client = static::createClient();

        try {
            $engine = new MemcachedEngine($client->getContainer(), array());
        } catch (MemcachedCacheUnavailableException $e) {
            $engine = '';
            $this->assertTrue(false, 'MemcachedEngine failed to instantiate. Is it installed?');
        }

        $this->assertTrue(is_object($engine));
        $this->assertEquals('Tickit\CacheBundle\Engine\MemcachedEngine', get_class($engine));
    }

}
