<?php
require_once __DIR__ . '/_bootstrap.php';
admin_require();
$title = trim($_POST['title'] ?? '');
$desc = trim($_POST['description'] ?? '');
$points = (int)($_POST['points_required'] ?? 0);
$stock = (int)($_POST['stock'] ?? 0);
$image = trim($_POST['image_path'] ?? '');

if ($title==='' || $points<=0){die('Invalid input');}
$stmt = $mysqli->prepare("INSERT INTO reward_items (title, description, points_required, stock, image_path) VALUES (?,?,?,?,?)");
$stmt->bind_param('ssiis', $title, $desc, $points, $stock, $image);
$stmt->execute();
header('Location: rewards_list.php'); exit;
if ($title==='' || $points<=0){die('Invalid input');}
$stmt = $mysqli->prepare("INSERT INTO reward_items (title, description, points_required, stock, image_path) VALUES (?,?,?,?,?)");
$stmt->bind_param('ssiis', $title, $desc, $points, $stock, $image);
$stmt->execute();
header('Location: rewards_list.php');
exit;
