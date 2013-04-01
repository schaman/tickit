<?php

namespace Tickit\PreferenceBundle\Tests\Entity;

use Tickit\PreferenceBundle\Entity\Preference;

/**
 * Tests for Preference entity
 *
 * @package Tickit\PreferenceBundle\Tests\Entity
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PreferenceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the setType() method
     *
     * Ensures that no exception is thrown for valid types
     *
     * @return void
     */
    public function testSetTypeAcceptsValidType()
    {
        $preference = new Preference();

        $preference->setType(Preference::TYPE_SYSTEM);
        $this->assertEquals(Preference::TYPE_SYSTEM, $preference->getType());

        $preference->setType(Preference::TYPE_USER);
        $this->assertEquals(Preference::TYPE_USER, $preference->getType());
    }

    /**
     * Tests the setType() method
     *
     * Ensures that the correct exception is thrown for invalid types
     *
     * @return void
     */
    public function testSetTypeDoesNotAcceptValidType()
    {
        $this->setExpectedException('InvalidArgumentException');

        $preference = new Preference();
        $preference->setType('something that is not valid');
    }
}
