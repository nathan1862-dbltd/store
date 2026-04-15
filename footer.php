<?php
// footer.php
?>

<!-- ================= FOOTER ================= -->
<footer class="site-footer">
  <div class="footer-wrap">

    <div class="footer-grid">

      <!-- Brand -->
      <div class="footer-col">
        <div class="footer-logo">DB Stores</div>
        <p class="footer-text">
          Your one-stop destination for curated beauty and skincare essentials.
        </p>
      </div>
 
      <!-- Shop -->
      <div class="footer-col footer-accordion">
        <button class="footer-toggle" type="button">
          <span>Shop</span>
          <span class="toggle-icon">+</span>
        </button>

        <ul class="footer-links">
          <li class="submenu-title">Browse</li>
          <ul class="footer-submenu">
            <li><a href="categories.php">Categories</a></li>
            <li><a href="brands.php">Brands</a></li>
            <li><a href="offers.php">Offers</a></li>
          </ul>

          <li class="submenu-title">Collections</li>
          <ul class="footer-submenu">
            <li><a href="best-sellers.php">Best Sellers</a></li>
            <li><a href="featured.php">Featured</a></li>
          </ul>
        </ul>
      </div>

      <!-- Support -->
      <div class="footer-col footer-accordion">
        <button class="footer-toggle" type="button">
          <span>Support</span>
          <span class="toggle-icon">+</span>
        </button>

        <ul class="footer-links">
          <li class="submenu-title">Help</li>
          <ul class="footer-submenu">
            <li><a href="contact.php">Contact</a></li>
            <li><a href="faq.php">FAQ</a></li>
          </ul>

          <li class="submenu-title">Orders</li>
          <ul class="footer-submenu">
            <li><a href="shipping.php">Shipping</a></li>
            <li><a href="returns.php">Returns</a></li>
            <li><a href="track-order.php">Track Order</a></li>
          </ul>
        </ul>
      </div>

      <!-- Newsletter -->
      <div class="footer-col footer-accordion">
        <button class="footer-toggle" type="button">
          <span>Stay in the loop</span>
          <span class="toggle-icon">+</span>
        </button>

        <div class="footer-inner">
          <p class="footer-text">Get new arrivals and exclusive offers.</p>
          <form class="footer-form">
            <input type="email" placeholder="Email address" required>
            <button type="submit">Subscribe</button>
          </form>
        </div>
      </div>

    </div>

    <div class="footer-bottom">
      <span>© <?php echo date('Y'); ?> DB Stores. All rights reserved.</span>
      <div class="footer-legal">
        <a href="privacy.php">Privacy</a>
        <a href="terms.php">Terms</a>
      </div>
    </div>

  </div>
</footer>

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
   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--! Font Awesome Free 7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2025 Fonticons, Inc. --><path fill="currentColor" d="M64 416L64 192C64 139 107 96 160 96L480 96C533 96 576 139 576 192L576 416C576 469 533 512 480 512L360 512C354.8 512 349.8 513.7 345.6 516.8L230.4 603.2C226.2 606.3 221.2 608 216 608C202.7 608 192 597.3 192 584L192 512L160 512C107 512 64 469 64 416z"/></svg>
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

