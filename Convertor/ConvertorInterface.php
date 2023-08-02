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

namespace Magehqm2\NextGenImages\Convertor;

use Magehqm2\NextGenImages\Exception\ConvertorException;
use Magehqm2\NextGenImages\Image\SourceImage;

interface ConvertorInterface
{
    /**
     * @param string $imageUrl
     * @return SourceImage
     * @throws ConvertorException
     */
    public function getSourceImage(string $imageUrl): SourceImage;

    /**
     * @param string $sourceImageUri
     * @param string|null $destinationImageUri
     * @return bool
     * @throws ConvertorException
     */
    public function convert(string $sourceImageUri, ?string $destinationImageUri = null): bool;
}
