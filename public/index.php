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

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    if ($origin !== $allowedOrigin) {
        http_response_code(403);
        exit;
    }

    http_response_code(204);
    exit;
}