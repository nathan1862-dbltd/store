<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/header.php';

$categoryId = (int)($_GET['id'] ?? 0);
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 12;
$offset = ($page - 1) * $limit;

/* Get category */
$stmt = $mysqli->prepare("
    SELECT id, name
    FROM categories
    WHERE id=?
    LIMIT 1
");
$stmt->bind_param("i", $categoryId);
$stmt->execute();
$category = $stmt->get_result()->fetch_assoc();

if (!$category) {
    echo "<div class='container'><h2>Category not found</h2></div>";
    require_once __DIR__ . '/footer.php';
    exit;
}

/* Count products */
$stmt = $mysqli->prepare("
    SELECT COUNT(*) as total
    FROM product_categories pc
    JOIN products p ON pc.product_id = p.id
    WHERE pc.category_id=?
");
$stmt->bind_param("i", $categoryId);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

/* Fetch products */
$stmt = $mysqli->prepare("
    SELECT p.*, b.name AS brand_name
    FROM product_categories pc
    JOIN products p ON pc.product_id = p.id
    LEFT JOIN brands b ON p.brand_id = b.id
    WHERE pc.category_id=?
    ORDER BY p.created_at DESC
    LIMIT ? OFFSET ?
");
$stmt->bind_param("iii", $categoryId, $limit, $offset);
$stmt->execute();
$products = $stmt->get_result();
?>

<div class="container page-container">

    <h1 class="page-title">
        <?= htmlspecialchars($category['name']); ?>
    </h1>

    <?php if ($products->num_rows > 0): ?>
        <div class="product-grid">
            <?php while ($product = $products->fetch_assoc()): ?>
                <?php include __DIR__ . '/components/product-card.php'; ?>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No products found in this category.</p>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>