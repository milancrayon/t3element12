<?php 
declare(strict_types=1);

defined('TYPO3') or die();

use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;


     call_user_func(function() 
     {
       
         $extensionKey = 't3element';
         
         ExtensionManagementUtility::registerPageTSConfigFile(
             $extensionKey,
             'Configuration/TsConfig/Page/All.tsconfig',
             $extensionKey
         );
     });
     