<!-- ================= STYLES ================= -->
<style>
.site-footer{background:#111;color:#eee}
.footer-wrap{max-width:1200px;margin:auto;padding:40px 20px}
.footer-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:24px}
.footer-logo{font-size:1.4em;font-weight:700}
.footer-text{color:#bbb}

/* Toggle title */
.footer-toggle{
  width:100%;
  background:none;
  border:0;
  color:#fff;
  font-size:1.1em;
  font-weight:700;
  padding:14px 0;
  display:flex;
  justify-content:space-between;
  align-items:center;
  border-bottom:1px solid #333;
  cursor:pointer;
}

.toggle-icon{
  font-size:1.4em;
  font-weight:400;
  transition:transform .2s ease;
}

/* Accordion state */
.footer-accordion.active .toggle-icon{
  content:"−";
  transform:rotate(45deg);
}

/* Links */
.footer-links{list-style:none;padding:0;margin:14px 0}
.footer-links a{color:#bbb;text-decoration:none}
.footer-links a:hover{color:#fff}

.footer-submenu{list-style:none;padding-left:14px;margin:6px 0 16px}
.submenu-title{
  font-size:.75em;
  letter-spacing:1px;
  text-transform:uppercase;
  color:#777;
  margin-top:14px;
}

.footer-form{display:flex;gap:10px}
.footer-form input{flex:1;padding:10px;border-radius:6px;border:0}
.footer-form button{padding:10px 14px;border-radius:6px;border:0;font-weight:700}

.footer-bottom{
  border-top:1px solid #222;
  margin-top:30px;
  padding-top:16px;
  display:flex;
  justify-content:space-between;
  flex-wrap:wrap;
}

/* Responsive */
@media(max-width:1024px){
  .footer-grid{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:600px){
  .footer-grid{grid-template-columns:1fr}
  .footer-accordion .footer-links,
  .footer-accordion .footer-inner{display:none}
  .footer-accordion.active .footer-links,
  .footer-accordion.active .footer-inner{display:block}
}


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
  bottom: 80px !important;   /* adjust to your menu height */
  z-index: 99999 !important;
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

/* Move Chatbase widget above bottom menu */
iframe[src*="chatbase"] {
  bottom: 80px !important;   /* adjust to your menu height */
  z-index: 99999 !important;
}

/* Floating SVG launcher */
#chat-launcher {
  position: fixed;
  bottom: 80px;
  right: 20px;
  width: 56px;
  height: 56px;
  background: #000;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  z-index: 9999;
  box-shadow: 0 6px 18px rgba(0,0,0,0.25);
  transition: transform .15s ease, box-shadow .15s ease;
}

#chat-launcher:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 24px rgba(0,0,0,0.35);
}

#chat-launcher svg {
  width: 26px;
  height: 26px;
  color: #fff;
}

/* Chat window */
#chat-widget {
  position: fixed;
  bottom: 90px;
  right: 20px;
  width: 300px;
  font-family: Arial, sans-serif;
  display: none;
  z-index: 9999;
}

#chat-header {
  background: #000;
  color: #fff;
  padding: 10px;
  font-weight: 600;
}

#chat-body {
  border: 1px solid #000;
  background: #fff;
  height: 350px;
  display: flex;
  flex-direction: column;
}

#chat-messages {
  flex: 1;
  overflow-y: auto;
  padding: 10px;
  font-size: 14px;
}

#chat-input {
  display: flex;
  border-top: 1px solid #ccc;
}

#chat-input input {
  flex: 1;
  border: none;
  padding: 10px;
  outline: none;
  font-size: 14px;
}

#chat-input button {
  border: none;
  background: #000;
  color: #fff;
  padding: 10px 15px;
  cursor: pointer;
  font-size: 14px;
}

</style>

<!-- ================= SCRIPTS ================= -->
<script>
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".footer-toggle").forEach(function(btn){
    btn.addEventListener("click", function(){
      const parent = this.closest(".footer-accordion");
      parent.classList.toggle("active");

      // Toggle + / -
      const icon = this.querySelector(".toggle-icon");
      icon.textContent = parent.classList.contains("active") ? "−" : "+";
    });
  });
});

function openShopSheet(){
  document.getElementById('shopSheetOverlay').classList.add('active');
  document.body.style.overflow = 'hidden';
}
 
function closeShopSheet(){
  document.getElementById('shopSheetOverlay').classList.remove('active');
  document.body.style.overflow = '';
}

// Close when tapping outside
document.addEventListener('click', function(e){
  const overlay = document.getElementById('shopSheetOverlay');
  if(e.target === overlay){
    closeShopSheet();
  }
});

function slide(id, dir) {
  const el = document.getElementById(id);
  if (!el) return;
  el.scrollBy({ left: dir * 260, behavior: 'smooth' });
}

</script>



<script src="assets/js/slider.js"></script>


</body>
</html>