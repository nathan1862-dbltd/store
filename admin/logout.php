<?php
require_once __DIR__ . '/../functions.php';
ensureSessionStarted();
unset($_SESSION['is_admin'],$_SESSION['user_id'],$_SESSION['username']);
header('Location: login.php');
