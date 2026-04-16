<?php
if (!isset($mysqli)) return;

$currentSlug = $_GET['slug'] ?? '';

$result = $mysqli->query("
    SELECT id, name, slug 
    FROM categories
    ORDER BY name ASC
");

if (!$result || $result->num_rows == 0) return;
?>

<section class="category-slider-wrapper">
    
        <div class="category-slider" id="categorySlider">

            <?php while ($cat = $result->fetch_assoc()): ?>
                <?php
                    $isActive = ($currentSlug === $cat['slug']) ? 'active' : '';
                ?>
                <a href="/V3/category.php?slug=<?= htmlspecialchars($cat['slug']) ?>"
                   class="category-pill <?= $isActive ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
            <?php endwhile; ?>

        </div>
</section>