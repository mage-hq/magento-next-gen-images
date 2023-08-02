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

namespace Magehqm2\NextGenImages\Image;

use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Directory\ReadFactory as DirectoryReadFactory;
use Magento\Framework\Filesystem\Driver\File as FileDriver;
use Magento\Framework\Filesystem\File\ReadFactory as FileReadFactory;
use Magento\Framework\View\Asset\File\NotFoundException;
use Magehqm2\NextGenImages\Logger\Debugger;

class File
{
    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * @var DirectoryReadFactory
     */
    private $directoryReadFactory;

    /**
     * @var FileDriver
     */
    private $fileDriver;

    /**
     * @var Debugger
     */
    private $debugger;

    /**
     * @var FileReadFactory
     */
    private $fileReadFactory;

    /**
     * File constructor.
     *
     * @param DirectoryList $directoryList
     * @param DirectoryReadFactory $directoryReadFactory
     * @param FileDriver $fileDriver
     * @param Debugger $debugger
     * @param FileReadFactory $fileReadFactory
     */
    public function __construct(
        DirectoryList $directoryList,
        DirectoryReadFactory $directoryReadFactory,
        FileDriver $fileDriver,
        Debugger $debugger,
        FileReadFactory $fileReadFactory
    ) {
        $this->directoryList = $directoryList;
        $this->directoryReadFactory = $directoryReadFactory;
        $this->fileDriver = $fileDriver;
        $this->debugger = $debugger;
        $this->fileReadFactory = $fileReadFactory;
    }

    /**
     * @param string $uri
     *
     * @return string
     */
    public function resolve(string $uri): string
    {
        if ($this->fileExists($uri)) {
            return $uri;
        }

        if (strpos($uri, $this->directoryList->getRoot()) === 0) {
            return $uri;
        }

        // phpcs:disable Magento2.Functions.DiscouragedFunction
        $parsedUrl = parse_url($uri);
        if (!$parsedUrl) {
            return '';
        }

        $path = $parsedUrl['path'];
        $path = preg_replace('/^\/pub\//', '/', (string)$path);
        $path = preg_replace('/\/static\/version([0-9]+\/)/', '/static/', (string)$path);
        $path = $this->getAbsolutePathFromImagePath((string)$path);

        return $path;
    }

    /**
     * @param string $uri
     * @return bool
     * @deprecated Use uriExists($uri) instead
     */
    public function urlExists(string $uri): bool
    {
        return $this->uriExists($uri);
    }

    /**
     * @param string $uri
     * @return bool
     */
    public function uriExists(string $uri): bool
    {
        if ($this->fileExists($uri)) {
            return true;
        }

        $filePath = $this->resolve($uri);
        if ($this->fileExists($filePath)) {
            return true;
        }

        return false;
    }

    /**
     * @param $filePath
     * @return bool
     */
    public function fileExists($filePath): bool
    {
        try {
            $fileRead = $this->fileReadFactory->create($filePath, 'file');
            return (bool)$fileRead->readAll();
        } catch (FileSystemException $fileSystemException) {
            return false;
        }
    }

    /**
     * @param $filePath
     * @return bool
     * @throws FileSystemException
     */
    public function isWritable($filePath): bool
    {
        if ($this->fileExists($filePath)) {
            return $this->fileDriver->isWritable($filePath);
        }

        return $this->fileDriver->isWritable($this->fileDriver->getParentDirectory($filePath));
    }

    /**
     * @param string $sourceFilename
     *
     * @return string
     */
    public function convertSuffix(string $sourceFilename, string $destinationSuffix): string
    {
        return (string)preg_replace('/\.(jpg|jpeg|png)/i', $destinationSuffix, $sourceFilename);
    }

    /**
     * @param string $imagePath
     *
     * @return string
     */
    public function getAbsolutePathFromImagePath(string $imagePath): string
    {
        return $this->directoryList->getRoot() . '/pub' . $imagePath;
    }

    /**
     * @param string $filePath
     *
     * @return int
     */
    public function getModificationTime(string $filePath): int
    {
        try {
            $stat = $this->fileDriver->stat($filePath);
            return (isset($stat['mtime'])) ? (int)$stat['mtime'] : $stat['ctime'];
        } catch (FileSystemException $e) {
            $this->debugger->debug($e->getMessage(), ['filePath' => $filePath]);
            return 0;
        }
    }

    /**
     * @param string $targetFile
     * @param string $comparisonFile
     *
     * @return bool
     */
    public function isNewerThan(string $targetFile, string $comparisonFile): bool
    {
        if (!$this->fileExists($targetFile)) {
            return false;
        }

        $targetFileModificationTime = $this->getModificationTime($targetFile);
        if ($targetFileModificationTime === 0) {
            return false;
        }

        $comparisonFileModificationTime = $this->getModificationTime($comparisonFile);
        if ($comparisonFileModificationTime === 0) {
            return true;
        }

        if ($targetFileModificationTime > $comparisonFileModificationTime) {
            return true;
        }

        return false;
    }

    /**
     * @param string $sourceImageFilename
     * @param string $destinationImageFilename
     * @return bool
     * @throws NotFoundException
     */
    public function needsConversion(string $sourceImageFilename, string $destinationImageFilename): bool
    {
        if (!$this->fileExists($sourceImageFilename)) {
            return false;
        }

        if ($this->fileExists($destinationImageFilename)) {
            return false;
        }

        if ($this->isNewerThan($destinationImageFilename, $sourceImageFilename)) {
            return false;
        }

        return true;
    }
}
