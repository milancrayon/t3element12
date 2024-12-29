<?php 

$pageResolverCallpoint = [
	'before' 	=> 'typo3/cms-frontend/content-length-headers',
	'after' 	=> 'typo3/cms-frontend/shortcut-and-mountpoint-redirect',
];

return [
	'frontend' => [
 
		't3element/resolver' => [
			'target' => \Crayon\T3element\Middleware\PageResolver::class,
			'before' => [
				$pageResolverCallpoint['before'],
			],
			'after' => [
				$pageResolverCallpoint['after'],
			],
		]
	]
];