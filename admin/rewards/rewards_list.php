<?php
require_once __DIR__ . '/_bootstrap.php';

admin_require();

$rewards = $mysqli->query('SELECT * FROM reward_items ORDER BY created_at DESC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Rewards</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 16px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #eee; padding: 10px; text-align: left; }
    th { background: #fafafa; }
    .top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
    .btn { padding: 8px 12px; border: 1px solid #111; text-decoration: none; border-radius: 8px; color: #111; }
  </style>
</head>
<body>
  <div class="top">
    <h2>Rewards</h2>
    <div>
      <a class="btn" href="reward_add.php">+ Add Reward</a>
      <a class="btn" href="claims_list.php">View Claims</a>
    </div>
  </div>

  <table>
    <tr>
      <th>Title</th><th>Points</th><th>Stock</th><th>Status</th><th>Actions</th>
    </tr>
    <?php while ($r = $rewards->fetch_assoc()): ?>
      <tr>
        <td><?php echo e($r['title']); ?></td>
        <td><?php echo (int) $r['points_required']; ?></td>
        <td><?php echo (int) $r['stock']; ?></td>
        <td><?php echo ((int) $r['is_active'] === 1) ? 'Yes' : 'No'; ?></td>
        <td>
          <a class="btn" href="reward_edit.php?id=<?php echo (int) $r['id']; ?>">Edit</a>
          <a class="btn" href="reward_toggle.php?id=<?php echo (int) $r['id']; ?>">
            <?php echo ((int) $r['is_active'] === 1) ? 'Disable' : 'Enable'; ?>
          </a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
