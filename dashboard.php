<?php

ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/functions.php';

ensureSessionStarted();
redirectIfNotLoggedIn();
require_once __DIR__ . '/header.php';

$userId = (int)$_SESSION['user_id'];

// Prefer username from session/db
$name = (string)($_SESSION['username'] ?? '');
if ($name === '') {
  $u = getUserById($mysqli, $userId);
  $name = $u['username'] ?? ('user_' . $userId);
}

$initial = strtoupper(substr($name, 0, 1));

// Loyalty (ledger)
$points   = loyalty_balance($mysqli, $userId);
$expiring = loyalty_expiring_soon($mysqli, $userId);


?>

<style>
/* PAGE */
.account-page{
  margin:0;
  background:#f6f6f6;
  font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Arial,sans-serif;
  color:#111;
}

/* HEADER */
.acc-header{
  background:#fff;
  padding:16px;
  display:flex;
  align-items:flex-start;
  gap:12px;
  border-bottom:1px solid #e5e5e5;
}
.acc-avatar{
  width:44px;height:44px;
  border-radius:50%;
  background:#eee;
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight:700;
}
.acc-head-text{flex:1;min-width:0}
.acc-head-text strong{font-size:16px;display:block}

/* LOYALTY MINI CARD */
.loyalty-mini{
  margin-top:10px;
  padding:12px 14px;
  border:1px solid #eee;
  border-radius:14px;
  background:#fafafa;
}
.loyalty-row{
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:10px;
}
.loyalty-title{
  font-size:12px;
  color:#666;
  font-weight:700;
  text-transform:uppercase;
  letter-spacing:.2px;
}
.loyalty-points{
  font-size:18px;
  font-weight:900;
}
.loyalty-expiring{
  margin-top:6px;
  font-size:12px;
  color:#c62828;
  font-weight:700;
}
.loyalty-actions{
  margin-top:10px;
  display:flex;
  gap:10px;
}
.loyalty-actions a{
  flex:1;
  text-align:center;
  padding:10px 12px;
  border-radius:12px;
  text-decoration:none;
  font-weight:800;
  font-size:13px;
}
.btn-dark{background:#111;color:#fff}
.btn-light{background:#fff;color:#111;border:1px solid #ddd}

/* LIST */
.acc-list{
  background:#fff;
  margin-top:10px;
  border-top:1px solid #eaeaea;
  border-bottom:1px solid #eaeaea;
}
.acc-item{
  display:flex;
  align-items:center;
  gap:14px;
  padding:16px;
  border-bottom:1px solid #eee;
  text-decoration:none;
  color:#111;
}
.acc-item:last-child{border-bottom:none}
.acc-icon{width:22px;display:flex;align-items:center;justify-content:center}
.acc-icon svg{width:18px;height:18px;color:#111}
.acc-item-title{font-weight:700}
.acc-item-sub{font-size:13px;color:#666;margin-top:2px}

/* SECTION GAP */
.acc-spacer{height:14px}
</style>

<div class="account-page">

  <!-- PROFILE HEADER -->
  <div class="acc-header">
    <div class="acc-avatar"><?= e($initial) ?></div>

    <div class="acc-head-text">
      <strong>Good morning, <?= e($name) ?> ☀️</strong> </div>
  </div>  
  <div class="loyalty-row">
  <div class="loyalty-title">Loyalty Points</div>
  <div class="loyalty-points"><?= number_format($points) ?></div>
</div>


  <!-- ACTION LIST -->
  <div class="acc-list">

    <a href="rewards.php" class="acc-item">
      <div class="acc-icon">
        <!-- your svg -->
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path fill="currentColor" d="M385.5 132.8C393.1 119.9 406.9 112 421.8 112L424 112C446.1 112 464 129.9 464 152C464 174.1 446.1 192 424 192L350.7 192L385.5 132.8zM254.5 132.8L289.3 192L216 192C193.9 192 176 174.1 176 152C176 129.9 193.9 112 216 112L218.2 112C233.1 112 247 119.9 254.5 132.8zM344.1 108.5L320 149.5L295.9 108.5C279.7 80.9 250.1 64 218.2 64L216 64C167.4 64 128 103.4 128 152C128 166.4 131.5 180 137.6 192L96 192C78.3 192 64 206.3 64 224L64 256C64 273.7 78.3 288 96 288L544 288C561.7 288 576 273.7 576 256L576 224C576 206.3 561.7 192 544 192L502.4 192C508.5 180 512 166.4 512 152C512 103.4 472.6 64 424 64L421.8 64C389.9 64 360.3 80.9 344.1 108.4zM544 336L344 336L344 544L480 544C515.3 544 544 515.3 544 480L544 336zM296 336L96 336L96 480C96 515.3 124.7 544 160 544L296 544L296 336z"/></svg>
      </div>
      <div>
        <div class="acc-item-title">Rewards</div>
        <div class="acc-item-sub">Redeem items & perks</div>
      </div>
    </a>

    <a href="my_orders.php" class="acc-item">
      <div class="acc-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path fill="currentColor" d="M560.3 301.2C570.7 313 588.6 315.6 602.1 306.7C616.8 296.9 620.8 277 611 262.3L563 190.3C560.2 186.1 556.4 182.6 551.9 180.1L351.4 68.7C332.1 58 308.6 58 289.2 68.7L88.8 180C83.4 183 79.1 187.4 76.2 192.8L27.7 282.7C15.1 306.1 23.9 335.2 47.3 347.8L80.3 365.5L80.3 418.8C80.3 441.8 92.7 463.1 112.7 474.5L288.7 574.2C308.3 585.3 332.2 585.3 351.8 574.2L527.8 474.5C547.9 463.1 560.2 441.9 560.2 418.8L560.2 301.3zM320.3 291.4L170.2 208L320.3 124.6L470.4 208L320.3 291.4zM278.8 341.6L257.5 387.8L91.7 299L117.1 251.8L278.8 341.6z"/></svg>
      </div>
      <div>
        <div class="acc-item-title">My Orders</div>
        <div class="acc-item-sub">View & track purchases</div>
      </div>
    </a>

  </div>

  <div class="acc-spacer"></div>

  <div class="acc-list">

    <a href="offers.php" class="acc-item">
      <div class="acc-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path fill="currentColor" d="M288 192C288 139 245 96 192 96C139 96 96 139 96 192C96 245 139 288 192 288C245 288 288 245 288 192zM544 448C544 395 501 352 448 352C395 352 352 395 352 448C352 501 395 544 448 544C501 544 544 501 544 448zM534.6 150.6C547.1 138.1 547.1 117.8 534.6 105.3C522.1 92.8 501.8 92.8 489.3 105.3L105.3 489.3C92.8 501.8 92.8 522.1 105.3 534.6C117.8 547.1 138.1 547.1 150.6 534.6L534.6 150.6z"/></svg>
      </div>
      <div>
        <div class="acc-item-title">Special Offers</div>
        <div class="acc-item-sub">Exclusive deals for you</div>
      </div>
    </a>

    <a href="loyalty.php" class="acc-item">
      <div class="acc-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path fill="currentColor" d="M320 64c141.4 0 256 93.1 256 208s-114.6 208-256 208S64 386.9 64 272 178.6 64 320 64zm0 64c-94.1 0-170.7 64.5-170.7 144S225.9 416 320 416s170.7-64.5 170.7-144S414.1 128 320 128z"/></svg>
      </div>
      <div>
        <div class="acc-item-title">Loyalty Dashboard</div>
        <div class="acc-item-sub">Points history & expiry</div>
      </div>
    </a>

    <a href="support.php" class="acc-item">
      <div class="acc-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path fill="currentColor" d="M64 416L64 192C64 139 107 96 160 96L480 96C533 96 576 139 576 192L576 416C576 469 533 512 480 512L360 512C354.8 512 349.8 513.7 345.6 516.8L230.4 603.2C226.2 606.3 221.2 608 216 608C202.7 608 192 597.3 192 584L192 512L160 512C107 512 64 469 64 416z"/></svg>
      </div>
      <div>
        <div class="acc-item-title">Customer Service</div>
        <div class="acc-item-sub">Help, returns & support</div>
      </div>
    </a>

  </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
