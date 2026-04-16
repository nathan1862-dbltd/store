<?php
// Rewards Slider Component
// Requires: $mysqli, functions.php

require_once __DIR__ . '/../../functions.php';
ensureSessionStarted();

if (!isset($sliderTitle)) $sliderTitle = "Redeem With Points";
if (!isset($sliderLimit)) $sliderLimit = 12;

$userId = $_SESSION['user_id'] ?? 0;
$balance = 0;

if ($userId) {
    $stmt = $mysqli->prepare("
        SELECT COALESCE(SUM(points),0) AS balance
        FROM user_points
        WHERE user_id = ?
    ");
    $stmt->bind_param("i",$userId);
    $stmt->execute();
    $balance = (int)$stmt->get_result()->fetch_assoc()['balance'];
    $stmt->close();
}

$query = "
    SELECT *
    FROM reward_products
    WHERE is_active = 1 AND stock > 0
    ORDER BY id DESC
    LIMIT {$sliderLimit}
";

$result = $mysqli->query($query);
if (!$result || $result->num_rows === 0) return;
?>

<link rel="stylesheet" href="/components/product-sliders/product-slider.css">
<link rel="stylesheet" href="/components/rewards/reward-display.css">

<div class="product-slider-wrapper reward-slider-wrapper">

  <div class="ps-header">
    <h2 class="ps-title"><?= htmlspecialchars($sliderTitle) ?></h2>
    <?php if ($userId): ?>
      <div class="ps-meta">Your Points: <?= $balance ?> pts</div>
    <?php endif; ?>
  </div>

  <button class="ps-nav prev">&#10094;</button>

  <div class="product-slider-viewport">
    <div class="product-slider-track">

      <?php while ($reward = $result->fetch_assoc()): ?>
        <div class="product-slide">
          <?php
            // Make available to reward-card.php
            $p = null;
            include __DIR__ . '/../product-sliders/reward-card.php';
          ?>
        </div>
      <?php endwhile; ?>

    </div>
  </div>

  <button class="ps-nav next">&#10095;</button>

</div>

<script src="/components/product-sliders/product-slider.js"></script>