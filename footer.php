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

          <li>
            <span class="submenu-title">Browse</span>

            <ul class="footer-submenu">
              <li><a href="categories.php">Categories</a></li>
              <li><a href="brands.php">Brands</a></li>
              <li><a href="offers.php">Offers</a></li>
            </ul>
          </li>

          <li>
            <span class="submenu-title">Collections</span>

            <ul class="footer-submenu">
              <li><a href="best-sellers.php">Best Sellers</a></li>
              <li><a href="featured.php">Featured</a></li>
            </ul>
          </li>

        </ul>

      </div>

      <!-- Support -->
      <div class="footer-col footer-accordion">

        <button class="footer-toggle" type="button">
          <span>Support</span>
          <span class="toggle-icon">+</span>
        </button>

        <ul class="footer-links">

          <li>
            <span class="submenu-title">Help</span>

            <ul class="footer-submenu">
              <li><a href="contact.php">Contact</a></li>
              <li><a href="faq.php">FAQ</a></li>
            </ul>
          </li>

          <li>
            <span class="submenu-title">Orders</span>

            <ul class="footer-submenu">
              <li><a href="shipping.php">Shipping</a></li>
              <li><a href="returns.php">Returns</a></li>
              <li><a href="track-order.php">Track Order</a></li>
            </ul>
          </li>

        </ul>

      </div>

      <!-- Newsletter -->
      <div class="footer-col footer-accordion">

        <button class="footer-toggle" type="button">
          <span>Stay in the loop</span>
          <span class="toggle-icon">+</span>
        </button>

        <div class="footer-inner">

          <p class="footer-text">
            Get new arrivals and exclusive offers.
          </p>

          <form class="footer-form">

            <input
              type="email"
              placeholder="Email address"
              required
            >

            <button type="submit">
              Subscribe
            </button>

          </form>

        </div>

      </div>

    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">

      <span>
        © <?php echo date('Y'); ?> DB Stores. All rights reserved.
      </span>

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

.site-footer{
  background:#111;
  color:#eee;
}

.footer-wrap{
  max-width:1200px;
  margin:auto;
  padding:40px 20px;
}

.footer-grid{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:24px;
}

.footer-logo{
  font-size:1.4rem;
  font-weight:700;
}

.footer-text{
  color:#bbb;
  line-height:1.7;
}

/* Accordion Button */
.footer-toggle{
  width:100%;
  padding:14px 0;
  border:none;
  border-bottom:1px solid #333;
  background:none;
  color:#fff;
  font-size:1.05rem;
  font-weight:700;
  display:flex;
  align-items:center;
  justify-content:space-between;
  cursor:pointer;
}

.toggle-icon{
  font-size:1.4rem;
  transition:transform .25s ease;
}

.footer-accordion.active .toggle-icon{
  transform:rotate(45deg);
}

/* Links */
.footer-links{
  list-style:none;
  margin:14px 0;
  padding:0;
}

.footer-links > li{
  margin-bottom:16px;
}

.submenu-title{
  display:block;
  margin-bottom:8px;
  font-size:.75rem;
  letter-spacing:1px;
  text-transform:uppercase;
  color:#777;
}

.footer-submenu{
  list-style:none;
  padding-left:14px;
  margin:0;
}

.footer-submenu li{
  margin-bottom:8px;
}

.footer-links a{
  color:#bbb;
  text-decoration:none;
  transition:color .2s ease;
}

.footer-links a:hover{
  color:#fff;
}

/* Newsletter */
.footer-form{
  display:flex;
  gap:10px;
  margin-top:14px;
}

.footer-form input{
  flex:1;
  padding:10px 12px;
  border:none;
  border-radius:6px;
  outline:none;
}

.footer-form button{
  padding:10px 14px;
  border:none;
  border-radius:6px;
  font-weight:700;
  cursor:pointer;
}

/* Footer Bottom */
.footer-bottom{
  margin-top:30px;
  padding-top:16px;
  border-top:1px solid #222;
  display:flex;
  justify-content:space-between;
  align-items:center;
  flex-wrap:wrap;
  gap:10px;
}

.footer-legal{
  display:flex;
  gap:14px;
}

.footer-legal a{
  color:#bbb;
  text-decoration:none;
}

.footer-legal a:hover{
  color:#fff;
}

/* Responsive */
@media(max-width:1024px){

  .footer-grid{
    grid-template-columns:repeat(2,1fr);
  }

}

@media(max-width:600px){

  .footer-grid{
    grid-template-columns:1fr;
  }

  .footer-accordion .footer-links,
  .footer-accordion .footer-inner{
    display:none;
  }

  .footer-accordion.active .footer-links,
  .footer-accordion.active .footer-inner{
    display:block;
  }

}

/* ================= CHAT WIDGET ================= */

@media(max-width:768px){

  #chat-launcher{
    position:fixed;
    right:20px;
    bottom:80px;
    width:56px;
    height:56px;
    border-radius:50%;
    background:#000;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    z-index:9999;
    box-shadow:0 6px 18px rgba(0,0,0,.25);
    transition:.2s ease;
  }

  #chat-launcher:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 24px rgba(0,0,0,.35);
  }

  #chat-launcher svg{
    width:26px;
    height:26px;
    color:#fff;
  }

  #chat-widget{
    position:fixed;
    right:20px;
    bottom:90px;
    width:300px;
    display:none;
    z-index:9999;
    font-family:Arial,sans-serif;
  }

  #chat-header{
    padding:10px;
    background:#000;
    color:#fff;
    font-weight:600;
  }

  #chat-body{
    height:350px;
    background:#fff;
    border:1px solid #000;
    display:flex;
    flex-direction:column;
  }

  #chat-messages{
    flex:1;
    padding:10px;
    overflow-y:auto;
    font-size:14px;
  }

  #chat-input{
    display:flex;
    border-top:1px solid #ccc;
  }

  #chat-input input{
    flex:1;
    border:none;
    outline:none;
    padding:10px;
    font-size:14px;
  }

  #chat-input button{
    border:none;
    padding:10px 15px;
    background:#000;
    color:#fff;
    cursor:pointer;
    font-size:14px;
  }

}

</style>

<!-- ================= SCRIPTS ================= -->
<script>

document.addEventListener('DOMContentLoaded', () => {

  // Footer Accordion
  document.querySelectorAll('.footer-toggle').forEach(button => {

    button.addEventListener('click', () => {

      const accordion = button.closest('.footer-accordion');
      const icon = button.querySelector('.toggle-icon');

      accordion.classList.toggle('active');

      icon.textContent =
        accordion.classList.contains('active') ? '−' : '+';

    });

  });

});

/* Shop Sheet */
function openShopSheet(){

  const overlay = document.getElementById('shopSheetOverlay');

  if(!overlay) return;

  overlay.classList.add('active');
  document.body.style.overflow = 'hidden';

}

function closeShopSheet(){

  const overlay = document.getElementById('shopSheetOverlay');

  if(!overlay) return;

  overlay.classList.remove('active');
  document.body.style.overflow = '';

}

/* Close Shop Sheet Outside Click */
document.addEventListener('click', e => {

  const overlay = document.getElementById('shopSheetOverlay');

  if(e.target === overlay){
    closeShopSheet();
  }

});

/* Product Slider */
function slide(id, direction){

  const element = document.getElementById(id);

  if(!element) return;

  element.scrollBy({
    left: direction * 260,
    behavior: 'smooth'
  });

}

</script>

<script src="assets/js/slider.js"></script>

</body>
</html>