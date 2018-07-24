<?php

// API instance and configuration
require_once __DIR__.'/api.php';

// Properties request
$soopremaAPI->endpoint('/properties', [
	'fields' => 'reference',
	'image'	 => 'listing',
]);