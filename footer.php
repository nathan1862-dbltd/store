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

<?php include __DIR__ . '/mobile-bottom-menu.php'; ?>

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

@media(max-width:768px){

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
  const overlay = document.getElementById('shopSheetOverlay');
  if (!overlay) return;
  overlay.classList.add('active');
  document.body.style.overflow = 'hidden';
}
 
function closeShopSheet(){
  const overlay = document.getElementById('shopSheetOverlay');
  if (!overlay) return;
  overlay.classList.remove('active');
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
