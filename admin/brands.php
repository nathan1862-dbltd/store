<?php
require_once __DIR__ . '/_common.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  if ($name !== '') {
    $stmt = $mysqli->prepare("INSERT INTO brands (name,is_active) VALUES (?,1)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->close();
    apm_flash_set('ok', 'Brand added.');
  }
  apm_redirect('brands.php');
}

$brands = $mysqli->query("SELECT * FROM brands ORDER BY name");
require_once __DIR__ . '/_layout_top.php';
?>
<div class="card" style="padding:14px">
  <div style="font-weight:800;font-size:16px">Brands</div>
  <hr class="hr">
  <form method="post" style="display:flex;gap:10px;flex-wrap:wrap;align-items:end">
    <div style="flex:1;min-width:220px">
      <label>New Brand</label>
      <input name="name" placeholder="e.g. Beauty of Joseon" required>
    </div>
    <button class="btn primary" type="submit">Add</button>
  </form>
</div>

<div style="height:12px"></div>

<div class="card">
  <table class="table">
    <thead><tr><th>ID</th><th>Name</th><th>Status</th><th>Action</th></tr></thead>
    <tbody>
      <?php while($b=$brands->fetch_assoc()): ?>
        <tr>
          <td><?= (int)$b['id'] ?></td>
          <td><?= htmlspecialchars($b['name']) ?></td>
          <td><?= ((int)$b['is_active']===1) ? "<span class='badge on'>Active</span>" : "<span class='badge off'>Hidden</span>" ?></td>
          <td class="row-actions">
            <a href="brand-toggle.php?id=<?= (int)$b['id'] ?>">Toggle</a>
            <a class="danger" href="brand-delete.php?id=<?= (int)$b['id'] ?>" onclick="return confirm('Delete brand?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
      <?php if ($brands->num_rows===0): ?><tr><td colspan="4" class="help" style="padding:18px">No brands yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/_layout_bottom.php'; ?>
