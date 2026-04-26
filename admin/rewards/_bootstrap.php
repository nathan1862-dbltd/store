<?php
require_once __DIR__ . '/../../init.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($currentUser) || !is_array($currentUser)) {
    if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
        $currentUser = $_SESSION['user'];
    }
}

function admin_require(): void
{
    global $currentUser;

    $isAdmin = 0;
    if (is_array($currentUser)) {
        if (isset($currentUser['is_admin'])) {
            $isAdmin = (int) $currentUser['is_admin'];
        } elseif (isset($currentUser['isAdmin'])) {
            $isAdmin = (int) $currentUser['isAdmin'];
        }
    }

    if ($isAdmin !== 1) {
        header('Location: ../login.php');
        exit;
    }
}

function e($s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}
