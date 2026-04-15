<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/_helpers.php';

ensureSessionStarted();
$userId = currentUserId();
if (!$userId) redirect('login.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('rewards.php');
$rewardId = isset($_POST['reward_id']) ? (int)$_POST['reward_id'] : 0;
if ($rewardId <= 0) { flash('err', 'Invalid reward selection.'); redirect('rewards.php'); }

$mysqli->begin_transaction();
try {
  // Lock reward row
  $stmt = $mysqli->prepare("SELECT id, points_required, stock, is_active FROM reward_items WHERE id = ? FOR UPDATE");
  $stmt->bind_param('i', $rewardId);
  $stmt->execute();
  $reward = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  if (!$reward || (int)$reward['is_active'] !== 1) {
    throw new Exception('Reward is not available.');
  }
  if ((int)$reward['stock'] <= 0) {
    throw new Exception('Reward is out of stock.');
  }

  // Lock user points row
  $stmt = $mysqli->prepare("SELECT points FROM user_points WHERE user_id = ? FOR UPDATE");
  $stmt->bind_param('i', $userId);
  $stmt->execute();
  $up = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  $currentPoints = $up ? (int)$up['points'] : 0;
  $need = (int)$reward['points_required'];
  if ($currentPoints < $need) {
    throw new Exception('Insufficient points to claim this reward.');
  }

  // Deduct points
  if ($up) {
    $stmt = $mysqli->prepare("UPDATE user_points SET points = points - ? WHERE user_id = ?");
    $stmt->bind_param('ii', $need, $userId);
    $stmt->execute();
    $stmt->close();
  } else {
    // In the unlikely case wallet wasn't created
    $stmt = $mysqli->prepare("INSERT INTO user_points (user_id, points) VALUES (?, ?)");
    $zero = 0;
    $stmt->bind_param('ii', $userId, $zero);
    $stmt->execute();
    $stmt->close();
    throw new Exception('Points wallet was missing. Please try again.');
  }

  // Create claim (pending approval)
  $stmt = $mysqli->prepare("INSERT INTO reward_claims (user_id, reward_id, points_used) VALUES (?, ?, ?)");
  $stmt->bind_param('iii', $userId, $rewardId, $need);
  $stmt->execute();
  $stmt->close();

  $mysqli->commit();
  flash('ok', 'Claim submitted. Status: pending approval.');
  redirect('customer_rewards.php');

} catch (Throwable $e) {
  $mysqli->rollback();
  flash('err', $e->getMessage());
  redirect('rewards.php');
}
