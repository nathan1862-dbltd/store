<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- BOTTOM MENU -->

<div class="bottom-nav-wrapper">

    <div class="bottom-nav">

        <!-- HOME -->
        <a href="index.php"
           class="nav-item <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5"/>
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
                      d="M6 4v10"/>
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

.bottom-nav{
    height:74px;

    display:flex;
    align-items:center;
    justify-content:space-around;

    border-radius:999px;

    background:rgba(255,255,255,0.75);

    backdrop-filter:blur(20px);

    border:1px solid rgba(255,255,255,0.45);

    box-shadow:
        0 10px 30px rgba(0,0,0,0.06);
}

.nav-item{
    width:50px;
    height:50px;

    border-radius:999px;

    display:flex;
    align-items:center;
    justify-content:center;

    color:#71717a;

    text-decoration:none;

    transition:0.25s ease;
}

.nav-item svg{
    width:24px;
    height:24px;
}

.nav-item.active{
    background:#6b7280;
    color:#ffffff;
}

.nav-item:hover{
    transform:translateY(-2px);
}

body{
    padding-bottom:120px;
}

</style>
<script>

document.addEventListener('DOMContentLoaded', () => {

    const navItems = document.querySelectorAll('.nav-item');

    navItems.forEach(item => {

        item.addEventListener('click', () => {

            navItems.forEach(btn => {
                btn.classList.remove('active');
            });

            item.classList.add('active');

        });

    });

});

</script>