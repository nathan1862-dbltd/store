<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/adminconfig.php';
require_once __DIR__ . '/auth.php';

/* -----------------------------
   Inputs
------------------------------*/
$q     = trim($_GET['q'] ?? '');
$page  = max(1, (int)($_GET['page'] ?? 1));
$limit = 15;
$offset = ($page - 1) * $limit;

$like = '%' . $q . '%';

/* -----------------------------
   Count total
------------------------------*/
$countSql = "
  SELECT COUNT(DISTINCT p.id) AS total
  FROM products p
  LEFT JOIN brands b ON b.id = p.brand_id
  LEFT JOIN product_categories pc ON pc.product_id = p.id
  LEFT JOIN categories c ON c.id = pc.category_id
  WHERE (? = '' OR p.name LIKE ? OR p.slug LIKE ? OR b.name LIKE ? OR c.name LIKE ?)
";
$stmt = $mysqli->prepare($countSql);
$stmt->bind_param("sssss", $q, $like, $like, $like, $like);
$stmt->execute();
$total = (int)($stmt->get_result()->fetch_assoc()['total'] ?? 0);
$stmt->close();

$totalPages = max(1, (int)ceil($total / $limit));

/* -----------------------------
   Fetch list
------------------------------*/
$listSql = "
  SELECT
    p.id,
    p.name,
    p.slug,
    p.is_active,
    p.has_variants,
    p.is_on_sale,
    p.from_price,
    p.image_path,
    p.created_at,
    b.name AS brand_name,
    GROUP_CONCAT(DISTINCT c.name ORDER BY c.name SEPARATOR ', ') AS category_names,

    /* Variant rollups */
    (SELECT IFNULL(SUM(pv.stock),0) FROM product_variants pv WHERE pv.product_id = p.id) AS total_stock,
    (SELECT MIN(pv.price) FROM product_variants pv WHERE pv.product_id = p.id) AS min_price,
    (SELECT MIN(pv.discount_price)
       FROM product_variants pv
      WHERE pv.product_id = p.id
        AND pv.on_sale = 1
        AND pv.discount_price IS NOT NULL
    ) AS min_discount_price

  FROM products p
  LEFT JOIN brands b ON b.id = p.brand_id
  LEFT JOIN product_categories pc ON pc.product_id = p.id
  LEFT JOIN categories c ON c.id = pc.category_id
  WHERE (? = '' OR p.name LIKE ? OR p.slug LIKE ? OR b.name LIKE ? OR c.name LIKE ?)
  GROUP BY p.id
  ORDER BY p.id DESC
  LIMIT ? OFFSET ?
";
$stmt = $mysqli->prepare($listSql);
$stmt->bind_param("sssssii", $q, $like, $like, $like, $like, $limit, $offset);
$stmt->execute();
$rows = $stmt->get_result();
$stmt->close();

