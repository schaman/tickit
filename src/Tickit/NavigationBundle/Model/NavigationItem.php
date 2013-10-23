<?php

namespace Tickit\NavigationBundle\Model;

/**
 * Navigation item
 *
 * @package Tickit\NavigationBundle\Model
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class NavigationItem
{
    /**
     * Text to display in navigation
     *
     * @var string $text
     */
    private $text;

    /**
     * Navigation URL
     *
     * @var string $url
     */
    private $routeName;

    /**
     * Priority for item
     *
     * @var int $priority
     */
    private $priority;

    /**
     * Additional parameters for route generation
     *
     * @var array $params
     */
    private $params;

    /**
     * Constructor.
     *
     * @param string $text      Text to display in navigation
     * @param string $routeName Navigation URL
     * @param int    $priority  Priority for this item
     * @param array  $params    Additional parameters for route generation
     */
    public function __construct($text, $routeName, $priority, array $params = array())
    {
        $this->text      = $text;
        $this->routeName = $routeName;
        $this->priority  = $priority;
        $this->params    = $params;
    }

    /**
     * Get text to display in navigation
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get navigation URL
     *
     * @return string
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * Get additional parameters
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Get navigation priority
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }
}
