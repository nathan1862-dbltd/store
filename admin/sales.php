<?php
require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/admin_header.php';
require_once __DIR__ . '/sales_helpers.php';

$res = $mysqli->query("
  SELECT s.*,
    (SELECT COUNT(*) FROM sale_items si WHERE si.sale_id = s.id) AS items_count
  FROM sales s
  ORDER BY s.id DESC
");
?>
<div class="topbar">
  <div class="title">Sales Schedule</div>
  <a class="btn-link" href="sale_add.php">+ Create Sale</a>
</div>

<div class="card">
  <table class="table">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Discount</th>
      <th>Schedule</th>
      <th>Status</th>
      <th>Items</th>
      <th>Actions</th>
    </tr>
    <?php while($s = $res->fetch_assoc()): 
      $live = sale_is_live($s);
    ?>
      <tr>
        <td><?= (int)$s['id'] ?></td>
        <td><a href="sale_view.php?id=<?= (int)$s['id'] ?>"><?= htmlspecialchars($s['name']) ?></a></td>
        <td>
          <?php if ($s['discount_type'] === 'percent'): ?>
            <?= htmlspecialchars($s['discount_value']) ?>%
          <?php else: ?>
            Fixed price: <?= number_format((float)$s['discount_value'], 0) ?>
          <?php endif; ?>
        </td>
        <td><div class="small"><?= htmlspecialchars($s['starts_at']) ?> → <?= htmlspecialchars($s['ends_at']) ?></div></td>
        <td>
          <?php if ($live): ?>
            <span class="badge" style="background:#16a34a;color:#fff">LIVE</span>
          <?php elseif ((int)$s['is_active'] === 0): ?>
            <span class="badge" style="background:#6b7280;color:#fff">DISABLED</span>
          <?php else: ?>
            <span class="badge" style="background:#f59e0b;color:#111">SCHEDULED/ENDED</span>
          <?php endif; ?>
        </td>
        <td><?= (int)$s['items_count'] ?></td>
        <td style="white-space:nowrap">
          <a class="btn-link" href="sale_view.php?id=<?= (int)$s['id'] ?>">View</a> ·
          <a class="btn-link" href="sale_toggle.php?id=<?= (int)$s['id'] ?>&to=<?= ((int)$s['is_active']===1)?0:1 ?>">
            <?= ((int)$s['is_active']===1)?'Disable':'Enable' ?>
          </a> ·
          <a class="btn-link" href="sale_delete.php?id=<?= (int)$s['id'] ?>" onclick="return confirm('Delete this sale?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
