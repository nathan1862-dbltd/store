<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/admin_header.php';

$variantsByProduct = [];
$vres = $mysqli->query("SELECT id, product_id, variant_name, price FROM product_variants ORDER BY product_id DESC, price ASC");
while($v = $vres->fetch_assoc()){
  $variantsByProduct[(int)$v['product_id']][] = $v;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $discountType = $_POST['discount_type'] ?? 'percent';
  $discountValue = (float)($_POST['discount_value'] ?? 0);
  $startsAt = $_POST['starts_at'] ?? '';
  $endsAt = $_POST['ends_at'] ?? '';
  $isActive = isset($_POST['is_active']) ? 1 : 0;

  if ($name === '' || $discountValue <= 0 || $startsAt === '' || $endsAt === '') {
    die('Missing required fields');
  }

  $stmt = $mysqli->prepare("
    INSERT INTO sales (name, discount_type, discount_value, starts_at, ends_at, is_active)
    VALUES (?,?,?,?,?,?)
  ");
  $stmt->bind_param("ssdssi", $name, $discountType, $discountValue, $startsAt, $endsAt, $isActive);
  if (!$stmt->execute()) die('SQL ERROR: '.$stmt->error);
  $saleId = $stmt->insert_id;
  $stmt->close();

  $si = $mysqli->prepare("INSERT INTO sale_items (sale_id, product_id, variant_id) VALUES (?,?,?)");

  if (!empty($_POST['products']) && is_array($_POST['products'])) {
    foreach($_POST['products'] as $pid){
      $pid = (int)$pid;
      $null = null;
      $si->bind_param("iii", $saleId, $pid, $null);
      $si->execute();
    }
  }

  if (!empty($_POST['variants']) && is_array($_POST['variants'])) {
    foreach($_POST['variants'] as $vid){
      $vid = (int)$vid;
      $null = null;
      $si->bind_param("iii", $saleId, $null, $vid);
      $si->execute();
    }
  }

  $si->close();
  header("Location: sales.php");
  exit;
}
?>

<div class="topbar">
  <div class="title">Create Sale</div>
  <a class="btn-link" href="sales.php">← Back</a>
</div>

<div class="card">
  <form method="post">
    <div class="form-row">
      <input name="name" placeholder="Sale name (e.g. Weekend Sale)" required style="flex:1">
      <label style="display:flex;align-items:center;gap:8px">
        <input type="checkbox" name="is_active" checked> Active
      </label>
    </div>

    <div class="form-row" style="margin-top:12px">
      <select name="discount_type" required>
        <option value="percent">Percent (%)</option>
        <option value="fixed">Fixed final price</option>
      </select>
      <input type="number" step="0.01" name="discount_value" placeholder="Discount value" required>
    </div>

    <div class="form-row" style="margin-top:12px">
      <div style="flex:1">
        <div class="small">Starts at</div>
        <input type="datetime-local" name="starts_at" required style="width:100%">
      </div>
      <div style="flex:1">
        <div class="small">Ends at</div>
        <input type="datetime-local" name="ends_at" required style="width:100%">
      </div>
    </div>

    <div style="height:16px"></div>

    <div class="card" style="border:1px solid #eee;box-shadow:none">
      <strong>Bulk add Products</strong>
      <div class="small">Select products to apply this sale at product-level.</div>
      <div style="height:10px"></div>
      <div style="max-height:240px;overflow:auto;border:1px solid #f1f1f1;border-radius:10px;padding:10px">
        <?php $products = $mysqli->query("SELECT id, name FROM products ORDER BY id DESC"); ?>
        <?php while($p = $products->fetch_assoc()): ?>
          <label style="display:flex;align-items:center;gap:8px;margin:6px 0">
            <input type="checkbox" name="products[]" value="<?= (int)$p['id'] ?>">
            <?= htmlspecialchars($p['name']) ?>
          </label>
        <?php endwhile; ?>
      </div>
    </div>

    <div class="card" style="border:1px solid #eee;box-shadow:none">
      <strong>Bulk add Variants</strong>
      <div class="small">Variant sale overrides product sale.</div>
      <div style="height:10px"></div>

      <div style="max-height:320px;overflow:auto;border:1px solid #f1f1f1;border-radius:10px;padding:10px">
        <?php $products2 = $mysqli->query("SELECT id, name FROM products ORDER BY id DESC"); ?>
        <?php while($p = $products2->fetch_assoc()): $pid=(int)$p['id']; $vs=$variantsByProduct[$pid] ?? []; if(!$vs) continue; ?>
          <details style="margin:8px 0">
            <summary style="cursor:pointer;font-weight:700"><?= htmlspecialchars($p['name']) ?></summary>
            <div style="padding:8px 6px">
              <?php foreach($vs as $v): ?>
                <label style="display:flex;align-items:center;gap:8px;margin:6px 0">
                  <input type="checkbox" name="variants[]" value="<?= (int)$v['id'] ?>">
                  <?= htmlspecialchars($v['variant_name']) ?> — <?= number_format((float)$v['price'], 0) ?>
                </label>
              <?php endforeach; ?>
            </div>
          </details>
        <?php endwhile; ?>
      </div>
    </div>

    <button type="submit" style="width:100%;padding:14px;font-weight:800">Create Sale</button>
  </form>
</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
