<?php
require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/admin_header.php';
require_once __DIR__ . '/sales_helpers.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header("Location: sales.php"); exit; }

$stmt = $mysqli->prepare("SELECT * FROM sales WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$sale = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$sale) { die('Sale not found'); }

$live = sale_is_live($sale);

$prod = $mysqli->query("
  SELECT p.id, p.name
  FROM sale_items si
  JOIN products p ON p.id = si.product_id
  WHERE si.sale_id = $id AND si.product_id IS NOT NULL
  ORDER BY p.id DESC
");

$var = $mysqli->query("
  SELECT v.id, v.variant_name, v.price, p.name AS product_name
  FROM sale_items si
  JOIN product_variants v ON v.id = si.variant_id
  JOIN products p ON p.id = v.product_id
  WHERE si.sale_id = $id AND si.variant_id IS NOT NULL
  ORDER BY v.id DESC
");
?>
<div class="topbar">
  <div class="title">Sale #<?= (int)$sale['id'] ?> — <?= htmlspecialchars($sale['name']) ?></div>
  <a class="btn-link" href="sales.php">← Back</a>
</div>

<div class="card">
  <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center">
    <?php if ($live): ?>
      <span class="badge" style="background:#16a34a;color:#fff">LIVE</span>
    <?php elseif ((int)$sale['is_active']===0): ?>
      <span class="badge" style="background:#6b7280;color:#fff">DISABLED</span>
    <?php else: ?>
      <span class="badge" style="background:#f59e0b;color:#111">SCHEDULED/ENDED</span>
    <?php endif; ?>

    <div class="small">Schedule: <?= htmlspecialchars($sale['starts_at']) ?> → <?= htmlspecialchars($sale['ends_at']) ?></div>
    <div class="small">Discount:
      <?php if ($sale['discount_type']==='percent'): ?>
        <?= htmlspecialchars($sale['discount_value']) ?>%
      <?php else: ?>
        Fixed final price: <?= number_format((float)$sale['discount_value'], 0) ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="card">
  <h3>Products in this Sale</h3>
  <table class="table">
    <tr><th>ID</th><th>Name</th></tr>
    <?php while($p=$prod->fetch_assoc()): ?>
      <tr><td><?= (int)$p['id'] ?></td><td><?= htmlspecialchars($p['name']) ?></td></tr>
    <?php endwhile; ?>
  </table>
</div>

<div class="card">
  <h3>Variants in this Sale</h3>
  <table class="table">
    <tr><th>ID</th><th>Product</th><th>Variant</th><th>Base Price</th><th>Sale Price Preview</th></tr>
    <?php while($v=$var->fetch_assoc()):
      $salePrice = sale_calc_discount_price((float)$v['price'], $sale['discount_type'], (float)$sale['discount_value']);
    ?>
      <tr>
        <td><?= (int)$v['id'] ?></td>
        <td><?= htmlspecialchars($v['product_name']) ?></td>
        <td><?= htmlspecialchars($v['variant_name']) ?></td>
        <td><?= number_format((float)$v['price'], 0) ?></td>
        <td><strong><?= number_format((float)$salePrice, 0) ?></strong></td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
