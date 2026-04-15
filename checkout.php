<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/header.php';

ensureSessionStarted();
redirectIfNotLoggedIn();

$userId = (int)$_SESSION['user_id'];

/* ---------- LOAD STATES ---------- */
$states = $mysqli->query("SELECT id, name FROM shipping_states WHERE is_active=1 ORDER BY name");

/* ===============================
   LOAD CART ITEMS WITH DETAILS
================================ */
$stmt = $mysqli->prepare("
    SELECT 
        ci.quantity,
        ci.unit_price,
        p.name AS product_name,
        pv.variant_name
    FROM cart_items ci
    LEFT JOIN products p ON p.id = ci.product_id
    LEFT JOIN product_variants pv ON pv.id = ci.variant_id
    WHERE ci.cart_id = ?
");
$stmt->bind_param("i", $cart['id']);
$stmt->execute();
$result = $stmt->get_result();

$subtotal = 0;
$cartItems = [];

while ($row = $result->fetch_assoc()) {
    $row['line_total'] = $row['quantity'] * $row['unit_price'];
    $subtotal += $row['line_total'];
    $cartItems[] = $row;
}

$stmt->close();

/* ===============================
   COUPON (SESSION BASED)
================================ */
$discount  = 0;
$coupon_id = null;

if (!empty($_SESSION['applied_coupon'])) {
    $discount  = (float)$_SESSION['applied_coupon']['discount'];
    $coupon_id = (int)$_SESSION['applied_coupon']['id'];

    // Safety: never allow discount > subtotal
    if ($discount > $subtotal) {
        $discount = $subtotal;
    }
}

$shipping = 0;

$total = $subtotal - $discount + $shipping;
if ($total < 0) $total = 0;
?>

<div class="checkout-page">
  <h1>Checkout</h1>

  <div class="checkout-grid">

    <!-- LEFT -->
    <form method="post" action="place_order.php" class="checkout-form" id="checkoutForm">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="order_nonce" value="<?= e(order_nonce()) ?>">
      <input type="hidden" name="coupon_code" id="couponHidden" value="">

      <input name="full_name" placeholder="Full name" required>
      <input name="phone" placeholder="Phone" required>
      <input name="email" placeholder="Email (optional)">
      <textarea name="address" placeholder="Address" required></textarea>

      <select id="stateSelect" name="state_id" required>
        <option value="">Select State</option>
        <?php if ($states): while ($s = $states->fetch_assoc()): ?>
          <option value="<?= (int)$s['id'] ?>"><?= e($s['name']) ?></option>
        <?php endwhile; endif; ?>
      </select>

      <select id="townshipSelect" name="township_id" required disabled>
        <option value="">Select Township</option>
      </select>

      <select name="payment_method" required>
        <option value="cod">Cash on Delivery</option>
        <option value="bank">Bank Transfer</option>
      </select>

      <div class="coupon-box">
        <h3>Coupon</h3>
        <input id="couponInput" placeholder="Coupon code">
        <button type="button" id="applyCouponBtn">Apply</button>
        <button type="button" id="removeCouponBtn" style="display:none; margin-left:8px;">Remove</button>
        <small id="couponMsg"></small>
      </div>

      <div class="loyalty-box">
        <h3>Redeem points</h3>
        <input type="number" name="points_to_redeem" min="0" value="0">
        <small style="display:block;opacity:.75">Points are applied at order placement based on your current balance.</small>
      </div>

      <button type="submit" class="checkout-btn" id="placeOrderBtn" disabled>Place Order</button>
    </form>

<!-- ================= ORDER ITEMS ================= -->
<div class="mb-4">
    <h5 class="mb-3">Order Items</h5>

    <div class="border rounded p-3 bg-white">

        <?php foreach ($cartItems as $item): ?>

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>
                <div class="fw-semibold">
                    <?= htmlspecialchars($item['product_name']) ?>
                </div>

                <?php if (!empty($item['variant_name'])): ?>
                    <div class="text-muted small">
                        Variant: <?= htmlspecialchars($item['variant_name']) ?>
                    </div>
                <?php endif; ?>

                <div class="small text-muted">
                    Qty: <?= $item['quantity'] ?> × 
                    <?= number_format($item['unit_price'], 0) ?>
                </div>
            </div>

            <div class="fw-semibold">
                <?= number_format($item['line_total'], 0) ?>
            </div>

        </div>

        <?php endforeach; ?>

    </div>
</div>

    <!-- RIGHT -->
    <div class="checkout-summary">
      <h3>Summary</h3>
      <p>Subtotal: <span id="subtotalAmount"><?= number_format($subtotal, 2) ?></span></p>
      <p>Shipping: <span id="shippingAmount">0.00</span></p>
      <p>Discount: <span id="discountAmount">0.00</span></p>
      <hr>
      <p><strong>Total: <span id="totalAmount"><?= number_format($subtotal, 2) ?></span></strong></p>
    </div>

  </div>
</div>

<style>
.checkout-page{max-width:1000px;margin:auto;padding:20px}
.checkout-grid{display:grid;grid-template-columns:2fr 1fr;gap:30px}
.checkout-form input,.checkout-form textarea,.checkout-form select{width:100%;padding:12px;margin-bottom:12px}
.checkout-btn{width:100%;padding:14px;background:#111;color:#fff;border:none;border-radius:10px}
.checkout-btn:disabled{opacity:.5;cursor:not-allowed}
.checkout-summary{background:#fafafa;padding:20px;border-radius:12px}
.coupon-box,.loyalty-box{background:#fff;border:1px solid #eee;border-radius:12px;padding:12px;margin:12px 0}
@media(max-width:900px){.checkout-grid{grid-template-columns:1fr}}
</style>

<script>
const subtotal = <?= json_encode((float)$subtotal) ?>;
let discountAmount = 0;
let shippingAmount = 0;

const discountEl = document.getElementById('discountAmount');
const shippingEl = document.getElementById('shippingAmount');
const totalEl    = document.getElementById('totalAmount');
const couponHidden = document.getElementById('couponHidden');
const townshipSelect = document.getElementById('townshipSelect');
const placeOrderBtn  = document.getElementById('placeOrderBtn');

function money(n){
  return (Number(n||0)).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2});
}

function recalc(){
  const total = Math.max(0, subtotal - discountAmount + shippingAmount);
  totalEl.textContent = money(total);
}

function updateSubmitState(){
  placeOrderBtn.disabled = !townshipSelect.value;
}

// State -> townships
document.getElementById('stateSelect').addEventListener('change', function(){
  const stateId = this.value;
  townshipSelect.innerHTML = '<option value="">Loading...</option>';
  townshipSelect.disabled = true;

  shippingAmount = 0;
  shippingEl.textContent = money(0);
  updateSubmitState();
  recalc();

  if(!stateId){
    townshipSelect.innerHTML = '<option value="">Select Township</option>';
    return;
  }

  fetch('get_townships.php?state=' + encodeURIComponent(stateId))
    .then(r=>r.json())
    .then(data=>{
      townshipSelect.innerHTML = '<option value="">Select Township</option>';
      if(!Array.isArray(data) || data.length===0){
        townshipSelect.innerHTML = '<option value="">No townships available</option>';
        return;
      }
      data.forEach(t=>{
        const opt = document.createElement('option');
        opt.value = t.id;
        opt.dataset.fee = t.shipping_fee;
        opt.textContent = t.name + ' (+ ' + money(t.shipping_fee) + ')';
        townshipSelect.appendChild(opt);
      });
      townshipSelect.disabled = false;
    })
    .catch(()=>{
      townshipSelect.innerHTML = '<option value="">Error loading</option>';
    });
});

// Township -> shipping
TownshipChange();
function TownshipChange(){
  townshipSelect.addEventListener('change', function(){
    const selected = this.options[this.selectedIndex];
    shippingAmount = selected ? Number(selected.dataset.fee||0) : 0;
    shippingEl.textContent = money(shippingAmount);
    updateSubmitState();
    recalc();
  });
}

// Coupon apply/remove
const couponMsg = document.getElementById('couponMsg');
const removeBtn = document.getElementById('removeCouponBtn');

document.getElementById('applyCouponBtn').addEventListener('click', function(){
  const code = document.getElementById('couponInput').value.trim();
  if(!code){
    couponMsg.style.color = '#CC2230';
    couponMsg.textContent = 'Enter coupon code.';
    return;
  }

fetch('validate_coupon.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'code=' + encodeURIComponent(code) + 
          '&subtotal=' + encodeURIComponent(subtotal)
})
  .then(r=>r.json())
  .then(d=>{
    if(d.error){
      couponMsg.style.color = '#CC2230';
      couponMsg.textContent = d.error;
      discountAmount = 0;
      couponHidden.value = '';
      discountEl.textContent = money(0);
      removeBtn.style.display='none';
      recalc();
      return;
    }

    discountAmount = Number(d.discount||0);
    couponHidden.value = d.coupon_code || code;
    discountEl.textContent = money(discountAmount);
    couponMsg.style.color = '#4CAF50';
    couponMsg.textContent = 'Coupon applied.';
    removeBtn.style.display='inline-block';
    recalc();
  })
  .catch(()=>{
    couponMsg.style.color = '#CC2230';
    couponMsg.textContent = 'Coupon validation failed.';
  });
});

removeBtn.addEventListener('click', function(){
  discountAmount = 0;
  couponHidden.value = '';
  discountEl.textContent = money(0);
  couponMsg.textContent = '';
  this.style.display='none';
  recalc();
});

updateSubmitState();
recalc();
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
