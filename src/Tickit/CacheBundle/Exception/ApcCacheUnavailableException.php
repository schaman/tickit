<?php

namespace Tickit\CacheBundle\Exception;

use Exception;

/**
 * APC cache unavailable exception class
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class ApcCacheUnavailableException extends CacheUnavailableException
{
    /**
     * Constructs the exception and passes cache type to parent constructor
     *
     * @param string     $message  The customised exception message
     * @param int        $code     The exception code
     * @param \Exception $previous The previous exception (for exception chaining)
     */
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct('APC', $message, $code, $previous);
    }

}