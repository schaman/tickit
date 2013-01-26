<?php

namespace Tickit\CacheBundle\Util;

use Tickit\CacheBundle\Exception\InvalidArgumentException;

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
     *
     * @throws InvalidArgumentException If the $identifier parameter is empty
     */
    public function sanitizeIdentifier($identifier)
    {
        if (empty($identifier) && $identifier !== 0) {
            throw new InvalidArgumentException(
                sprintf('The identifier provided in %s on line %d must not be empty', __CLASS__, __LINE__)
            );
        }

        if (is_string($identifier)) {
            $identifier = preg_replace('/[^0-9a-z]*/i', '', $identifier);
        }

        return $identifier;
    }

    /**
     * Sanitizes a string ready for use as a file system path
     *
     * @param string $path     The path name
     * @param bool   $realPath [Optional] True if the path given actually exists on disk, defaults to true
     *
     * @throws \Tickit\CacheBundle\Exception\InvalidArgumentException
     *
     * @return string
     */
    public function sanitizePath($path, $realPath = true)
    {
        if (empty($path)) {
            throw new InvalidArgumentException(
                sprintf('The path provided in %s on line %d must not be empty', __CLASS__, __LINE__)
            );
        }

        $sanitizedPath = preg_replace('#[^0-9a-z_\-/\\\]*#i', '', $path);

        if (true === $realPath) {
            $sanitizedPath = realpath($sanitizedPath);
        }

        return $sanitizedPath;
    }

}