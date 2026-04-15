<?php
require_once __DIR__ . '/init.php';
header('Content-Type: application/json');

ensureSessionStarted();
redirectIfNotLoggedIn();

$code     = trim((string)($_POST['coupon'] ?? $_POST['code'] ?? ''));
$subtotal = (float)($_POST['subtotal'] ?? 0);

if ($code === '') {
    echo json_encode(['error' => 'Enter coupon code.']);
    exit;
}

[$coupon, $discount] = coupon_discount($mysqli, $code, $subtotal);

if (!$coupon) {
    unset($_SESSION['coupon']);
    echo json_encode(['error' => 'Invalid or expired coupon.']);
    exit;
}

$_SESSION['coupon'] = [
    'id' => (int)$coupon['id'],
    'code' => (string)$coupon['code'],
    'type' => (string)$coupon['type'],
    'value' => (float)$coupon['value'],
];

echo json_encode([
    'ok' => true,
    'discount' => round($discount, 2),
    'coupon_code' => (string)$coupon['code'],
]);
