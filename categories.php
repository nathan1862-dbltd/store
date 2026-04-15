<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/header.php';

/* Fetch categories */
$categories = $mysqli->query("
    SELECT id, name 
    FROM categories 
    ORDER BY name ASC
");
?>

<div class="container page-container">

<?php while ($cat = $categories->fetch_assoc()): ?>

    <?php
    $categoryId = (int)$cat['id'];

    /* Fetch 4 products per category */
    $stmt = $mysqli->prepare("
        SELECT * FROM product_categories
        WHERE category_id=?
        ORDER BY created_at DESC
        LIMIT 4
    ");
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $products = $stmt->get_result();
    ?>

    <?php if ($products->num_rows > 0): ?>
        <div class="section-header">
            <h2><?= htmlspecialchars($cat['name']); ?></h2>
            <a href="category.php?id=<?= $categoryId; ?>">View All</a>
        </div>

        <div class="product-grid">
            <?php while ($product = $products->fetch_assoc()): ?>
                <?php include __DIR__ . '/components/product-card.php'; ?>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

<?php endwhile; ?>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>