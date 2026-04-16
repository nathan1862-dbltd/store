<?php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/_common.php';

ensureSessionStarted();
if(empty($_SESSION['is_admin'])||$_SESSION['is_admin']!=1){
 header('Location: /admin/login.php'); exit;
}
