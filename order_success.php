<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/header.php';

ensureSessionStarted();
redirectIfNotLoggedIn();

$userId = (int)$_SESSION['user_id'];
$orderId = (int)($_GET['id'] ?? 0);

if (!$orderId) {
    die("Invalid order.");
}

/* ===============================
   FETCH ORDER (SECURE)
================================ */
$stmt = $mysqli->prepare("
    SELECT *
    FROM orders
    WHERE id = ? AND user_id = ?
    LIMIT 1
");
$stmt->bind_param("ii", $orderId, $userId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    die("Order not found.");
}

/* ===============================
   FETCH ORDER ITEMS
================================ */
$stmt = $mysqli->prepare("
    SELECT *
    FROM order_items
    WHERE order_id = ?
");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$items = $stmt->get_result();
$stmt->close();

/* STATUS COLOR */
$statusClass = [
    'pending'    => 'secondary',
    'processing' => 'warning',
    'shipped'    => 'info',
    'delivered'  => 'success',
    'cancelled'  => 'danger'
][$order['status']] ?? 'secondary';

?>
<style>
.order-success-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px 16px 100px;
    font-family: -apple-system, BlinkMacSystemFont, sans-serif;
}

.success-header {
    text-align: center;
    margin-bottom: 30px;
}

.success-icon {
    width: 70px;
    height: 70px;
    background: #4CAF50;
    color: white;
    border-radius: 50%;
    font-size: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
}

.success-header h2 {
    font-size: 22px;
    margin-bottom: 6px;
}

.success-header p {
    color: #777;
    font-size: 14px;
}

/* ORDER CARD */
.order-card,
.items-card {
    background: #fff;
    border-radius: 12px;
    padding: 18px;
    margin-bottom: 20px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.05);
}

.order-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.label {
    font-size: 12px;
    color: #888;
}

.value {
    font-weight: 600;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 12px;
    color: #fff;
}

.status-processing { background: #f0ad4e; }
.status-delivered { background: #4CAF50; }
.status-shipped { background: #5bc0de; }
.status-cancelled { background: #d9534f; }
.status-pending { background: #777; }

.shipping-block h4 {
    margin-bottom: 10px;
    font-size: 15px;
}

.price-block .row-line {
    display: flex;
    justify-content: space-between;
    margin: 8px 0;
    font-size: 14px;
}

.price-block .discount {
    color: #28a745;
}

.price-block .total {
    font-weight: bold;
    font-size: 16px;
    margin-top: 10px;
}

/* ITEMS */
.item-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #eee;
}

.item-row img {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 10px;
}

.item-info {
    flex: 1;
}

.item-name {
    font-weight: 600;
    font-size: 14px;
}

.item-variant,
.item-qty {
    font-size: 12px;
    color: #777;
}

.item-price {
    font-weight: 600;
    font-size: 14px;
}

/* BUTTONS */
.action-buttons {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: #fff;
    padding: 12px 16px;
    display: flex;
    gap: 10px;
    box-shadow: 0 -3px 10px rgba(0,0,0,0.05);
}

.btn-primary {
    flex: 1;
    background: #CC2230;
    color: #fff;
    padding: 12px;
    text-align: center;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
}

.btn-outline {
    flex: 1;
    border: 1px solid #000;
    padding: 12px;
    text-align: center;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    color: #000;
}

/* DESKTOP IMPROVEMENT */
@media (min-width: 768px) {
    .action-buttons {
        position: static;
        box-shadow: none;
        margin-top: 20px;
    }
}
</style>
<div class="order-success-container
">

    <div class="success-header">
        <div class="success-icon">✓</div>
        <h2>Order Placed Successfully</h2>
        <p>Thank you for shopping with us.</p>
    </div>

    <div class="order-card">

        <div class="order-top">
            <div>
                <div class="label">Order Number</div>
                <div class="value"><?= htmlspecialchars($order['order_number']) ?></div>
            </div>

            <div class="status-badge status-<?= $order['status'] ?>">
                <?= ucfirst($order['status']) ?>
            </div>
        </div>

        <div class="shipping-block">
            <h4>Shipping Info</h4>
            <p><strong><?= htmlspecialchars($order['full_name']) ?></strong></p>
            <p><?= htmlspecialchars($order['phone']) ?></p>
            <p><?= nl2br(htmlspecialchars($order['address'])) ?></p>
        </div>

        <div class="price-block">
            <div class="row-line">
                <span>Subtotal</span>
                <span><?= number_format($order['subtotal'], 0) ?></span>
            </div>

            <div class="row-line">
                <span>Shipping</span>
                <span><?= number_format($order['shipping'], 0) ?></span>
            </div>

            <?php if ($order['discount'] > 0): ?>
            <div class="row-line discount">
                <span>Discount</span>
                <span>-<?= number_format($order['discount'], 0) ?></span>
            </div>
            <?php endif; ?>

            <div class="row-line total">
                <span>Total</span>
                <span><?= number_format($order['total'], 0) ?></span>
            </div>
        </div>

    </div>

    <div class="items-card">
        <h3>Order Items</h3>

        <?php while ($item = $items->fetch_assoc()): ?>
        <div class="item-row">

            <img src="/V3/<?= htmlspecialchars($item['image_path']) ?>" alt="">

            <div class="item-info">
                <div class="item-name">
                    <?= htmlspecialchars($item['product_name']) ?>
                </div>

                <?php if (!empty($item['variant_name'])): ?>
                    <div class="item-variant">
                        <?= htmlspecialchars($item['variant_name']) ?>
                    </div>
                <?php endif; ?>

                <div class="item-qty">
                    Qty: <?= $item['quantity'] ?>
                </div>
            </div>

            <div class="item-price">
                <?= number_format($item['line_total'], 0) ?>
            </div>

        </div>
        <?php endwhile; ?>
    </div>

    <div class="action-buttons">
        <a href="orders.php" class="btn-outline">View Orders</a>
        <a href="index.php" class="btn-primary">Continue Shopping</a>
    </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>