<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/header.php';

$brandId = (int)($_GET['id'] ?? 0);
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 12;
$offset = ($page - 1) * $limit;

/* Fetch Brand Info */
$stmt = $mysqli->prepare("
    SELECT id, name, logo
    FROM brands
    WHERE id=?
    LIMIT 1
");
$stmt->bind_param("i", $brandId);
$stmt->execute();
$brand = $stmt->get_result()->fetch_assoc();

if (!$brand) {
    echo "<div class='container'><h2>Brand not found</h2></div>";
    require_once __DIR__ . '/footer.php';
    exit;
}

/* Count Products */
$stmt = $mysqli->prepare("
    SELECT COUNT(*) as total
    FROM products
    WHERE brand_id=?
");
$stmt->bind_param("i", $brandId);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

/* Fetch Products */
$stmt = $mysqli->prepare("
    SELECT p.*, b.name AS brand_name
    FROM products p
    LEFT JOIN brands b ON p.brand_id = b.id
    WHERE p.brand_id=?
    ORDER BY p.created_at DESC
    LIMIT ? OFFSET ?
");
$stmt->bind_param("iii", $brandId, $limit, $offset);
$stmt->execute();
$products = $stmt->get_result();
?>
<style>

.brand-header {
    text-align: center;
    margin-bottom: 40px;
}

.brand-logo {
    max-height: 80px;
    margin-bottom: 20px;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}

@media(max-width: 768px) {
    .product-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

</style>

<div class="container page-container">

    <div class="brand-header">
        <?php if (!empty($brand['logo'])): ?>
            <img src="<?= htmlspecialchars($brand['logo']); ?>" class="brand-logo">
        <?php endif; ?>

        <h1 class="page-title">
            <?= htmlspecialchars($brand['name']); ?>
        </h1>
    </div>

    <?php if ($products->num_rows > 0): ?>
        <div class="product-grid">
            <?php while ($product = $products->fetch_assoc()): ?>
                <?php include __DIR__ . '/components/product-card.php'; ?>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No products under this brand yet.</p>
    <?php endif; ?>

    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?id=<?= $brandId ?>&page=<?= $i ?>"
                   class="<?= ($i == $page) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>