<?php

require_once __DIR__ . '/adminconfig.php';
require_once __DIR__ . '/auth.php';

?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Admin</title>
<style>
*{box-sizing:border-box;font-family:Arial}
body{margin:0;background:#f4f6f8}
.admin{display:flex;min-height:100vh}
.sidebar{width:220px;background:#111;color:#fff;padding:20px}
.sidebar a{display:block;color:#ccc;text-decoration:none;margin:10px 0}
.main{flex:1;padding:30px}
.card{background:#fff;padding:20px;border-radius:12px;margin-bottom:20px}
table{width:100%;border-collapse:collapse}
th,td{padding:10px;border-bottom:1px solid #eee}
button{padding:10px 14px;background:#111;color:#fff;border:none;border-radius:6px}
input{padding:10px;border:1px solid #ccc;border-radius:6px}
</style></head><body><div class="admin">
<div class="sidebar">
<a href="products.php">Products</a>
<a href="categories.php">Categories</a>
<a href="brands.php">Brands</a>
<a href="highlights.php">Highlights</a>
</div><div class="main">