function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?php echo h(ADMIN_PANEL_NAME); ?> - Products</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:Arial, sans-serif;margin:0;background:#f4f6f9;color:#111;}
    .topbar{background:#111827;color:#fff;padding:14px 16px;display:flex;gap:12px;align-items:center;justify-content:space-between;}
    .topbar .left{display:flex;flex-direction:column;gap:3px}
    .topbar .title{font-weight:700}
    .topbar .meta{font-size:12px;opacity:.85}
    .topbar a{color:#93c5fd;text-decoration:none;font-size:13px}
    .wrap{padding:16px;max-width:1100px;margin:0 auto;}
    .toolbar{display:flex;gap:10px;flex-wrap:wrap;align-items:center;justify-content:space-between;margin-bottom:12px}
    .search{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
    .search input{padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;min-width:240px}
    .btn{display:inline-block;padding:10px 12px;border-radius:8px;border:0;cursor:pointer;text-decoration:none;font-size:14px}
    .btn-primary{background:#111827;color:#fff}
    .btn-soft{background:#e5e7eb;color:#111}
    .cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:12px}
    .card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden}
    .card .img{height:140px;background:#f3f4f6;display:flex;align-items:center;justify-content:center}
    .card .img img{max-height:140px;max-width:100%;object-fit:contain}
    .card .body{padding:12px}
    .row{display:flex;gap:8px;align-items:center;justify-content:space-between}
    .name{font-weight:700;margin:0 0 6px}
    .muted{color:#6b7280;font-size:12px}
    .pill{font-size:12px;padding:4px 8px;border-radius:999px;border:1px solid #e5e7eb;background:#f9fafb}
    .pill.on{background:#dcfce7;border-color:#bbf7d0}
    .pill.off{background:#fee2e2;border-color:#fecaca}
    .price{font-weight:700}
    .actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:10px}
    .actions a{font-size:13px}
    .pager{display:flex;gap:8px;flex-wrap:wrap;justify-content:center;margin-top:16px}
    .pager a{padding:8px 10px;border:1px solid #e5e7eb;border-radius:10px;background:#fff;text-decoration:none;color:#111}
    .pager .active{background:#111827;color:#fff;border-color:#111827}
    .empty{background:#fff;border:1px dashed #d1d5db;border-radius:12px;padding:18px;text-align:center;color:#6b7280}
  </style>
</head>
<body>

<div class="topbar">
  <div class="left">
    <div class="title">Products</div>
    <div class="meta">Total: <?php echo number_format($total); ?> • Page <?php echo $page; ?>/<?php echo $totalPages; ?></div>
  </div>
  <div class="right">
    <a href="dashboard.php">Dashboard</a> •
    <a href="logout.php">Logout</a>
  </div>
</div>

<div class="wrap">

  <div class="toolbar">
    <form class="search" method="get" action="products.php">
      <input type="text" name="q" value="<?php echo h($q); ?>" placeholder="Search products, brands, categories…">
      <button class="btn btn-soft" type="submit">Search</button>
      <?php if ($q !== ''): ?>
        <a class="btn btn-soft" href="products.php">Clear</a>
      <?php endif; ?>
    </form>

    <div>
      <a class="btn btn-primary" href="product_add.php">+ Add Product</a>
    </div>
  </div>

  <?php if ($total === 0): ?>
    <div class="empty">No products found.</div>
  <?php else: ?>

    <div class="cards">
      <?php while ($p = $rows->fetch_assoc()): ?>
        <?php
          $active = ((int)$p['is_active'] === 1);
          $hasVar = ((int)$p['has_variants'] === 1);

          $brand = $p['brand_name'] ?: '—';
          $cats  = $p['category_names'] ?: '—';

          $stock = (int)($p['total_stock'] ?? 0);

          // Price logic:
          // - If variants exist -> show min_price and min_discount_price (if on sale)
          // - Else fallback to products.from_price (if any)
          $minPrice = $p['min_price'];
          $minDisc  = $p['min_discount_price'];

          $showPrice = null;
          $showDisc  = null;

          if ($hasVar && $minPrice !== null) {
              $showPrice = (float)$minPrice;
              if ($minDisc !== null) $showDisc = (float)$minDisc;
          } else {
              if ($p['from_price'] !== null) $showPrice = (float)$p['from_price'];
          }
        ?>
        <div class="card">
          <div class="img">
            <?php if (!empty($p['image_path'])): ?>
              <img src="<?php echo h($p['image_path']); ?>" alt="">
            <?php else: ?>
              <span class="muted">No image</span>
            <?php endif; ?>
          </div>

          <div class="body">
            <div class="row">
              <div class="pill <?php echo $active ? 'on' : 'off'; ?>">
                <?php echo $active ? 'Active' : 'Inactive'; ?>
              </div>
              <div class="pill"><?php echo $hasVar ? 'Variants' : 'Single'; ?></div>
            </div>

            <p class="name"><?php echo h($p['name']); ?></p>
            <div class="muted">Brand: <?php echo h($brand); ?></div>
            <div class="muted">Categories: <?php echo h($cats); ?></div>
            <div class="muted">Slug: <?php echo h($p['slug']); ?></div>

            <div class="row" style="margin-top:10px">
              <div class="muted">Stock: <b><?php echo number_format($stock); ?></b></div>
              <div>
                <?php if ($showPrice === null): ?>
                  <span class="muted">No price</span>
                <?php else: ?>
                  <?php if ($showDisc !== null && $showDisc > 0): ?>
                    <span class="muted" style="text-decoration:line-through;margin-right:6px;">
                      <?php echo number_format($showPrice, 2); ?>
                    </span>
                    <span class="price"><?php echo number_format($showDisc, 2); ?></span>
                  <?php else: ?>
                    <span class="price"><?php echo number_format($showPrice, 2); ?></span>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
            </div>

            <div class="actions">
              <a class="btn btn-soft" href="product_edit.php?id=<?php echo (int)$p['id']; ?>">Edit</a>
              <a class="btn btn-soft" href="product_variants.php?product_id=<?php echo (int)$p['id']; ?>">Variants</a>
              <a class="btn btn-soft" href="highlights.php?product_id=<?php echo (int)$p['id']; ?>">Highlights</a>
              <a class="btn btn-soft" href="product-delete.php?id=<?php echo (int)$p['id']; ?>"
                 onclick="return confirm('Delete this product?')">Delete</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

    <div class="pager">
      <?php
        $base = 'products.php?q=' . urlencode($q) . '&page=';
        $prev = max(1, $page - 1);
        $next = min($totalPages, $page + 1);
      ?>
      <a href="<?php echo $base . $prev; ?>">&laquo; Prev</a>

      <?php
        // show up to 7 page buttons
        $start = max(1, $page - 3);
        $end = min($totalPages, $start + 6);
        $start = max(1, $end - 6);
        for ($i=$start; $i<=$end; $i++):
      ?>
        <a class="<?php echo ($i === $page) ? 'active' : ''; ?>" href="<?php echo $base . $i; ?>">
          <?php echo $i; ?>
        </a>
      <?php endfor; ?>

      <a href="<?php echo $base . $next; ?>">Next &raquo;</a>
    </div>

  <?php endif; ?>

</div>
</body>
</html>