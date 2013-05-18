<?php

namespace Tickit\CoreBundle\Decorator;

/**
 * Abstract decorator implementation.
 *
 * @package Tickit\CoreBundle\Decorator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractDecorator
{
    /**
     * The data that is being decorated
     *
     * @var mixed
     */
    protected $data;

    /**
     * Constructor.
     *
     * @param mixed $data The data to decorate
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Renders decorated data
     *
     * @return mixed
     */
    public function render()
    {
        $parsedData = $this->parseData();

        return $this->doRender($parsedData);
    }

    /**
     * Parses data on the current decorator into a format ready for the renderer
     *
     * @return mixed
     */
    abstract protected function parseData();


    /**
     * Internal function that renders data into output format
     *
     * @param mixed $data The data that needs rendering
     *
     * @return mixed
     */
    abstract protected function doRender($data);
}
