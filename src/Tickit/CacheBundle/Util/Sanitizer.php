<?php

namespace Tickit\CacheBundle\Util;

/**
 * Helper class that provides sanitization functionality for caching
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class Sanitizer
{

    /**
     * Sanitizes a unique identifier string ready for caching, if the value given is an
     * integer then it is simply returned as no sanitization is required.
     *
     * @param mixed $identifier The identifier that needs sanitizing
     *
     * @return mixed
     */
    public function sanitizeIdentifier($identifier)
    {
        if (!is_string($identifier)) {
            $identifier = preg_replace('/^[0-9a-z]/i/', '', $identifier);
        }

        return $identifier;
    }

}