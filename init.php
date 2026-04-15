<?php
/**
 * init.php
 * Central bootstrap file
 * - Loads core dependencies
 * - Starts session
 * - Loads authenticated user
 */

declare(strict_types=1);

/* ================= LOAD CORE ================= */

require_once __DIR__ . '/functions.php';

/* ================= SESSION ================= */

ensureSessionStarted();

/* ================= CURRENT USER ================= */

$currentUser = null;

if (!empty($_SESSION['user_id'])) {

    $userId = (int)$_SESSION['user_id'];

    $currentUser = getUserById($mysqli, $userId);

    // If user deleted but session still exists
    if (!$currentUser) {
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit;
    }
}

/* ================= GLOBAL HELPERS ================= */

/**
 * Shortcut: check login state
 */
function is_logged_in(): bool {
    return !empty($_SESSION['user_id']);
}

/**
 * Require login for protected pages
 */
function require_login(): void {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}