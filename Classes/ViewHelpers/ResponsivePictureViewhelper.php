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
	 * @param array                     $arguments
	 * @param \Closure                  $renderChildrenClosure
	 * @param RenderingContextInterface $renderingContext
	 *
	 * @return string
	 * @throws Exception
	 */
	public static function renderStatic(
		array $arguments,
		\Closure $renderChildrenClosure,
		RenderingContextInterface $renderingContext
	) {
		$sources = $arguments['sources'];
		$src     = "";
		foreach ($sources as $sourcekey => $source)
		{
			$arguments['cropVariant'] = $source['variant'];
			if (!empty($source['query']))
			{
				$src .= "media=\"" . $source['query'] . "\" ";
			}
			$src .= "<source ";
			$src .= "sizes=\"" . $source['sizes'] . "\" srcset=\"";
			$src .= static::getSrcSet($source, false, $arguments, $renderChildrenClosure, $renderingContext);
			$src .= "\" />";
			if ($source === end($sources))
			{
				$src .= "<img alt=\"" . $arguments['alt'] . "\" src = \"";
				$src .= static::getSrcSet($source, true, $arguments, $renderChildrenClosure, $renderingContext);
				$src .= "\" />";
			}
		}

		return $src;
	}

	protected static function getSrcset($source, $single, $arguments, $renderChildrenClosure, $renderingContext)
	{
		$divider = "";
		$src     = "";
		foreach ($source['width'] as $width)
		{
			if ($width === end($source['width']) && $single)
			{
				$arguments['maxWidth'] = $width;
				$src .= parent::renderStatic($arguments, $renderChildrenClosure, $renderingContext);
			}
			elseif (!$single)
			{
				$arguments['maxWidth'] = $width;
				$src .= $divider . parent::renderStatic($arguments, $renderChildrenClosure,
						$renderingContext) . " " . $width . "w";
				$divider               = ", ";
				$arguments['maxWidth'] = $width * 2;
				$src .= $divider . parent::renderStatic($arguments, $renderChildrenClosure,
						$renderingContext) . " " . ($width * 2) . "w";
			}
		}

		return $src;
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