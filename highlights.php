<?php require 'admin_auth.php'; require '../config.php'; require 'admin_header.php';
if($_POST){$s=$mysqli->prepare("INSERT INTO highlights(title)VALUES(?)");$s->bind_param("s",$_POST['title']);$s->execute();header("Location: highlights.php");}
$r=$mysqli->query("SELECT * FROM highlights ORDER BY id DESC"); ?>
<div class="card"><h3>Add Highlight</h3><form method="post">
<input name="title" placeholder="Highlight"><button>Add</button></form></div>
<div class="card"><h3>List</h3><table>
<?php while($c=$r->fetch_assoc()): ?><tr><td><?=$c['id']?></td><td><?=htmlspecialchars($c['title'])?></td></tr><?php endwhile; ?>
</table></div><?php require 'admin_footer.php'; ?>