<?php
require_once __DIR__ . '/_bootstrap.php';

admin_require();

$claims = $mysqli->query(
    "SELECT c.*, u.username, r.title
     FROM reward_claims c
     JOIN users u ON u.id = c.user_id
     JOIN reward_items r ON r.id = c.reward_id
     ORDER BY c.claimed_at DESC"
);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Reward Claims</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 16px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #eee; padding: 10px; }
    th { background: #fafafa; }
    .btn { padding: 6px 10px; border: 1px solid #111; border-radius: 8px; text-decoration: none; color: #111; }
  </style>
</head>
<body>
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
    <h2 style="margin:0">Claims</h2>
    <a class="btn" href="rewards_list.php">Rewards</a>
  </div>

  <table>
    <tr>
      <th>User</th><th>Reward</th><th>Points</th><th>Status</th><th>Claimed</th><th>Actions</th>
    </tr>
    <?php while ($c = $claims->fetch_assoc()): ?>
      <tr>
        <td><?php echo e($c['username']); ?></td>
        <td><?php echo e($c['title']); ?></td>
        <td><?php echo (int) $c['points_used']; ?></td>
        <td><?php echo e($c['status']); ?></td>
        <td><?php echo e($c['claimed_at']); ?></td>
        <td>
          <?php if ($c['status'] === 'pending'): ?>
            <a class="btn" href="claim_action.php?id=<?php echo (int) $c['id']; ?>&action=approve">Approve</a>
            <a class="btn" href="claim_action.php?id=<?php echo (int) $c['id']; ?>&action=reject">Reject</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
