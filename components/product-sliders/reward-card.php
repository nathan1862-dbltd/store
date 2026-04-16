<?php
if (!function_exists('h')) {
    function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
}

$cost  = (int)$reward['points_cost'];
$stock = (int)$reward['stock'];
$canRedeem = ($userId > 0 && $balance >= $cost && $stock > 0);
?>

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