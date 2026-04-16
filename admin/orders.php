<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/adminconfig.php';
require_once __DIR__ . '/auth.php';

$q = trim($_GET['q'] ?? '');
$status = trim($_GET['status'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 15;
$offset = ($page - 1) * $limit;
$like = '%' . $q . '%';

/* ================= COUNT ================= */
$countSql = "
SELECT COUNT(*) AS total
FROM orders o
WHERE
 (? = '' OR o.order_number LIKE ? OR o.phone LIKE ?)
AND
 (? = '' OR o.status = ?)
";

$stmt = $mysqli->prepare($countSql);
$stmt->bind_param("sssss", $q, $like, $like, $status, $status);
$stmt->execute();
$total = (int)$stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

$totalPages = max(1, ceil($total / $limit));

/* ================= FETCH ================= */
$listSql = "
SELECT
 o.id,
 o.order_number,
 o.full_name,
 o.phone,
 o.total,
 o.status,
 o.payment_status,
 o.payment_method,
 o.created_at,
 o.tracking_number,
 o.shipped_at,
 o.delivered_at,
 (
   SELECT COUNT(*) FROM order_items oi
   WHERE oi.order_id = o.id
 ) AS item_count
FROM orders o
WHERE
 (? = '' OR o.order_number LIKE ? OR o.phone LIKE ?)
AND
 (? = '' OR o.status = ?)
ORDER BY o.id DESC
LIMIT ? OFFSET ?
";

$stmt = $mysqli->prepare($listSql);
$stmt->bind_param("sssssii", $q, $like, $like, $status, $status, $limit, $offset);
$stmt->execute();
$rows = $stmt->get_result();
$stmt->close();

/* ================= REVENUE ================= */
$rev = $mysqli->query("
SELECT IFNULL(SUM(total),0) AS total
FROM orders
WHERE status != 'cancelled'
")->fetch_assoc()['total'] ?? 0;

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo ADMIN_PANEL_NAME; ?> - Orders</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
*{box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif}
body{margin:0;background:#f3f4f6}

/* TOPBAR */
.topbar{
background:#111827;
color:#fff;
padding:16px 20px;
display:flex;
justify-content:space-between;
align-items:center;
font-weight:600;
}

.topbar a{color:#93c5fd;text-decoration:none;margin-left:10px}

/* WRAPPER */
.wrap{padding:16px;max-width:1100px;margin:auto}

/* KPI CARDS */
.kpi-grid{
display:grid;
grid-template-columns:1fr;
gap:12px;
margin-bottom:20px;
}

.kpi{
background:#fff;
padding:16px;
border-radius:12px;
box-shadow:0 4px 15px rgba(0,0,0,0.05);
}

.kpi h4{margin:0;font-size:13px;color:#6b7280;text-transform:uppercase}
.kpi p{margin:6px 0 0;font-size:22px;font-weight:bold}

/* FILTERS */
.filters{
background:#fff;
padding:14px;
border-radius:12px;
box-shadow:0 4px 15px rgba(0,0,0,0.05);
display:flex;
flex-wrap:wrap;
gap:10px;
margin-bottom:20px;
}

input,select{
padding:10px;
border-radius:8px;
border:1px solid #ddd;
font-size:14px;
}

button{
padding:10px 14px;
border:none;
border-radius:8px;
background:#111827;
color:#fff;
cursor:pointer;
font-weight:500;
}

/* ORDER CARD */
.order-card{
background:#fff;
padding:16px;
border-radius:12px;
margin-bottom:15px;
box-shadow:0 5px 20px rgba(0,0,0,0.05);
}

.row{
display:flex;
justify-content:space-between;
flex-wrap:wrap;
gap:10px;
}

.muted{color:#6b7280;font-size:13px}

/* STATUS BADGES */
.badge{
padding:5px 10px;
border-radius:999px;
font-size:12px;
font-weight:500;
display:inline-block;
}

.pending{background:#fef3c7}
.processing{background:#bfdbfe}
.shipped{background:#c7d2fe}
.delivered{background:#bbf7d0}
.cancelled{background:#fecaca}

.payment-paid{color:#16a34a;font-weight:600}
.payment-unpaid{color:#dc2626;font-weight:600}

/* ACTION BUTTONS */
.actions{
margin-top:10px;
display:flex;
gap:8px;
flex-wrap:wrap;
}

.btn{
padding:8px 12px;
border-radius:8px;
font-size:13px;
text-decoration:none;
background:#111827;
color:#fff;
}

.btn.secondary{background:#374151}

/* PAGINATION */
.pagination{
display:flex;
gap:6px;
flex-wrap:wrap;
margin-top:20px;
}

.page-link{
padding:6px 10px;
border-radius:6px;
background:#e5e7eb;
text-decoration:none;
color:#111;
font-size:13px;
}

.page-link.active{
background:#111827;
color:#fff;
}

@media(min-width:768px){
.kpi-grid{grid-template-columns:1fr 1fr}
}
</style>
</head>
<body>

<div class="topbar">
<div>Orders Management</div>
<div>
<a href="dashboard.php">Dashboard</a>
<a href="logout.php" style="color:#fca5a5">Logout</a>
</div>
</div>

<div class="wrap">

<!-- KPI -->
<div class="kpi-grid">
<div class="kpi">
<h4>Total Orders</h4>
<p><?php echo number_format($total); ?></p>
</div>
<div class="kpi">
<h4>Total Revenue</h4>
<p><?php echo number_format($rev); ?> Ks</p>
</div>
</div>

<!-- FILTER -->
<form class="filters" method="get">
<input type="text" name="q" placeholder="Search order number / phone" value="<?php echo h($q); ?>">
<select name="status">
<option value="">All Status</option>
<option value="pending">Pending</option>
<option value="processing">Processing</option>
<option value="shipped">Shipped</option>
<option value="delivered">Delivered</option>
<option value="cancelled">Cancelled</option>
</select>
<button>Filter</button>
</form>

<?php if($total==0): ?>
<div class="order-card">No orders found.</div>
<?php else: ?>

<?php while($o=$rows->fetch_assoc()): ?>
<div class="order-card">

<div class="row">
<div>
<b><?php echo h($o['order_number'] ?? ('Order #'.$o['id'])); ?></b><br>
<span class="muted">Customer: <?php echo h($o['full_name']); ?></span><br>
<span class="muted">Phone: <?php echo h($o['phone']); ?></span><br>
<span class="muted">Items: <?php echo $o['item_count']; ?></span><br>
<span class="muted">Date: <?php echo $o['created_at']; ?></span>
</div>

<div style="text-align:right">
<span class="badge <?php echo h($o['status']); ?>">
<?php echo ucfirst($o['status']); ?>
</span>
<br><br>
<b><?php echo number_format($o['total']); ?> Ks</b><br>
<span class="<?php echo $o['payment_status']=='paid'?'payment-paid':'payment-unpaid'; ?>">
<?php echo ucfirst($o['payment_status']); ?>
</span>
</div>
</div>

<?php if($o['tracking_number']): ?>
<div class="muted">Tracking: <?php echo h($o['tracking_number']); ?></div>
<?php endif; ?>

<div class="actions">
<a class="btn" href="order_view.php?id=<?php echo $o['id']; ?>">View</a>
<a class="btn secondary" href="order_update.php?id=<?php echo $o['id']; ?>">Update</a>
</div>

</div>
<?php endwhile; ?>

<!-- PAGINATION -->
<div class="pagination">
<?php for($i=1;$i<=$totalPages;$i++): ?>
<a class="page-link <?php echo $i==$page?'active':''; ?>"
href="?q=<?php echo urlencode($q); ?>&status=<?php echo urlencode($status); ?>&page=<?php echo $i; ?>">
<?php echo $i; ?>
</a>
<?php endfor; ?>
</div>

<?php endif; ?>

</div>
</body>
</html>