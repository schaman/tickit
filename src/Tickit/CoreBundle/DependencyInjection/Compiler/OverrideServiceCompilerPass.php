<?php

namespace Tickit\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Description
 *
 * @package Namespace\Class
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class OverrideServiceCompilerPass implements CompilerPassInterface
{
    /**
    * You can modify the container here before it is dumped to PHP code.
    *
    * @param ContainerBuilder $container The service container
    *
    * @return void
    */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('hearsay_require_js.optimizer_filter');
        $definition->setClass('Tickit\CoreBundle\Filter\RequireJSOptimizerFilter');
    }
}
