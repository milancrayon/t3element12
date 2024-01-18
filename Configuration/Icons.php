<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    't3element' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:t3element/Resources/Public/Icons/ic.png',
        'spinning' => true,
    ],
];