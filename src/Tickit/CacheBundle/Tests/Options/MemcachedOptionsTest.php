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

        var_dump($resolver->getServers()); die;
    }

}