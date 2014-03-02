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

namespace Tickit\Bundle\IssueBundle\Tests\Form\Type;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Tickit\Bundle\CoreBundle\Tests\Form\Type\AbstractFormTypeTestCase;
use Tickit\Bundle\IssueBundle\Form\Type\IssueAttachmentFormType;
use Tickit\Component\Model\Issue\IssueAttachment;

/**
 * IssueAttachmentFormType tests
 *
 * @package Tickit\Bundle\IssueBundle\Tests\Form\Type
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueAttachmentFormTypeTest extends AbstractFormTypeTestCase
{
    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();

        $this->formType = new IssueAttachmentFormType();
    }

    /**
     * Tests the submit() method
     *
     * @dataProvider getSubmitDataFixtures
     */
    public function testSubmitValidData($requestData, $expectedData)
    {
        $form = $this->factory->create($this->formType);
        $form->submit($requestData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expectedData, $form->getData());

        $expectedViewComponents = ['id', 'file'];
        $this->assertViewHasComponents($expectedViewComponents, $form->createView());
    }

    /**
     * @return array
     */
    public function getSubmitDataFixtures()
    {
        $tmp = sys_get_temp_dir();
        $originalName = uniqid() . '.json';
        $filePath = $tmp . '/' . $originalName;
        file_put_contents($filePath, '{"data": []}');
        $rawData = [
            'id' => '4',
            'file' => new UploadedFile($filePath, $originalName)
        ];

        $attachment = new IssueAttachment();
        $attachment->setId(4)
                   ->setFilename('4_' . $originalName)
                   ->setMimeType('application/json')
                   ->setFile($rawData['file']);

        return [
            [$rawData, $attachment]
        ];
    }
}
