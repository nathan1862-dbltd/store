<?php
require_once __DIR__ . '/_common.php';

$product_id = (int)($_GET['product_id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $product_id = (int)($_POST['product_id'] ?? 0);
  $variant_name = trim($_POST['variant_name'] ?? '');
  $variant_value = trim($_POST['variant_value'] ?? '');
  $price = (($_POST['price'] ?? '') === '') ? null : (float)($_POST['price'] ?? 0);
  $stock = (int)($_POST['stock'] ?? 0);
  $sku = trim($_POST['sku'] ?? '');

  if ($product_id>0 && $variant_name!=='' && $variant_value!=='') {
    $stmt = $mysqli->prepare("
      INSERT INTO product_variants (product_id, variant_name, variant_value, price, stock, sku)
      VALUES (?,?,?,?,?,?)
    ");
    $stmt->bind_param("issdis", $product_id, $variant_name, $variant_value, $price, $stock, $sku);
    $stmt->execute(); $stmt->close();

    $mysqli->query("UPDATE products SET has_variants=1 WHERE id=".(int)$product_id);
    apm_flash_set('ok','Variant added.');
  } else {
    apm_flash_set('err','Select product and fill variant name/value.');
  }
  apm_redirect('variants.php?product_id='.$product_id);
}

$products = $mysqli->query("SELECT id,name FROM products ORDER BY created_at DESC LIMIT 500");

$vars = null;
$pname = null;
if ($product_id>0) {
  $stmt = $mysqli->prepare("SELECT name FROM products WHERE id=?");
  $stmt->bind_param("i",$product_id);
  $stmt->execute();
  $pname = $stmt->get_result()->fetch_row()[0] ?? null;
  $stmt->close();

  $stmt = $mysqli->prepare("SELECT * FROM product_variants WHERE product_id=? ORDER BY created_at DESC");
  $stmt->bind_param("i",$product_id);
  $stmt->execute();
  $vars = $stmt->get_result();
  $stmt->close();
}

require_once __DIR__ . '/_layout_top.php';
?>
<div class="card" style="padding:14px">
  <div style="font-weight:800;font-size:16px">Variants <?= $pname ? '— '.htmlspecialchars($pname) : '' ?></div>
  <hr class="hr">

  <form method="get" style="display:flex;gap:10px;flex-wrap:wrap;align-items:end">
    <div style="flex:1;min-width:240px">
      <label>Select Product</label>
      <select name="product_id" onchange="this.form.submit()">
        <option value="0">— Choose —</option>
        <?php while($p=$products->fetch_assoc()): ?>
          <option value="<?= (int)$p['id'] ?>" <?= ((int)$product_id===(int)$p['id'])?'selected':'' ?>>
            #<?= (int)$p['id'] ?> — <?= htmlspecialchars($p['name']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>
    <noscript><button class="btn" type="submit">Go</button></noscript>
  </form>

  <div style="height:12px"></div>

  <form method="post" class="grid2">
    <input type="hidden" name="product_id" value="<?= (int)$product_id ?>">
    <div>
      <label>Variant Name</label>
      <input name="variant_name" placeholder="e.g. Size, Color" required>
    </div>
    <div>
      <label>Variant Value</label>
      <input name="variant_value" placeholder="e.g. 50ml, Red" required>
    </div>
    <div>
      <label>Price Override (optional)</label>
      <input type="number" step="0.01" name="price" placeholder="Leave blank to use base price">
    </div>
    <div>
      <label>Stock</label>
      <input type="number" name="stock" value="0">
    </div>
    <div style="grid-column:1/-1">
      <label>SKU (optional)</label>
      <input name="sku" placeholder="Variant SKU">
      <div class="help">This variant ID maps to order_items.variant_id.</div>
    </div>
    <div style="grid-column:1/-1;display:flex;gap:10px;flex-wrap:wrap">
      <button class="btn primary" type="submit" <?= ($product_id<=0)?'disabled':'' ?>>Add Variant</button>
    </div>
  </form>
</div>

<div style="height:12px"></div>

<div class="card">
  <table class="table">
    <thead><tr><th>ID</th><th>Name</th><th>Value</th><th>Price</th><th>Stock</th><th>SKU</th><th>Action</th></tr></thead>
    <tbody>
      <?php if ($vars): while($v=$vars->fetch_assoc()): ?>
        <tr>
          <td><?= (int)$v['id'] ?></td>
          <td><?= htmlspecialchars($v['variant_name'] ?? '-') ?></td>
          <td><?= htmlspecialchars($v['variant_value'] ?? '-') ?></td>
          <td><?= ($v['price']===null) ? "<span class='badge'>Base</span>" : number_format((float)$v['price'],2) ?></td>
          <td><?= (int)$v['stock'] ?></td>
          <td><?= htmlspecialchars($v['sku'] ?? '-') ?></td>
          <td class="row-actions">
            <a class="danger" href="variant-delete.php?id=<?= (int)$v['id'] ?>&product_id=<?= (int)$product_id ?>" onclick="return confirm('Delete variant?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; else: ?>
        <tr><td colspan="7" class="help" style="padding:18px">Select a product to view variants.</td></tr>
      <?php endif; ?>

      <?php if ($vars && $vars->num_rows===0): ?>
        <tr><td colspan="7" class="help" style="padding:18px">No variants yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/_layout_bottom.php'; ?>
