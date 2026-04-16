<?php
require_once __DIR__ . '/_admin_guard.php';

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
    $stmt = $mysqli->prepare("INSERT INTO reward_items (title, description, points_required, stock, image_path, is_active) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param('ssiisi', $title, $description, $points, $stock, $image, $active);
    $stmt->execute();
    $stmt->close();

    flash('ok', 'Reward item created successfully.');
    redirect('index.php');
  }
}
?>
<?php if (file_exists(__DIR__ . '/../admin_header.php')) require __DIR__ . '/../admin_header.php'; ?>
<link rel="stylesheet" href="../../assets/css/rewards.css">

<div class="rewards-wrap">
  <div class="rewards-top">
    <h2>Add Reward</h2>
    <a class="btn btn-primary" href="index.php">Back</a>
  </div>

  <?php if ($err = flash('err')): ?><div class="notice notice-err"><?php echo e($err); ?></div><?php endif; ?>

  <form method="post" style="background:#fff;border:1px solid #eee;border-radius:14px;padding:14px;">
    <div style="display:grid;gap:10px;">
      <label>Title<br><input name="title" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:10px"></label>
      <label>Description<br><textarea name="description" rows="4" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:10px"></textarea></label>
      <label>Points Required<br><input type="number" name="points_required" min="1" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:10px"></label>
      <label>Stock<br><input type="number" name="stock" min="0" value="0" style="width:100%;s"></label>
      <label>Image Path (optional)<br><input name="image_path" placeholder="assets/images/rewards/sample.png" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:10px"></label>
      <label><input type="checkbox" name="is_active" checked> Active</label>
      <button class="btn btn-primary" type="submit">Create Reward</button>
    </div>
  </form>
</div>

<?php if (file_exists(__DIR__ . '/../admin_footer.php')) require __DIR__ . '/../admin_footer.php'; ?>
