<?php
/**
 * SLIDER WRAPPER
 * Requires:
 * - $sliderId
 * - $sliderTitle
 * - $products (mysqli_result)
 */
?>

<section class="section slider-section">

    <?php if (!empty($sliderTitle)): ?>
        <h2 class="section-title">
            <?php echo htmlspecialchars($sliderTitle); ?>
        </h2>
    <?php endif; ?>

    <div class="slider-wrapper">

        <!-- PREV -->
        <button
            class="slide-btn prev"
            type="button"
            aria-label="Previous"
            onclick="slide('<?php echo $sliderId; ?>', -1)"
        >
            ❮
        </button>

        <!-- SLIDER -->
        <div class="slider" id="<?php echo $sliderId; ?>">

            <?php if ($products && $products->num_rows > 0): ?>

                <?php while ($card = $products->fetch_assoc()): ?>

                    <?php
                        /* Pass card → product */
                        $product = $card;

                        /* Badge logic */
                        $showSaleBadge =
                            !empty($product['has_sale_badge']) &&
                            (int)$product['has_sale_badge'] === 1;

                        include __DIR__ . '/product-card.php';
                    ?>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="slider-empty">
                    No products available
                </div>

            <?php endif; ?>

        </div>

        <!-- NEXT -->
        <button
            class="slide-btn next"
            type="button"
            aria-label="Next"
            onclick="slide('<?php echo $sliderId; ?>', 1)"
        >
            ❯
        </button>

    </div>

</section>