<?php
require_once __DIR__ . '/_common.php';
$id = (int)($_GET['id'] ?? 0);
$product_id = (int)($_GET['product_id'] ?? 0);
if ($id<=0) apm_redirect('variants.php?product_id='.$product_id);

$stmt = $mysqli->prepare("DELETE FROM product_variants WHERE id=?");
$stmt->bind_param("i",$id);
try { $stmt->execute(); apm_flash_set('ok','Variant deleted.'); }
catch (Throwable $e) { apm_flash_set('err','Delete failed: '.$e->getMessage()); }
$stmt->close();

apm_redirect('variants.php?product_id='.$product_id);
