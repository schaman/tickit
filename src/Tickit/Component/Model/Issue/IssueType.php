<?php

namespace Tickit\Component\Model\Issue;

use Doctrine\Common\Collections\Collection;

/**
 * Issue type.
 *
 * @package Tickit\Component\Model\Issue
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueType
{
    /**
     * The identifier of the issue type
     *
     * @var integer
     */
    protected $id;

    /**
     * The name of the issue type
     *
     * @var string
     */
    protected $name;

    /**
     * Issues that are assigned this issue type.
     *
     * @var Collection
     */
    protected $issues;

    /**
     * Sets the identifier of the issue type
     *
     * @param integer $id The identifier
     *
     * @return IssueType
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the identifier of the issue type
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the name of the issue type
     *
     * @param string $name The issue type name
     *
     * @return IssueType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the name of the issue type
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
