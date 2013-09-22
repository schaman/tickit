<?php

namespace Tickit\NavigationBundle\Builder;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Abstract implementation of a navigation builder.
 *
 * @package Tickit\NavigationBundle\Builder
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractBuilder
{
    /**
     * An event dispatcher
     *
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $dispatcher An event dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
}
