<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/admin_header.php';

/* ----------------------------------------------------
   PRODUCT ID (MANDATORY)
---------------------------------------------------- */
$productId = (int)($_GET['product_id'] ?? 0);
if ($productId <= 0) {
    die('❌ Product ID missing. Open Variants from Products page.');
}

/* ----------------------------------------------------
   IMAGE UPLOAD HELPER
---------------------------------------------------- */
function uploadVariantImage(array $file): ?string
{
    if (
        empty($file['name']) ||
        empty($file['tmp_name']) ||
        !is_uploaded_file($file['tmp_name'])
    ) {
        return null;
    }

    $dir = __DIR__ . '/../uploads/variants/';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $safe = preg_replace('/[^a-zA-Z0-9\._-]/', '_', basename($file['name']));
    $name = time() . '_' . $safe;

    if (move_uploaded_file($file['tmp_name'], $dir . $name)) {
        return 'uploads/variants/' . $name;
    }

    return null;
}

/* ----------------------------------------------------
   ADD VARIANT (OPTION A – DB SAFE)
---------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $variantName = trim($_POST['variant_name'] ?? '');
    $price       = (float)($_POST['price'] ?? 0);
    $stock       = (int)($_POST['stock'] ?? 0);
    $sku         = trim($_POST['sku'] ?? '');

    if ($variantName === '' || $price <= 0) {
        die('❌ Variant name and price are required.');
    }

    $discount = ($_POST['discount_price'] !== '')
        ? (float)$_POST['discount_price']
        : null;

    $imagePath = uploadVariantImage($_FILES['image'] ?? []);

    /* 🔥 CORRECT INSERT (MATCHES YOUR TABLE EXACTLY) */
    $stmt = $mysqli->prepare("
        INSERT INTO product_variants
        (product_id, variant_name, price, discount_price, sku, stock, image_path, is_active)
        VALUES (?, ?, ?, ?, ?, ?, ?, 1)
    ");

    $stmt->bind_param(
        "isddsis",
        $productId,
        $variantName,
        $price,
        $discount,
        $sku,
        $stock,
        $imagePath
    );

    if (!$stmt->execute()) {
        die('SQL ERROR: ' . $stmt->error);
    }

    $variantId = $stmt->insert_id;
    $stmt->close();

    /* ATTRIBUTES */
    if (!empty($_POST['attr_name']) && is_array($_POST['attr_name'])) {
        $a = $mysqli->prepare("
            INSERT INTO variant_attributes
            (variant_id, attribute_name, attribute_value)
            VALUES (?, ?, ?)
        ");

        foreach ($_POST['attr_name'] as $i => $name) {
            $name = trim($name);
            if ($name === '') continue;
            $value = $_POST['attr_value'][$i] ?? '';
            $a->bind_param("iss", $variantId, $name, $value);
            $a->execute();
        }
        $a->close();
    }

    header("Location: product_variants.php?product_id={$productId}");
    exit;
}

/* ----------------------------------------------------
   FETCH VARIANTS
---------------------------------------------------- */
$variants = $mysqli->query("
    SELECT *
    FROM product_variants
    WHERE product_id = {$productId}
    ORDER BY id DESC
");
?>

<!-- ================= UI ================= -->

<div class="topbar">
    <div class="title">Product Variants</div>
    <a class="btn-link" href="product_edit.php?id=<?= $productId ?>">← Back to Product</a>
</div>

<div class="card">
<h3>Add Variant</h3>

<form method="post" enctype="multipart/form-data">

    <input name="variant_name" placeholder="Variant name (e.g. 50ml – Original)" required><br><br>

    <input name="sku" placeholder="SKU (optional)"><br><br>

    <input type="number" step="0.01" name="price" placeholder="Price" required><br><br>

    <input type="number" step="0.01" name="discount_price" placeholder="Discount price (optional)"><br><br>

    <input type="number" name="stock" placeholder="Stock" required><br><br>

    <label>Variant Image</label><br>
    <input type="file" name="image" accept="image/*"><br><br>

    <h4>Attributes</h4>
    <div id="attrs">
        <div class="form-row">
            <input name="attr_name[]" placeholder="Attribute (e.g. Size)">
            <input name="attr_value[]" placeholder="Value (e.g. 50ml)">
        </div>
    </div>

    <button type="button" onclick="addAttr()">+ Add Attribute</button><br><br>

    <button>Add Variant</button>
</form>
</div>

<div class="card">
<h3>Existing Variants</h3>

<table class="table">
<tr>
    <th>Image</th>
    <th>Variant</th>
    <th>SKU</th>
    <th>Price</th>
    <th>Stock</th>
</tr>

<?php while ($v = $variants->fetch_assoc()): ?>
<tr>
    <td>
        <?php if ($v['image_path']): ?>
            <img src="../<?= htmlspecialchars($v['image_path']) ?>" width="45">
        <?php endif; ?>
    </td>
    <td><?= htmlspecialchars($v['variant_name']) ?></td>
    <td><?= htmlspecialchars($v['sku']) ?></td>
    <td>
        <?php if ($v['discount_price'] !== null && $v['discount_price'] < $v['price']): ?>
            <del><?= number_format($v['price'],2) ?></del><br>
            <strong><?= number_format($v['discount_price'],2) ?></strong>
        <?php else: ?>
            <?= number_format($v['price'],2) ?>
        <?php endif; ?>
    </td>
    <td><?= (int)$v['stock'] ?></td>
</tr>
<?php endwhile; ?>
</table>
</div>

<script>
function addAttr(){
    document.getElementById('attrs').insertAdjacentHTML(
        'beforeend',
        `<div class="form-row">
            <input name="attr_name[]" placeholder="Attribute">
            <input name="attr_value[]" placeholder="Value">
        </div>`
    );
}
</script>

<?php require_once __DIR__ . '/admin_footer.php'; ?>