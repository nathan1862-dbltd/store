<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/header.php';

ensureSessionStarted();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = strtolower(trim($_POST['username'] ?? ''));
    $newPassword = $_POST['password'] ?? '';

    if ($username === '' || $newPassword === '') {
        $error = "All fields are required.";
    } else {

        // Find user by username
        $stmt = $mysqli->prepare("
            SELECT id, username
            FROM users
            WHERE LOWER(username) = ?
            LIMIT 1
        ");

        if (!$stmt) {
            die("DB Error: " . $mysqli->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user) {

            // Hash password correctly
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            $stmt = $mysqli->prepare("
                UPDATE users
                SET password_hash = ?
                WHERE id = ?
            ");

            $stmt->bind_param("si", $hashedPassword, $user['id']);
            $stmt->execute();
            $stmt->close();

            $success = "Password reset successfully for user: " . htmlspecialchars($user['username']);

        } else {
            $error = "Username not found.";
        }
    }
}
?>

<div class="container" style="max-width:420px;margin:80px auto;">
    <h2>Temporary Password Reset</h2>

    <?php if ($error): ?>
        <div style="background:#f8d7da;padding:10px;border-radius:6px;color:#721c24;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="background:#d4edda;padding:10px;border-radius:6px;color:#155724;">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="New Password" required>

        <button type="submit"
            style="background:#CC2230;color:#fff;padding:12px;border:none;border-radius:6px;width:100%;">
            Reset Password
        </button>
    </form>

    <p style="margin-top:20px;font-size:13px;color:#888;">
        ⚠ Delete this file after testing.
    </p>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>