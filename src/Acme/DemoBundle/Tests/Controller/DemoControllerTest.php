<?php

namespace Acme\DemoBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DemoControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/demo/hello/Fabien');

        $this->assertTrue($client->getResponse() instanceof \Symfony\Component\HttpFoundation\RedirectResponse);
    }
}
