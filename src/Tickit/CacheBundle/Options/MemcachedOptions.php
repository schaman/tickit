<?php

namespace Tickit\CacheBundle\Options;

use \Memcached;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Options resolver class for the Memcached caching engine
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class MemcachedOptions extends AbstractOptions
{
    /**
     * The Memcached instance on this options set
     *
     * @var array
     */
    protected $memcached;

    /**
     * Constructs the Memcached options object and calls parent constructor
     *
     * @param array                                                     $options   An array of user defined options
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container The symfony service container instance
     */
    public function __construct(array $options, ContainerInterface $container)
    {
        $this->memcached = new Memcached();
        parent::__construct($options, $container);
    }

    /**
     * Returns the servers configuration
     *
     * @return array
     */
    public function getMemcached()
    {
        return $this->memcached;
    }

    /**
     * Overrides abstract implementation and sets up engine specific options
     */
    protected function _resolveOptions()
    {
        $servers = $this->container->getParameter('tickit_cache.memcached.servers');
        foreach ($servers as $server) {
            if (!$server['enabled']) {
                continue;
            }
            $host = ($server['host'] == 'localhost') ? '127.0.0.1' : $server['host'];
            $this->memcached->addServer($host, $server['port'], $server['weight']);
        }

        parent::_resolveOptions();
    }
}