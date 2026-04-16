<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once __DIR__ . '/adminconfig.php';
require_once __DIR__ . '/auth.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo ADMIN_PANEL_NAME; ?> - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<h2><?php echo ADMIN_PANEL_NAME; ?></h2>
<form method="POST" action="login_process.php">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Login</button>
</form>
</body>
</html>
