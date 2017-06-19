<?php

namespace Streit\streit_ext_viewhelper\ViewHelpers;

use TYPO3\CMS\Fluid\ViewHelpers\Uri\ImageViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\Exception;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class ResponsivePictureViewHelper extends ImageViewHelper
{
	use CompileWithRenderStatic;
	/**
	 * Resizes the image (if required) and returns its path. If the image was not resized, the path will be equal to $src
	 *
	 * @param array $arguments
	 * @param \Closure $renderChildrenClosure
	 * @param RenderingContextInterface $renderingContext
	 * @return string
	 * @throws Exception
	 */
	public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
	{
		$output = "";

		foreach ($arguments['sources'] as $source)
		{
			$arguments['cropVariant'] = $source['variant'];
			foreach ($source["widths"] as $width)
			{
				$output .= "<source";
				$output .= " media=\"(max-width: ".$width."px)\"";

				$output .= " srcset=\"";
				$arguments['maxWidth'] = $width;
				$output .= parent::renderStatic($arguments, $renderChildrenClosure, $renderingContext) . " 1x";
				$arguments['maxWidth'] = $width * 2;
				$output .= ", " . parent::renderStatic($arguments, $renderChildrenClosure, $renderingContext) . " 2x";
				$output .= "\">";
			}
		}
		return $output;
	}

	/**
	 * Initialize arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments()
	{
		parent::initializeArguments();
		$this->registerArgument('sources', 'array', 'Source-Configuration of the Image delivered in TypoScript');
		$this->registerArgument('alt', 'string', 'Specifies an alternate text for an image', false);
	}
}