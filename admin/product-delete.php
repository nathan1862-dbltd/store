<?php
require_once __DIR__ . '/_common.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) apm_redirect('products.php');

/* Guardrail: prevent deletion if exists in order_items */
$stmt = $mysqli->prepare("SELECT 1 FROM order_items WHERE product_id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$exists = (bool)$stmt->get_result()->fetch_row();
$stmt->close();

if ($exists) {
  apm_flash_set('err', 'Cannot delete: product exists in order history.');
  apm_redirect('products.php');
}

$stmt = $mysqli->prepare("DELETE FROM products WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);

try { $stmt->execute(); apm_flash_set('ok', 'Product deleted.'); }
catch (Throwable $e) { apm_flash_set('err', 'Delete failed: '.$e->getMessage()); }
$stmt->close();

apm_redirect('products.php');
