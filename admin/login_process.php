<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 

require_once __DIR__ . '/../auth/Auth.php';

Session::start();

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    $_SESSION['error'] = "Invalid input";
    header("Location: login.php");
    exit;
}

if (Auth::login($username, $password)) {
    header("Location: dashboard.php");
    exit;
} else {
    $_SESSION['error'] = "Invalid username or password";
    header("Location: login.php");
    exit;
}
