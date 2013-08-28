<?php

namespace Tickit\CoreBundle\Filter;

use Hearsay\RequireJSBundle\Filter\RequireJSOptimizerFilter as BaseFilter;

/**
 * RequireJSOptimizer assetic filter
 *
 * @package Tickit\CoreBundle\Filter
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class RequireJSOptimizerFilter extends BaseFilter
{
    /**
     * Override set option method
     *
     * @param string $name  The option name
     * @param string $value The option value
     *
     * @return void
     */
    public function setOption($name, $value)
    {
        if (is_file($value)) {
            // strip the file suffix to fix bug in HearsayRequireJsBundle
            $value = substr($value, 0, -3);
        }

        $this->options[$name] = $value;
    }
}
