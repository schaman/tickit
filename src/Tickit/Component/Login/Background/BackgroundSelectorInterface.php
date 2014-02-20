<?php

namespace Tickit\Component\Login\Background;

/**
 * Background selector interface.
 *
 * Background selectors are responsible for selecting a random
 * background image and returning it's web path.
 *
 * @package Tickit\Component\Login\Background
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface BackgroundSelectorInterface
{
    /**
     * Selects a random background image and returns its web path
     *
     * @return string
     */
    public function select();
}
