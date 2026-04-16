<?php
require_once __DIR__ . '/_admin_guard.php';

$claims = $mysqli->query("\
  SELECT c.*, u.username, r.title
  FROM reward_claims c
  JOIN users u ON u.id = c.user_id
  JOIN reward_items r ON r.id = c.reward_id
  ORDER BY c.claimed_at DESC
");
$ok = flash('ok');
$err = flash('err');
?>
<?php if (file_exists(__DIR__ . '/../admin_header.php')) require __DIR__ . '/../admin_header.php'; ?>
<link rel="stylesheet" href="../../assets/css/rewards.css">

<div class="rewards-wrap">
  <div class="rewards-top">
    <h2>Admin: Reward Claims</h2>
    <div style="display:flex;gap:10px;">
      <a class="btn btn-primary" href="index.php">Reward Items</a>
    </div>
  </div>

  <?php if ($ok): ?><div class="notice notice-ok"><?php echo e($ok); ?></div><?php endif; ?>
  <?php if ($err): ?><div class="notice notice-err"><?php echo e($err); ?></div><?php endif; ?>

  <table class="table">
    <thead>
      <tr>
        <th>Customer</th>
        <th>Reward</th>
        <th>Points Used</th>
        <th>Status</th>
        <th>Claimed At</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($c = $claims->fetch_assoc()): ?>
        <tr>
          <td><?php echo e($c['username']); ?></td>
          <td><?php echo e($c['title']); ?></td>
          <td><?php echo (int)$c['points_used']; ?></td>
          <td><?php echo e($c['status']); ?></td>
          <td><?php echo e($c['claimed_at']); ?></td>
          <td>
            <?php if ($c['status'] === 'pending'): ?>
              <form method="post" action="claim_action.php" style="display:flex;gap:8px;">
                <input type="hidden" name="claim_id" value="<?php echo (int)$c['id']; ?>">
                <button class="btn btn-primary" name="action" value="approve" type="submit">Approve</button>
                <button class="btn" style="background:#eee" name="action" value="reject" type="submit">Reject</button>
              </form>
            <?php else: ?>
              <span style="color:#777;">No action</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php if (file_exists(__DIR__ . '/../admin_footer.php')) require __DIR__ . '/../admin_footer.php'; ?>
