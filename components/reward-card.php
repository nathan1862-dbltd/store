<?php
if (!function_exists('h')) {
    function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
}

$cost  = (int)$reward['points_cost'];
$stock = (int)$reward['stock'];
$canRedeem = ($userId > 0 && $balance >= $cost && $stock > 0);
?>
<style>

@media (max-width: 768px) {
  .slide-btn { display: none; }
  .reward-card {
    min-width: 150px;
    max-width: 150px;
  }
}

.slider-wrapper { position: relative; }
.slider {
  display: flex;
  gap: 14px;
  overflow-x: auto;
  scroll-behavior: smooth;
  padding-bottom: 6px;
}
.slider::-webkit-scrollbar { display: none; }

.reward-card {
  position: relative;
  max-width: 300px;
  background: #fff;
  border-radius: 12px;
  padding: 10px;
  flex-shrink: 0;
}

.reward-card:hover {
  box-shadow: 0 12px 28px rgba(0,0,0,0.08);
  transform: translateY(-3px);
}

.reward-card-img {
  width: 100%;
  aspect-ratio: 1 / 1;
  object-fit: cover;
  border-radius: 8px;

}

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
</style> 

<div class="reward-card">

  <div class="reward-card-img">
    <img src="<?= h($reward['image'] ?: '/assets/images/no-image.png') ?>">
  </div>

  <div class="reward-card-body">

    <div class="reward-card-name">
      <?= h($reward['name']) ?>
    </div>

    <div class="reward-card-points">
      <?= $cost ?> pts
    </div>

    <?php if($canRedeem): ?>
      <a href="/add-reward-to-cart.php?id=<?= $reward['id'] ?>"
         class="reward-card-btn">
         Add Reward
      </a>
    <?php else: ?>
      <span class="reward-card-btn disabled">
        <?= $userId ? 'Not enough points' : 'Login to redeem' ?>
      </span>
    <?php endif; ?>

  </div>
</div>