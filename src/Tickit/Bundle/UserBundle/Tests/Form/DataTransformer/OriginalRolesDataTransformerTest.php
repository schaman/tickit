<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\Bundle\UserBundle\Tests\Form\Type\DataTransformer;

use Tickit\Bundle\UserBundle\Form\DataTransformer\OriginalRolesDataTransformer;

/**
 * OriginalRolesDataTransformer tests
 *
 * @package Tickit\Bundle\UserBundle\Tests\Form\Type\DataTransformer
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class OriginalRolesDataTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the transform() method
     *
     * @dataProvider getTransformDataFixtures
     */
    public function testTransformReturnsExpectedData($value, $expectedReturnValue)
    {
        $this->assertEquals($this->getTransformer()->transform($value), $expectedReturnValue);
    }

    /**
     * Tests the reverseTransform() method
     *
     * @dataProvider getReverseTransformDataFixtures
     */
    public function testReverseTransformReturnsExpectedData($value, $editableRoles, $originalRoles, $expectedReturnValue)
    {
        $transformer = $this->getTransformer();
        $transformer->setEditableRoles($editableRoles);
        $transformer->setOriginalRoles($originalRoles);

        $this->assertEquals($transformer->reverseTransform($value), $expectedReturnValue);
    }

    /**
     * Gets data fixtures for testing the transform() method
     *
     * @return array
     */
    public function getTransformDataFixtures()
    {
        return [
            [null, ''],
            [['ROLE_USER'], ['ROLE_USER']],
            [['ROLE_ADMIN', 'ROLE_SUPER_ADMIN'], ['ROLE_ADMIN', 'ROLE_SUPER_ADMIN']]
        ];
    }

    /**
     * Gets data fixtures for testing the reverseTransform() method
     *
     * @return array
     */
    public function getReverseTransformDataFixtures()
    {
        return [
            ['', [], [], null],
            [
                // the data being reverseTransformed(), i.e. the data that was submitted
                ['ROLE_USER'],
                // the roles that are editable for the user who has submitted the form data
                ['ROLE_USER'],
                // the original roles on the user
                ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'],
                // the data that we expect to be returned from reverseTransform()
                ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN']
            ],
            [
                ['ROLE_USER', 'ROLE_ADMIN'],
                ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'],
                ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'],
                ['ROLE_USER', 'ROLE_ADMIN']
            ],
            [
                [],
                ['ROLE_USER', 'ROLE_ADMIN'],
                ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'],
                ['ROLE_SUPER_ADMIN']
            ]
        ];
    }

    /**
     * Gets a new instance of the transformer
     *
     * @return OriginalRolesDataTransformer
     */
    private function getTransformer()
    {
        return new OriginalRolesDataTransformer();
    }
}
