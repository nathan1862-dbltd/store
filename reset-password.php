<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/header.php';

ensureSessionStarted();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $login = strtolower(trim($_POST['login'] ?? ''));
    $newPassword = $_POST['password'] ?? '';

    if ($login === '' || $newPassword === '') {
        $error = "All fields required.";
    } else {

        $stmt = $mysqli->prepare("
            SELECT id FROM users
            WHERE LOWER(username)=?
               OR LOWER(email)=?
            LIMIT 1
        ");

        $stmt->bind_param("ss", $login, $login);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user) {

            $hashed = password_hash($newPassword, PASSWORD_BCRYPT);

            $stmt = $mysqli->prepare("
                UPDATE users
                SET password=?
                WHERE id=?
            ");
            $stmt->bind_param("si", $hashed, $user['id']);
            $stmt->execute();
            $