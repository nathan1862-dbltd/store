<?php
require_once __DIR__ . '/_bootstrap.php';
admin_require();
$id=(int)($_POST['id']??0);
$title=trim($_POST['title']??'');
$desc=trim($_POST['description']??'');
$points=(int)($_POST['points_required']??0);
$stock=(int)($_POST['stock']??0);
$image=trim($_POST['image_path']??'');
if($id<=0||$title===''||$points<=0){die('Invalid input');}
$id=(int)($_POST['id']??0);
$title=trim($_POST['title']??'');
$desc=trim($_POST['description']??'');
$points=(int)($_POST['points_required']??0);
$stock=(int)($_POST['stock']??0);
$image=trim($_POST['image_path']??'');
if($id<=0||$title===''||$points<=0){die('Invalid input');}
$id=(int)($_POST['id']??0);
$title=trim($_POST['title']??'');
$desc=trim($_POST['description']??'');
$points=(int)($_POST['points_required']??0);
$stock=(int)($_POST['stock']??0);
$image=trim($_POST['image_path']??'');
if($id<=0||$title===''||$points<=0){die('Invalid input');}
$stmt=$mysqli->prepare("UPDATE reward_items SET title=?, description=?, points_required=?, stock=?, image_path=? WHERE id=? LIMIT 1");
$stmt->bind_param('ssiisi',$title,$desc,$points,$stock,$image,$id);
$stmt->execute();
header('Location: rewards_list.php');
exit;
