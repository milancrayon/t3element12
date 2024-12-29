<?php
	
declare(strict_types=1);
	
namespace  Crayon\T3element\ViewHelpers;
	
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
	
final class ImgsourceViewHelper extends AbstractViewHelper
{
	use CompileWithRenderStatic;
	protected $escapeOutput = false;
	public function initializeArguments(): void
	{
		$this->registerArgument('uid', 'int', 'Uid not valid', true);
		$this->registerArgument('field', 'string', 'Field not valid', true); 
	}
	
	 /**
	 * @param array $arguments
	 * @param Closure $renderChildrenClosure
	 * @param RenderingContextInterface $renderingContext
	 * @return array
	 */
	public static function renderStatic(
		array $arguments,
		\Closure $renderChildrenClosure,
		RenderingContextInterface $renderingContext
	): array { 
		$files = GeneralUtility::makeInstance(FileRepository::class)->findByRelation('tt_content', $arguments['field'], $arguments['uid']);
		$_imgs = [];
		if (sizeof($files) > 0) {
			foreach ($files as $key => $value) {
				$file = $value->getCombinedIdentifier();
				$_imgs[] = $file;
			}
		}
		return $_imgs;
	}
}