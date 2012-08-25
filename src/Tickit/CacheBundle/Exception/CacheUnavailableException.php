<?php

namespace Tickit\CacheBundle\Exception;

use Exception;

/**
 * Exception thrown when a cache is unavailable
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class CacheUnavailableException extends Exception
{
    /* @var string */
    protected $cacheType;

    /**
     * Constructs the exception and sets the cache type
     *
     * @param string     $cacheType The type of cache that is unavailable
     * @param string     $message   The customised error message
     * @param int        $code      The exception code
     * @param \Exception $previous  The previous exception (for exception chaining)
     */
    public function __construct($cacheType, $message = '', $code = 0, Exception $previous = null)
    {
        $this->cacheType = $cacheType;

        if ('' === $message) {
            $message = $this->_constructDefaultMessage();
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * Creates a default exception message based on the cache type
     *
     * @return string
     */
    private function _constructDefaultMessage()
    {
        return sprintf('The %s cache is unavailable. Have you enabled it in your config?', $this->cacheType);
    }

}