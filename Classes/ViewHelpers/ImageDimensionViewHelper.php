<?php

namespace Streit\streittemplate\ViewHelpers;

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;


/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * ViewHelper to render the page title
 *
 * # Example: Basic Example
 * # Description: Render the content of the VH as page title
 * <code>
 *    <n:titleTag>{newsItem.title}</n:titleTag>
 * </code>
 * <output>
 *    <title>TYPO3 is awesome</title>
 * </output>
 *
 */
class ImageDimensionViewHelper extends AbstractViewHelper
{

    /**
     * Render a given media file
     *
     * @param TYPO3\CMS\Core\Resource\FileReference $file
     * @param string        $width  This can be a numeric value representing the fixed width of in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @param string        $height This can be a numeric value representing the fixed height in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @param string        $get    a String representing the Dimension we want to get.
     *
     * @return string Dimension in Pixel
     */
    public function render($file = null, $width = null, $height = null, $get = 'height')
    {
        // get Resource Object (non ExtBase version)
        if (is_callable([$file, 'getOriginalResource'])) {
            // We have a domain model, so we need to fetch the FAL resource object from there
            $file = $file->getOriginalResource();
        }
        $crop                   = $file instanceof FileReference ? $file->getProperty('crop') : null;
        $processingInstructions = [
            'width'  => $width,
            'height' => $height,
            'crop'   => $crop,
        ];
        $imageService           = $this->getImageService();
        $processedImage         = $imageService->applyProcessingInstructions($file, $processingInstructions);

        if ($get == 'height') {
            return $processedImage->getProperty('height');
        } else {
            return $processedImage->getProperty('width');
        }
    }

    /**
     * Return an instance of ImageService
     *
     * @return ImageService
     */
    protected function getImageService()
    {
        return $this->objectManager->get(ImageService::class);
    }
}
