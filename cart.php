<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/init.php';
include __DIR__ . '/header.php';

if (session_status() === PHP_SESSION_NONE) session_start();

/* ---------- RESOLVE CART ---------- */
$userId = $_SESSION['user_id'] ?? null;
$sessionId = session_id();

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

if (!$cart) {
    echo "<div class='cart-empty'>Your cart is empty</div>";
    include __DIR__ . '/footer.php';
    exit;
} 

$cartId = $cart['id'];

/* ---------- FETCH CART ITEMS ---------- */
$stmt = $mysqli->prepare("
    SELECT 
        ci.id AS cart_item_id,
        ci.quantity,
        ci.unit_price,
        p.name AS product_name,
        p.image_path,
        pv.variant_name,
        pv.price AS original_price,
        b.name AS brand_name
    FROM cart_items ci
    JOIN products p ON p.id = ci.product_id
    LEFT JOIN product_variants pv ON pv.id = ci.variant_id
    LEFT JOIN brands b ON b.id = p.brand_id
    WHERE ci.cart_id = ?
");
$stmt->bind_param("i", $cartId);
$stmt->execute();
$items = $stmt->get_result();
$stmt->close();
?>

<div class="cart-page">
  <h2 class="cart-header">Cart</h2>

<?php if ($items->num_rows === 0): ?>
  <div class="cart-empty">Your cart is empty</div>
<?php else: ?>

<?php $subtotal = 0; ?>

<?php while ($row = $items->fetch_assoc()):
    $lineTotal = $row['unit_price'] * $row['quantity'];
    $subtotal += $lineTotal;
?>

<div class="cart-item"
     data-id="<?= $row['cart_item_id'] ?>"
     data-price="<?= $row['unit_price'] ?>">

  <img
    src="<?= e($row['image_path'] ?: 'assets/images/no-image.png') ?>"
    class="cart-img"
    alt="<?= e($row['product_name']) ?>"
  >

  <div class="cart-info">

    <?php if (!empty($row['brand_name'])): ?>
      <div class="cart-brand"><?= e($row['brand_name']) ?></div>
    <?php endif; ?>

    <div class="cart-name"><?= e($row['product_name']) ?></div>

    <?php if (!empty($row['variant_name'])): ?>
      <div class="cart-variant"><?= e($row['variant_name']) ?></div>
    <?php endif; ?>

    <!-- UNIT PRICE -->
    <div class="cart-price">
        <?php if (
            !empty($row['original_price']) &&
            $row['original_price'] > $row['unit_price']
        ): ?>
            <span class="old-price">
                Ks <?= number_format($row['original_price'], 0) ?>
            </span>
        <?php endif; ?>

        <span class="final-price">
            Ks <?= number_format($row['unit_price'], 0) ?>
        </span>
    </div>

    <!-- LINE TOTAL -->
    <div class="cart-line-total">
        Subtotal: 
        <span class="line-total">
            <?= number_format($lineTotal, 0) ?>
        </span> Ks
    </div>

</div>

  <div class="cart-qty-box">
    <button class="qty-btn minus">−</button>
    <input
      type="number"
      class="cart-qty"
      value="<?= $row['quantity'] ?>"
      min="1"
    >
    <button class="qty-btn plus">+</button>
  </div>

  <button class="cart-remove" aria-label="Remove item">✕</button>
</div>

<?php endwhile; ?>

<?php endif; ?>
</div>

<div class="coupon-box">
  <input id="couponInput" placeholder="Coupon code" >
  <button class="coupon-btn" id="applyCoupon">Apply</button>
  <small class="coupon-msg" id="couponMsg"></small>
</div>


<!-- ================= STICKY CHECKOUT ================= -->

<div class="checkout-bar">
  <div>
    <small>Amount Price</small>
    <p>Discount: <strong>- <span id="couponDiscount">0</span> Ks</strong></p>
      <p>Total: <strong>Ks <span id="cartGrandTotal">
    <?= number_format($subtotal,0) ?>
</span></strong></p>
  </div>
  <a href="checkout.php" class="checkout-btn">
    Check Out
  </a>
</div>

<!-- ================= CART JS ================= -->
<script>
let currentSubtotal = <?= (float)$subtotal ?>;

document.querySelectorAll('.cart-item').forEach(item => {

  const minus = item.querySelector('.minus');
  const plus  = item.querySelector('.plus');
  const input = item.querySelector('.cart-qty');
  const id    = item.dataset.id;

  minus.onclick = () => {
    const val = Math.max(1, parseInt(input.value) - 1);
    updateQty(val);
  };

  plus.onclick = () => {
    const val = parseInt(input.value) + 1;
    updateQty(val);
  };

  function updateQty(val) {

    fetch('cart_update.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({ id: id, qty: val })
    })
    .then(res => res.json())
    .then(data => {

      if (!data.success) return;

      input.value = val;

      // Update line total
      const price = parseFloat(item.dataset.price);
      const lineTotal = price * val;

      item.querySelector('.line-total').innerText =
          lineTotal.toLocaleString();

      // Update grand total
      currentSubtotal = data.subtotal;

      document.getElementById('cartGrandTotal').innerText =
          currentSubtotal.toLocaleString();

      // Update cart badge
      const badge = document.querySelector('.cart-badge');
      if (badge) badge.innerText = data.total_items;
    });
  }

  item.querySelector('.cart-remove').onclick = () => {

    fetch('cart_update.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({ remove: id })
    })
    .then(res => res.json())
    .then(data => {

      if (!data.success) return;

      item.remove();

      currentSubtotal = data.subtotal;

      document.getElementById('cartGrandTotal').innerText =
          currentSubtotal.toLocaleString();

      const badge = document.querySelector('.cart-badge');
      if (badge) badge.innerText = data.total_items;

      if (data.total_items === 0) {
        location.reload();
      }
    });
  };

});

