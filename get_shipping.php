<?php
require_once __DIR__ . '/init.php';
header('Content-Type: application/json');

$township_id = (int)($_POST['township_id'] ?? 0);
$subtotal    = (float)($_POST['subtotal'] ?? 0);
$discount    = (float)($_POST['discount'] ?? 0);

$shipping = get_shipping_fee($mysqli, $township_id);
$total    = max(0.0, $subtotal - $discount + $shipping);

echo json_encode([
    'shipping' => round($shipping, 2),
    'total'    => round($total, 2),
]);
