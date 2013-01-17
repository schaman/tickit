<?php

namespace Tickit\CacheBundle\Options;

/**
 * Options resolver class for the Memcached caching engine
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class MemcachedOptions extends AbstractOptions
{
    /**
     * An array of server configurations indexed by their weight value
     *
     * @var array
     */
    protected $servers = array();

    /**
     * Returns the servers configuration
     *
     * @return array
     */
    public function getServers()
    {
        return $this->servers;
    }

    /**
     * Overrides abstract implementation and sets up engine specific options
     */
    protected function _resolveOptions()
    {
        $servers = $this->container->getParameter('tickit_cache.memcached.servers');
        var_dump($servers); die;
        foreach ($servers as $server) {
            $this->servers[$server['weight']][] = $server;
        }

        parent::_resolveOptions();
    }
}