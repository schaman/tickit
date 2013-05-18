<?php

namespace Tickit\CoreBundle\Decorator;

/**
 * Abstract decorator implementation.
 *
 * @package Tickit\CoreBundle\Decorator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractJsonDecorator extends AbstractDecorator
{
    /**
     * Internal function that renders data into JSON output format
     *
     * @param mixed $data The data that needs rendering
     *
     * @return mixed
     */
    protected function doRender($data)
    {
        return json_encode($data);
    }
}
