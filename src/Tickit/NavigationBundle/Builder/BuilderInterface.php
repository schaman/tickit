<?php

namespace Tickit\NavigationBundle\Builder;

/**
 * Navigation builder interface.
 *
 * Builders provide functionality for building navigation elements in the application.
 *
 * @package Tickit\NavigationBundle\Builder
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
interface BuilderInterface
{
    /**
     * Builds the navigation component.
     *
     * @return mixed
     */
    public function build();
}
