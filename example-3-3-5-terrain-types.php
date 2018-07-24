<?php

// API instance and configuration
require_once __DIR__.'/api.php';

// Endpoint request
$data = $soopremaAPI->endpoint('/terrain-types', [
	'sales' => 1,
]);

// Check error response
if ($soopremaAPI->isError($data)) {

	// Show error
	echo 'API request error: '.$data['response']['reason']."\n";

// Succcess
} else {

	// Show items
	print_r($data['response']['results']['items']);
}