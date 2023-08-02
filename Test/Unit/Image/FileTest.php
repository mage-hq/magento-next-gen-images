<?php 
/**
 * Magehqm2
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the magehq.com license that is
 * available through the world-wide-web at this URL:
 * https://magehq.com/license.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category   magehqm2
 * @package    Magehqm2_NextGenImages
 * @copyright  Copyright (c) 2023 magehqm2 (https://magehq.com/)
 * @license    https://magehq.com/license.html
 */
declare(strict_types=1);

namespace Magehqm2\NextGenImages\Test\Unit\Image;

use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Directory\ReadFactory as DirectoryReadFactory;
use Magento\Framework\Filesystem\Driver\File as FileDriver;
use Magehqm2\NextGenImages\Image\File;
use PHPUnit\Framework\TestCase;
use Magehqm2\NextGenImages\Logger\Debugger;

/**
 * Class FileTest testing behaviour of File
 */
class FileTest extends TestCase
{
    /**
     * @test Test the resolve() function
     */
    public function testResolve(): void
    {
        $target = $this->getTarget();
        $resolvedFile = $target->resolve('http://anotherhost.com/some/fake/url.png');
        $this->assertSame($resolvedFile, '/pub/some/fake/url.png');
    }

    /**
     * @return File
     */
    public function getTarget(): File
    {
        $directoryListMock = $this->getMockBuilder(DirectoryList::class)
            ->disableOriginalConstructor()
            ->getMock();

        $directoryReadFactoryMock = $this->getMockBuilder(DirectoryReadFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fileDriverMock = $this->getMockBuilder(FileDriver::class)
            ->disableOriginalConstructor()
            ->getMock();

        $debugger = $this->getMockBuilder(Debugger::class)
            ->disableOriginalConstructor()
            ->getMock();

        $target = new File($directoryListMock, $directoryReadFactoryMock, $fileDriverMock, $debugger); // phpstan:ignore
        return $target;
    }
}
