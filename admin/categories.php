<?php
require_once __DIR__ . '/_common.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $parent_id = (int)($_POST['parent_id'] ?? 0); if ($parent_id===0) $parent_id=null;
  if ($name !== '') {
    $stmt = $mysqli->prepare("INSERT INTO categories (name,parent_id,is_active) VALUES (?,?,1)");
    $stmt->bind_param("si", $name, $parent_id);
    $stmt->execute(); $stmt->close();
    apm_flash_set('ok', 'Category added.');
  }
  apm_redirect('categories.php');
}

$cats_all = $mysqli->query("SELECT id,name FROM categories ORDER BY name");
$cats = $mysqli->query("SELECT c.*, p.name AS parent_name FROM categories c LEFT JOIN categories p ON c.parent_id=p.id ORDER BY c.name");

require_once __DIR__ . '/_layout_top.php';
?>
<div class="card" style="padding:14px">
  <div style="font-weight:800;font-size:16px">Categories</div>
  <hr class="hr">
  <form method="post" class="grid2">
    <div>
      <label>New Category</label>
      <input name="name" required placeholder="e.g. Sunscreen">
    </div>
    <div>
      <label>Parent (optional)</label>
      <select name="parent_id">
        <option value="0">— None —</option>
        <?php while($c=$cats_all->fetch_assoc()): ?>
          <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div style="grid-column:1/-1;display:flex;gap:10px">
      <button class="btn primary" type="submit">Add</button>
    </div>
  </form>
</div>

<div style="height:12px"></div>

<div class="card">
  <table class="table">
    <thead><tr><th>ID</th><th>Name</th><th>Parent</th><th>Status</th><th>Action</th></tr></thead>
    <tbody>
      <?php while($c=$cats->fetch_assoc()): ?>
        <tr>
          <td><?= (int)$c['id'] ?></td>
          <td><?= htmlspecialchars($c['name']) ?></td>
          <td><?= htmlspecialchars($c['parent_name'] ?? '-') ?></td>
          <td><?= ((int)$c['is_active']===1) ? "<span class='badge on'>Active</span>" : "<span class='badge off'>Hidden</span>" ?></td>
          <td class="row-actions">
            <a href="category-toggle.php?id=<?= (int)$c['id'] ?>">Toggle</a>
            <a class="danger" href="category-delete.php?id=<?= (int)$c['id'] ?>" onclick="return confirm('Delete category?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
      <?php if ($cats->num_rows===0): ?><tr><td colspan="5" class="help" style="padding:18px">No categories yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/_layout_bottom.php'; ?>
