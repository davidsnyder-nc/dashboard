<?php
// Set the content type to application/json so the browser interprets the response correctly.
header('Content-Type: application/json');

// --- Configuration ---
// IMPORTANT: Set the base URL of your Glances instance here.
$glances_base_url = 'http://127.0.0.1:61208';

// Get the specific API endpoint requested (e.g., 'quicklook', 'cpu').
$endpoint = $_GET['p'] ?? 'quicklook';

// A whitelist of allowed endpoints to prevent abuse of the proxy.
$allowed_endpoints = [
    'quicklook',
    'cpu',
    'mem',
    'load',
    'fs',
    'diskio',
    'network',
    'sensors',
    'processlist',
    'system' // <-- This was the missing endpoint
];

// --- Validation ---
// Check if the requested endpoint is in our allowed list.
if (!in_array($endpoint, $allowed_endpoints, true)) {
    // If not allowed, send a 400 Bad Request error and exit.
    http_response_code(400);
    echo json_encode(['error' => 'Bad endpoint specified.']);
    exit;
}

// --- API Request ---
// Construct the full URL to the Glances API.
$api_url = rtrim($glances_base_url, '/') . "/api/4/$endpoint";

// Create a stream context with a short timeout to prevent the script from hanging.
$context = stream_context_create(['http' => ['timeout' => 2]]);

// Use the '@' symbol to suppress warnings if file_get_contents fails (e.g., Glances is down).
// Fetch the content from the Glances API.
$response = @file_get_contents($api_url, false, $context);

// --- Response Handling ---
// Check if the request was successful.
if ($response === false) {
    // If it failed, send a 503 Service Unavailable error.
    http_response_code(503);
    echo json_encode(['error' => 'The Glances API is unreachable. Please ensure it is running and the URL in glances.php is correct.']);
    exit;
}

// If successful, echo the JSON response from Glances directly to the browser.
echo $response;
