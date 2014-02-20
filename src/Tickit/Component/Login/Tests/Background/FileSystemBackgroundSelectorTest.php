<?php

namespace Tickit\Component\Login\Tests\Background;

use Tickit\Component\Login\Background\FileSystemBackgroundSelector;

/**
 * FileSystemBackgroundSelector tests
 *
 * @package Tickit\Component\Login\Tests\Background
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FileSystemBackgroundSelectorTest extends \PHPUnit_Framework_TestCase
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
        $selector = new FileSystemBackgroundSelector($filePath, $webPath);

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
                $this->fail('FileSystemBackgroundSelector has returned no images');
            }

            if (count($buckets) !== count($imageArray)) {
                $this->fail('FileSystemBackgroundSelector did not produce an acceptable spread of images');
            }
        }
    }

    /**
     * @return array
     */
    public function getSelectFixtures()
    {
        $validFilePath = __DIR__ . '/../Mock/backgrounds';
        $webPath = 'assets';
        $validImages = ['assets/bg-1.png', 'assets/bg-2.png', 'assets/bg-3.png', 'assets/bg-4.png'];

        return [
            ['/invalid/file-path', $webPath, [], '\RuntimeException'],
            [$validFilePath, $webPath, $validImages]
        ];
    }
}
