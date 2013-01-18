<?php

namespace Tickit\CacheBundle\Tests\Options;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tickit\CacheBundle\Options\MemcachedOptions;

/**
 * Test suite for the MemcachedOptions resolver class. This basically ensures that all
 * options managed specifically by the MemcachedOptions class are handled correctly
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class MemcachedOptionsTest extends WebTestCase
{
    /**
     * Ensures that all server related settings are processed correctly by the resolver
     */
    public function testServerOptions()
    {
        $client = static::createClient();

        $resolver = new MemcachedOptions(array(), $client->getContainer());
        $memcached = $resolver->getMemcached();

        $this->assertInstanceOf('Memcached', $memcached);
        $servers = $memcached->getServerList();

        $expected = array(
            array(
                'host' => '127.0.0.1',
                'port' => 11211,
                'weight' => 100
            ),
            array(
                'host' => 'backup.local',
                'port' => 30000,
                'weight' => 0
            )
        );
        $this->assertEquals($expected, $servers);
    }

}