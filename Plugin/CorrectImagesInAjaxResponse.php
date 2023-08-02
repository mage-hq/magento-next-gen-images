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

use Exception;
use Magento\Swatches\Helper\Data;
use Magehqm2\NextGenImages\Browser\BrowserSupport;
use Magehqm2\NextGenImages\Image\UrlReplacer;
use Magehqm2\NextGenImages\Logger\Debugger;

class CorrectImagesInAjaxResponse
{
    /**
     * @var BrowserSupport
     */
    private $browserSupport;

    /**
     * @var UrlReplacer
     */
    private $urlReplacer;
    /**
     * @var Debugger
     */
    private $debugger;

    /**
     * CorrectImagesInAjaxResponse constructor.
     *
     * @param BrowserSupport $browserSupport
     * @param UrlReplacer $urlReplacer
     * @param Debugger $debugger
     */
    public function __construct(
        BrowserSupport $browserSupport,
        UrlReplacer $urlReplacer,
        Debugger $debugger
    ) {
        $this->browserSupport = $browserSupport;
        $this->urlReplacer = $urlReplacer;
        $this->debugger = $debugger;
    }

    /**
     * @param Data $dataHelper
     * @param array $data
     * @return mixed[]
     */
    public function afterGetProductMediaGallery(Data $dataHelper, array $data): array
    {
        if (!$this->browserSupport->hasWebpSupport()) {
            return $data;
        }

        $data = $this->replaceUrls($data);
        return $data;
    }

    /**
     * @param mixed[] $dataArray
     * @return mixed[]
     */
    private function replaceUrls(array $dataArray): array
    {
        if (empty($dataArray)) {
            return $dataArray;
        }

        foreach ($dataArray as $name => $value) {
            if (is_array($value)) {
                $dataArray[$name] = $this->replaceUrls($value);
                continue;
            }

            if (!is_string($value)) {
                continue;
            }

            if (!preg_match('/\.(jpg|png)$/', $value)) {
                continue;
            }

            try {
                $dataArray[$name] = $this->urlReplacer->getNewImageUrlFromImageUrl($value);
            } catch (Exception $e) {
                $this->debugger->debug($e->getMessage(), [$name => $value]);
                continue;
            }
        }

        return $dataArray;
    }
}
