<?php
$flash_ok = apm_flash_get('ok');
$flash_err = apm_flash_get('err');
?>
<link rel="stylesheet" href="../assets/admin-product-manager.css">
<div class="container">
  <div class="topbar">
    <div class="h1">Admin Product Manager</div>
    <div style="display:flex;gap:10px;flex-wrap:wrap">
      <a class="btn" href="../dashboard.php">Dashboard</a>
      <a class="btn" href="products.php">Products</a>
      <a class="btn" href="brands.php">Brands</a>
      <a class="btn" href="categories.php">Categories</a>
      <a class="btn" href="variants.php">Variants</a>
    </div>
  </div>

  <?php if ($flash_ok): ?>
    <div class="card" style="padding:12px;border-color:rgba(76,175,80,.35);margin-bottom:12px">
      ✅ <?= htmlspecialchars($flash_ok) ?>
    </div>
  <?php endif; ?>

  <?php if ($flash_err): ?>
    <div class="card" style="padding:12px;border-color:rgba(204,34,48,.35);margin-bottom:12px">
      ⚠️ <?= htmlspecialchars($flash_err) ?>
    </div>
  <?php endif; ?>
