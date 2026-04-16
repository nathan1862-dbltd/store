<?php

require_once __DIR__ . '/adminconfig.php';
require_once __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id     = (int)($_POST['id'] ?? 0);
    $name   = trim($_POST['name'] ?? '');
    $desc   = trim($_POST['description'] ?? '');
    $image  = trim($_POST['image'] ?? '');
    $cost   = (int)($_POST['points_cost'] ?? 0);
    $stock  = (int)($_POST['stock'] ?? 0);
    $active = isset($_POST['is_active']) ? 1 : 0;

    if ($name === '' || $cost <= 0) {
        die("Invalid data");
    }

    if ($id > 0) {
        // Update
        $stmt = $mysqli->prepare("
            UPDATE reward_products
            SET name=?, description=?, image=?, points_cost=?, stock=?, is_active=?
            WHERE id=?
        ");
        $stmt->bind_param("sssiiii",
            $name, $desc, $image, $cost, $stock, $active, $id
        );
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert
        $stmt = $mysqli->prepare("
            INSERT INTO reward_products
            (name, description, image, points_cost, stock, is_active)
            VALUES (?,?,?,?,?,?)
        ");
        $stmt->bind_param("sssiii",
            $name, $desc, $image, $cost, $stock, $active
        );
        $stmt->execute();
        $stmt->close();
    }

    header("Location: reward-product.php");
    exit;
}

/* ================= EDIT MODE ================= */

$edit = null;
$editId = (int)($_GET['edit'] ?? 0);

if ($editId > 0) {
    $stmt = $mysqli->prepare("
        SELECT * FROM reward_products WHERE id=?
    ");
    $stmt->bind_param("i",$editId);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

/* ================= FETCH LIST ================= */

$list = $mysqli->query("
    SELECT * FROM reward_products
    ORDER BY id DESC
");

function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>

<link rel="stylesheet" href="../assets/css/rewards.css">

<div class="rw-wrap">

<div class="rw-head">
<h1 class="rw-title">Admin · Reward Products</h1>
</div>

<!-- ================= FORM ================= -->

<div class="rw-msg">
<form method="post" style="display:grid;gap:15px">

<input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">

<div>
<label><strong>Reward Name</strong></label><br>
<input type="text" name="name"
value="<?= h($edit['name'] ?? '') ?>"
required
style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px">
</div>

<div>
<label><strong>Description</strong></label><br>
<textarea name="description"
style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px"><?= h($edit['description'] ?? '') ?></textarea>
</div>

<div>
<label><strong>Image URL / Path</strong></label><br>
<input type="text" name="image"
value="<?= h($edit['image'] ?? '') ?>"
style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px">
</div>

<div style="display:flex;gap:20px">
<div style="flex:1">
<label><strong>Points Cost</strong></label><br>
<input type="number" name="points_cost"
value="<?= (int)($edit['points_cost'] ?? 0) ?>"
required
style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px">
</div>

<div style="flex:1">
<label><strong>Stock</strong></label><br>
<input type="number" name="stock"
value="<?= (int)($edit['stock'] ?? 0) ?>"
required
style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px">
</div>
</div>

<div>
<label>
<input type="checkbox" name="is_active"
<?= ((int)($edit['is_active'] ?? 1) === 1) ? 'checked' : '' ?>>
Active
</label>
</div>

<div>
<button class="rw-btn" type="submit">
<?= $edit ? 'Update Reward' : 'Add Reward' ?>
</button>

<?php if ($edit): ?>
<a href="reward-product.php"
class="rw-btn"
style="background:#fff;color:#111;border:1px solid #ccc">
Cancel
</a>
<?php endif; ?>
</div>

</form>
</div>

<!-- ================= LIST ================= -->

<table class="rw-table">
<thead>
<tr>
<th>ID</th>
<th>Reward</th>
<th>Cost</th>
<th>Stock</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>

<?php while($row = $list->fetch_assoc()): ?>
<tr>
<td>#<?= (int)$row['id'] ?></td>

<td>
<div style="display:flex;gap:10px;align-items:center">
<img src="<?= h($row['image'] ?: '../assets/images/no-image.png') ?>"
style="width:50px;height:50px;border-radius:8px;object-fit:cover;background:#f3f4f6">

<div>
<strong><?= h($row['name']) ?></strong><br>
<span style="font-size:12px;color:#666">
<?= h($row['description']) ?>
</span>
</div>
</div>
</td>

<td><?= (int)$row['points_cost'] ?> pts</td>
<td><?= (int)$row['stock'] ?></td>

<td>
<?= $row['is_active'] ? 'Active' : 'Inactive' ?>
</td>

<td>
<a class="rw-btn"
style="background:#fff;color:#111;border:1px solid #ccc"
href="reward-product.php?edit=<?= $row['id'] ?>">
Edit
</a>
</td>

</tr>
<?php endwhile; ?>

</tbody>
</table>

</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>