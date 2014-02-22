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

namespace Tickit\Component\Image\Tests\Selector;

use Tickit\Component\Image\Selector\FileSystemImageSelector;

/**
 * FileSystemImageSelector tests
 *
 * @package Tickit\Component\Image\Tests\Selector
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FileSystemImageSelectorTest extends \PHPUnit_Framework_TestCase
{
    const SELECTOR_RUNS = 50;

    /**
     * Tests the select() method
     *
     * @dataProvider getSelectFixtures
     */
    public function testSelectThrowsExceptionForNonExistentDirectory(
        $filePath,
        $webPath,
        array $imageArray,
        $expectedException = null
    ) {
        $selector = new FileSystemImageSelector($filePath, $webPath);

        if (null !== $expectedException) {
            $this->setExpectedException($expectedException);
            $selector->select();
        } else {
            $buckets = [];
            $i = static::SELECTOR_RUNS;
            while ($i--) {
                $imagePath = $selector->select();
                $this->assertContains($imagePath, $imageArray);
                isset($buckets[$imagePath]) ? $buckets[$imagePath]++ : $buckets[$imagePath] = 0;
            }

            if (empty($buckets)) {
                $this->fail('FileSystemImageSelector has returned no images');
            }

            if (count($buckets) !== count($imageArray)) {
                $this->fail('FileSystemImageSelector did not produce an acceptable spread of images');
            }
        }
    }

    /**
     * @return array
     */
    public function getSelectFixtures()
    {
        $validFilePath = __DIR__ . '/../Mock/images';
        $webPath = 'assets';
        $validImages = ['assets/bg-1.png', 'assets/bg-2.png', 'assets/bg-3.png', 'assets/bg-4.png'];

        return [
            ['/invalid/file-path', $webPath, [], '\RuntimeException'],
            [$validFilePath, $webPath, $validImages]
        ];
    }
}
