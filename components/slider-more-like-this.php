<?php
/**
 * NEW ARRIVALS SLIDER (NO BADGES)
 *
 * Optional:
 * $productId → exclude current product
 */

$where  = [];
$params = [];
$types  = '';

if (isset($productId) && !empty($productId)) {
    $where[]  = 'p.id != ?';
    $params[] = $productId;
    $types   .= 'i';
}


$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$sql = "
    SELECT
        p.id,
        p.name,       
        p.from_price,
        p.image_path,
        p.created_at,
        b.name AS brand_name
    FROM products p
    LEFT JOIN brands b ON b.id = p.brand_id
    $whereSQL
    ORDER BY RAND()
    LIMIT 12
";

$stmt = $mysqli->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$products = $stmt->get_result();

if (!$products || $products->num_rows === 0) {
    return;
}

$sliderId    = 'more';
$sliderTitle = 'Check These, too';

include __DIR__ . '/slider-wrapper.php';