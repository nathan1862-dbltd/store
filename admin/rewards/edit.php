<?php
require_once __DIR__ . '/_admin_guard.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { flash('err', 'Invalid reward id.'); redirect('index.php'); }

$stmt = $mysqli->prepare("SELECT * FROM reward_items WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$item) { flash('err', 'Reward not found.'); redirect('index.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $points = (int)($_POST['points_required'] ?? 0);
  $stock = (int)($_POST['stock'] ?? 0);
  $image = trim($_POST['image_path'] ?? '');
  $active = isset($_POST['is_active']) ? 1 : 0;

  if ($title === '' || $points <= 0) {
    flash('err', 'Title and points required are mandatory.');
  } else {
    $stmt = $mysqli->prepare("UPDATE reward_items SET title=?, description=?, points_required=?, stock=?, image_path=?, is_active=? WHERE id=?");
    $stmt->bind_param('ssiisii', $title, $description, $points, $stock, $image, $active, $id);
    $stmt->execute();
    $stmt->close();

    flash('ok', 'Reward updated successfully.');
    redirect('index.php');
  }
}
?>
<?php if (file_exists(__DIR__ . '/../admin_header.php')) require __DIR__ . '/../admin_header.php'; ?>
<link rel="stylesheet" href="../../assets/css/rewards.css">

<div class="rewards-wrap">
  <div class="rewards-top">
    <h2>Edit Reward</h2>
    <a class="btn btn-primary" href="index.php">Back</a>
  </div>

  <?php if ($err = flash('err')): ?><div class="notice notice-err"><?php echo e($err); ?></div><?php endif; ?>

  <form method="post" style="background:#fff;border:1px solid #eee;border-radius:14px;padding:14px;">
    <div style="display:grid;gap:10px;">
      <label>Title<br><input name="title" value="<?php echo e($item['title']); ?>" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:10px"></label>
      <label>Description<br><textarea name="description" rows="4" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:10px"><?php echo e((string)$item['description']); ?></textarea></label>
      <label>Points Required<br><input type="number" name="points_required" min="1" value="<?php echo (int)$item['points_required']; ?>" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:10px"></label>
      <label>Stock<br><input type="number" name="stock" min="0" value="<?php echo (int)$item['stock']; ?>" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:10px"></label>
      <label>Image Path (optional)<br><input name="image_path" value="<?php echo e((string)$item['image_path']); ?>" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:10px"></label>
      <label><input type="checkbox" name="is_active" <?php echo ((int)$item['is_active']===1)?'checked':''; ?>> Active</label>
      <button class="btn btn-primary" type="submit">Save Changes</button>
    </div>
  </form>
</div>

<?php if (file_exists(__DIR__ . '/../admin_footer.php')) require __DIR__ . '/../admin_footer.php'; ?>
