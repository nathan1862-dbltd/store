<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userId    = $_SESSION['user_id'] ?? null;
$sessionId = session_id();

$cartCount = 0;

/* ---------- FIND CART ---------- */
$stmt = $mysqli->prepare("
    SELECT id FROM carts
    WHERE (user_id = ? AND ? IS NOT NULL)
       OR (session_id = ? AND ? IS NULL)
    LIMIT 1
");
$stmt->bind_param("isis", $userId, $userId, $sessionId, $userId);
$stmt->execute();
$cart = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($cart) {
    $cartId = (int)$cart['id'];

    /* ---------- SUM QUANTITIES ---------- */
    $stmt = $mysqli->prepare("
        SELECT COALESCE(SUM(quantity), 0) AS total
        FROM cart_items
        WHERE cart_id = ?
    ");
    $stmt->bind_param("i", $cartId);
    $stmt->execute();
    $cartCount = (int)$stmt->get_result()->fetch_assoc()['total'];
    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>DB Stores</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="/assets/css/storefront-main.css">
<link rel="stylesheet" href="components/product-sliders/product-slider.css">

<style>
/* STICKY HEADER */
.site-header {
  position: sticky;
  top: 0;
  z-index: 1000;
  background: #fff;
  border-bottom: 1px solid #eee;
}

/* HEADER LAYOUT */
.header-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 12px 20px;
  display: flex;
  align-items: center;
  gap: 20px;
}

/* LOGO */
.logo a {
  font-size: 1.1em;
  font-weight: 600;
  color: #111;
  text-decoration: none;
  white-space: nowrap;
}

/* SEARCH */
.search-bar {
  flex: 1;
  position: relative;
}

.search-bar input {
  width: 100%;
  padding: 10px 14px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 0.95em;
}
.search-results{
  position:absolute;top:110%;left:0;right:0;
  background:#fff;border:1px solid #eee;
  max-height:300px;overflow-y:auto;
  display:none;z-index:2000
}
.search-results a{
  display:block;
  padding:10px 14px;
  border-bottom:1px solid #f1f1f1;
  text-decoration:none;
  color:#111;
  font-size:.9em
}
.search-results a:hover{background:#f7f7f7}
.search-results .section-title{
  padding:6px 14px;
  font-size:.75em;
  font-weight:700;
  color:#777;
  background:#fafafa
}

/* CART */
.header-cart {
  position: relative;
}

.header-cart a {
  display: inline-flex;
  align-items: center;
  color: #111;
  text-decoration: none;
}

/* CART SVG */
.header-cart svg {
  width: 30px;
  height: 30px;
  display: block;
}
 
/* CART BADGE */
.cart-badge {
  position: absolute;
  top: -6px;
  right: -10px;
  background: #d00;
  color: #fff;
  font-size: 0.7em;
  padding: 2px 6px;
  border-radius: 50%;
  line-height: 1;
}

/* MOBILE */
@media (max-width: 768px) {
  .logo a { font-size: 1em; }
}
</style>

</head>

<body>

<header class="site-header">
  <div class="header-container">

    <!-- LOGO -->
    <div class="logo">
      <a href="index.php">DELUX BEAUTi</a>
    </div>

    <!-- SEARCH -->
    <div class="search-bar">
      <input type="text" id="searchInput" placeholder="Search products, brands, categoriesâ¦">
      <div id="searchResults" class="search-results"></div>
    </div>
 
    <!-- CART -->
    <div class="header-cart">
      <a href="cart.php" aria-label="Cart">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" aria-hidden="true">
          <path fill="currentColor" d="M24-16C10.7-16 0-5.3 0 8S10.7 32 24 32l45.3 0c3.9 0 7.2 2.8 7.9 6.6l52.1 286.3c6.2 34.2 36 59.1 70.8 59.1L456 384c13.3 0 24-10.7 24-24s-10.7-24-24-24l-255.9 0c-11.6 0-21.5-8.3-23.6-19.7l-5.1-28.3 303.6 0c30.8 0 57.2-21.9 62.9-52.2L568.9 69.9C572.6 50.2 557.5 32 537.4 32l-412.7 0-.4-2c-4.8-26.6-28-46-55.1-46L24-16zM208 512a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm224 0a48 48 0 1 0 0-96 48 48 0 1 0 0 96z"/>
        </svg>
      </a>

      <?php if ($cartCount > 0): ?>
    <span class="cart-badge"><?= $cartCount ?></span>
      <?php endif; ?>
    </div>

  </div>
</header>

<script>
// LIVE AJAX SEARCH
const input = document.getElementById("searchInput");
const resultsBox = document.getElementById("searchResults");
let timeout = null;

input.addEventListener("keyup", function () {
  clearTimeout(timeout);
  const query = this.value.trim();

  if (query.length < 2) {
    resultsBox.style.display = "none";
    return;
  }

  timeout = setTimeout(() => {
    fetch("search_ajax.php?q=" + encodeURIComponent(query))
      .then(res => res.text())
      .then(data => {
        resultsBox.innerHTML = data;
        resultsBox.style.display = "block";
      });
  }, 300);
});

document.addEventListener("click", function(e) {
  if (!e.target.closest(".search-bar")) {
    resultsBox.style.display = "none";
  }
});
</script>
