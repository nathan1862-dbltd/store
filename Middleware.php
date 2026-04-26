<?php
require_once __DIR__ . '/Auth.php';

function requireAuth($role = null) {
    if (!Auth::check()) {
        header("Location: /admin/login.php");
        exit;
    }

    $user = Auth::user();

    if ($role && (!isset($user['role']) || $user['role'] !== $role)) {
        http_response_code(403);
        die("Unauthorized access");
    }
}
