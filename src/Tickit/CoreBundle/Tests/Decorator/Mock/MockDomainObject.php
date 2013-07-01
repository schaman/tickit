<?php

namespace Tickit\CoreBundle\Tests\Decorator\Mock;

/**
 * Mock domain object
 *
 * @package Tickit\CoreBundle\Tests\Decorator\Mock
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class MockDomainObject
{
    /**
     * String
     *
     * @var string
     */
    protected $name = 'name';

    /**
     * Boolean
     *
     * @var boolean
     */
    protected $active = true;

    /**
     * Boolean
     *
     * @var boolean
     */
    protected $enabled = false;

    /**
     * DateTime
     *
     * @var \DateTime
     */
    protected $date;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * Returns string
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns boolean
     *
     * @return boolean
     */
    public function active()
    {
        return $this->active;
    }

    /**
     * Returns boolean
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Returns DateTime
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
