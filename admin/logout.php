<?php
require_once __DIR__ . '/auth.php';

Auth::logout();
header('Location: login.php');
exit;
