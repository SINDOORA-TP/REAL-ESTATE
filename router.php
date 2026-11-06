<?php
/**
 * Magaram Real Estate - Built-in PHP Development Server Router
 * Usage: php -S localhost:8000 router.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files directly if they exist
$filePath = __DIR__ . $uri;
if ($uri !== '/' && file_exists($filePath) && !is_dir($filePath)) {
    return false;
}

// Clean URL rewrites for PHP built-in server:
// /property/the-sovereign-penthouse -> property.php?slug=the-sovereign-penthouse
if (preg_match('#^/property/([a-zA-Z0-9_-]+)/?$#', $uri, $matches)) {
    $_GET['slug'] = $matches[1];
    require __DIR__ . '/property.php';
    exit;
}

if ($uri === '/properties' || $uri === '/properties/') {
    require __DIR__ . '/properties.php';
    exit;
}

if ($uri === '/about' || $uri === '/about/') {
    require __DIR__ . '/about.php';
    exit;
}

if ($uri === '/services' || $uri === '/services/') {
    require __DIR__ . '/services.php';
    exit;
}

if ($uri === '/contact' || $uri === '/contact/') {
    require __DIR__ . '/contact.php';
    exit;
}

if ($uri === '/sitemap.xml') {
    require __DIR__ . '/sitemap.php';
    exit;
}

// Default to index.php
require __DIR__ . '/index.php';

