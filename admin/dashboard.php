<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once __DIR__ . '/adminconfig.php';
require_once __DIR__ . '/auth.php';

// QUICK STATS
$users   = $mysqli->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'] ?? 0;
$orders  = $mysqli->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'] ?? 0;
$revenue = $mysqli->query("SELECT IFNULL(SUM(total),0) as total FROM orders WHERE status != 'cancelled'")->fetch_assoc()['total'] ?? 0;
$pending = $mysqli->query("SELECT COUNT(*) as total FROM orders WHERE status = 'received'")->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

body {
    background: #f3f4f6;
}

/* ================= TOPBAR ================= */
.topbar {
    background: #111827;
    color: white;
    padding: 16px 20px;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logout {
    color: #f87171;
    text-decoration: none;
    font-size: 14px;
}

.logout:hover {
    text-decoration: underline;
}

/* ================= CONTAINER ================= */
.container {
    padding: 20px;
}

/* ================= STATS GRID ================= */
.grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 15px;
}

.card {
    background: white;
    padding: 18px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: 0.2s ease;
}

.card:hover {
    transform: translateY(-3px);
}

.card h3 {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card p {
    font-size: 26px;
    font-weight: bold;
    color: #111827;
}

/* ================= MENU ================= */
.menu {
    margin-top: 30px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.menu a {
    text-decoration: none;
    background: #1f2937;
    color: white;
    padding: 14px;
    border-radius: 10px;
    text-align: center;
    font-size: 14px;
    font-weight: 500;
    transition: 0.2s ease;
}

.menu a:hover {
    background: #cc2230;
}

/* ================= DESKTOP ENHANCEMENT ================= */
@media (min-width: 768px) {
    .grid {
        grid-template-columns: repeat(4, 1fr);
    }

    .menu {
        grid-template-columns: repeat(4, 1fr);
    }
}
</style>
</head>

<body>

<div class="topbar">
    <span>Admin Dashboard</span>
    <a class="logout" href="logout.php">Logout</a>
</div>

<div class="container">

    <div class="grid">
        <div class="card">
            <h3>Total Users</h3>
            <p><?php echo number_format($users); ?></p>
        </div>

        <div class="card">
            <h3>Total Orders</h3>
            <p><?php echo number_format($orders); ?></p>
        </div>

        <div class="card">
            <h3>Pending Orders</h3>
            <p><?php echo number_format($pending); ?></p>
        </div>

        <div class="card">
            <h3>Total Revenue</h3>
            <p><?php echo number_format($revenue); ?> Ks</p>
        </div>
    </div>

    <div class="menu">
        <a href="orders.php">Orders</a>
        <a href="products.php">Products</a>
        <a href="categories.php">Categories</a>
        <a href="brands.php">Brands</a>
        <a href="sales.php">Sales</a>
        <a href="points.php">Loyalty Points</a>
        <a href="rewards/index.php">Rewards</a>
    </div>

</div>

</body>
</html>