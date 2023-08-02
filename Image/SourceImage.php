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

class SourceImage
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $mimeType;

    /**
     * SourceImage constructor.
     * @param string $url
     * @param string $mimeType
     */
    public function __construct(
        string $url,
        string $mimeType
    ) {
        $this->url = $url;
        $this->mimeType = $mimeType;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }
}
