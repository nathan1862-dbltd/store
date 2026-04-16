<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once __DIR__ . '/adminconfig.php';
require_once __DIR__ . '/auth.php';

$orderId = (int)($_GET['id'] ?? 0);
if (!$orderId) die("Invalid order");

/* Fetch order */
$stmt = $mysqli->prepare("
    SELECT *
    FROM orders
    WHERE id = ?
    LIMIT 1
");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) die("Order not found");

$subtotal = (float)$order['subtotal'];
$discount = (float)$order['discount'];
$shipping = (float)$order['shipping'];
$total    = (float)$order['total'];

/* ===============================
   HANDLE POST
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ---------- Update Order Status ---------- */
    if (isset($_POST['status'])) {

        $allowed = ['pending','processing','shipped','delivered','cancelled'];
        $newStatus = $_POST['status'];

        if (!in_array($newStatus, $allowed, true)) {
            die("Invalid status");
        }

        $oldStatus = $order['status'];

        $stmt = $mysqli->prepare("
            UPDATE orders
            SET status = ?
            WHERE id = ?
        ");
        $stmt->bind_param("si", $newStatus, $orderId);
        $stmt->execute();
        $stmt->close();

        /* Add loyalty when delivered */
        if ($oldStatus !== 'delivered' && $newStatus === 'delivered') {

            $points = floor($total / 1000);

            if ($points > 0) {

                $check = $mysqli->prepare("
                    SELECT id FROM user_points
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

                    $stmt = $mysqli->prepare("
                        INSERT INTO user_points
                        (user_id, type, points, status, order_id, description, expires_at)
                        VALUES (?, 'earn', ?, 'approved', ?, ?, DATE_ADD(NOW(), INTERVAL 1 YEAR))
                    ");
                    $stmt->bind_param("iiis", $order['user_id'], $points, $orderId, $desc);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }

        header("Location: order_view.php?id=" . $orderId);
        exit;
    }

    /* ---------- Update Payment Status ---------- */
    if (isset($_POST['payment_status'])) {

        $allowedPayment = ['unpaid','paid','failed','refunded'];
        $newPaymentStatus = $_POST['payment_status'];

        if (!in_array($newPaymentStatus, $allowedPayment, true)) {
            die("Invalid payment status");
        }

        $stmt = $mysqli->prepare("
            UPDATE orders
            SET payment_status = ?
            WHERE id = ?
        ");
        $stmt->bind_param("si", $newPaymentStatus, $orderId);
        $stmt->execute();
        $stmt->close();

        header("Location: order_view.php?id=" . $orderId);
        exit;
    }
}

/* Fetch items */
$stmt = $mysqli->prepare("
    SELECT product_name, variant_name, quantity, unit_price
    FROM order_items
    WHERE order_id = ?
");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$items = $stmt->get_result();
$stmt->close();
?>


<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
*{box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif}
body{background:#f3f4f6;margin:0;padding:20px}
.card{background:#fff;padding:20px;border-radius:12px;box-shadow:0 5px 20px rgba(0,0,0,0.05);margin-bottom:20px}
.row{display:flex;flex-wrap:wrap;justify-content:space-between;gap:15px}
.muted{color:#6b7280;font-size:13px}
.badge{padding:6px 12px;border-radius:999px;font-size:12px;font-weight:500;display:inline-block}
.pending{background:#fef3c7}
.processing{background:#bfdbfe}
.shipped{background:#c7d2fe}
.delivered{background:#bbf7d0}
.cancelled{background:#fecaca}
.payment-paid{color:#16a34a;font-weight:600}
.payment-unpaid{color:#dc2626;font-weight:600}
select,button{
padding:10px;
border-radius:8px;
border:1px solid #ddd;
font-size:14px;
}
button{
background:#111827;
color:#fff;
cursor:pointer;
border:none;
}
table{
width:100%;
border-collapse:collapse;
margin-top:15px;
}
th,td{
padding:10px;
border-bottom:1px solid #eee;
font-size:14px;
text-align:left;
}
th{background:#f9fafb}
.total-box{
text-align:right;
margin-top:20px;
}
.total-box p{margin:5px 0}
@media(max-width:768px){
table,thead,tbody,tr,td,th{display:block}
thead{display:none}
tr{margin-bottom:15px;background:#fff;padding:10px;border-radius:8px}
td{border:none;padding:6px 0}
td:before{
content:attr(data-label);
font-weight:600;
display:block;
color:#6b7280;
font-size:12px;
}
}
</style>
</head>
<body>

<div class="card">
<div class="row">
<div>
<h2>Order #<?= $order['id'] ?></h2>
<div class="muted">
<?= date("d M Y H:i", strtotime($order['created_at'])) ?>
</div>
</div>

<div style="text-align:right">
<span class="badge <?= htmlspecialchars($order['status']) ?>">
<?= ucfirst($order['status']) ?>
</span>
<br><br>
<span class="<?= $order['payment_status']=='paid'?'payment-paid':'payment-unpaid' ?>">
<?= ucfirst($order['payment_status']) ?>
</span>
</div>
</div>
</div>
<div class="card" style="margin-top:15px;">
  <h3>Payment Status</h3>

  <form method="post">
    <select name="payment_status" required>
      <option value="unpaid" <?= $order['payment_status']=='unpaid'?'selected':'' ?>>Unpaid</option>
      <option value="paid" <?= $order['payment_status']=='paid'?'selected':'' ?>>Paid</option>
      <option value="failed" <?= $order['payment_status']=='failed'?'selected':'' ?>>Failed</option>
      <option value="refunded" <?= $order['payment_status']=='refunded'?'selected':'' ?>>Refunded</option>
    </select>

    <button type="submit" class="btn">Update</button>
  </form>
</div>

<div class="card">
<h3>Customer Information</h3>
<p><strong>Name:</strong> <?= htmlspecialchars($order['full_name'] ?? 'N/A') ?></p>
<p><strong>Phone:</strong> <?= htmlspecialchars($order['phone'] ?? 'N/A') ?></p>
<p><strong>Address:</strong><br>
<?= nl2br(htmlspecialchars($order['address'] ?? 'N/A')) ?>
</p>
<p><strong>Payment Method:</strong> <?= ucfirst($order['payment_method']) ?></p>
</div>

<div class="card">
<h3>Update Order Status</h3>
<form method="post">
<select name="status">
<?php foreach(['pending','processing','shipped','delivered','cancelled'] as $s): ?>
<option value="<?= $s ?>" <?= ($order['status']===$s?'selected':'') ?>>
<?= ucfirst($s) ?>
</option>
<?php endforeach; ?>
</select>
<button type="submit">Update</button>
</form>
</div>

<div class="card">
<h3>Order Items</h3>

<table>
<thead>
<tr>
<th>Product</th>
<th>Variant</th>
<th>Qty</th>
<th>Unit Price</th>
<th>Total</th>
</tr>
</thead>
<tbody>

<?php while($i = $items->fetch_assoc()):
$line = $i['quantity'] * $i['unit_price'];
?>
<tr>
<td data-label="Product"><?= htmlspecialchars($i['product_name']) ?></td>
<td data-label="Variant"><?= htmlspecialchars($i['variant_name']) ?></td>
<td data-label="Qty"><?= $i['quantity'] ?></td>
<td data-label="Unit Price"><?= number_format($i['unit_price'],2) ?></td>
<td data-label="Total"><?= number_format($line,2) ?></td>
</tr>
<?php endwhile; ?>

</tbody>
</table>

<div class="total-box">
<p><strong>Subtotal:</strong> <?= number_format($subtotal,2) ?></p>
<p><strong>Discount:</strong> -<?= number_format($discount,2) ?></p>
<p><strong>Shipping:</strong> <?= number_format($shipping,2) ?></p>
<p style="font-size:18px;font-weight:bold">
Grand Total: <?= number_format($total,2) ?>
</p>
</div>

</div>

</body>
</html>