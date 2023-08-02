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

namespace Magehqm2\NextGenImages\Plugin;

use Magento\Framework\Image;
use Magehqm2\NextGenImages\Config\Config;
use Magehqm2\NextGenImages\Convertor\ConvertorListing;
use Magehqm2\NextGenImages\Exception\ConvertorException;
use Magehqm2\NextGenImages\Logger\Debugger;

/**
 * Class ConvertAfterImageSave
 * @package Magehqm2\NextGenImages\Plugin
 */
class ConvertAfterImageSave
{
    /**
     * @var ConvertorListing
     */
    private $convertorListing;
    /**
     * @var Debugger
     */
    private $debugger;
    /**
     * @var Config
     */
    private $config;

    /**
     * ConvertAfterImageSave constructor.
     * @param ConvertorListing $convertorListing
     * @param Debugger $debugger
     * @param Config $config
     */
    public function __construct(
        ConvertorListing $convertorListing,
        Debugger $debugger,
        Config $config
    ) {
        $this->convertorListing = $convertorListing;
        $this->debugger = $debugger;
        $this->config = $config;
    }

    /**
     * @param Image $subject
     * @param $return
     * @param null $destination
     * @param null $newFileName
     * @return void
     */
    public function afterSave(Image $subject, $return, $destination = null, $newFileName = null)
    {
        if (!$this->config->convertImagesOnSave()) {
            return;
        }

        foreach ($this->convertorListing->getConvertors() as $convertor) {
            try {
                $convertor->convert((string)$destination);
            } catch (ConvertorException $e) {
                $this->debugger->debug($e->getMessage());
            }
        }
    }
}
