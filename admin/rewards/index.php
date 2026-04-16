<?php
require_once __DIR__ . '/_admin_guard.php';

$items = $mysqli->query("SELECT * FROM reward_items ORDER BY created_at DESC");
$ok = flash('ok');
$err = flash('err');
?>
<?php if (file_exists(__DIR__ . '/../admin_header.php')) require __DIR__ . '/../admin_header.php'; ?>
<link rel="stylesheet" href="../../assets/css/rewards.css">

<div class="rewards-wrap">
  <div class="rewards-top">
    <h2>Admin: Reward Items</h2>
    <div style="display:flex;gap:10px;">
      <a class="btn btn-primary" href="add.php">Add Reward</a>
      <a class="btn btn-primary" href="claims.php">View Claims</a>
    </div>
  </div>

  <?php if ($ok): ?><div class="notice notice-ok"><?php echo e($ok); ?></div><?php endif; ?>
  <?php if ($err): ?><div class="notice notice-err"><?php echo e($err); ?></div><?php endif; ?>

  <table class="table">
    <thead>
      <tr>
        <th>Title</th>
        <th>Points</th>
        <th>Stock</th>
        <th>Active</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($r = $items->fetch_assoc()): ?>
        <tr>
          <td><?php echo e($r['title']); ?></td>
          <td><?php echo (int)$r['points_required']; ?></td>
          <td><?php echo (int)$r['stock']; ?></td>
          <td><?php echo ((int)$r['is_active'] === 1) ? 'Yes' : 'No'; ?></td>
          <td>
            <a class="btn btn-primary" href="edit.php?id=<?php echo (int)$r['id']; ?>">Edit</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php if (file_exists(__DIR__ . '/../admin_footer.php')) require __DIR__ . '/../admin_footer.php'; ?>
