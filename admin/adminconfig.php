<?php
/*
|--------------------------------------------------------
| ADMIN CONFIGURATION FILE
| Centralized configuration for Admin Panel
|--------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Strict',
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'path' => '/',
    ]);
    session_start();
}

/* DATABASE CONFIG */
define('ADMIN_DB_HOST', 'localhost');
define('ADMIN_DB_USER', 'riusfnxmti_admin');
define('ADMIN_DB_PASS', 'riusfnxmti_admin');
define('ADMIN_DB_NAME', 'riusfnxmti_ecommerce');

$mysqli = new mysqli(
    ADMIN_DB_HOST,
    ADMIN_DB_USER,
    ADMIN_DB_PASS,
    ADMIN_DB_NAME
);

if ($mysqli->connect_error) {
    die("Admin DB Connection Failed: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");

/* ADMIN SETTINGS */
define('ADMIN_PANEL_NAME', 'Delux Beauti Admin');
define('ADMIN_SESSION_KEY', 'admin_id');
define('ADMIN_LOGIN_PAGE', '/V3/admin/login.php');
define('ADMIN_DASHBOARD_PAGE', '/V3/admin/dashboard.php');
