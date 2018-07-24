<?php

// Load custom API configuration
$config = include __DIR__.'/config.php';

// Sooprema main API client class
require_once __DIR__.'/src/sooprema/api-sdk/client.php';

// API client instance
$soopremaAPI = new \Sooprema\APISDK\Client($config);