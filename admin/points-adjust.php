<?php require_once 'admin_auth.php'; require_once '../init.php';
$user_id=(int)$_GET['user_id'];

if($_SERVER['REQUEST_METHOD']==='POST'){
 $points=(int)$_POST['points'];
 $stmt=$mysqli->prepare("UPDATE users SET points_balance=points_balance+? WHERE id=?");
 $stmt->bind_param("ii",$points,$user_id);
 $stmt->execute();
 header("Location: points.php"); exit;
}
?>
<form method="post">
<input type="number" name="points" placeholder="+/- points" required>
<button>Apply</button>
</form>