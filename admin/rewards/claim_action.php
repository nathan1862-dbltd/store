<?php
require_once __DIR__ . '/_bootstrap.php';

admin_require();

$id = (int) ($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';

if ($id <= 0 || !in_array($action, ['approve', 'reject'], true)) {
    header('Location: claims_list.php');
    exit;
}

$mysqli->begin_transaction();

try {
    $stmt = $mysqli->prepare('SELECT * FROM reward_claims WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $claim = $stmt->get_result()->fetch_assoc();

    if (!$claim) {
        throw new Exception('Claim not found');
    }

    if ($claim['status'] !== 'pending') {
        throw new Exception('Already processed');
    }

    $userId = (int) $claim['user_id'];
    $rewardId = (int) $claim['reward_id'];
    $pts = (int) $claim['points_used'];

    if ($action === 'approve') {
        $mysqli->query("UPDATE reward_claims SET status = 'approved' WHERE id = $id LIMIT 1");
        $mysqli->query("UPDATE reward_items SET stock = GREATEST(stock - 1, 0) WHERE id = $rewardId LIMIT 1");

        $stmt = $mysqli->prepare('INSERT INTO points_ledger (user_id, change_amount, reason, reference_id) VALUES (?, ?, ?, ?)');
        $reason = 'reward_approved';
        $zero = 0;
        $stmt->bind_param('iisi', $userId, $zero, $reason, $id);
        $stmt->execute();
    } else {
        $mysqli->query("UPDATE reward_claims SET status = 'rejected' WHERE id = $id LIMIT 1");
        $mysqli->query("INSERT INTO user_points (user_id, points) VALUES ($userId, $pts) ON DUPLICATE KEY UPDATE points = points + $pts");

        $stmt = $mysqli->prepare('INSERT INTO points_ledger (user_id, change_amount, reason, reference_id) VALUES (?, ?, ?, ?)');
        $reason = 'reward_rejected_refund';
        $stmt->bind_param('iisi', $userId, $pts, $reason, $id);
        $stmt->execute();
    }

    $mysqli->commit();
} catch (Exception $e) {
    $mysqli->rollback();
}

header('Location: claims_list.php');
exit;
