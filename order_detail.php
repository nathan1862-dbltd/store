<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/header.php';

ensureSessionStarted();
redirectIfNotLoggedIn();

$userId  = (int)($_SESSION['user_id'] ?? 0);
$orderId = (int)($_GET['id'] ?? 0);

if ($userId <= 0 || $orderId <= 0) {
    echo "<div class='od-container'><div class='od-card'>Invalid request.</div></div>";
    require_once __DIR__ . '/footer.php';
    exit;
}

/* ================= LOAD ORDER (SECURE) ================= */
$stmt = $mysqli->prepare("
    SELECT id, order_number, subtotal, discount, shipping, total,
           status, address, payment_method, payment_status,
           tracking_number, created_at
    FROM orders
    WHERE id = ? AND user_id = ?
    LIMIT 1
");
$stmt->bind_param("ii", $orderId, $userId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    echo "<div class='od-container'><div class='od-card'>Order not found.</div></div>";
    require_once __DIR__ . '/footer.php';
    exit;
}

/* ================= LOAD ITEMS ================= */

$itemStmt = $mysqli->prepare("
    SELECT 
        oi.product_name,
        oi.product_id,
        oi.variant_id,
        oi.quantity,
        oi.unit_price,
        oi.line_total,
        p.image_path
    FROM order_items oi
    LEFT JOIN products p ON p.id = oi.product_id
    WHERE oi.order_id = ?
");
$itemStmt->bind_param("i", $orderId);
$itemStmt->execute();
$items = $itemStmt->get_result();

function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

$status = $order['status'] ?? 'received';
$steps  = ['received','processing','shipped','delivered'];
$current = array_search($status, $steps);
if ($current === true) $current = 0;

?>
<style>

/* ====== ORDER DETAIL PAGE ====== */

.od-section {
  padding: 40px 0;
  background: #f9fafb;
}

.od-container {
  max-width: 1200px;
  margin: auto;
  padding: 0 20px;
}

.od-title {
  font-size: 24px;
  font-weight: 600;
  margin-bottom: 25px;
  color: #111827;
}

/* ====== LAYOUT ====== */

.od-grid {
  display: flex;
  gap: 30px;
  flex-wrap: wrap;
}

.od-left {
  flex: 1 1 65%;
}

.od-right {
  flex: 1 1 30%;
}

/* ====== CARD ====== */

.od-card {
  background: #ffffff;
  border-radius: 12px;
  border: 1px solid #e5e7eb;
  overflow: hidden;
  margin-bottom: 20px;
}

.od-card-body {
  padding: 20px;
}

.od-divider {
  border-bottom: 1px solid #e5e7eb;
}

/* ====== PRODUCT ITEM ====== */

.od-product {
  display: flex;
  gap: 15px;
  align-items: center;
}

.od-product img {
  width: 60px;
  height: 60px;
  object-fit: contain;
}

.od-variant-meta {
  font-size: 12px;
  color: #6b7280;
  margin-top: 4px;
}

.od-product-title {
  font-weight: 500;
  color: #111827;
  text-decoration: none;
}

.od-product-meta {
  font-size: 13px;
  color: #6b7280;
  margin-top: 4px;
}

.od-row-between {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.od-qty {
  font-size: 14px;
}

.od-price {
  font-size: 18px;
  font-weight: 600;
}

/* ====== SUMMARY ====== */

.od-summary {
  background: #f3f4f6;
  padding: 20px;
}

.od-summary dl {
  display: flex;
  justify-content: space-between;
  margin-bottom: 8px;
}

.od-summary dt {
  color: #6b7280;
}

.od-summary dd {
  font-weight: 500;
}

.od-total {
  border-top: 1px solid #e5e7eb;
  padding-top: 10px;
  font-size: 18px;
  font-weight: 700;
}

/* ====== HISTORY ====== */

.od-history {
  position: relative;
  padding-left: 25px;
}

.od-history::before {
  content: "";
  position: absolute;
  left: 8px;
  top: 0;
  bottom: 0;
  width: 2px;
  background: #e5e7eb;
}

.od-history-item {
  position: relative;
  margin-bottom: 25px;
}

.od-history-item::before {
  content: "";
  position: absolute;
  left: -5px;
  right: 3px;
  top: 5px;
  width: 12px;
  height: 12px;
  background: #3b82f6;
  border-radius: 50%;
}

.od-history-title {
  font-weight: 600;
  font-size: 14px;
  left: 5px;
}

.od-history-text {
  font-size: 13px;
  color: #6b7280;
}

/* ====== BUTTON ====== */

.od-btn {
  display: inline-block;
  padding: 10px 16px;
  border-radius: 6px;
  font-size: 14px;
  text-align: center;
  cursor: pointer;
  text-decoration: none;
}

.od-btn-secondary {
  border: 1px solid #d1d5db;
  background: #ffffff;
  color: #111827;
}

.od-btn-primary {
  background: #2563eb;
  color: #ffffff;
  border: none;
}

/* ====== RESPONSIVE ====== */

@media (max-width: 900px) {
  .od-grid {
    flex-direction: column;
  }
}

</style>

<section class="od-section">
<div class="od-container">

<h2 class="od-title">
Track the delivery of order #<?= h($order['order_number']) ?>
</h2>

<div class="od-grid">

<!-- LEFT SIDE -->
<div class="od-left od-card">

<?php while($item = $items->fetch_assoc()): ?>
<div class="od-card-body od-divider">

<div class="od-product">

<div class="od-item-img">
    <?php if(!empty($item['image_path'])): ?>
        <img src="<?= h($item['image_path']) ?>" 
             alt="<?= h($item['product_name']) ?>" 
             class="od-img">
    <?php else: ?>
        <img src="assets/images/no-image.png" 
             alt="No image" 
             class="od-img">
    <?php endif; ?>
</div>

<div>
    <div class="od-item-name">
        <?= h($item['product_name']) ?>
    </div>

    <div class="od-variant-meta">
        Product ID: <?= (int)$item['product_id'] ?>
        <?php if (!empty($item['variant_id'])): ?>
            | Variant ID: <?= (int)$item['variant_id'] ?>
        <?php endif; ?>
    </div> <div class="od-row-between" style="margin-top:15px;">
<div class="od-qty">x<?= (int)$item['quantity'] ?></div>
</div>

</div>
</div>

<div class="od-row-between" style="margin-top:15px;"> <div class="od-price">$<?= number_format($item['line_total'],2) ?></div>
</div>

</div>
<?php endwhile; ?>

<div class="od-summary">
<dl>
<dt>Subtotal</dt>
<dd>$<?= number_format($order['subtotal'],2) ?></dd>
</dl>
<dl>
<dt>Discount</dt>
<dd>-$<?= number_format($order['discount'],2) ?></dd>
</dl>
<dl>
<dt>Shipping</dt>
<dd>$<?= number_format($order['shipping'],2) ?></dd>
</dl>

<div class="od-total">
Total: $<?= number_format($order['total'],2) ?>
</div>
</div>

</div>

<!-- RIGHT SIDE -->
<div class="od-right">

<div class="od-card">
<div class="od-card-body">

<h3 style="margin-bottom:20px;">Order History</h3>

<div class="od-history">

<div class="od-history-item">
<div class="od-history-title"><?= date("d M Y, h:i A", strtotime($order['created_at'])) ?></div>
<div class="od-history-text">Order placed</div>
</div>

<div class="od-history-item">
<div class="od-history-title">Payment: <?= h($order['payment_method']) ?></div>
<div class="od-history-text">Status: <?= h($order['payment_status']) ?></div>
</div>

<div class="od-history-item">
<div class="od-history-title">Order Status</div>
<div class="od-history-text"><?= ucfirst(h($order['status'])) ?></div>
</div>

</div>

<div style="margin-top:20px;">
<a href="my_orders.php" class="od-btn od-btn-secondary">Back to Orders</a>
</div>

</div>
</div>

</div>

</div>
</div>
</section>

<?php require_once __DIR__ . '/footer.php'; ?>