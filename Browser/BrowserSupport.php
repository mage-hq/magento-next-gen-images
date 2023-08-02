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

namespace Magehqm2\NextGenImages\Browser;

use Magento\Framework\App\Request\Http as Request;
use Magento\Framework\HTTP\Header;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\LayoutInterface;
use Magehqm2\NextGenImages\Config\Config;

class BrowserSupport implements ArgumentInterface
{
    /**
     * @var Header
     */
    private $headerService;

    /**
     * @var CookieManagerInterface
     */
    private $cookieManager;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var LayoutInterface
     */
    private $layout;

    /**
     * BrowserSupport constructor.
     *
     * @param Header $headerService
     * @param CookieManagerInterface $cookieManager
     * @param Request $request
     * @param Config $config
     * @param LayoutInterface $layout
     */
    public function __construct(
        Header $headerService,
        CookieManagerInterface $cookieManager,
        Request $request,
        Config $config,
        LayoutInterface $layout
    ) {
        $this->headerService = $headerService;
        $this->cookieManager = $cookieManager;
        $this->request = $request;
        $this->config = $config;
        $this->layout = $layout;
    }

    /**
     * @return bool
     */
    public function hasWebpSupport(): bool
    {
        if ($this->config->hasFullPageCacheEnabled($this->layout) === true) {
            return false;
        }

        if ($this->acceptsWebpHeader()) {
            return true;
        }

        if ($this->isChromeBrowser()) {
            return true;
        }

        if ($this->hasCookie()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function acceptsWebpHeader(): bool
    {
        if (strpos((string)$this->request->getHeader('ACCEPT'), 'image/webp') !== false) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isChromeBrowser(): bool
    {
        $userAgent = $this->headerService->getHttpUserAgent();

        // Chrome 9 or higher
        if (preg_match('/Chrome\/([0-9]+)/', $userAgent, $match)) {
            if ($match[1] > 9) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasCookie(): bool
    {
        if ((int)$this->cookieManager->getCookie('webp') === 1) {
            return true;
        }

        return false;
    }
}
