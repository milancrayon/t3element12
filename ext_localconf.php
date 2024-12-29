<?php
declare(strict_types=1);
defined('TYPO3') or die('Access denied.');

  use Crayon\T3element\Routing\Enhancer\T3Enhancer;

	
$GLOBALS['TYPO3_CONF_VARS']['FE']['pageNotFoundOnCHashError'] = false;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['enhancers']['T3Enhancer'] = T3Enhancer::class;