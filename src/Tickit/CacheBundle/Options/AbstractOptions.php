<?php

namespace Tickit\CacheBundle\Options;

/**
 * Abstract options resolver for all caching engines
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractOptions
{
    /**
     * The namespace of the current cache engine instance
     *
     * @var string $namespace
     */
    protected $namespace = '';

    /**
     * Sets the current namespace
     *
     * @param string $namespace The new namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $this->_sanitizeNamespace($namespace);
    }

    /**
     * Sanitizes a namespace ready for use
     *
     * @param string $namespace The unsanitized namespace
     *
     * @return mixed
     */
    protected function _sanitizeNamespace($namespace)
    {
        return preg_replace('/^[a-zA-Z0-9]/', '', $namespace);
    }

}