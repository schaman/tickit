<?php

namespace Tickit\PermissionBundle\Tests\Evaluator;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;

/**
 * PermissionEvaluator tests.
 *
 * @package Tickit\PermissionBundle\Tests\Evaluator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PermissionEvaluatorTest extends AbstractFunctionalTest
{
    /**
     * Tests the service container configuration.
     *
     * @return void
     */
    public function testServiceContainerReturnsCorrectInstance()
    {
        $container = static::createClient()->getContainer();
        $evaluator = $container->get('tickit_permission.evaluator');

        $this->assertInstanceOf('Tickit\PermissionBundle\Evaluator\PermissionEvaluator', $evaluator);
    }
}
