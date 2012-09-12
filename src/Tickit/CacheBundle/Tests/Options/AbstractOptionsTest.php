<?php

namespace Tickit\CacheBundle\Tests\Options;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test suite for the AbstractOptions resolver class. This basically ensures that all
 * options managed specifically by the AbstractOptions class are handled correctly
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractOptionsTest extends WebTestCase
{
    /**
     * Makes sure that the abstract options resolver takes valid options and correctly accepts them
     */
    public function testValidOptions()
    {
        $client = static::createClient();

        $options = array(
            'namespace' => 'testNamespace'
        );

        $args = array($options, $client->getContainer());
        $mock = $this->getMockForAbstractClass('Tickit\CacheBundle\Options\AbstractOptions', $args);

        $this->assertEquals('testNamespace', $mock->getNamespace());

    }

    /**
     * Makes sure that the abstract options resolver correctly rejects illegal options and falls back
     * to sensible defaults where applicable (from app/config_test.yml)
     */
    public function testIllegalOptions()
    {
        $client = static::createClient();

        $options = array(
            'namespace' => 'test%$%^*Name-1-space',
            'illegalOptionName' => 'adwdawdadwa'
        );

        $args = array($options, $client->getContainer());
        $mock = $this->getMockForAbstractClass('Tickit\CacheBundle\Options\AbstractOptions', $args);

        $this->assertEquals('testName1space', $mock->getNamespace());
    }


    /**
     * Makes sure that when options are omitted that the default options are used instead
     * (from app/config_test.yml)
     */
    public function testDefaultOptions()
    {
        $client = static::createClient();

        $args = array(array(), $client->getContainer());
        $mock = $this->getMockForAbstractClass('Tickit\CacheBundle\Options\AbstractOptions', $args);

        $this->assertEquals('tickit', $mock->getNamespace());
    }

}