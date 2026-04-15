<?php
require_once __DIR__ . '/init.php';

$result = $mysqli->query("SELECT id, name FROM categories ORDER BY name ASC");

/* Category → SVG icon mapping */
function categoryIcon($name){
    $name = strtolower($name);

    switch ($name) {

        case 'cleanser':
        case 'cleansers':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" id="soap">
  <defs>
    <linearGradient id="a" x1="97.274" x2="468.814" y1="472.426" y2="100.886" gradientUnits="userSpaceOnUse">
      <stop offset="0" stop-color="#faac1e"></stop>
      <stop offset="1" stop-color="#eb1b45"></stop>
    </linearGradient>
  </defs>
  <path fill="url(#a)" d="M439.717,467.414h-8.536c38.907-36.484,61.67-90.319,61.67-147.756a208.131,208.131,0,0,0-27-103.227c-16.467-28.8-39.393-52.2-66.459-67.948V124.955A33.127,33.127,0,0,0,366.3,91.866H349.945V56.68H360.68a26.488,26.488,0,0,0,0-52.975H266.537a7,7,0,0,0-7,7v2.211H225.276a47.991,47.991,0,0,0-34.157,14.147l-8.8,8.8A17.277,17.277,0,0,0,206.749,60.3l8.8-8.8a13.657,13.657,0,0,1,9.723-4.028h34.261V49.68a7,7,0,0,0,7,7h24.269V91.866h-16.36a33.127,33.127,0,0,0-33.089,33.089v23.527c-27.13,15.775-50.158,39.167-66.753,67.924a206.706,206.706,0,0,0-27.319,103.252c0,57.437,22.762,111.272,61.671,147.756h-9.345a18.486,18.486,0,0,0-18.465,18.464v3.953A18.486,18.486,0,0,0,199.611,508.3H439.717a18.486,18.486,0,0,0,18.465-18.464v-3.953A18.486,18.486,0,0,0,439.717,467.414ZM225.276,33.468A27.571,27.571,0,0,0,205.652,41.6l-8.8,8.8a3.279,3.279,0,0,1-4.633,0h0a3.272,3.272,0,0,1,0-4.632l8.8-8.8a34.073,34.073,0,0,1,24.256-10.048h34.261v6.552Zm48.261,7.019V17.7H360.68a12.488,12.488,0,0,1,0,24.975H273.537ZM304.806,56.68h31.139V91.866H304.806Zm-49.449,68.275a19.112,19.112,0,0,1,19.089-19.089H366.3a19.112,19.112,0,0,1,19.09,19.089v20.584H255.357Zm67.925,201.072c48.577-29.327,114.744-7.763,132.043-1.265-1.354,46.838-21.306,90.929-54.023,119.2H238.834c-31.413-27.148-51.058-68.879-53.772-113.621a238.165,238.165,0,0,0,40.053,10.167C263.4,346.7,297.344,341.688,323.282,326.027Zm-7.236-11.984c-.228.138-.46.265-.689.4v-154.9h10.037V309.033Q320.564,311.327,316.046,314.043Zm-154.761,5.615c0-67.012,34.86-129.639,88.936-160.119h51.136v161.7c-54.061,21.084-119.9-7.221-120.8-7.611a7,7,0,0,0-9.824,6.42,189.176,189.176,0,0,0,16.154,76.366,166.861,166.861,0,0,0,44.893,59.929,7,7,0,0,0,4.48,1.621H403.871a7,7,0,0,0,4.48-1.621,166.865,166.865,0,0,0,44.893-59.93A189.138,189.138,0,0,0,469.4,320.05a7,7,0,0,0-4.2-6.43,228.6,228.6,0,0,0-50.591-14.062c-27.873-4.472-53.406-3.087-75.215,3.94V159.539h51.135c53.7,30.283,88.322,92.909,88.322,160.119,0,59.256-25.849,114.292-69.239,147.756H230.523C187.134,433.95,161.285,378.913,161.285,319.658Zm282.9,170.173a4.47,4.47,0,0,1-4.465,4.464H199.611a4.47,4.47,0,0,1-4.465-4.464v-3.953a4.47,4.47,0,0,1,4.465-4.464H439.717a4.47,4.47,0,0,1,4.465,4.464ZM151.108,118.188a22.146,22.146,0,1,0-22.146-22.146A22.171,22.171,0,0,0,151.108,118.188Zm0-30.292a8.146,8.146,0,1,1-8.146,8.146A8.155,8.155,0,0,1,151.108,87.9ZM59.4,188.308a26.5,26.5,0,1,0-26.5-26.5A26.527,26.527,0,0,0,59.4,188.308Zm0-39a12.5,12.5,0,1,1-12.5,12.5A12.512,12.512,0,0,1,59.4,149.312Zm49.748,73.117a37.249,37.249,0,1,0,37.248,37.248A37.291,37.291,0,0,0,109.146,222.429Zm0,60.5a23.249,23.249,0,1,1,23.248-23.249A23.276,23.276,0,0,1,109.146,282.926ZM63.9,331.046a44.75,44.75,0,1,0,44.75,44.75A44.8,44.8,0,0,0,63.9,331.046Zm0,75.5A30.75,30.75,0,1,1,94.648,375.8,30.784,30.784,0,0,1,63.9,406.545Z"></path>
</svg>'; 

        case 'toner':
        case 'toners':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" id="cleansing-water">
  <linearGradient id="a" x1="155.582" x2="287.138" y1="46.362" y2="391.251" gradientUnits="userSpaceOnUse">
    <stop offset="0" stop-color="#ffd3b6"></stop>
    <stop offset=".485" stop-color="#e68688"></stop>
    <stop offset="1" stop-color="#cc4f66"></stop>
  </linearGradient>
  <path fill="url(#a)" d="M203.588 512h104.648c41.727 0 75.676-33.949 75.676-75.676V182.316c0-26.43-14.078-51.293-36.742-64.895a59.568 59.568 0 0 1-11.492-8.969l-15.766-15.766V16c0-8.824-7.176-16-16-16h-96c-8.824 0-16 7.176-16 16v76.688l-15.766 15.766a59.648 59.648 0 0 1-11.496 8.973c-22.66 13.598-36.738 38.461-36.738 64.891v254.008c0 41.725 33.949 75.674 75.676 75.674zm96.316-385.539c5.285 7.379 11.062 13.602 17.223 18.627-15.358.929-30.75.981-45.328-2.182a222.22 222.22 0 0 1-5.262-1.25c-7.043-1.73-14.305-3.523-22.203-2.996-6.133.414-11.637 2.168-16.957 3.863-2.742.875-5.484 1.758-8.27 2.395-7.961 1.793-16.234 1.459-24.542.289 6.211-5.044 12.029-11.311 17.354-18.738L228.022 104h55.78l16.102 22.461zM167.912 360h176v76.324c0 19.672-16.004 35.676-35.676 35.676H203.588c-19.672 0-35.676-16.004-35.676-35.676V360zm176-16h-176V232h176v112zm0-128h-176v-33.684c0-9.04 3.43-17.475 9.412-24.001 9.362 1.988 19.354 3.876 29.627 3.876 5.188 0 10.441-.48 15.699-1.672 3.234-.738 6.414-1.742 9.586-2.75 4.617-1.473 8.98-2.863 13.168-3.145 5.367-.375 11.18 1.062 17.312 2.57 1.879.461 3.758.922 5.664 1.344 22.414 4.856 45.247 3.056 67.35 1.206 5.209 6.314 8.182 14.181 8.182 22.572V216zm-136-128V40h9.42c4.473 13.762 19.998 24 38.58 24s34.107-10.238 38.58-24h9.42v48h-96zm27.443-48h41.115c-4.227 4.724-11.905 8-20.558 8s-16.331-3.276-20.557-8zm68.557-24v8h-96v-8h96zm-160 166.316c0-20.84 11.102-40.449 28.969-51.168a75.765 75.765 0 0 0 14.578-11.383L203.225 104h5.11l-9.423 13.148c-6.008 8.383-12.625 14.93-19.672 19.465l-1.988 1.242c-15.867 9.574-25.34 26.195-25.34 44.461v254.008c0 28.492 23.184 51.676 51.676 51.676h104.648c28.492 0 51.676-23.184 51.676-51.676V182.316c0-18.262-9.48-34.887-25.363-44.48l-1.941-1.207c-7.059-4.535-13.684-11.09-19.695-19.484L303.49 104h5.109l15.766 15.766a75.685 75.685 0 0 0 14.574 11.379c17.871 10.723 28.973 30.332 28.973 51.172v254.008c0 32.906-26.77 59.676-59.676 59.676H203.588c-32.906 0-59.676-26.77-59.676-59.676V182.316z"></path>
  <linearGradient id="b" x1="120.32" x2="251.875" y1="59.813" y2="404.701" gradientUnits="userSpaceOnUse">
    <stop offset="0" stop-color="#ffd3b6"></stop>
    <stop offset=".485" stop-color="#e68688"></stop>
    <stop offset="1" stop-color="#cc4f66"></stop>
  </linearGradient>
  <path fill="url(#b)" d="M176.076 304.555c.93 10.004 6.02 18.52 14.715 24.633 6.414 4.512 13.77 6.77 21.121 6.77s14.707-2.258 21.121-6.77c8.695-6.113 13.785-14.629 14.719-24.633 2.582-27.68-26.754-58.691-30.109-62.137-3.008-3.094-8.449-3.098-11.465.004-3.352 3.441-32.688 34.457-30.102 62.133zm35.836-44.493c8.898 10.828 21.207 29.191 19.906 43.02-.508 5.406-3.047 9.543-7.988 13.02-7.352 5.172-16.484 5.172-23.836 0-4.941-3.477-7.48-7.609-7.988-13.016-1.301-13.832 11.008-32.195 19.906-43.024z"></path>
  <linearGradient id="c" x1="194.312" x2="325.868" y1="31.589" y2="376.478" gradientUnits="userSpaceOnUse">
    <stop offset="0" stop-color="#ffd3b6"></stop>
    <stop offset=".485" stop-color="#e68688"></stop>
    <stop offset="1" stop-color="#cc4f66"></stop>
  </linearGradient>
  <path fill="url(#c)" d="M263.912 264h32a8 8 0 0 0 0-16h-32a8 8 0 0 0 0 16z"></path>
  <linearGradient id="d" x1="197.624" x2="329.179" y1="30.325" y2="375.214" gradientUnits="userSpaceOnUse">
    <stop offset="0" stop-color="#ffd3b6"></stop>
    <stop offset=".485" stop-color="#e68688"></stop>
    <stop offset="1" stop-color="#cc4f66"></stop>
  </linearGradient>
  <path fill="url(#d)" d="M263.912 296h64a8 8 0 0 0 0-16h-64a8 8 0 0 0 0 16z"></path>
  <linearGradient id="e" x1="186.968" x2="318.524" y1="34.39" y2="379.279" gradientUnits="userSpaceOnUse">
    <stop offset="0" stop-color="#ffd3b6"></stop>
    <stop offset=".485" stop-color="#e68688"></stop>
    <stop offset="1" stop-color="#cc4f66"></stop>
  </linearGradient>
  <path fill="url(#e)" d="M263.912 328h64a8 8 0 0 0 0-16h-64a8 8 0 0 0 0 16z"></path>
</svg>';
        case 'serum':
        case 'serums':
            return '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M11 2h2v8l3 3v9H8v-9l3-3z"/></svg>';

        case 'moisturizer':
        case 'moisturizers':
        case 'cream':
            return '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M4 6h16v12H4zM2 4h20v2H2z"/></svg>';

        case 'mask':
        case 'masks':
            return '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M4 5h16l-2 14H6z"/></svg>';

        case 'sunscreen':
        case 'sun care':
            return '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2v4m0 12v4m10-10h-4M6 12H2m14.1-7.1l-2.8 2.8M8.7 15.3l-2.8 2.8M15.3 15.3l2.8 2.8M8.7 8.7L5.9 5.9M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>';

        case 'makeup':
            return '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M7 2l10 10-3 3L4 5zM2 22l4-4 4 4z"/></svg>';

        default:
            // Fallback icon
            return '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M4 4h16v16H4z"/></svg>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Shop</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
  margin:0;
  font-family:Arial, sans-serif;
  background:#f7f7f7;
}

/* Header */
.shop-header{
  padding:16px;
  font-size:20px;
  font-weight:700;
  background:#fff;
  border-bottom:1px solid #eee;
}

/* List */
.category-list{padding:12px}

/* Item */
.category-item{
  display:flex;
  align-items:center;
  gap:14px;
  padding:14px 16px;
  background:#fff;
  border-radius:14px;
  margin-bottom:10px;
  text-decoration:none;
  color:#222;
  box-shadow:0 2px 6px rgba(0,0,0,.05);
}

/* Icon container */
.category-icon{
  width:44px;
  height:44px;
  border-radius:50%;
  color:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  flex-shrink:0;
}

.category-icon svg{
  width:40px;
  height:40px;
}

/* Name */
.category-name{
  font-size:15px;
  font-weight:600;
}

/* Arrow */
.category-arrow{
  margin-left:auto;
  font-size:18px;
  color:#aaa;
}
</style>
</head>

<body>

<div class="shop-header">Shop by Category</div>

<div class="category-list">
<?php while($cat = $result->fetch_assoc()): ?>
  <a href="category.php?id=<?= (int)$cat['id'] ?>"
   class="category-item"
   onclick="openInMainSite(this.href); return false;">
    
    <div class="category-icon">
      <?= categoryIcon($cat['name']); ?>
    </div>

    <div class="category-name">
      <?= htmlspecialchars($cat['name']); ?>
    </div>

    <div class="category-arrow">›</div>
 
  </a>
<?php endwhile; ?>
</div>
<script>
function openInMainSite(url){
  // Close slide-up if present
  if (window.parent && window.parent.closeShopSheet) {
    window.parent.closeShopSheet();
  }

  // Navigate main site
  window.top.location.href = url;
}
</script>

</body>
</html>

