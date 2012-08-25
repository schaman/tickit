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
     * Makes sure that the APC availability detection works as expected
     */
    public function testApcAvailibility()
    {
        ini_set('apc.enabled', 0);

        try {
            $engine = new ApcEngine();
        } catch (ApcCacheUnavailableException $e) {
            $this->assertTrue(true, true);
        }

        $this->assertTrue(true, !isset($engine));

        ini_set('apc.enabled', 1);

        try {
            $engine = new ApcEngine();
        } catch (ApcCacheUnavailableException $e) {
            $this->assertTrue(true, false, 'ApcEngine failed to insantiate even though APC is enabled. Is it installed?');
        }

        $this->assertEquals('Tickit\CacheBundle\Engine\ApcEngine', get_class($engine));
    }

}
