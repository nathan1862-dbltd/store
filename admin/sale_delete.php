<?php
require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config.php';

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
  $mysqli->query("DELETE FROM sale_items WHERE sale_id = $id");
  $mysqli->query("DELETE FROM sales WHERE id = $id");
}
header("Location: sales.php");
exit;
