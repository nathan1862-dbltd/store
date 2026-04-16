<?php
require_once __DIR__ . '/adminconfig.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Basic input */
$username = trim($_POST['username'] ?? '');
$password = (string)($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    die("Missing credentials.");
}

/* Lookup user */
$stmt = $mysqli->prepare("
    SELECT id, username, password_hash, is_admin, is_verified
    FROM users
    WHERE username = ?
    LIMIT 1
");
$stmt->bind_param("s", $username);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();

if (!$user) {
    die("Invalid login.");
}

/* Must be admin */
if ((int)$user['is_admin'] !== 1) {
    die("Access denied.");
}

/* Password check (your DB stores password_hash) */
if (!password_verify($password, $user['password_hash'])) {
    die("Invalid login.");
}

/* Login success */
session_regenerate_id(true);
$_SESSION[ADMIN_SESSION_KEY] = (int)$user['id'];
$_SESSION['admin_username'] = $user['username'];

header("Location: " . ADMIN_DASHBOARD_PAGE);
exit;