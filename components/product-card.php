<?php
/* Safety fallback */
if (!isset($product) || !is_array($product)) return;

/* Normalize product fields */
$productId   = $product['id'] ?? 0;
$productName = $product['name'] ?? $product['product_name'] ?? 'Product';
$productImage = $product['image_path'] ?? $product['image'] ?? 'assets/images/no-image.png';
$brandName   = $product['brand_name'] ?? $product['brand'] ?? null;

/* Price logic */
$price = 0;
if (isset($product['from_price'])) {
    $price = (float)$product['from_price'];
} elseif (isset($product['price'])) {
    $price = (float)$product['price'];
}

/* Sale badge */
$showSaleBadge = false;
if (!empty($product['is_on_sale'])) {
    $showSaleBadge = true;
}
?>

<div class="product-card">

    <?php if ($showSaleBadge): ?>
        <span class="pc-badge pc-sale">SALE</span>
    <?php endif; ?>

    <a href="/V3/product.php?id=<?= (int)$productId ?>">

        <div class="pc-image">
            <img src="<?= htmlspecialchars($productImage) ?>"
                 alt="<?= htmlspecialchars($productName) ?>">
        </div>

        <?php if ($brandName): ?>
            <div class="brand">
                <?= htmlspecialchars($brandName) ?>
            </div>
        <?php endif; ?>

        <h3 class="product-name">
            <?= htmlspecialchars($productName) ?>
        </h3>

        <?php if ($price > 0): ?>
            <div class="price">
                From <?= number_format($price, 0) ?> Ks
            </div>
        <?php endif; ?>

    </a>

</div>