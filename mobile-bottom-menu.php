<!-- ================= MOBILE BOTTOM STICKY MENU ================= -->
<nav class="mobile-bottom-menu">

  <!-- Home -->
  <a href="index.php" class="menu-item">
    <svg viewBox="0 0 512 512"><path fill="currentColor" d="M277.8 8.6c-12.3-11.4-31.3-11.4-43.5 0l-224 208c-9.6 9-12.8 22.9-8 35.1S18.8 272 32 272l16 0 0 176c0 35.3 28.7 64 64 64l288 0c35.3 0 64-28.7 64-64l0-176 16 0c13.2 0 25-8.1 29.8-20.3s1.6-26.2-8-35.1l-224-208zM240 320l32 0c26.5 0 48 21.5 48 48l0 96-128 0 0-96c0-26.5 21.5-48 48-48z"/></svg>
    <span>Home</span>
  </a>

  <!-- Shop -->
  <a href="javascript:void(0)" class="menu-item" onclick="openShopSheet()">
    <svg viewBox="0 0 640 640"><path fill="currentColor" d="M94.7 136.3C101.6 112.4 123.5 96 148.4 96L492.4 96C517.3 96 539.2 112.4 546.2 136.3L569.6 216.5C582.4 260.2 549.5 304 504 304C477.7 304 454.6 289.1 443.2 266.9C431.6 288.8 408.6 304 381.8 304C355.2 304 332.1 289 320.5 267C308.9 289 285.8 304 259.2 304C232.4 304 209.4 288.9 197.8 266.9C186.4 289 163.3 304 137 304C91.4 304 58.6 260.3 71.4 216.5L94.7 136.3zM160.4 416L480.4 416L480.4 349.6C488 351.2 495.9 352 503.9 352C518.2 352 531.9 349.4 544.4 344.8L544.4 496C544.4 522.5 522.9 544 496.4 544L144.4 544C117.9 544 96.4 522.5 96.4 496L96.4 344.8C108.9 349.4 122.5 352 136.9 352C145 352 152.8 351.2 160.4 349.6L160.4 416z"/></svg>
    <span>Shop</span>
  </a>

  <!-- Cart -->
  <a href="cart.php" class="menu-item cart-link">
    <svg viewBox="0 0 448 512"><path fill="currentColor" d="M192 128a96 96 0 1 0 -192 0 96 96 0 1 0 192 0zM448 384a96 96 0 1 0 -192 0 96 96 0 1 0 192 0zM438.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-384 384c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l384-384z"/></svg>
    <span>Cart</span>
    <span class="cart-count-bubble">
      <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
    </span>
  </a>

  <!-- Account -->
  <a href="dashboard.php" class="menu-item">
    <svg viewBox="0 0 448 512"><path fill="currentColor" d="M224 248a120 120 0 1 0 0-240 120 120 0 1 0 0 240zm-29.7 56C95.8 304 16 383.8 16 482.3 16 498.7 29.3 512 45.7 512l356.6 0c16.4 0 29.7-13.3 29.7-29.7 0-98.5-79.8-178.3-178.3-178.3l-59.4 0z"/></svg>
    <span>Account</span>
  </a>
  
  <!-- AI SKIN ANALYSIS -->
  <a href="beauty-ai/chat.html" class="menu-item">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path fill="currentColor" d="M64 416L64 192C64 139 107 96 160 96L480 96C533 96 576 139 576 192L576 416C576 469 533 512 480 512L360 512C354.8 512 349.8 513.7 345.6 516.8L230.4 603.2C226.2 606.3 221.2 608 216 608C202.7 608 192 597.3 192 584L192 512L160 512C107 512 64 469 64 416z"/></svg>
    <span>Account</span>
  </a>

</nav>

<!-- ================= SHOP SLIDE-UP SHEET ================= -->
<div id="shopSheetOverlay" class="shop-sheet-overlay">
  <div class="shop-sheet-panel">
    <button class="shop-sheet-close" onclick="closeShopSheet()">✕</button>
    <iframe src="categories.php" class="shop-sheet-frame"></iframe>
  </div>
</div>

<style>
.mobile-bottom-menu{display:none}
@media(max-width:768px){
  .mobile-bottom-menu{
    position:fixed;bottom:0;left:0;width:100%;height:64px;
    background:#fff;border-top:1px solid #eee;
    display:flex;justify-content:space-around;align-items:center;
    z-index:9999;
  }
  .menu-item{flex:1;text-align:center;text-decoration:none;color:#555;font-size:11px;position:relative}
  .menu-item svg{width:22px;height:22px;display:block;margin:auto auto 4px}
  .cart-count-bubble{
    position:absolute;top:4px;right:30%;
    background:#e60023;color:#fff;font-size:10px;
    padding:2px 6px;border-radius:12px;font-weight:bold
  }
  body{padding-bottom:70px}

  /* Move Chatbase widget above bottom menu */
  iframe[src*="chatbase"] {
    bottom: 80px !important;
    z-index: 99999 !important;
  }
}

/* ================= SHOP SLIDE-UP ================= */
.shop-sheet-overlay{
  position:fixed;
  inset:0;
  background:rgba(0,0,0,.45);
  display:none;
  z-index:10000;
}

.shop-sheet-overlay.active{
  display:block;
}

.shop-sheet-panel{
  position:absolute;
  bottom:-100%;
  left:0;
  width:100%;
  height:90%;
  background:#fff;
  border-radius:18px 18px 0 0;
  transition:bottom .35s ease;
  overflow:hidden;
}

.shop-sheet-overlay.active .shop-sheet-panel{
  bottom:0;
}

.shop-sheet-frame{
  width:100%;
  height:100%;
  border:0;
}

.shop-sheet-close{
  position:absolute;
  top:10px;
  right:16px;
  background:none;
  border:0;
  font-size:20px;
  z-index:5;
  cursor:pointer;
}
</style>
