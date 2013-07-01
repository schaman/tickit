<?php

namespace Tickit\CoreBundle\Tests\Decorator\Mock;

/**
 * Description
 *
 * @package Namespace\Class
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class MockDomainObject
{
    /**
     * String
     *
     * @var string
     */
    protected $name;

    /**
     * Boolean
     *
     * @var boolean
     */
    protected $active;

    /**
     * Boolean
     *
     * @var boolean
     */
    protected $enabled;

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
     * Sets active
     *
     * @param boolean $active Boolean value
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * Sets enabled
     *
     * @param boolean $enabled Boolean value
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Sets the name
     *
     * @param string $name String value
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
