<?php

// API instance and configuration
require_once __DIR__.'/api.php';

// Endpoint request
$data = $soopremaAPI->endpoint('/properties', [
	'page' 		=> 2,
	'perPage' 	=> 5,
]);

// Check error response
if ($soopremaAPI->isError($data)) {

	// Show error
	echo 'API request error: '.$data['response']['reason']."\n";

// Succcess
} else {

	// Check results
	$results = $data['response']['results'];
	if (!$results['info']['count']) {
		echo 'No results'."\n";

	// With results
	} else {

		// Enum items
		foreach ($results['items'] as $item) {
			print_r($item);
		}
	}
}