<?php
// expects: $mode, $p, $brands, $cats
?>
<form class="form" method="post" enctype="multipart/form-data">
  <div class="grid2">
    <div>
      <label>Product Name</label>
      <input required name="name" value="<?= htmlspecialchars($p['name'] ?? '') ?>">
    </div>
    <div>
      <label>SKU</label>
      <input name="sku" value="<?= htmlspecialchars($p['sku'] ?? '') ?>" placeholder="Optional but recommended">
    </div>
  </div>

  <div class="grid2">
    <div>
      <label>Brand</label>
      <select name="brand_id">
        <option value="0">— None —</option>
        <?php while($b = $brands->fetch_assoc()): ?>
          <option value="<?= (int)$b['id'] ?>" <?= ((int)($p['brand_id'] ?? 0)===(int)$b['id'])?'selected':'' ?>>
            <?= htmlspecialchars($b['name']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>
    <div>
      <label>Category</label>
      <select name="category_id">
        <option value="0">— None —</option>
        <?php while($c = $cats->fetch_assoc()): ?>
          <option value="<?= (int)$c['id'] ?>" <?= ((int)($p['category_id'] ?? 0)===(int)$c['id'])?'selected':'' ?>>
            <?= htmlspecialchars($c['name']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>
  </div>

  <div class="grid2">
    <div>
      <label>Base Price</label>
      <input required type="number" step="0.01" name="base_price" value="<?= htmlspecialchars($p['base_price'] ?? '0') ?>">
      <div class="help">Primary sell price used in checkout.</div>
    </div>
    <div>
      <label>Sale Price (optional)</label>
      <input type="number" step="0.01" name="sale_price" value="<?= htmlspecialchars($p['sale_price'] ?? '') ?>">
      <div class="help">If set, your frontend can show strike-through pricing.</div>
    </div>
  </div>

  <div class="grid2">
    <div>
      <label>Stock</label>
      <input type="number" name="stock" value="<?= htmlspecialchars($p['stock'] ?? '0') ?>">
      <div class="help">If using variants, set has_variants = Yes and manage stock in variants.</div>
    </div>
    <div>
      <label>Has Variants</label>
      <select name="has_variants">
        <option value="0" <?= ((int)($p['has_variants'] ?? 0)===0)?'selected':'' ?>>No</option>
        <option value="1" <?= ((int)($p['has_variants'] ?? 0)===1)?'selected':'' ?>>Yes</option>
      </select>
    </div>
  </div>

  <div class="grid2">
    <div>
      <label>Status</label>
      <select name="is_active">
        <option value="1" <?= ((int)($p['is_active'] ?? 1)===1)?'selected':'' ?>>Active</option>
        <option value="0" <?= ((int)($p['is_active'] ?? 1)===0)?'selected':'' ?>>Hidden</option>
      </select>
    </div>
    <div>
      <label>Featured</label>
      <select name="is_featured">
        <option value="0" <?= ((int)($p['is_featured'] ?? 0)===0)?'selected':'' ?>>No</option>
        <option value="1" <?= ((int)($p['is_featured'] ?? 0)===1)?'selected':'' ?>>Yes</option>
      </select>
    </div>
  </div>

  <div>
    <label>Short Description</label>
    <textarea name="short_description" placeholder="Optional"><?= htmlspecialchars($p['short_description'] ?? '') ?></textarea>
  </div>

  <div>
    <label>Description</label>
    <textarea name="description" placeholder="Optional"><?= htmlspecialchars($p['description'] ?? '') ?></textarea>
  </div>

  <div>
    <label>Main Image</label>
    <?php if (!empty($p['image'])): ?>
      <div class="help" style="margin-bottom:8px">Current: <code><?= htmlspecialchars($p['image']) ?></code></div>
      <img class="thumb" src="../../uploads/products/<?= htmlspecialchars($p['image']) ?>" alt="">
      <div style="height:10px"></div>
    <?php endif; ?>
    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
    <div class="help">Uploads to /V3/uploads/products/</div>
  </div>

  <div style="display:flex;gap:10px;flex-wrap:wrap">
    <button class="btn primary" type="submit"><?= ($mode==='add') ? 'Create Product' : 'Save Changes' ?></button>
    <a class="btn" href="products.php">Back</a>
  </div>
</form>
