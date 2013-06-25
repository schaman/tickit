<?php

namespace Tickit\NavigationBundle\Builder;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Abstract implementation of a navigation builder.
 *
 * @package Tickit\NavigationBundle\Builder
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractBuilder
{
    /**
     * The event dispatcher service
     *
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * Constructor.
     *
     * @param EventDispatcher $dispatcher The event dispatcher service
     */
    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
}
