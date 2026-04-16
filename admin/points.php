<?php require_once 'admin_auth.php'; require_once '../init.php';
$res=$mysqli->query("SELECT id,name,points_balance FROM users ORDER BY id DESC");
?>
<h2>Points Management</h2>
<ul>
<?php while($u=$res->fetch_assoc()): ?>
<li>
<?= htmlspecialchars($u['name']) ?> - <?= $u['points_balance'] ?> pts
<a href="points-adjust.php?user_id=<?= $u['id'] ?>">Adjust</a>
</li>
<?php endwhile; ?>
</ul>