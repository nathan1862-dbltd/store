document.querySelectorAll('.product-slider').forEach(slider => {
  const track = slider.querySelector('.product-slider-track');
  const slides = slider.querySelectorAll('.product-slide');
  const prev = slider.querySelector('.ps-nav.prev');
  const next = slider.querySelector('.ps-nav.next');

  if (!track || slides.length === 0) return;

  let index = 0;
  const gap = 16;

  function slideWidth() {
    return slides[0].offsetWidth + gap;
  }

  function maxIndex() {
    const visible = Math.floor(
      slider.querySelector('.product-slider-viewport').offsetWidth / slideWidth()
    );
    return Math.max(0, slides.length - visible);
  }

  function update() {
    index = Math.max(0, Math.min(index, maxIndex()));
    track.style.transform = `translateX(-${index * slideWidth()}px)`;
  }

  next?.addEventListener('click', () => {
    index++;
    update();
  });

  prev?.addEventListener('click', () => {
    index--;
    update();
  });

  // Touch support (mobile swipe)
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