<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- MOBILE BOTTOM MENU -->

<div class="bottom-nav-wrapper">

    <div class="bottom-nav">

        <!-- HOME -->
        <a href="index.php"
           class="nav-item <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
  <polyline points="9 22 9 12 15 12 15 22"/>
</svg>


        </a>

        <!-- SHOP -->
        <a href="shop.php"
           class="nav-item <?php echo ($current_page == 'shop.php') ? 'active' : ''; ?>">

            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
  <line x1="3" y1="6" x2="21" y2="6"/>
  <path d="M16 10a4 4 0 0 1-8 0"/>
</svg>
        </a>

        <!-- SETTINGS -->
        <a href="settings.php"
           class="nav-item <?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>">

            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <path d="M12.59 21.41A2 2 0 0 1 11.17 22H3a1 1 0 0 1-1-1v-8.17a2 2 0 0 1 .59-1.42l9.58-9.58a2 2 0 0 1 2.83 0l6 6a2 2 0 0 1 0 2.83z"/>
  <circle cx="7.5" cy="7.5" r="1.5"/>
  <path d="M9 12l6 6"/>
  <path d="M15 12l-6 6"/>
</svg>

        </a>

        <!-- PROFILE -->
        <a href="profile.php"
           class="nav-item <?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">

           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <path d="M12.59 21.41A2 2 0 0 1 11.17 22H3a1 1 0 0 1-1-1v-8.17a2 2 0 0 1 .59-1.42l9.58-9.58a2 2 0 0 1 2.83 0l6 6a2 2 0 0 1 0 2.83z"/>
  <circle cx="7.5" cy="7.5" r="1.5"/>
  <path d="M9 12l6 6"/>
  <path d="M15 12l-6 6"/>
</svg>

        </a>

    </div>

</div>

<style>

*{
    box-sizing:border-box;
}

body{
    padding-bottom:120px;
}

/* NAV WRAPPER */

.bottom-nav-wrapper{
    position:fixed;

    bottom:20px;
    left:50%;

    transform:translateX(-50%);

    width:100%;
    max-width:420px;

    padding:0 16px;

    z-index:999;
}

/* NAVIGATION */

.bottom-nav{
    height:74px;

    display:flex;
    align-items:center;
    justify-content:space-around;

    border-radius:999px;

    background:rgba(255,255,255,0.78);

    backdrop-filter:blur(20px);
    -webkit-backdrop-filter:blur(20px);

    border:1px solid rgba(255,255,255,0.45);

    box-shadow:
        0 10px 30px rgba(0,0,0,0.06),
        inset 0 1px 0 rgba(255,255,255,0.55);
}

/* MENU ITEM */

.nav-item{
    width:50px;
    height:50px;

    border-radius:999px;

    display:flex;
    align-items:center;
    justify-content:center;

    text-decoration:none;

    color:#71717a;

    transition:all 0.25s ease;
}

/* ICON */

.nav-item svg{
    width:24px;
    height:24px;

    stroke:currentColor;
}

/* HOVER */

.nav-item:hover{
    transform:translateY(-2px);
}

/* ACTIVE */

.nav-item.active{
    background:#6b7280;

    color:#ffffff;

    box-shadow:
        0 10px 20px rgba(107,114,128,0.30);
}

/* ACTIVE ICON */

.nav-item.active svg{
    stroke:#ffffff;
}

</style>