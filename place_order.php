<?php
require_once __DIR__ . '/init.php';

ensureSessionStarted();
redirectIfNotLoggedIn();

$mysqli->begin_transaction();

try {

    $userId = (int)$_SESSION['user_id'];

    /* ===============================
       FORM INPUT
    =============================== */
    $state_id    = (int)($_POST['state_id'] ?? 0);
    $township_id = (int)($_POST['township_id'] ?? 0);
    $full_name   = trim($_POST['full_name'] ?? '');
    $phone       = trim($_POST['phone'] ?? '');
    $address     = trim($_POST['address'] ?? '');

    if (!$state_id || !$township_id || !$full_name || !$phone || !$address) {
        throw new Exception("Missing required fields.");
    }

    /* ===============================
       LOAD CART
    =============================== */
    $stmt = $mysqli->prepare("SELECT id FROM carts WHERE user_id = ? LIMIT 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $cart = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$cart) {
        throw new Exception("Cart not found.");
    }

    $cartId = $cart['id'];

    /* ===============================
       LOAD CART ITEMS (VARIANT BASED STOCK)
    =============================== */
    $stmt = $mysqli->prepare("
        SELECT 
            ci.product_id,
            ci.variant_id,
            ci.quantity,
            ci.unit_price,
            p.name AS product_name,
            pv.variant_name,
            pv.sku,
            pv.image_path,
            pv.stock AS variant_stock
        FROM cart_items ci
        LEFT JOIN products p ON p.id = ci.product_id
        LEFT JOIN product_variants pv ON pv.id = ci.variant_id
        WHERE ci.cart_id = ?
    ");
    $stmt->bind_param("i", $cartId);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    $subtotal = 0;

    while ($row = $result->fetch_assoc()) {

        if ($row['variant_stock'] < $row['quantity']) {
            throw new Exception("Insufficient stock for selected product.");
        }

        $row['line_total'] = $row['quantity'] * $row['unit_price'];
        $subtotal += $row['line_total'];
        $items[] = $row;
    }

    $stmt->close();

    if (empty($items)) {
        throw new Exception("Cart is empty.");
    }

    /* ===============================
       SHIPPING FROM DB
    =============================== */
    $stmt = $mysqli->prepare("
        SELECT shipping_fee 
        FROM shipping_townships 
        WHERE id = ?
        LIMIT 1
    ");
    $stmt->bind_param("i", $township_id);
    $stmt->execute();
    $shipRow = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $shipping = $shipRow ? (float)$shipRow['shipping_fee'] : 0;

    /* ===============================
       COUPON REVALIDATION
    =============================== */
    $discount = 0;
    $coupon_code = null;

    if (!empty($_SESSION['applied_coupon'])) {

        $coupon_code = $_SESSION['applied_coupon']['code'];

        $stmt = $mysqli->prepare("
            SELECT type, value, min_order, max_discount, is_active, expires_at
            FROM coupons
            WHERE code = ?
            LIMIT 1
        ");
        $stmt->bind_param("s", $coupon_code);
        $stmt->execute();
        $coupon = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($coupon && (int)$coupon['is_active'] === 1) {

            if (empty($coupon['expires_at']) || strtotime($coupon['expires_at']) >= time()) {

                if ($subtotal >= (float)$coupon['min_order']) {

                    if ($coupon['type'] === 'percent') {
                        $discount = $subtotal * ($coupon['value'] / 100);
                    } else {
                        $discount = $coupon['value'];
                    }

                    if (!empty($coupon['max_discount']) &&
                        $discount > $coupon['max_discount']) {
                        $discount = $coupon['max_discount'];
                    }

                    if ($discount > $subtotal) {
                        $discount = $subtotal;
                    }
                }
            }
        }
    }

    $discount = round($discount, 2);

    /* ===============================
       FINAL TOTAL
    =============================== */
    $total = $subtotal - $discount + $shipping;
    if ($total < 0) $total = 0;

    /* ===============================
       GENERATE ORDER NUMBER
    =============================== */
    $orderNumber = 'ORD-' . date('YmdHis') . '-' . rand(100,999);

    /* ===============================
       INSERT ORDER
    =============================== */
    $stmt = $mysqli->prepare("
        INSERT INTO orders (
            user_id,
            order_number,
            subtotal,
            shipping,
            discount,
            total,
            full_name,
            phone,
            address,
            state_id,
            township_id,
            coupon_code,
            status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'processing')
    ");

    $stmt->bind_param(
        "isdddsssiiss",
        $userId,
        $orderNumber,
        $subtotal,
        $shipping,
        $discount,
        $total,
        $full_name,
        $phone,
        $address,
        $state_id,
        $township_id,
        $coupon_code
    );

    $stmt->execute();
    $orderId = $stmt->insert_id;
    $stmt->close();

    /* ===============================
       INSERT ORDER ITEMS + STOCK DEDUCTION
    =============================== */
    $stmt = $mysqli->prepare("
        INSERT INTO order_items (
            order_id,
            product_id,
            variant_id,
            product_name,
            variant_name,
            sku,
            image_path,
            quantity,
            unit_price,
            line_total
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    foreach ($items as $item) {

        $stmt->bind_param(
            "iiissssidd",
            $orderId,
            $item['product_id'],
            $item['variant_id'],
            $item['product_name'],
            $item['variant_name'],
            $item['sku'],
            $item['image_path'],
            $item['quantity'],
            $item['unit_price'],
            $item['line_total']
        );

        $stmt->execute();

        /* STOCK DEDUCTION (VARIANT ONLY) */
        $stockStmt = $mysqli->prepare("
            UPDATE product_variants
            SET stock = stock - ?
            WHERE id = ?
        ");
        $stockStmt->bind_param(
            "ii",
            $item['quantity'],
            $item['variant_id']
        );
        $stockStmt->execute();
        $stockStmt->close();
    }

    $stmt->close();

    /* ===============================
       CLEAR CART
    =============================== */
    $mysqli->query("DELETE FROM cart_items WHERE cart_id = $cartId");

    unset($_SESSION['applied_coupon']);

    $mysqli->commit();

    header("Location: order_success.php?id=" . $orderId);
    exit;

} catch (Exception $e) {

    $mysqli->rollback();
    die("Order failed: " . $e->getMessage());
}