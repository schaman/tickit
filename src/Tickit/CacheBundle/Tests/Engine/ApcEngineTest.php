<?php

namespace Tickit\CacheBundle\Tests\Engine;

use PHPUnit_Framework_TestCase;
use Tickit\CacheBundle\Engine\ApcEngine;
use Tickit\CacheBundle\Exception\ApcCacheUnavailableException;

/**
 * Test suite for the APC caching engine
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class ApcEngineTest extends PHPUnit_Framework_TestCase
{
    /**
     * Makes sure that the APC availability detection works as expected.
     *
     * NOTE: The incompleteness of this test is caused by the inability to use ini_set() to disable APC
     *       which would allows us to properly make sure that the ApcEngine detects when it is disabled.
     */
    public function testApcAvailability()
    {
        try {
            $engine = new ApcEngine();
        } catch (ApcCacheUnavailableException $e) {
            $this->assertTrue(true, false, 'ApcEngine failed to insantiate even though APC is enabled. Is it installed?');
        }

        $this->assertEquals('Tickit\CacheBundle\Engine\ApcEngine', get_class($engine));
    }

}
