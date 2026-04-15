<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);


require_once __DIR__ . '/init.php';
require_once __DIR__ . '/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$productId = (int)$_GET['id'];

/* ================= PRODUCT + BRAND ================= */
$stmt = $mysqli->prepare("
    SELECT p.*, b.name AS brand_name
    FROM products p
    LEFT JOIN brands b ON b.id = p.brand_id
    WHERE p.id = ?
    LIMIT 1
");
$stmt->bind_param("i", $productId);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    echo "<div class='container'>Product not found.</div>";
    require_once __DIR__ . '/footer.php';
    exit;
}

/* ================= VARIANTS ================= */
$stmt = $mysqli->prepare("
    SELECT id, variant_name, price, discount_price, stock, image_path, is_default
    FROM product_variants
    WHERE product_id = ?
    ORDER BY is_default DESC, id ASC
");
$stmt->bind_param("i", $productId);
$stmt->execute();
$res = $stmt->get_result();

$variants = [];
while ($row = $res->fetch_assoc()) {
    $variants[] = $row;
}
$stmt->close();

if (!$variants) {
    echo "<div class='container'>No variants available.</div>";
    require_once __DIR__ . '/footer.php';
    exit;
}

/* ================= GALLERY IMAGES ================= */

$images = [];

foreach ($variants as $v) {
    if (!empty($v['image'])) {
        $images[] = $v['image'];
    }
}

if (empty($images) && !empty($product['image_path'])) {
    $images[] = $product['image_path'];
}

if (empty($images)) {
    $images[] = "assets/images/no-image.png";
}

/* ================= HIGHLIGHTS ================= */
$highlights = null;

$highlightsStmt = $mysqli->prepare("
  SELECT 
    hi.label,
    hi.svg_file,
    hi.color,
    ph.sort_order
  FROM product_highlights ph
  INNER JOIN highlight_icons hi 
    ON hi.id = ph.icon_id
  WHERE ph.product_id = ?
    AND ph.is_active = 1
  ORDER BY ph.sort_order ASC
  LIMIT 4
");

if ($highlightsStmt) {
    $highlightsStmt->bind_param("i", $productId);
    $highlightsStmt->execute();
    $highlights = $highlightsStmt->get_result();
    $highlightsStmt->close();
}

?>

<style>
.container{max-width:1100px;margin:auto;padding:20px}
.product-grid{display:grid;grid-template-columns:1fr 1fr;gap:40px}

.brand-link{font-size:14px;font-weight:600;color:#777;text-transform:uppercase;text-decoration:none}
.product-title{font-size:28px;margin:6px 0 16px}

/* GALLERY */
.gallery{overflow:hidden;border-radius:14px}
.gallery-track{display:flex;transition:transform .35s ease}
.gallery-slide{min-width:100%}
.gallery-slide img{width:100%;display:block}
.gallery-dots{display:flex;justify-content:center;gap:8px;margin-top:10px}
.gallery-dots button{width:8px;height:8px;border-radius:50%;border:none;background:#ccc}
.gallery-dots button.active{background:#111}

/* ================= MOBILE ADJUSTMENTS ================= */
@media (max-width: 768px) {

  .container {
    padding: 12px;
  }

  .product-grid {
    grid-template-columns: 1fr;
    gap: 20px;
  }

  /* Gallery */
  .gallery {
    border-radius: 12px;
  }

  .gallery-slide img {
    border-radius: 12px;
  }

  /* Brand + Title */
  .brand-link {
    font-size: 13px;
  }

  .product-title {
    font-size: 22px;
    line-height: 1.3;
  }

.variant-option {
    width: 100%;
    border: 1px solid #e5e5e5;
    background: #fff;
    border-radius: 12px;
    padding: 12px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    transition: 0.2s ease;
}

.variant-option:hover:not(.variant-out) {
    border-color: #000;
}
<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);


require_once __DIR__ . '/init.php';
require_once __DIR__ . '/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$productId = (int)$_GET['id'];

/* ================= PRODUCT + BRAND ================= */
$stmt = $mysqli->prepare("
    SELECT p.*, b.name AS brand_name
    FROM products p
    LEFT JOIN brands b ON b.id = p.brand_id
    WHERE p.id = ?
    LIMIT 1
");
$stmt->bind_param("i", $productId);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    echo "<div class='container'>Product not found.</div>";
    require_once __DIR__ . '/footer.php';
    exit;
}

/* ================= VARIANTS ================= */
$stmt = $mysqli->prepare("
    SELECT id, variant_name, price, discount_price, stock, image_path, is_default
    FROM product_variants
    WHERE product_id = ?
    ORDER BY is_default DESC, id ASC
");
$stmt->bind_param("i", $productId);
$stmt->execute();
$res = $stmt->get_result();

$variants = [];
while ($row = $res->fetch_assoc()) {
    $variants[] = $row;
}
$stmt->close();

if (!$variants) {
    echo "<div class='container'>No variants available.</div>";
    require_once __DIR__ . '/footer.php';
    exit;
}

/* ================= GALLERY IMAGES ================= */

$images = [];

foreach ($variants as $v) {
    if (!empty($v['image'])) {
        $images[] = $v['image'];
    }
}

if (empty($images) && !empty($product['image_path'])) {
    $images[] = $product['image_path'];
}

if (empty($images)) {
    $images[] = "assets/images/no-image.png";
}

/* ================= HIGHLIGHTS ================= */
$highlights = null;

$highlightsStmt = $mysqli->prepare("
  SELECT 
    hi.label,
    hi.svg_file,
    hi.color,
    ph.sort_order
  FROM product_highlights ph
  INNER JOIN highlight_icons hi 
    ON hi.id = ph.icon_id
  WHERE ph.product_id = ?
    AND ph.is_active = 1
  ORDER BY ph.sort_order ASC
  LIMIT 4
");

if ($highlightsStmt) {
    $highlightsStmt->bind_param("i", $productId);
    $highlightsStmt->execute();
    $highlights = $highlightsStmt->get_result();
    $highlightsStmt->close();
}

?>

<style>
.container{max-width:1100px;margin:auto;padding:20px}
.product-grid{display:grid;grid-template-columns:1fr 1fr;gap:40px}

.brand-link{font-size:14px;font-weight:600;color:#777;text-transform:uppercase;text-decoration:none}
.product-title{font-size:28px;margin:6px 0 16px}

/* GALLERY */
.gallery{overflow:hidden;border-radius:14px}
.gallery-track{display:flex;transition:transform .35s ease}
.gallery-slide{min-width:100%}
.gallery-slide img{width:100%;display:block}
.gallery-dots{display:flex;justify-content:center;gap:8px;margin-top:10px}
.gallery-dots button{width:8px;height:8px;border-radius:50%;border:none;background:#ccc}
.gallery-dots button.active{background:#111}

/* ================= MOBILE ADJUSTMENTS ================= */
@media (max-width: 768px) {

  .container {
    padding: 12px;
  }

  .product-grid {
    grid-template-columns: 1fr;
    gap: 20px;
  }

  /* Gallery */
  .gallery {
    border-radius: 12px;
  }

  .gallery-slide img {
    border-radius: 12px;
  }

  /* Brand + Title */
  .brand-link {
    font-size: 13px;
  }

  .product-title {
    font-size: 22px;
    line-height: 1.3;
  }
  
.variant-options {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

/* Wrapper */
.variant-wrapper {
    position: relative;
    display: inline-block;
}

/* SALE badge above */
.variant-sale-badge {
    position: absolute;
    top: -10px;
    left: 8px;
    background: #d6001c; /* Sephora red */
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 6px;
    z-index: 2;
} 


/* PRICE */
.old-price {
    text-decoration: line-through;
    color: #999;
    font-size: 14px;
    margin-right: 6px;
}

.sale-price {
    color: #CC2230;
    font-weight: 600;
}

.normal-price {
    font-weight: 500;
}

/* SALE HIGHLIGHT */
.variant-sale {
    background: #fff7f8;
    border-color: #f3c6cb;
}

/* OUT OF STOCK */
.variant-out {
    background: #f5f5f5;
    color: #aaa;
    cursor: not-allowed;
}

/* SELECTED */
.variant-option.active {
    border-color: #000;
}
  .price-box del{color:#999;font-size: 18px;margin-left:8px}
  .price-box .sale{font-size: 20px;color:#d32f2f}


.sephora-highlights {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 18px 14px;
  margin: 20px 0;
}

.sephora-highlight-item {
  display: flex;
  align-items: center;
  gap: 12px;
}

.sephora-icon {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.sephora-icon img {
  width: 40px;
  height: 40px;
  filter: brightness(0) invert(1); /* white icon */
}

.sephora-label {
  font-size: 14px;
  color: #111;
  line-height: 1.3;
}

/* Tablet+ */
@media (min-width: 768px) {
  .sephora-highlights {
    grid-template-columns: repeat(3, 1fr);
  }
}


/* Desktop */
@media (min-width: 768px) {
  .product-highlights {
    grid-template-columns: repeat(4, 1fr);
  }
}

.highlight-icon img {
  width: 35px;
  height: 35px;
  object-fit: contain;
}
  /* Accordions */
  .accordion-header {
    padding: 12px 0;
    font-size: 15px;
  }

  .accordion-content {
    font-size: 14px;
    line-height: 1.5;
  }

  /* Sticky bar spacing fix */
  body {
    padding-bottom: 90px;
  }

  .sticky-bar {
    padding: 10px 12px;
  }

  .sticky-bar input {
    width: 60px;
    font-size: 14px;
  }

  .sticky-bar button {
    font-size: 15px;
    padding: 12px;
    border-radius: 8px;
  }

}

/* VARIANTS */
.variant-group{margin-bottom:16px}
.variant-options{display:flex;gap:10px;flex-wrap:wrap}
.variant-option{padding:8px 14px;border:1px solid #ddd;border-radius:6px;cursor:pointer}
.variant-option.active{background:#111;color:#fff;border-color:#111}

/* PRICE */
.price-box{font-size:22px;font-weight:700;margin:16px 0}
.price-box del{color:#999;margin-right:8px}
.price-box .sale{color:#d32f2f}

/* ACCORDION */
.accordion{border-top:1px solid #eee;margin-top:20px}
.accordion-item{border-bottom:1px solid #eee}
.accordion-header{padding:14px 0;font-weight:600;cursor:pointer}
.accordion-content{display:none;padding-bottom:14px;color:#555}

/* STICKY BAR */
.sticky-bar{
  position:fixed;bottom:60px;left:0;right:0;
  background:#fff;border-top:1px solid #ddd;
  padding:12px 20px;display:flex;gap:12px;
  align-items:center;z-index:999
}
.sticky-bar input{width:70px;padding:8px}
.sticky-bar button{
  flex:1;padding:14px;background:#111;color:#fff;
  border:none;border-radius:10px;font-size:16px
}
.sticky-bar button:disabled{opacity:.5}

</style>

<div class="container">
  <div class="product-grid">

    <!-- GALLERY -->
    <div>
      <div class="gallery">
        <div class="gallery-track" id="galleryTrack">
          <?php foreach ($images as $img): ?>
            <div class="gallery-slide">
              <img src="<?= '/' . ltrim($img, '/') ?>" alt="">
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="gallery-dots" id="galleryDots"></div>
    </div>

    <!-- INFO -->
    <div>
      <a class="brand-link" href="brand.php?id=<?= $product['brand_id'] ?>">
        <?= htmlspecialchars($product['brand_name']) ?>
      </a>

      <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>

      <!-- VARIANTS -->
      <!-- VARIANTS -->
<div class="variant-group">
  
  <div class="variant-options">
    <?php foreach ($variants as $v): 
        $isOnSale = !empty($v['discount_price']) && $v['discount_price'] > 0;
    ?>
      
      <div class="variant-wrapper">
        
        <?php if ($isOnSale): ?>
          <span class="variant-sale-badge">SALE</span>
        <?php endif; ?>

        <button type="button"
          class="variant-option"
          data-id="<?= $v['id'] ?>"
          data-price="<?= $v['price'] ?>"
          data-discount="<?= $v['discount_price'] ?>"
          data-stock="<?= $v['stock'] ?>"
          data-image="<?= htmlspecialchars($v['image_path'] ?: $product['image_path']) ?>">
          <?= htmlspecialchars($v['variant_name']) ?>
        </button>

      </div>

    <?php endforeach; ?>
  </div>
</div>

      <div class="price-box" id="priceBox"></div>
      <?php if ($highlights && $highlights->num_rows > 0): ?>
<div class="sephora-highlights">

  <?php while ($h = $highlights->fetch_assoc()): ?>
    <div class="sephora-highlight-item">

      <div class="sephora-icon"
           style="background-color: <?= htmlspecialchars($h['color']) ?>;">
        <img
          src="/V3/assets/uploads/highlights/<?= htmlspecialchars($h['svg_file']) ?>"
          alt="<?= htmlspecialchars($h['label']) ?>">
      </div>

      <div class="sephora-label">
        <?= htmlspecialchars($h['label']) ?>
      </div>

    </div>
  <?php endwhile; ?>

</div>
<!-- DESCRIPTION (ALWAYS OPEN) -->
  <?php if (!empty($product['description'])): ?>
    <div class="details-block description-block">
      <h3 class="details-title">Description</h3>
      <div class="details-content">
        <?= nl2br(htmlspecialchars($product['description'])) ?>
      </div>
    </div>
  <?php endif; ?>

<?php endif; ?>

      <!-- ACCORDIONS -->
      <div class="accordion">
        
        <div class="accordion-item">
          <div class="accordion-header">Ingredients</div>
          <div class="accordion-content"><?= nl2br(htmlspecialchars($product['ingredients'])) ?></div>
        </div>
        <div class="accordion-item">
          <div class="accordion-header">How to Use</div>
          <div class="accordion-content"><?= nl2br(htmlspecialchars($product['how_to_use'])) ?></div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- STICKY ADD TO CART BAR -->
<form method="post" action="add_to_cart.php" class="sticky-bar">

  <!-- REQUIRED -->
  <input type="hidden" name="product_id" value="<?= $productId ?>">
  <input type="hidden" name="variant_id" id="variant_id">

  <!-- QUANTITY -->
  <input
    type="number"
    name="quantity"
    value="1"
    min="1"
    aria-label="Quantity"
  >

  <!-- ADD TO CART -->
  <button
    type="submit"
    id="addBtn"
    disabled
  >
    Add to Cart
  </button>

</form>

<div class="container">

<link rel="stylesheet" href="/components/product-sliders/product-slider.css">

<?php include 'components/slider-more-like-this.php';
?>
</div>

<script>
/* ===== VARIANTS ===== */
const variants = <?= json_encode(array_values($variants)) ?>;
let selected = {};
let current = null;

const priceBox = document.getElementById('priceBox');
const variantInput = document.getElementById('variant_id');
const addBtn = document.getElementById('addBtn');

/* ===== VARIANT SELECTION ===== */

document.querySelectorAll('.variant-option').forEach(btn => {

  btn.onclick = () => {

    document.querySelectorAll('.variant-option')
      .forEach(b => b.classList.remove('active'));

    btn.classList.add('active');

    const id = btn.dataset.id;
    const price = Number(btn.dataset.price);
    const discount = Number(btn.dataset.discount);
    const stock = Number(btn.dataset.stock);
    const image = btn.dataset.image;

    variantInput.value = id;

    if (discount > 0 && discount < price) {
      priceBox.innerHTML =
        `<span class="sale">${discount.toLocaleString()} Ks</span>
         <del>${price.toLocaleString()} Ks</del>`;
    } else {
      priceBox.innerHTML =
        `${price.toLocaleString()} Ks`;
    }

    addBtn.disabled = stock <= 0;

    if (image) {
      mainImage.src = image;
    }

  };

});

/* ===== DEFAULT VARIANT ===== */
const def = variants.find(v=>v.is_default==1);
if(def){
  for(const k in def.attributes){
    document.querySelector(
      `[data-attr="${k}"][data-value="${def.attributes[k]}"]`
    )?.click();
  }
}

/* ===== GALLERY SLIDER ===== */
const track=document.getElementById('galleryTrack');
const dots=document.getElementById('galleryDots');
const slides=track.children;
let idx=0,startX=0,dx=0,drag=false;

for(let i=0;i<slides.length;i++){
  const b=document.createElement('button');
  if(i===0)b.classList.add('active');
  b.onclick=()=>goTo(i);
  dots.appendChild(b);
}

function goTo(i){
  idx=Math.max(0,Math.min(i,slides.length-1));
  track.style.transform=`translateX(-${idx*100}%)`;
  [...dots.children].forEach((d,n)=>d.classList.toggle('active',n===idx));
}

track.onpointerdown=e=>{
  drag=true;startX=e.clientX;track.style.transition='none';
};
window.onpointermove=e=>{
  if(!drag)return;
  dx=e.clientX-startX;
  track.style.transform=`translateX(calc(-${idx*100}% + ${dx}px))`;
};
window.onpointerup=()=>{
  if(!drag)return;
  drag=false;track.style.transition='transform .35s ease';
  if(dx<-50&&idx<slides.length-1)idx++;
  if(dx>50&&idx>0)idx--;
  goTo(idx);dx=0;
};

/* ===== ACCORDION ===== */
document.querySelectorAll('.accordion-header').forEach(h=>{
  h.onclick=()=>{
    const c=h.nextElementSibling;
    c.style.display=c.style.display==='block'?'none':'block';
  };
});

document.querySelectorAll('.product-slider').forEach(slider => {
  const track = slider.querySelector('.product-slider-track');
  const slides = slider.querySelectorAll('.product-slide');
  const prev = slider.querySelector('.ps-nav.prev');
  const next = slider.querySelector('.ps-nav.next');
  const viewport = slider.querySelector('.product-slider-viewport');

  if (!track || slides.length === 0) return;

  let index = 0;
  const gap = 16;

  function slideWidth() {
    return slides[0].offsetWidth + gap;
  }

  function maxIndex() {
    const visible = Math.floor(viewport.offsetWidth / slideWidth());
    return Math.max(0, slides.length - visible);
  }

  function update() {
    index = Math.max(0, Math.min(index, maxIndex()));
    track.style.transform = `translateX(-${index * slideWidth()}px)`;
  }

  next.addEventListener('click', () => {
    index++;
    update();
  });

  prev.addEventListener('click', () => {
    index--;
    update();
  });

  // Touch swipe support
  let startX = 0;
  track.addEventListener('touchstart', e => {
    startX = e.touches[0].clientX;
  });

  track.addEventListener('touchend', e => {
    const diff = startX - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 40) {
      index += diff > 0 ? 1 : -1;
      update();
    }
  });

  window.addEventListener('resize', update);
});

</script>


<?php require_once __DIR__ . '/footer.php'; ?>