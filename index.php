<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path === '/' || $path === '/login') {
    require 'public/views/login.html';
} elseif ($path === '/dashboard') {
    require 'public/views/dashboard.html';
} elseif ($path === '/index') {
    require 'public/views/index.html';
} else {
    http_response_code(404);
    echo "404 Not Found";
}