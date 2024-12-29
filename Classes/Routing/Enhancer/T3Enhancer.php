<?php
namespace Crayon\T3element\Routing\Enhancer;

use TYPO3\CMS\Core\Routing\Enhancer\AbstractEnhancer;
use TYPO3\CMS\Core\Routing\Enhancer\RoutingEnhancerInterface;
use TYPO3\CMS\Core\Routing\RouteCollection;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class T3Enhancer extends AbstractEnhancer implements RoutingEnhancerInterface
{
	public const ENHANCER_NAME = 'T3Enhancer';

	/**
	 * @var array
	 */
	protected $configuration;

	public function __construct(array $configuration) {
		$this->configuration = $configuration;
	}

	/**
	 * {@inheritdoc}
	 */
	public function enhanceForMatching(RouteCollection $collection): void { 

		$basePath = '/api/';
		$variant = clone $collection->get('default');
		$variant->setPath( $basePath . '{params?}');
		$variant->setRequirement('params', '.*');
		$collection->add('enhancer_' . $basePath . spl_object_hash($variant), $variant);
 
	}

	/**
	 * {@inheritdoc}
	 */
	public function enhanceForGeneration(RouteCollection $collection, array $parameters): void {
	}
}
