<?php

namespace Tickit\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Bundle build file for the TickitUserBundle
 *
 * @package Tickit\UserBundle
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TickitUserBundle extends Bundle
{

    /**
     * Gets the bundle's parent bundle name
     *
     * @return string
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
