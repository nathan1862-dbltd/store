<?php
// Stand-alone Product Slider
// Dependency: product-card.php + $mysqli

if (!isset($sliderTitle)) $sliderTitle = "Products";
if (!isset($sliderLimit)) $sliderLimit = 12;
if (!isset($sliderQuery)) {
    $sliderQuery = "SELECT * FROM products ORDER BY created_at DESC LIMIT {$sliderLimit}";
}

$result = $mysqli->query($sliderQuery);
if (!$result || $result->num_rows === 0) return;
?>

<link rel="stylesheet" href="/components/product-sliders/product-slider.css">

<div class="product-slider-wrapper">
  <button class="ps-nav prev">&#10094;</button>

  <!-- 🔑 THIS WRAPPER WAS MISSING -->
  <div class="product-slider-viewport">
    <div class="product-slider-track">
      <?php while ($product = $result->fetch_assoc()): ?>
        <div class="product-slide">
          <?php
            $p = $product; // required by product-card.php
            include __DIR__ . '/../product-card.php';
          ?>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <button class="ps-nav next">&#10095;</button>
</div>

