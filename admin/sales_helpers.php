<?php
// admin/sales_helpers.php
function sale_is_live(array $sale): bool {
    $now = new DateTime('now');
    $start = new DateTime($sale['starts_at']);
    $end = new DateTime($sale['ends_at']);
    return (int)$sale['is_active'] === 1 && $now >= $start && $now <= $end;
}

function sale_calc_discount_price(float $price, string $type, float $value): float {
    if ($type === 'percent') {
        $p = $price - ($price * ($value / 100.0));
        return max(0.0, round($p, 2));
    }
    // fixed = final price
    return max(0.0, round($value, 2));
}
?>
