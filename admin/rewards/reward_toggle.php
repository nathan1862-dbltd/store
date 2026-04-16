<?php
require_once __DIR__ . '/_bootstrap.php';
admin_require();
$id=(int)($_GET['id']??0);
if($id<=0){header('Location: rewards_list.php');exit;}
$mysqli->query("UPDATE reward_items SET is_active = IF(is_active=1,0,1) WHERE id=\$id LIMIT 1");
header('Location: rewards_list.php');
exit;