/* ===== Coupon ===== */
document.getElementById('applyCoupon').onclick = () => {

  const code = document.getElementById('couponInput').value;
  const msg  = document.getElementById('couponMsg');

  fetch('apply_coupon.php', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: 'coupon=' + encodeURIComponent(code) +
          '&subtotal=' + currentSubtotal
  })
  .then(res => res.json())
  .then(data => {

    if (data.error) {
      msg.textContent = data.error;
      msg.className = 'coupon-msg error';
      return;
    }

    document.getElementById('couponDiscount').textContent =
      data.discount.toLocaleString();

    document.getElementById('cartGrandTotal').textContent =
      (currentSubtotal - data.discount).toLocaleString();

    msg.textContent = 'Coupon applied';
    msg.className = 'coupon-msg success';
  });
};
</script>

<style>
/* ================= CART PAGE ================= */

.cart-page {
  padding: 16px;
  max-width: 520px;
  margin: 0 auto;
}

/* Header */
.cart-header {
  text-align: center;
  font-size: 20px;
  font-weight: 700;
  margin-bottom: 18px;
}

/* Empty state */
.cart-empty {
  text-align: center;
  padding: 40px 0;
  color: #777;
  font-size: 15px;
}

/* ================= CART ITEM ================= */

.cart-item {
  display: flex;
  align-items: center;
  gap: 12px;
  background: #fff;
  padding: 12px;
  border-radius: 16px;
  margin-bottom: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,.04);
}

/* Product image */
.cart-img {
  width: 64px;
  height: 64px;
  border-radius: 12px;
  object-fit: cover;
  flex-shrink: 0;
}

/* Info */
.cart-info {
  flex: 1;
  min-width: 0;
}

.cart-brand {
  font-size: 12px;
  color: #999;
  margin-bottom: 2px;
}

.cart-name {
  font-size: 14px;
  font-weight: 600;
  line-height: 1.3;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.cart-price {
  margin-top: 6px;
  display: flex;
  gap: 8px;
  align-items: center;
}

.old-price {
  font-size: 12px;
  color: #999;
  text-decoration: line-through;
}

.final-price {
  font-size: 14px;
  font-weight: 700;
  color: #111;
}

.cart-line-total {
  margin-top: 4px;
  font-size: 12px;
  font-weight: 600;
  color: #333;
}
/* ================= QUANTITY ================= */

.cart-qty-box {
  display: flex;
  align-items: center;
  gap: 6px;
}

.cart-qty {
  width: 36px;
  height: 28px;
  text-align: center;
  border: none;
  background: transparent;
  font-weight: 600;
  font-size: 14px;
  outline: none;
}

/* Qty buttons */
.qty-btn {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  border: none;
  background: #f1f1f1;
  font-size: 16px;
  line-height: 1;
  cursor: pointer;
  transition: background .15s ease;
}

.qty-btn:hover {
  background: #e6e6e6;
}

/* ================= REMOVE ================= */

.cart-remove {
  background: none;
  border: none;
  color: #999;
  font-size: 16px;
  padding: 4px;
  cursor: pointer;
  transition: color .15s ease;
}

.cart-remove:hover {
  color: #cc3031;
}
/* ===== Coupon Wrapper ===== */
.coupon-wrap {
  display: flex;
  gap: 8px;
  margin-top: 12px;
}

/* ===== Input ===== */
.coupon-box {
  width: full-width;
  flex: 1;
  padding: 13px 14px;
  border-radius: 12px;
  border: 1px solid #ddd;
  font-size: 14px;
  outline: none;
  transition: border 0.2s ease;
}
.coupon-box input {
  flex: 1;
  padding: 13px 14px;
  border-radius: 12px;
  border: 1px solid #ddd;
  font-size: 14px;
  outline: none;
  transition: border 0.2s ease;
}
 

.coupon-input:focus {
  border-color: #cc2230;
}

/* ===== Button ===== */
.coupon-btn {
  padding: 13px 18px;
  border-radius: 12px;
  border: none;
  background: #111;
  color: #fff;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: opacity 0.2s ease;
}

.coupon-btn:hover {
  opacity: 0.9;
}

/* ===== Message ===== */
.coupon-msg {
  margin-top: 8px;
  font-size: 13px;
}

.coupon-msg.success {
  color: #2e7d32;
}

.coupon-msg.error {
  color: #d32f2f;
}
/* ================= CHECKOUT BAR ================= */

.checkout-bar {
  position: sticky;
  bottom: 0;
  background: #fff;
  border-top: 1px solid #eee;
  padding: 14px 16px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 520px;
  margin: 0 auto;
}

.checkout-bar small {
  display: block;
  font-size: 11px;
  color: #777;
}

.checkout-bar strong {
  font-size: 16px;
  font-weight: 800;
}

/* Checkout button */
.checkout-btn {
  background: #cc3031;
  color: #fff;
  padding: 12px 20px;
  border-radius: 14px;
  font-size: 14px;
  font-weight: 700;
  text-decoration: none;
  transition: background .15s ease;
}

.checkout-btn:hover {
  background: #b42829;
}
</style>


<?php include __DIR__ . '/footer.php'; ?>