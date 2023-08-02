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

use Exception as ExceptionAlias;
use Magehqm2\NextGenImages\Convertor\ConvertorInterface;

class UrlReplacer
{
    /**
     * @var ConvertorInterface
     */
    private $convertor;

    /**
     * ReplaceTags constructor.
     *
     * @param ConvertorInterface $convertor
     */
    public function __construct(
        ConvertorInterface $convertor
    ) {
        $this->convertor = $convertor;
    }

    /**
     * @param string $imageUrl
     * @return string
     * @throws ExceptionAlias
     */
    public function getNewImageUrlFromImageUrl(string $imageUrl): string
    {
        $alternateSourceImage = $this->convertor->getSourceImage($imageUrl);
        return $alternateSourceImage->getUrl();
    }
}
