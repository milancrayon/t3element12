<?php
declare(strict_types=1);
defined('TYPO3') or die();
use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
  
ExtensionManagementUtility::allowTableOnStandardPages('tx_t3element_domain_model_elements');
ExtensionManagementUtility::allowTableOnStandardPages('tx_t3element_domain_model_license');
ExtensionManagementUtility::allowTableOnStandardPages('tx_t3element_domain_model_themeconfig');
