<?php
require_once __DIR__ . '/init.php';
ensureSessionStarted();

header('Content-Type: application/json');

$code = trim($_POST['code'] ?? '');
$subtotal = (float)($_POST['subtotal'] ?? 0);

if ($code === '') {
    echo json_encode(["success" => false, "message" => "Enter coupon code"]);
    exit;
}

$stmt = $mysqli->prepare("
    SELECT id, type, value, min_order, max_discount, expires_at, is_active
    FROM coupons
    WHERE code = ?
    LIMIT 1
");
$stmt->bind_param("s", $code);
$stmt->execute();
$coupon = $stmt->get_result()->fetch_assoc();
$stmt->close();

/* ================= VALIDATIONS ================= */

if (!$coupon) {
    echo json_encode(["success" => false, "message" => "Coupon not found"]);
    exit;
}

if ((int)$coupon['is_active'] !== 1) {
    echo json_encode(["success" => false, "message" => "Coupon inactive"]);
    exit;
}

if (!empty($coupon['expires_at']) && strtotime($coupon['expires_at']) < time()) {
    echo json_encode(["success" => false, "message" => "Coupon expired"]);
    exit;
}

if ($subtotal < (float)$coupon['min_order']) {
    echo json_encode([
        "success" => false,
        "message" => "Minimum order not reached"
    ]);
    exit;
}

/* ================= CALCULATE DISCOUNT ================= */

$discount = 0;

if ($coupon['type'] === 'percent') {
    $discount = $subtotal * ((float)$coupon['value'] / 100);
} else {
    $discount = (float)$coupon['value'];
}

if (!empty($coupon['max_discount']) && $discount > (float)$coupon['max_discount']) {
    $discount = (float)$coupon['max_discount'];
}

if ($discount > $subtotal) {
    $discount = $subtotal;
}

$discount = round($discount, 2);

/* ================= STORE SESSION ================= */

$_SESSION['applied_coupon'] = [
    'id'       => $coupon['id'],
    'code'     => $code,
    'discount' => $discount
];

echo json_encode([
    "success"  => true,
    "discount" => $discount
]);