<?php
require_once __DIR__ . '/adminconfig.php';
require_once __DIR__ . '/auth.php';

if (!isset($_SESSION)) session_start();

/* =========================
   CSRF TOKEN INIT
========================= */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/* =========================
   DB CONNECTION
========================= */
$conn = $db; // assuming $db from config

$product = [
    'id' => '',
    'category_id' => '',
    'subcategory_id' => '',
    'product_name' => '',
    'description' => '',
    'price' => ''
];

/* =========================
   FETCH PRODUCT (EDIT MODE)
========================= */
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $product = $row;
    }
}

/* =========================
   FETCH CATEGORIES
========================= */
$categories = [];
$res = $conn->query("SELECT id, name FROM categories WHERE parent_id = 0");
while ($row = $res->fetch_assoc()) {
    $categories[] = $row;
}

/* =========================
   FETCH SUBCATEGORIES
========================= */
$subcategories = [];
$res = $conn->query("SELECT id, name, parent_id FROM categories WHERE parent_id != 0");
while ($row = $res->fetch_assoc()) {
    $subcategories[$row['parent_id']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Product Edit</title>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
body {
    font-family: "Inter", sans-serif;
    background: #0f172a;
    color: #fff;
    padding: 20px;
}
.form-group {
    margin-bottom: 15px;
}
input, select, textarea {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: none;
}
button {
    background: linear-gradient(135deg,#fbc2eb,#a6c1ee);
    border: none;
    padding: 12px;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
}
</style>
</head>

<body>

<h2><?= $product['id'] ? 'Edit Product' : 'Add Product' ?></h2>

<form method="POST" action="" id="product-form">

<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
<input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">

<div class="form-group">
    <label>Category</label>
    <select name="category_id" id="category" required>
        <option value="">Select Category</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"
                <?= $product['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="form-group">
    <label>Subcategory</label>
    <select name="subcategory_id" id="subcategory">
        <option value="">Select Subcategory</option>
    </select>
</div>

<div class="form-group">
    <label>Product Name</label>
    <input type="text" name="product_name"
        value="<?= htmlspecialchars($product['product_name']) ?>" required>
</div>

<div class="form-group">
    <label>Description</label>
    <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea>
</div>

<div class="form-group">
    <label>Price</label>
    <input type="number" step="0.01" name="price"
        value="<?= htmlspecialchars($product['price']) ?>" required>
</div>

<button type="submit">Save Product</button>

</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>
$('#category, #subcategory').select2();

/* =========================
   SUBCATEGORY DYNAMIC LOAD
========================= */
const subcategories = <?= json_encode($subcategories) ?>;

function loadSubcategories(catId, selectedId = null) {
    let options = '<option value="">Select Subcategory</option>';

    if (subcategories[catId]) {
        subcategories[catId].forEach(sub => {
            let selected = (selectedId == sub.id) ? 'selected' : '';
            options += `<option value="${sub.id}" ${selected}>${sub.name}</option>`;
        });
    }

    $('#subcategory').html(options).trigger('change');
}

$('#category').on('change', function () {
    loadSubcategories(this.value);
});

/* INIT */
loadSubcategories(
    "<?= $product['category_id'] ?>",
    "<?= $product['subcategory_id'] ?>"
);
</script>

<?php
/* =========================
   HANDLE FORM SUBMIT
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }

    $id = intval($_POST['id']);
    $category_id = intval($_POST['category_id']);
    $subcategory_id = intval($_POST['subcategory_id']);
    $product_name = trim($_POST['product_name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);

    if (!$product_name || !$price) {
        echo "<script>alert('Required fields missing');</script>";
        exit;
    }

    if ($id) {
        $stmt = $conn->prepare("
            UPDATE products 
            SET category_id=?, subcategory_id=?, product_name=?, description=?, price=?
            WHERE id=?
        ");
        $stmt->bind_param("iissdi",
            $category_id,
            $subcategory_id,
            $product_name,
            $description,
            $price,
            $id
        );
    } else {
        $stmt = $conn->prepare("
            INSERT INTO products (category_id, subcategory_id, product_name, description, price)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iissd",
            $category_id,
            $subcategory_id,
            $product_name,
            $description,
            $price
        );
    }

    if ($stmt->execute()) {
        echo "<script>alert('Saved successfully'); window.location.href='product_list.php';</script>";
    } else {
        echo "<script>alert('Error saving product');</script>";
    }
}
?>

</body>
</html>