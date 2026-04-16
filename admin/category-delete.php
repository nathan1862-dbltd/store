<?php
require_once __DIR__ . '/_common.php';
$id = (int)($_GET['id'] ?? 0);
if ($id<=0) apm_redirect('categories.php');
$stmt = $mysqli->prepare("DELETE FROM categories WHERE id=?");
$stmt->bind_param("i", $id);
try { $stmt->execute(); apm_flash_set('ok','Category deleted.'); }
catch (Throwable $e) { apm_flash_set('err','Delete failed: '.$e->getMessage()); }
$stmt->close();
apm_redirect('categories.php');
