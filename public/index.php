<?php
define('PREVENT_DIRECT_ACCESS', TRUE);

// CORS configuration for deployed React frontend
$allowedOrigin = 'https://perez-michael-dave-lab6-frontend.onrender.com';
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if ($origin === $allowedOrigin) {
    header("Access-Control-Allow-Origin: $allowedOrigin");
    header('Vary: Origin');
    header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
}

// Handle browser CORS preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    if ($origin !== $allowedOrigin) {
        http_response_code(403);
        exit;
    }

    http_response_code(204);
    exit;
}

/*
 * LavaLust system directory
 */
$system_path = 'scheme';

/*
 * Application directory
 */
$application_folder = 'app';

/*
 * Public directory
 */
$public_folder = 'public';

/*
 * Define application constants
 */
define('ROOT_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('SYSTEM_DIR', ROOT_DIR . $system_path . DIRECTORY_SEPARATOR);
define('APP_DIR', ROOT_DIR . $application_folder . DIRECTORY_SEPARATOR);
define('PUBLIC_DIR', $public_folder);

/*
 * Load LavaLust framework
 */
require_once SYSTEM_DIR . 'kernel/LavaLust.php';