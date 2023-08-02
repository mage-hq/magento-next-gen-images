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

namespace Magehqm2\NextGenImages\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Gd extends Field
{
    /**
     * Override to set a different PHTML template
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
            $this->setTemplate('config/gd.phtml');

        return $this;
    }

    /**
     * Override to render the template instead of the regular output
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->toHtml();
    }

    /**
     * Check if GD supports WebP
     *
     * @return bool
     */
    public function hasGdSupport(): bool
    {
        if (!function_exists('gd_info')) {
            return false;
        }

        if (!function_exists('imagecreatefromwebp')) {
            return false;
        }

        $gdInfo = gd_info();
        $webpMatch = false;
        foreach ($gdInfo as $gdInfoLine => $gdInfoSupport) {
            if (stristr($gdInfoLine, 'webp')) {
                $webpMatch = true;
                break;
            }
        }

        if ($webpMatch === false) {
            return false;
        }

        return true;
    }
}
