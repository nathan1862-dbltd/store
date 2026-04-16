document.addEventListener("DOMContentLoaded", function(){

  const slider = document.querySelector(".reward-slider");
  if(!slider) return;

  let index = 0;

  const cardWidth = 250;

  document.querySelector(".reward-arrow.left")?.addEventListener("click", function(){
    index = Math.max(index - 1, 0);
    slider.style.transform = `translateX(-${index * cardWidth}px)`;
  });

  document.querySelector(".reward-arrow.right")?.addEventListener("click", function(){
    const maxIndex = slider.children.length - 1;
    index = Math.min(index + 1, maxIndex);
    slider.style.transform = `translateX(-${index * cardWidth}px)`;
  });

});