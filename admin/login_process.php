<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once __DIR__ . '/adminconfig.php';
require_once __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . ADMIN_LOGIN_PAGE);
    exit;
}

$usernameInput = filter_input(INPUT_POST, 'username', FILTER_DEFAULT);
$username = is_string($usernameInput) ? trim($usernameInput) : '';
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    $_SESSION['error'] = 'Invalid input';
    header('Location: ' . ADMIN_LOGIN_PAGE);
    exit;
}

if (Auth::login($username, $password)) {
    header('Location: ' . ADMIN_DASHBOARD_PAGE);
    exit;
}

$_SESSION['error'] = 'Invalid credentials';
header('Location: ' . ADMIN_LOGIN_PAGE);
exit;
