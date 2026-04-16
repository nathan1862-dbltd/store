<?php
// IMAGE SLIDER (separate from Hero slider)
$imgRow = $mysqli->query("SELECT * FROM image_slider WHERE id=1 LIMIT 1")->fetch_assoc();

$imageSliderImages = [];
if ($imgRow) {
  for ($i=1; $i<=4; $i++){
    $k = "image{$i}";
    if (!empty($imgRow[$k])) $imageSliderImages[] = $imgRow[$k];
  }
}
if (empty($imageSliderImages)) {
  $imageSliderImages = ['assets/images/no-image.png'];
}
?>

<style>
/* =========================================
   IMAGE SLIDER (NAMESPACED - NO HERO CONFLICT)
========================================= */
.imgslider {
  position: relative;
  width: 100%;
  height: 380px;
  overflow: hidden;
  border-radius: 14px;
  background: #000;
}

.imgslider__track {
  display: flex;
  width: 100%;
  height: 100%;
  transform: translate3d(0,0,0);
  transition: transform 0.9s cubic-bezier(0.65, 0, 0.35, 1); /* smooth in-out */
  will-change: transform;
}

.imgslider__slide {
  min-width: 100%;
  flex: 0 0 100%;
  height: 100%;
  position: relative;
}

.imgslider__slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

/* optional overlay for readability */
.imgslider__slide::after{
  content:"";
  position:absolute;
  inset:0;
  background: linear-gradient(to right, rgba(0,0,0,.35), rgba(0,0,0,0));
}

/* arrows */
.imgslider__arrow {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 42px;
  height: 42px;
  border-radius: 50%;
  background: rgba(255,255,255,.22);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  z-index: 5;
  user-select: none;
  transition: .2s;
  backdrop-filter: blur(6px);
}
.imgslider__arrow:hover { background: rgba(255,255,255,.35); }
.imgslider__arrow--left { left: 14px; }
.imgslider__arrow--right { right: 14px; }

/* dots */
.imgslider__dots {
  position: absolute;
  bottom: 14px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 8px;
  z-index: 6;
}
.imgslider__dot {
  width: 10px;
  height: 10px;
  border-radius: 999px;
  background: rgba(255,255,255,.45);
  cursor: pointer;
  transition: .2s;
}
.imgslider__dot.is-active { background: #CC2230; }

/* hide controls when only one image */
.imgslider.is-single .imgslider__arrow,
.imgslider.is-single .imgslider__dots { display: none; }

@media (max-width: 768px) {
  .imgslider { height: 240px; border-radius: 12px; }
  .imgslider__arrow { width: 36px; height: 36px; }
}
</style>

<div class="imgslider" id="imgSlider">
  <div class="imgslider__track" id="imgSliderTrack">
    <?php foreach ($imageSliderImages as $img): ?>
      <div class="imgslider__slide">
        <img src="<?= htmlspecialchars($img) ?>" alt="">
      </div>
    <?php endforeach; ?>
  </div>

  <div class="imgslider__arrow imgslider__arrow--left" id="imgSliderPrev">&#10094;</div>
  <div class="imgslider__arrow imgslider__arrow--right" id="imgSliderNext">&#10095;</div>

  <div class="imgslider__dots" id="imgSliderDots"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const root  = document.getElementById("imgSlider");
  const track = document.getElementById("imgSliderTrack");
  const dotsEl= document.getElementById("imgSliderDots");
  const prev  = document.getElementById("imgSliderPrev");
  const next  = document.getElementById("imgSliderNext");

  if (!root || !track) return;

  const slides = Array.from(track.querySelectorAll(".imgslider__slide"));
  const total  = slides.length;

  if (total <= 1) {
    root.classList.add("is-single");
    return;
  }

  let index = 0;
  let timer = null;

  // build dots
  dotsEl.innerHTML = "";
  slides.forEach((_, i) => {
    const d = document.createElement("div");
    d.className = "imgslider__dot" + (i === 0 ? " is-active" : "");
    d.addEventListener("click", () => { go(i); restart(); });
    dotsEl.appendChild(d);
  });
  const dots = Array.from(dotsEl.querySelectorAll(".imgslider__dot"));

  function render() {
    track.style.transform = "translate3d(-" + (index * 100) + "%,0,0)";
    dots.forEach((d, i) => d.classList.toggle("is-active", i === index));
  }

  function go(i) {
    index = (i + total) % total;
    render();
  }

  function restart() {
    clearInterval(timer);
    timer = setInterval(() => go(index + 1), 4200);
  }

  prev && prev.addEventListener("click", () => { go(index - 1); restart(); });
  next && next.addEventListener("click", () => { go(index + 1); restart(); });

  // pause on hover (desktop)
  root.addEventListener("mouseenter", () => clearInterval(timer));
  root.addEventListener("mouseleave", restart);

  // touch safety
  root.addEventListener("touchstart", () => clearInterval(timer), {passive:true});
  root.addEventListener("touchend", restart, {passive:true});

  render();
  restart();
});
</script>