<?php
$hero = $mysqli->query("SELECT * FROM hero_slider WHERE id = 1 LIMIT 1")->fetch_assoc();
$heroImages = [];
if ($hero) {
  for ($i=1;$i<=4;$i++){
    $k="image{$i}";
    if(!empty($hero[$k])) $heroImages[]=$hero[$k];
  }
}
if(empty($heroImages)){
  $heroImages=['assets/images/no-image.png'];
}
?>
<div class="hero-slider">
  <div class="hero-track" id="heroTrack">
    <?php foreach($heroImages as $img): ?>
      <div class="hero-slide">
        <img src="<?= htmlspecialchars($img) ?>">
      </div>
    <?php endforeach; ?>
  </div>
  <div class="hero-dots" id="heroDots"></div>
</div>
