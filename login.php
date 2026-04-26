<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/init.php';

ensureSessionStarted();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = strtolower(trim($_POST['username'] ?? ''));
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Please fill in all fields.";
    } else {

        $stmt = $mysqli->prepare("
            SELECT id, username, password_hash, is_admin
            FROM users
            WHERE LOWER(username) = ?
            LIMIT 1
        ");

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password_hash'])) {

            session_regenerate_id(true);

            $_SESSION['user_id']   = (int)$user['id'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['is_admin']  = (int)$user['is_admin'];

            if ($user['is_admin'] == 1) {
                header("Location: /admin/dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit;

        } else {
            $error = "Invalid username or password.";
        }
    }
}
$pageTitle = 'Login';
include __DIR__ . '/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | Delux Beauti</title>
<link rel="stylesheet" href="/assets/css/layout.css">
</head> 

<body>

    <main class="form-panel">
        <section class="login-card card">
            <h2 class="login-title">Sign in</h2>
            <p class="login-subtitle">Use your account credentials to continue.</p>
            <?php if($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" id="loginForm">
                <div class="field">
                    <label for="username">Username</label>
                    <div class="input-wrap">
                        <input id="username" class="input" type="text" name="username" placeholder="Enter your username" required>
                    </div>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <input type="password" id="password" class="input" name="password" placeholder="Enter your password" required>
                        <button class="toggle" type="button" onclick="togglePassword()">Show</button>
                    </div>
                </div>

                <button class="login-btn btn btn-primary full" type="submit" id="loginBtn">Sign in</button>
            </form>

            <div class="signin-foot">
                New here? <a href="register.php">Create an account</a>
            </div>
        </section>
    </main>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const toggle = document.querySelector('.toggle');
    if (input.type === 'password') {
        input.type = 'text';
        toggle.textContent = 'Hide';
    } else {
        input.type = 'password';
        toggle.textContent = 'Show';
    }
}

document.querySelector('form').addEventListener('submit', function () {
    const btn = document.getElementById('loginBtn');
    btn.textContent = 'Signing in...';
    btn.disabled = true;
});
</script>
</body>

<?php include __DIR__ . '/footer.php'; ?>
