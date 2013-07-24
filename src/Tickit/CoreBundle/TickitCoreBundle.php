<?php

namespace Tickit\CoreBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tickit\CoreBundle\DependencyInjection\Compiler\RequireJsOptimizerFilterServiceCompilerPass;

/**
 * Bundle build file for the TickitCoreBundle
 *
 * @package Tickit\CoreBundle
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TickitCoreBundle extends Bundle
{
    /**
     * {@inheritDoc}
     *
     * @param ContainerBuilder $container The service container
     *
     * @return void
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RequireJsOptimizerFilterServiceCompilerPass());
    }

}
