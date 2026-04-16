<?php
require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config.php';

$id = (int)($_GET['id'] ?? 0);
$to = (int)($_GET['to'] ?? 1);
if ($id > 0) {
  $to = ($to === 1) ? 1 : 0;
  $mysqli->query("UPDATE sales SET is_active = $to WHERE id = $id");
}
header("Location: sales.php");
exit;
