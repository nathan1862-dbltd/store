<?php
require_once __DIR__ . '/auth.php';

$username = trim($_POST['username'] ?? '');
$password = (string) ($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    http_response_code(400);
    exit('Missing credentials.');
}

if (!Auth::login($username, $password)) {
    http_response_code(401);
    exit('Invalid login credentials.');
}

header('Location: ' . ADMIN_DASHBOARD_PAGE);
exit;
