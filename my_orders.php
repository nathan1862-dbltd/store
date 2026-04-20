<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/functions.php';

ensureSessionStarted();
redirectIfNotLoggedIn();
require_once __DIR__ . '/header.php';

$userId = (int)$_SESSION['user_id'];

/* ================= FETCH ORDERS ================= */
$stmt = $mysqli->prepare("
    SELECT 
        id,
        order_number,
        subtotal,
        discount,
        shipping,
        total,
        status,
        payment_method,
        payment_status,
        created_at
    FROM orders
    WHERE user_id = ?
    ORDER BY created_at DESC
");

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<style>
.orders-container {
    max-width: 1100px;
    margin: 40px auto;
    padding: 0 15px;
}

.order-card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    border:solid 2px #000;
   
}

.order-header {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    margin-bottom: 12px;
}

.order-number {
    font-weight: 600;
    font-size: 16px;
}

.status {
    padding: 5px 12px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
}

.status.received { background:#fff3cd; color:#856404; }
.status.processing { background:#d1ecf1; color:#0c5460; }
.status.shipped { background:#cce5ff; color:#004085; }
.status.delivered { background:#d4edda; color:#155724; }
.status.cancelled { background:#f8d7da; color:#721c24; }

.order-body {
    font-size: 14px;
    line-height: 1.6;
}

.order-total {
    font-size: 18px;
    font-weight: 700;
    margin-top: 10px;
    color: #CC2230;
}

@media(max-width:768px){
    .order-header { flex-direction: column; gap: 8px; }
}
</style>

<div class="orders-container">
    <h2 style="margin-bottom:25px;">My Orders</h2>

<?php if ($result->num_rows === 0): ?>
    <p>You haven't placed any orders yet.</p>
<?php else: ?>
    <?php while($order = $result->fetch_assoc()): ?>
        
        <div class="order-card">
            <div class="order-header">
                <div class="order-number">
                    Order #<?= htmlspecialchars($order['order_number']) ?>
                </div>

                <div class="status <?= htmlspecialchars($order['status']) ?>">
                    <?= ucfirst($order['status']) ?>
                </div>
            </div>

            <div class="order-body">
                Date: <?= date("d M Y", strtotime($order['created_at'])) ?><br>
                Payment: <?= htmlspecialchars($order['payment_method']) ?> 
                (<?= htmlspecialchars($order['payment_status']) ?>)<br>
                Subtotal: <?= number_format($order['subtotal'], 2) ?><br>
                Discount: <?= number_format($order['discount'], 2) ?><br>
                Shipping: <?= number_format($order['shipping'], 2) ?>
            </div>

            <div class="order-total">
                Total: <?= number_format($order['total'], 2) ?>
            </div>

            <div style="margin-top:10px;">
                <a href="order_detail.php?id=<?= $order['id'] ?>" 
                   style="color:#CC2230; font-weight:600;">
                   View Details →
                </a>
            </div>
        </div>

    <?php endwhile; ?>
<?php endif; ?>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
