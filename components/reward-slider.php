<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * Reward Slider (self-contained)
 * Usage:
 *   $sliderTitle = "Redeem With Points";
 *   $sliderLimit = 12;
 *   include __DIR__ . "/components/reward-slider.php";
 *
 * Optional overrides:
 *   $sliderId
 *   $sliderTitle
 *   $sliderLimit
 *   $sliderQuery  (custom SQL)
 */

require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../functions.php';

ensureSessionStarted();

if (!isset($sliderId) || $sliderId === '') {
    $sliderId = 'rewardSlider_' . uniqid();
}
if (!isset($sliderTitle)) $sliderTitle = "Rewards";
if (!isset($sliderLimit) || (int)$sliderLimit <= 0) $sliderLimit = 12;

$userId = (int)($_SESSION['user_id'] ?? 0);
$balance = 0;

if ($userId > 0) {
    $stmt = $mysqli->prepare("SELECT COALESCE(SUM(points),0) AS balance FROM user_points WHERE user_id=?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $balance = (int)($stmt->get_result()->fetch_assoc()['balance'] ?? 0);
    $stmt->close();
}

/* Build rewards query */
if (!isset($sliderQuery) || trim((string)$sliderQuery) === '') {
    $sliderQuery = "
        SELECT id, name, description, image, points_cost, stock
        FROM reward_products
        WHERE is_active = 1
        ORDER BY id DESC
        LIMIT " . (int)$sliderLimit;
}

/* Now we DEFINITELY have $products */
$products = $mysqli->query($sliderQuery);
if (!$products || $products->num_rows === 0) {
    return;
}

function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>

<style>

.slider-wrapper {
  position: relative;
  overflow: hidden;
}

.slider {
  display: flex;
  gap: 20px;
  overflow-x: auto;
  scroll-behavior: smooth;
}

.slider::-webkit-scrollbar {
  display: none;
}

.slide-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: #fff;
  border: 1px solid #ddd;
  width: 40px;
  height: 40px;
  border-radius: 10px;
  cursor: pointer;
}

.slide-btn.prev { left: -10px; }
.slide-btn.next { right: -10px; }

/* Inline slider essentials (prevents “flat” issue) */
.slider-section{margin:28px 0}
.section-title{font-size:20px;font-weight:900;margin:0 0 12px 0}
.slider-wrapper{position:relative;overflow:hidden}
.slider{display:flex;gap:16px;overflow-x:auto;scroll-behavior:smooth;padding:4px 2px}
.slider::-webkit-scrollbar{display:none}
.slide-btn{
  position:absolute;top:50%;transform:translateY(-50%);
  width:40px;height:40px;border-radius:12px;
  border:1px solid #e5e7eb;background:#fff;cursor:pointer;z-index:5
}
.slide-btn.prev{left:-8px}
.slide-btn.next{right:-8px}
.slide-btn:hover{background:#111827;color:#fff;border-color:#111827}
.slider-empty{padding:18px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;color:#6b7280}

.reward-card-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.reward-card-body {
  padding: 16px;
  display: flex;
  flex-direction: column;
  gap: 10px;
  flex-grow: 1;
}

.reward-card-name {
  font-size: 15px;
  font-weight: 700;
}

.reward-card-desc {
  font-size: 12px;
  color: #6b7280;
  min-height: 36px;
}

.reward-card-points {
  font-weight: 800;
  font-size: 14px;
}

.reward-card-stock {
  font-size: 12px;
  color: #6b7280;
}

.reward-card-btn {
  margin-top: auto;
  padding: 10px;
  text-align: center;
  border-radius: 10px;
  font-size: 13px;
  font-weight: 700;
  text-decoration: none;
  border: 1px solid #111827;
  background: #111827;
  color: #fff;
}

.reward-card-btn.disabled {
  background: #9ca3af;
  border-color: #9ca3af;
  pointer-events: none;

@media(max-width:520px){.reward-card{min-width:180px;max-width:180px}}
</style>

<section style="margin:30px 0;">

  <?php if (!empty($sliderTitle)): ?>
    <h2 style="font-size:22px;font-weight:800;margin-bottom:15px;">
      <?= h($sliderTitle) ?>
    </h2>
  <?php endif; ?>

  <div style="position:relative;">

    <!-- PREV -->
    <button
      type="button"
      aria-label="Previous"
      onclick="slide('<?= h($sliderId) ?>', -1)"
      style="
        position:absolute;
        left:-5px;
        top:50%;
        transform:translateY(-50%);
        z-index:10;
        width:40px;
        height:40px;
        border-radius:10px;
        border:1px solid #ddd;
        background:#fff;
        cursor:pointer;
      ">
      ❮
    </button>

    <!-- SLIDER -->
    <div
      id="<?= h($sliderId) ?>"
      style="
        display:flex;
        gap:16px;
        overflow-x:auto;
        scroll-behavior:smooth;
        padding:5px 0;
      "
    >

      <?php while ($reward = $products->fetch_assoc()): ?>
        <?php
          $cost  = (int)($reward['points_cost'] ?? 0);
          $stock = (int)($reward['stock'] ?? 0);
          $canRedeem = ($userId > 0 && $balance >= $cost && $stock > 0);
        ?>

        <div style="min-width:200px; max-width:200px;">
          <?php include __DIR__ . '/product-sliders/reward-card.php'; ?>
        </div>

      <?php endwhile; ?>

    </div>

    <!-- NEXT -->
    <button
      type="button"
      aria-label="Next"
      onclick="slide('<?= h($sliderId) ?>', 1)"
      style="
        position:absolute;
        right:-5px;
        top:50%;
        transform:translateY(-50%);
        z-index:10;
        width:40px;
        height:40px;
        border-radius:10px;
        border:1px solid #ddd;
        background:#fff;
        cursor:pointer;
      ">
      ❯
    </button>

  </div>

</section>
