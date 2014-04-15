<?php

namespace Tickit\Component\Decorator\Tests\Mock;

/**
 * MockValueObject
 *
 * @package Tickit\Component\Decorator\Tests\Mock
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class MockValueObject
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = (string) $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }
}
