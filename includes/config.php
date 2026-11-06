<?php
/**
 * Magaram Real Estate - Core Configuration
 */

// Error reporting (set to 0 in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Security: Secure session cookie settings before starting session
if (session_status() === PHP_SESSION_NONE) {
    // Only attempt cookie parameters if not running in pure CLI
    if (PHP_SAPI !== 'cli') {
        $cookieParams = [
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
            'httponly' => true,
            'samesite' => 'Lax'
        ];
        @session_set_cookie_params($cookieParams);
    }
    @session_start();
}

// Database Credentials
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'magaram_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Site Constants
define('APP_NAME', 'Magaram Real Estate');
define('APP_TAGLINE', 'Curating Extraordinary Living Spaces');

// Dynamic base URL detection
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
if ($scriptDir === '.' || $scriptDir === '/' || $scriptDir === '\\') {
    $scriptDir = '';
}
// Trim trailing admin or api directory if inside subfolder
$baseDir = preg_replace('/(\/admin(\/properties)?|\/api)$/', '', $scriptDir);
if (!empty($baseDir) && $baseDir[0] !== '/') {
    $baseDir = '/' . $baseDir;
}
$baseUrl = rtrim($protocol . $host . $baseDir, '/');
define('BASE_URL', $baseUrl);

// Uploads Directories
define('ROOT_PATH', dirname(__DIR__));
define('UPLOAD_DIR', ROOT_PATH . DIRECTORY_SEPARATOR . 'uploads');
define('PROPERTY_UPLOAD_DIR', UPLOAD_DIR . DIRECTORY_SEPARATOR . 'properties');
define('BROCHURE_UPLOAD_DIR', UPLOAD_DIR . DIRECTORY_SEPARATOR . 'brochures');

// Security & File constraints
define('MAX_FILE_SIZE', 8 * 1024 * 1024); // 8MB limit
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/avif']);
define('ALLOWED_IMAGE_EXTS', ['jpg', 'jpeg', 'png', 'webp', 'avif']);

// Email notification settings (PHPMailer / Mail fallback)
define('ADMIN_EMAIL', 'admin@magaramrealestate.com');
define('SMTP_HOST', 'smtp.example.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'notifications@magaramrealestate.com');
define('SMTP_PASS', '');
define('SMTP_FROM_NAME', 'Magaram Real Estate Portal');
