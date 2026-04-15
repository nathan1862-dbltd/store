<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/header.php';

?>

<?php include __DIR__ . '/components/greeting-bar.php'; ?>

<link rel="stylesheet" href="/components/product-sliders/product-slider.css">

<?php include __DIR__ . '/components/category-slider.php'; ?>

<link rel="stylesheet" href="assets/css/hero-slider.css">

<?php include __DIR__ . '/components/hero-slider.php'; ?>

<div class="container">

<link rel="stylesheet" href="/components/product-sliders/product-slider.css">

<?php include 'components/slider-featured.php';
?>
</div>

<div class="container">

<link rel="stylesheet" href="/components/product-sliders/product-slider.css">

<?php include 'components/slider-new-arrivals.php';
?>
</div>

<style>

/* Greeting Bar Wrapper */
.greeting-bar {
    background: linear-gradient(135deg, #ffffff, #f8f9fb);
    padding: 14px 20px;
    border-radius: 14px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
    display: inline-block;
    margin: 10px 0;
}

/* Greeting Text */
.greeting-text {
    font-size: 18px;
    font-weight: 600;
    color: #033F63; /* Deep blue premium tone */
    letter-spacing: 0.3px;
}

/* Day Highlight */
.greeting-text::first-letter {
    color: #CC2230; /* Accent red */
}

/* Responsive */
@media (max-width: 768px) {
    .greeting-bar {
        padding: 12px 16px;
        border-radius: 12px;
    }

    .greeting-text {
        font-size: 16px;
    }
}
</style>

<script>
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


function slideCategory(direction) {
    const slider = document.getElementById('categorySlider');
    const scrollAmount = 250;
    slider.scrollLeft += direction * scrollAmount;
}


</script>


<?php require_once __DIR__ . '/footer.php'; ?>