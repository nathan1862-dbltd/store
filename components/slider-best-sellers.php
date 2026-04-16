<?php
$stmt = $mysqli->prepare("
    SELECT p.id, p.name, p.from_price, p.discount_price, p.from_price, p.image_path, b.name AS brand_name
    FROM products p
    LEFT JOIN brands b ON b.id = p.brand_id
    WHERE p.stock > 0
    ORDER BY p.sold_count DESC
    LIMIT 12
");
$stmt->execute();
$products = $stmt->get_result();

$sliderId = 'best-sellers';
$sliderTitle = 'Best Sellers';

include __DIR__ . '/slider-wrapper.php';
