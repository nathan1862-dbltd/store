STAND-ALONE PRODUCT SLIDER

Dependencies:
- product-card.php
- $mysqli active

Usage:

$sliderTitle = "New Arrivals";
$sliderLimit = 10;
$sliderQuery = "SELECT * FROM products WHERE status=1 ORDER BY created_at DESC LIMIT 10";
include __DIR__.'/components/product-sliders/product-slider.php';

Supports:
- New Arrivals
- Best Sellers
- Random Products
- Related Products
