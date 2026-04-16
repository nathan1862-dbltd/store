<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DB_HOST', 'localhost');
define('DB_USER', 'uwnktxcpef_newecommerce');
define('DB_PASS', 'uwnktxcpef_newecommerce');
define('DB_NAME', 'uwnktxcpef_newecommerce');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($mysqli->connect_error) {
    die("Database Connection Failed: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");
