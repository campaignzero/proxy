<?php

// Get URL from REQUEST_URI
$url = (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] !== '/') ? ltrim($_SERVER['REQUEST_URI'], '/') : NULL;

// Initial setting for whether URL is an Image
$accepted = false;

// Initial setting for whether URL even exists
$missing = false;

// Initial headers from URL
$headers = [];

// Check if URL exists and is NOT this proxy ( that would cause an infinite loop )
if (isset($url) && strpos($url, 'proxy.joincampaignzero.org') === false) {
    $headers = get_headers($url);
    $acceptable = ['Content-Type: image/gif', 'Content-Type: image/jpeg', 'Content-Type: image/png'];

    // Loop through URL headers before fetching its content and see if it's an acceptable MIME type
    foreach ($headers as $header) {
        if (strpos(strtolower($header), '404 notfound') !== false) {
            $missing = true;
        }
        if (!$missing && in_array($header, $acceptable)) {
            $accepted = true;
        }
    }
}

// Load Image Content if Accepted, otherwise load a blank pixel image
if ($accepted) {
    $image = file_get_contents($url);
} else {
    $url = 'https://proxy.joincampaignzero.org/pixel.gif';
    $image = file_get_contents($url);
    $headers = get_headers($url);
}

// Add Response Headers from Original Image
foreach ($headers as $header) {
    header($header);
}

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one you want to allow, and if so:
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }

    exit(0);
}

// Return Content to Browser
exit($image);