<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once __DIR__ . '/adminconfig.php';
require_once __DIR__ . '/auth.php';

$errorMessage = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo ADMIN_PANEL_NAME; ?> - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<h2><?php echo ADMIN_PANEL_NAME; ?></h2>
<?php if ($errorMessage): ?>
    <p style="color: #c00;"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>
<form method="POST" action="login_process.php">
    <input type="text" name="username" placeholder="Username" autocomplete="username" required><br><br>
    <input type="password" name="password" placeholder="Password" autocomplete="current-password" required><br><br>
    <button type="submit">Login</button>
</form>
</body>
</html>
