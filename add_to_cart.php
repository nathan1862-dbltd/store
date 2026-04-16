<?php
/**
 * add_to_cart.php
 * DB-backed cart (carts + cart_items)
 * SAFE for PHP 8 / mysqli
 */

require_once __DIR__ . '/init.php';
if (session_status() === PHP_SESSION_NONE) session_start();

/* ================= INPUT ================= */
$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$variantId = isset($_POST['variant_id']) ? (int)$_POST['variant_id'] : 0;
$qty       = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;

if ($productId <= 0) {
    http_response_code(400);
    exit('Invalid product');
}

/* ================= IDENTIFY CART OWNER ================= */
$userId    = $_SESSION['user_id'] ?? null;
$sessionId = session_id();

/* ================= FIND OR CREATE CART ================= */
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
    $stmt = $mysqli->prepare("
        INSERT INTO carts (user_id, session_id, created_at)
        VALUES (?, ?, NOW())
    ");
    $stmt->bind_param("is", $userId, $sessionId);
    $stmt->execute();
    $cartId = $stmt->insert_id;
    $stmt->close();
} else {
    $cartId = (int)$cart['id'];
}

/* ================= VALIDATE PRODUCT ================= */
$stmt = $mysqli->prepare("
    SELECT id FROM products
    WHERE id = ?
    LIMIT 1
");
$stmt->bind_param("i", $productId);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    http_response_code(404);
    exit('Product not found');
}

/* ================= RESOLVE PRICE ================= */
$unitPrice = 0.0;

if ($variantId <= 0) {
    http_response_code(400);
    exit('Product requires variant selection');
}

$stmt = $mysqli->prepare("
    SELECT price, discount_price, stock
    FROM product_variants
    WHERE id = ? AND product_id = ?
    LIMIT 1
");
$stmt->bind_param("ii", $variantId, $productId);
$stmt->execute();
$v = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$v || $v['price'] === null) {
    http_response_code(400);
    exit('Invalid variant');
}

$basePrice = (float)$v['price'];
$discountPrice = isset($v['discount_price']) ? (float)$v['discount_price'] : 0.0;
$stockQty = (int)($v['stock'] ?? 0);

if ($stockQty <= 0) {
    http_response_code(409);
    exit('Selected variant is out of stock');
}

if ($discountPrice > 0 && $discountPrice < $basePrice) {
    $unitPrice = $discountPrice;
} else {
    $unitPrice = $basePrice;
}

if ($unitPrice <= 0) {
    http_response_code(400);
    exit('Invalid product price detected');
}

/* ================= MERGE CART ITEM ================= */
$stmt = $mysqli->prepare("
    SELECT id, quantity
    FROM cart_items
    WHERE cart_id = ?
      AND product_id = ?
      AND variant_id = ?
    LIMIT 1
");

$stmt->bind_param(
    "iii",
    $cartId,
    $productId,
    $variantId
);
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($existing) {
    $newQty = (int)$existing['quantity'] + $qty;
    if ($newQty > $stockQty) {
        http_response_code(409);
        exit("Only {$stockQty} item(s) in stock for this variant");
    }

    // Update quantity
    $stmt = $mysqli->prepare("
        UPDATE cart_items
        SET quantity = quantity + ?
        WHERE id = ?
    ");
    $stmt->bind_param("ii", $qty, $existing['id']);
    $stmt->execute();
    $stmt->close();
} else {
    if ($qty > $stockQty) {
        http_response_code(409);
        exit("Only {$stockQty} item(s) in stock for this variant");
    }

    // Insert new cart item
    $stmt = $mysqli->prepare("
        INSERT INTO cart_items
          (cart_id, product_id, variant_id, quantity, unit_price)
        VALUES (?, ?, ?, ?, ?)
    ");

    // IMPORTANT: bind_param needs VARIABLES only
    $stmt->bind_param(
        "iiiid",
        $cartId,
        $productId,
        $variantId,
        $qty,
        $unitPrice
    );

    $stmt->execute();
    $stmt->close();
}

/* ================= REDIRECT ================= */
header("Location: cart.php");
exit;
