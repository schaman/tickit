<?php

namespace Tickit\CoreBundle\Tests\Form\Type\Picker\DataTransformer\Mock;

/**
 * Mock entity.
 *
 * @package Tickit\CoreBundle\Tests\Form\Type\Picker\DataTransformer\Mock
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class MockEntity
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}
 