<?php

// Load custom API configuration
$config = include __DIR__.'/config.php';

// Load Sooprema API client class
require_once __DIR__.'/src/client.php';

// API client instance
$soopremaAPI = new \Sooprema\APISDK\Client($config);