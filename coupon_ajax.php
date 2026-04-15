<?php
require_once __DIR__ . '/init.php';
header('Content-Type: application/json');

ensureSessionStarted();

$action = (string)($_POST['action'] ?? '');
$code   = strtoupper(trim((string)($_POST['code'] ?? '')));

if ($action === 'remove') {
    unset($_SESSION['coupon']);
    echo json_encode(['ok' => true]);
    exit;
}

if ($code === '') {
    echo json_encode(['ok' => false, 'msg' => 'Enter coupon code']);
    exit;
}

// Store minimal coupon info in session (validation is re-done at place_order)
$stmt = $mysqli->prepare("\
    SELECT id, code, type, value, min_order, max_discount, expires_at, is_active
    FROM coupons
    WHERE code = ?
    LIMIT 1
");
$stmt->bind_param('s', $code);
$stmt->execute();
$coupon = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$coupon || (int)$coupon['is_active'] !== 1) {
    echo json_encode(['ok' => false, 'msg' => 'Invalid coupon']);
    exit;
}

if (!empty($coupon['expires_at']) && strtotime((string)$coupon['expires_at']) < time()) {
    echo json_encode(['ok' => false, 'msg' => 'Coupon expired']);
    exit;
}

$_SESSION['coupon'] = [
    'id' => (int)$coupon['id'],
    'code' => (string)$coupon['code'],
    'type' => (string)$coupon['type'],
    'value' => (float)$coupon['value'],
];

echo json_encode(['ok' => true]);
