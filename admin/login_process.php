<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 

require_once __DIR__ . '/adminconfig.php';
require_once __DIR__ . '/auth.php';

Session::start();

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
    $_SESSION['error'] = "Invalid input";
    header("Location: login.php");
    exit;
}

if (Auth::login($username, $password)) {
    header("Location: dashboard.php");
    exit;
} else {
    $_SESSION['error'] = "Invalid credentials";
    header("Location: login.php");
    exit;
}