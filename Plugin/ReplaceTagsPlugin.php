<?php 
/**
 * Magehqm2
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magehqm2.com license that is
 * available through the world-wide-web at this URL:
 * http://magehq.com/license
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category   Magehqm2
 * @package    Magehqm2_WebP
 * @copyright  Magehqm2\Copyright (c) 2023 Magehqm2 (http://www.magehq.com/)
 * @license    http://www.magehq.com/LICENSE-1.0.html
 */
declare(strict_types=1);

namespace Magehqm2\NextGenImages\Plugin;

use Exception as ExceptionAlias;
use Magento\Framework\View\LayoutInterface;
use Magehqm2\NextGenImages\Image\HtmlReplacer;

class ReplaceTagsPlugin
{
    /**
     * @var HtmlReplacer
     */
    private $htmlReplacer;

    /**
     * ReplaceTags constructor.
     *
     * @param HtmlReplacer $htmlReplacer
     */
    public function __construct(
        HtmlReplacer $htmlReplacer
    ) {
        $this->htmlReplacer = $htmlReplacer;
    }

    /**
     * Interceptor of getOutput()
     *
     * @param LayoutInterface $layout
     * @param string $output
     * @return string
     */
    public function afterGetOutput(LayoutInterface $layout, string $output): string
    {
        if ($this->shouldModifyOutput($layout) === false) {
            return $output;
        }

        return $this->htmlReplacer->replaceImagesInHtml($layout, $output);
    }

    /**
     * @param LayoutInterface $layout
     * @return bool
     */
    private function shouldModifyOutput(LayoutInterface $layout): bool
    {
        $handles = $layout->getUpdate()->getHandles();
        if (empty($handles)) {
            return false;
        }

        foreach ($handles as $handle) {
            if (strstr($handle, '_email_')) {
                return false;
            }
        }

        $skippedHandles = [
            'webp_skip',
            'nextgenimages_skip',
        ];

        if (array_intersect($skippedHandles, $handles)) {
            return false;
        }

        return true;
    }
}
