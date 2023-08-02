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

namespace Magehqm2\NextGenImages\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\LayoutInterface;
use Magento\PageCache\Model\DepersonalizeChecker;

class Config implements ArgumentInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var DepersonalizeChecker
     */
    private $depersonalizeChecker;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param DepersonalizeChecker $depersonalizeChecker
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        DepersonalizeChecker $depersonalizeChecker
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->depersonalizeChecker = $depersonalizeChecker;
    }

    /**
     * @return bool
     */
    public function enabled(): bool
    {
        return (bool)$this->scopeConfig->getValue('magehqm2_nextgenimages/settings/enabled');
    }

    /**
     * @return bool
     */
    public function enabledIgnoreClass(): bool
    {
        return (bool)$this->scopeConfig->getValue('magehqm2_nextgenimages/settings/enable_ignore_image');
    }

    /**
     * @return string
     */
    public function getIgnoreClass()
    {
        return $this->scopeConfig->getValue('magehqm2_nextgenimages/settings/ignore_css_class');
    }

    /**
     * @return bool
     */
    public function allowImageCreation(): bool
    {
        return (bool)$this->scopeConfig->getValue('magehqm2_nextgenimages/settings/convert_images');
    }

    /**
     * @return bool
     */
    public function convertImagesOnSave(): bool
    {
        return (bool)$this->scopeConfig->getValue('magehqm2_nextgenimages/settings/convert_on_save');
    }

    /**
     * @return bool
     */
    public function addLazyLoading(): bool
    {
        return (bool)$this->scopeConfig->getValue('magehqm2_nextgenimages/settings/lazy_loading');
    }

    /**
     * @param LayoutInterface $block
     * @return bool
     */
    public function hasFullPageCacheEnabled(LayoutInterface $block): bool
    {
        if ($this->depersonalizeChecker->checkIfDepersonalize($block)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isDebugging(): bool
    {
        return (bool)$this->scopeConfig->getValue('magehqm2_nextgenimages/settings/debug');
    }

    /**
     * @return bool
     */
    public function isReplaceOriginalImage(): bool
    {
        return (bool)$this->scopeConfig->getValue('magehqm2_nextgenimages/settings/allow_replace_org_image');
    }

    /**
     * @return bool
     */
    public function isLogging(): bool
    {
        return (bool)$this->scopeConfig->getValue('magehqm2_nextgenimages/settings/log');
    }
}
