<?php

/**
 * Router script for PHP built-in web server.
 * Used with: php -S 127.0.0.1:8000 -t public server.php
 *
 * The document root is already set to /public/ via -t flag.
 * Returning false tells PHP to serve the file directly from document root.
 * All other requests go through Laravel's index.php.
 */
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/'
);

// If the file exists in public/ (CSS, JS, images, fonts…), serve it directly.
// PHP built-in server resolves the path against the -t document root (public/).
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri) && ! is_dir(__DIR__.'/public'.$uri)) {
    return false;
}

// Everything else goes through Laravel's front controller.
require_once __DIR__.'/public/index.php';
