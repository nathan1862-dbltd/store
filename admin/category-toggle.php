<?php
require_once __DIR__ . '/_common.php';
$id = (int)($_GET['id'] ?? 0);
if ($id<=0) apm_redirect('categories.php');
$stmt = $mysqli->prepare("UPDATE categories SET is_active = IF(is_active=1,0,1) WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute(); $stmt->close();
apm_flash_set('ok','Category status updated.');
apm_redirect('categories.php');
