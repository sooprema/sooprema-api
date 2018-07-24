<?php

// Load configuration
$config = include __DIR__.'/config.php';

// Sooprema main class
require_once __DIR__.'/src/sooprema/api-sdk/sooprema-api.php';

// API instance
$soopremaAPI = new SoopremaAPI($config);