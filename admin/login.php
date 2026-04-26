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
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>

<h2>Admin Login</h2>

<form method="POST" action="login_process.php">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>

    <button type="submit">Login</button>
</form>

<?php
if (isset($_SESSION['error'])) {
    echo "<p style='color:red'>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']);
}
?>

</body>
</html>
