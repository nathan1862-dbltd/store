<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/auth.php';

$orderId   = (int)($_POST['order_id'] ?? 0);
$newStatus = $_POST['status'] ?? '';

$allowed = ['pending','processing','shipped','delivered','cancelled'];

if (!$orderId || !in_array($newStatus, $allowed, true)) {
    header('Location: orders.php');
    exit;
}

/* Get current order */
$stmt = $mysqli->prepare("
    SELECT user_id, total, status
    FROM orders
    WHERE id = ?
    LIMIT 1
");
$stmt->bind_param('i', $orderId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    header('Location: orders.php');
    exit;
}

$oldStatus = $order['status'];
$userId    = (int)$order['user_id'];
$total     = (float)$order['total'];

$mysqli->begin_transaction();

try {

    /* Update order status */
    $stmt = $mysqli->prepare("
        UPDATE orders
        SET status = ?
        WHERE id = ?
    ");
    $stmt->bind_param('si', $newStatus, $orderId);
    $stmt->execute();
    $stmt->close();

    /* ==========================
       RESTORE STOCK IF CANCELLED
    =========================== */
    if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {

        $items = $mysqli->prepare("
            SELECT variant_id, quantity
            FROM order_items
            WHERE order_id = ?
        ");
        $items->bind_param('i', $orderId);
        $items->execute();
        $res = $items->get_result();

        while ($row = $res->fetch_assoc()) {

            if (!empty($row['variant_id'])) {

                $restore = $mysqli->prepare("
                    UPDATE product_variants
                    SET stock = stock + ?
                    WHERE id = ?
                ");
                $restore->bind_param('ii', $row['quantity'], $row['variant_id']);
                $restore->execute();
                $restore->close();
            }
        }

        $items->close();
    }

    /* ==========================
       ADD LOYALTY WHEN DELIVERED
    =========================== */
    if ($oldStatus !== 'delivered' && $newStatus === 'delivered') {

        $points = floor($total / 1000); // 1000 Ks = 1 point

        if ($points > 0) {

            /* Prevent duplicate earn */
            $check = $mysqli->prepare("
                SELECT id
                FROM user_points
                WHERE order_id = ?
                AND type = 'earn'
                LIMIT 1
            ");
            $check->bind_param("i", $orderId);
            $check->execute();
            $exists = $check->get_result()->fetch_assoc();
            $check->close();

            if (!$exists) {

                $desc = "Points from Order #" . $orderId;

                $insert = $mysqli->prepare("
                    INSERT INTO user_points
                    (user_id, type, points, status, order_id, description, expires_at)
                    VALUES (?, 'earn', ?, 'approved', ?, ?, DATE_ADD(NOW(), INTERVAL 1 YEAR))
                ");
                $insert->bind_param("iiis", $userId, $points, $orderId, $desc);
                $insert->execute();
                $insert->close();
            }
        }
    }

    $mysqli->commit();

} catch (Exception $e) {

    $mysqli->rollback();
}

header('Location: order_view.php?id=' . $orderId);
exit;