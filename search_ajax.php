<?php
require_once __DIR__ . '/init.php';

$q = trim($_GET['q'] ?? '');

if (strlen($q) < 2) {
    exit;
}

$qLike = "%{$q}%";

$output = "";

/* PRODUCTS */
$stmt = $mysqli->prepare("SELECT id, name FROM products WHERE name LIKE ? LIMIT 6");
$stmt->bind_param("s", $qLike);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows) {
    $output .= '<div class="section-title">Products</div>';
    while ($r = $res->fetch_assoc()) {
        $output .= '<a href="/product.php?id='.$r['id'].'">'.htmlspecialchars($r['name']).'</a>';
    }
}

/* BRANDS */
$stmt = $mysqli->prepare("SELECT id, name FROM brands WHERE name LIKE ? LIMIT 4");
$stmt->bind_param("s", $qLike);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows) {
    $output .= '<div class="section-title">Brands</div>';
    while ($r = $res->fetch_assoc()) {
        $output .= '<a href="/brand.php?id='.$r['id'].'">'.htmlspecialchars($r['name']).'</a>';
    }
}

/* CATEGORIES */
$stmt = $mysqli->prepare("SELECT id, name FROM categories WHERE name LIKE ? LIMIT 4");
$stmt->bind_param("s", $qLike);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows) {
    $output .= '<div class="section-title">Categories</div>';
    while ($r = $res->fetch_assoc()) {
        $output .= '<a href="/category.php?id='.$r['id'].'">'.htmlspecialchars($r['name']).'</a>';
    }
}

if ($output === "") {
    $output = '<a style="color:#999;pointer-events:none">No results found</a>';
}

echo $output;
