<?php
use Crayon\T3element\Controller\ElementsController;

return [
    't3element' => [
        'parent' => 'web',
        'position' => ['after' => 'web_info'],
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/t3element',
        'labels' => 'LLL:EXT:t3element/Resources/Private/Language/locallang_t3element.xlf',
        'icon'   => 'EXT:t3element/Resources/Public/Icons/ic.png',
        'extensionName' => 'T3element',
        'controllerActions' => [
            ElementsController::class => [
                'index',
            ],
        ],
    ],
];
