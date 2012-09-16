<?php

namespace Tickit\CacheBundle\Engine\Exception;

use Exception;

/**
 * Exception thrown when attempting to perform an operation that the engine
 * does not support
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class FeatureNotSupportedException extends Exception
{
}