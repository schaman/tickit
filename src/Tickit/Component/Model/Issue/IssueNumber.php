<?php

namespace Tickit\Component\Model\Issue;

/**
 * Issue Number.
 *
 * Value object representing an issue number.
 *
 * @package Tickit\Component\Model\Issue
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueNumber
{
    /**
     * The prefix part of the issue number
     *
     * @var string
     */
    private $prefix;

    /**
     * The number part of the issue number
     *
     * @var integer
     */
    private $number;

    /**
     * Constructor
     *
     * @param string $prefix  The prefix part of the issue number
     * @param integer $number The number part of the issue number
     */
    public function __construct($prefix, $number)
    {
        $this->prefix = $prefix;
        $this->number = $number;
    }

    /**
     * Gets the issue number prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Gets the number part of the issue number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Casts the issue number to a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getPrefix() . $this->number;
    }
}
