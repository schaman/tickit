<?php

namespace Tickit\WebAcceptance\Mixins;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Container mixins for context classes.
 *
 * Provides some helper methods for working with the service container.
 *
 * @package Tickit\WebAcceptance\Mixins
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
trait ContainerMixin
{
    /**
     * The application kernel
     *
     * @var KernelInterface
     */
    private $kernel;

    /**
     * Sets Kernel instance.
     *
     * @param KernelInterface $kernel HttpKernel instance
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Gets the security context service
     *
     * @return SecurityContextInterface
     */
    private function getSecurityContext()
    {
        return $this->getService('security.context');
    }

    /**
     * Fetches a service from the container by its ID
     *
     * @param string $id The service ID to fetch
     *
     * @return object
     */
    private function getService($id)
    {
        return $this->getContainer()->get($id);
    }

    /**
     * Gets the service container
     *
     * @return ContainerInterface
     */
    private function getContainer()
    {
        return $this->kernel->getContainer();
    }
}
