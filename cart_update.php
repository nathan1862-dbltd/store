<?php
require_once __DIR__ . '/init.php';
if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json');

/* ================= GET INPUT ================= */
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

$userId    = $_SESSION['user_id'] ?? null;
$sessionId = session_id();

/* ================= RESOLVE CART ================= */
$stmt = $mysqli->prepare("
    SELECT id FROM carts
    WHERE (user_id = ? AND ? IS NOT NULL)
       OR (session_id = ? AND ? IS NULL)
    LIMIT 1
");
$stmt->bind_param("isis", $userId, $userId, $sessionId, $userId);
$stmt->execute();
$cart = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$cart) {
    echo json_encode(['error' => 'Cart not found']);
    exit;
}

$cartId = (int)$cart['id'];

/* ================= REMOVE ITEM ================= */

if (isset($data['remove'])) {

    $itemId = (int)$data['remove'];

    $stmt = $mysqli->prepare("
        DELETE FROM cart_items
        WHERE id = ? AND cart_id = ?
    ");
    $stmt->bind_param("ii", $itemId, $cartId);
    $stmt->execute();
    $stmt->close();

    $result = $mysqli->query("
        SELECT SUM(quantity * unit_price) AS subtotal,
               SUM(quantity) AS total_items
        FROM cart_items
        WHERE cart_id = $cartId
    ");

    $totals = $result->fetch_assoc();

    echo json_encode([
        'success'     => true,
        'subtotal'    => (float)$totals['subtotal'],
        'total_items' => (int)$totals['total_items']
    ]);
    exit;
}

/* ================= UPDATE QUANTITY ================= */
if (isset($data['id'], $data['qty'])) {

    $itemId = (int)$data['id'];
    $qty    = max(1, (int)$data['qty']);

    $stmt = $mysqli->prepare("
        UPDATE cart_items
        SET quantity = ?
        WHERE id = ? AND cart_id = ?
    ");
    $stmt->bind_param("iii", $qty, $itemId, $cartId);
    $stmt->execute();
    $stmt->close();

    /* Get updated totals */
    $result = $mysqli->query("
        SELECT SUM(quantity * unit_price) AS subtotal,
               SUM(quantity) AS total_items
        FROM cart_items
        WHERE cart_id = $cartId
    ");

    $totals = $result->fetch_assoc();

    echo json_encode([
        'success'     => true,
        'subtotal'    => (float)$totals['subtotal'],
        'total_items' => (int)$totals['total_items']
    ]);
    exit;
}

echo json_encode(['error' => 'Invalid action']);

echo json_encode([
    'success' => true,
    'cart_count' => $newCount
]);