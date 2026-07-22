<?php

// config/view.php
return [
	'Twig' => [
		'paths' => [
			dirname(__DIR__) . '/resource/view',
			dirname(__DIR__) . '/resource/acme/blog',
		],
		'cache_path' =>  dirname(__DIR__) . '/storage/view',
		'debug' => $_ENV['APP_DEBUG'] ?? true,
		'auto_reload' => true,
		'strict_variables' => false,
	],
];
