<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- MOBILE BOTTOM MENU -->

<div class="bottom-nav-wrapper">

    <div class="bottom-nav">

        <!-- HOME -->
        <a href="index.php"
           class="nav-item <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">

            <?xml version="1.0" encoding="UTF-8"?>
<!-- Generator: Adobe Illustrator 25.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="512" height="512">
<g>
	<path d="M256,319.841c-35.346,0-64,28.654-64,64v128h128v-128C320,348.495,291.346,319.841,256,319.841z"/>
	<g>
		<path d="M362.667,383.841v128H448c35.346,0,64-28.654,64-64V253.26c0.005-11.083-4.302-21.733-12.011-29.696l-181.29-195.99    c-31.988-34.61-85.976-36.735-120.586-4.747c-1.644,1.52-3.228,3.103-4.747,4.747L12.395,223.5    C4.453,231.496-0.003,242.31,0,253.58v194.261c0,35.346,28.654,64,64,64h85.333v-128c0.399-58.172,47.366-105.676,104.073-107.044    C312.01,275.383,362.22,323.696,362.667,383.841z"/>
		<path d="M256,319.841c-35.346,0-64,28.654-64,64v128h128v-128C320,348.495,291.346,319.841,256,319.841z"/>
	</g>
</g>
</svg>


        </a>

        <!-- SHOP -->
        <a href="shop.php"
           class="nav-item <?php echo ($current_page == 'shop.php') ? 'active' : ''; ?>">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4"/>
            </svg>

        </a>

        <!-- ADD -->
        <a href="add.php"
           class="nav-item <?php echo ($current_page == 'add.php') ? 'active' : ''; ?>">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>

        </a>

        <!-- SETTINGS -->
        <a href="settings.php"
           class="nav-item <?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">

                <path stroke-linecap="round"
                      stroke-width="2"
                      d="M6 4v10m0 0a2 2 0 1 0 0 4"/>
            </svg>

        </a>

        <!-- PROFILE -->
        <a href="profile.php"
           class="nav-item <?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 21a9 9 0 1 0 0-18"/>
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