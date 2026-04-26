<?php
require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/admin_header.php';

function uploadImage($file, $subdir = 'products') {
    if (empty($file['name'])) return null;
    if (!is_uploaded_file($file['tmp_name'])) return null;

    $baseDir = __DIR__ . '/../assets/uploads/' . $subdir . '/';
    if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);

    $safeName = preg_replace('/[^a-zA-Z0-9\._-]/', '_', basename($file['name']));
    $finalName = time() . '_' . $safeName;

    if (move_uploaded_file($file['tmp_name'], $baseDir . $finalName)) {
        return 'uploads/' . $subdir . '/' . $finalName;
    }
    return null;
}

/* ---------------- PRODUCT ID ---------------- */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php");
    exit;
}
$productId = (int)$_GET['id'];

/* ---------------- FETCH PRODUCT ---------------- */
$stmt = $mysqli->prepare("
    SELECT *
    FROM products
    WHERE id = ?
    LIMIT 1
");
$stmt->bind_param("i", $productId);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    die('Product not found');
}

/* ---------------- FETCH MASTER DATA ---------------- */
$brands     = $mysqli->query("SELECT id, name FROM brands ORDER BY name ASC");
$cats       = $mysqli->query("SELECT id, name FROM categories ORDER BY name ASC");
$highlights = $mysqli->query("SELECT id, title, svg_icon FROM highlights ORDER BY title ASC");

/* ---------------- CURRENT CATEGORIES ---------------- */
$selectedCats = [];
$res = $mysqli->query("
    SELECT category_id
    FROM product_categories
    WHERE product_id = $productId
");
while ($r = $res->fetch_assoc()) {
    $selectedCats[] = (int)$r['category_id'];
}

/* ---------------- CURRENT HIGHLIGHTS ---------------- */
$selectedHighlights = [];
$res = $mysqli->query("
    SELECT highlight_id
    FROM product_highlights
    WHERE product_id = $productId
");
while ($r = $res->fetch_assoc()) {
    $selectedHighlights[] = (int)$r['highlight_id'];
}

/* ================= POST ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $img1 = uploadImage($_FILES['image1'] ?? []);
    $img2 = uploadImage($_FILES['image2'] ?? []);
    $img3 = uploadImage($_FILES['image3'] ?? []);

    /* ---------- PRODUCT UPDATE ---------- */
    $stmt = $mysqli->prepare("
        UPDATE products SET
          name = ?,
          description = ?,
          ingredients = ?,
          how_to_use = ?,
          brand_id = ?,
          image_path  = COALESCE(?, image_path),
          image_path2 = COALESCE(?, image_path2),
          image_path3 = COALESCE(?, image_path3)
        WHERE id = ?
    ");
    $stmt->bind_param(
        "ssssisssi",
        $_POST['name'],
        $_POST['description'],
        $_POST['ingredients'],
        $_POST['how_to_use'],
        $_POST['brand_id'],
        $img1,
        $img2,
        $img3,
        $productId
    );
    $stmt->execute();
    $stmt->close();

    /* ---------- CATEGORIES (REPLACE) ---------- */
    $mysqli->query("DELETE FROM product_categories WHERE product_id = $productId");

    if (!empty($_POST['categories'])) {
        $pc = $mysqli->prepare("
            INSERT INTO product_categories (product_id, category_id)
            VALUES (?,?)
        ");
        foreach ($_POST['categories'] as $cid) {
            $cid = (int)$cid;
            $pc->bind_param("ii", $productId, $cid);
            $pc->execute();
        }
        $pc->close();
    }

    /* ---------- HIGHLIGHTS (REPLACE, MAX 6) ---------- */
    $mysqli->query("DELETE FROM product_highlights WHERE product_id = $productId");

    if (!empty($_POST['highlights']) && is_array($_POST['highlights'])) {
        $selected = array_slice($_POST['highlights'], 0, 6);

        $ph = $mysqli->prepare("
            INSERT INTO product_highlights (product_id, highlight_id)
            VALUES (?, ?)
        ");
        foreach ($selected as $hid) {
            $hid = (int)$hid;
            $ph->bind_param("ii", $productId, $hid);
            $ph->execute();
        }
        $ph->close();
    }
}
?>

<!-- ================= UI ================= -->

<div class="topbar">
  <div class="title">Edit Product</div>
  <a class="btn-link" href="products.php">← Back</a>
</div>

<div class="card">
<form method="post" enctype="multipart/form-data">

  <div class="form-row">
    <input name="name" value="<?= htmlspecialchars($product['name']) ?>" required style="flex:1">
    <select name="brand_id" required>
      <?php while($b=$brands->fetch_assoc()): ?>
        <option value="<?= (int)$b['id'] ?>"
          <?= $b['id']==$product['brand_id']?'selected':'' ?>>
          <?= htmlspecialchars($b['name']) ?>
        </option>
      <?php endwhile; ?>
    </select>
  </div>

  <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea>
  <textarea name="ingredients"><?= htmlspecialchars($product['ingredients']) ?></textarea>
  <textarea name="how_to_use"><?= htmlspecialchars($product['how_to_use']) ?></textarea>

  <!-- CATEGORIES -->
  <div class="card" style="border:1px solid #eee">
    <strong>Categories</strong>
    <div class="form-row">
      <?php while($c=$cats->fetch_assoc()): ?>
        <label>
          <input type="checkbox" name="categories[]"
            value="<?= (int)$c['id'] ?>"
            <?= in_array($c['id'], $selectedCats) ? 'checked' : '' ?>>
          <?= htmlspecialchars($c['name']) ?>
        </label>
      <?php endwhile; ?>
    </div>
  </div>

  <!-- HIGHLIGHTS -->
  <div class="card" style="border:1px solid #eee">
    <strong>Highlights (max 6)</strong>
    <div class="form-row">
      <?php while ($h = $highlights->fetch_assoc()): ?>
        <label style="display:flex;align-items:center;gap:6px">
          <input type="checkbox" class="hlCheck" name="highlights[]"
            value="<?= (int)$h['id'] ?>"
            <?= in_array($h['id'], $selectedHighlights) ? 'checked' : '' ?>>
          <?php if ($h['svg_icon']): ?>
            <img src="../<?= htmlspecialchars($h['svg_icon']) ?>" width="16">
          <?php endif; ?>
          <?= htmlspecialchars($h['title']) ?>
        </label>
      <?php endwhile; ?>
    </div>
    <div class="small" id="hlMsg" style="display:none;color:#b00020">
      You can select up to 6 highlights only.
    </div>
  </div>

  <!-- IMAGES -->
  <div class="card" style="border:1px solid #eee">
    <strong>Images</strong>
    <div class="form-row">
      <input type="file" name="image1">
      <input type="file" name="image2">
      <input type="file" name="image3">
    </div>
  </div>

  <button type="submit">Update Product</button>
</form>
</div>

<script>
(function(){
  const max = 6;
  document.addEventListener('change', e => {
    if (!e.target.classList.contains('hlCheck')) return;
    const checked = document.querySelectorAll('.hlCheck:checked');
    if (checked.length > max) {
      e.target.checked = false;
      const msg = document.getElementById('hlMsg');
      msg.style.display = 'block';
      setTimeout(()=>msg.style.display='none',1500);
    }
  });
})();
</script>

<?php require_once __DIR__ . '/admin_footer.php'; ?>