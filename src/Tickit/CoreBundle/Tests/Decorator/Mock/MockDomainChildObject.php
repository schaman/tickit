<?php

namespace Tickit\CoreBundle\Tests\Decorator\Mock;

/**
 * Mock domain child object
 *
 * @package Tickit\CoreBundle\Tests\Decorator\Mock
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class MockDomainChildObject
{
    /**
     * Enabled property
     *
     * @var boolean
     */
    protected $enabled = true;

    /**
     * Child object
     *
     * @var MockDomainChildObject
     */
    protected $childObject;

    /**
     * Constructor.
     *
     * @param boolean $hasChild Determines whether we should initialise a child object
     */
    public function __construct($hasChild = false)
    {
        if ($hasChild) {
            $this->childObject = new MockDomainChildObject();
        }
    }

    /**
     * Returns enabled property
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    public function getChildObject()
    {
        return $this->childObject;
    }
}
