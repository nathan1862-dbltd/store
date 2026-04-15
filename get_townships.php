<?php
require_once __DIR__ . '/init.php';

header('Content-Type: application/json');

$stateId = (int)($_GET['state'] ?? 0);

if ($stateId <= 0) {
    echo json_encode([]);
    exit;
}

$stmt = $mysqli->prepare("
    SELECT id, name, shipping_fee
    FROM shipping_townships
    WHERE state_id = ? AND is_active = 1
    ORDER BY name ASC
");

$stmt->bind_param("i", $stateId);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'shipping_fee' => (float)$row['shipping_fee']
    ];
}

$stmt->close();

echo json_encode($data);
exit;