<?php

namespace Tickit\CoreBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;

/**
 * DefaultController tests
 *
 * @package Tickit\CoreBundle\Tests\Controller
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class DefaultControllerTest extends AbstractFunctionalTest
{
    /**
     * Tests the defaultAction() method
     *
     * @return void
     */
    public function testDefaultActionServesCorrectMarkup()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $crawler = $client->request('get', '/');

        $this->assertEquals(1, $crawler->filter('header')->count());
        $this->assertEquals(1, $crawler->filter('footer')->count());
        $content = $crawler->filter('#container')->text();
        $this->assertEmpty(trim($content));
    }
}
