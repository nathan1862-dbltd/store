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
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | Delux Beauti</title>

<style>
* { box-sizing: border-box; }
body{
  margin:0;
  min-height:100vh;
  font-family: "Inter","Segoe UI",Roboto,Arial,sans-serif;
  background:
    radial-gradient(circle at 85% 10%, rgba(255,255,255,.08), transparent 35%),
    linear-gradient(145deg, #0f172a, #1e293b 55%, #111827);
  color:#0f172a;
}

.login-layout{
  min-height:100vh;
  display:grid;
  grid-template-columns: 1.1fr 1fr;
}

.brand-panel{
  padding:64px 56px;
  color:#f8fafc;
  display:flex;
  flex-direction:column;
  justify-content:space-between;
  border-right:1px solid rgba(255,255,255,.12);
}

.brand-chip{
  display:inline-flex;
  align-items:center;
  gap:8px;
  font-size:12px;
  letter-spacing:.18em;
  text-transform:uppercase;
  padding:8px 12px;
  border:1px solid rgba(255,255,255,.25);
  border-radius:999px;
}

.brand-panel h1{
  font-family: "Times New Roman", serif;
  font-size:56px;
  line-height:1.06;
  margin:20px 0 14px;
}

.brand-panel p{
  max-width:440px;
  color:rgba(248,250,252,.82);
  line-height:1.75;
}

.brand-note{
  font-size:13px;
  opacity:.75;
}

.form-panel{
  display:flex;
  justify-content:center;
  align-items:center;
  padding:40px 24px;
}

.login-card{
  width:min(440px, 100%);
  background:#ffffff;
  border-radius:18px;
  border:1px solid #e2e8f0;
  box-shadow:0 20px 50px rgba(2,6,23,.18);
  padding:34px 30px;
}

.login-title{
  margin:0 0 8px;
  font-size:28px;
  font-family:"Times New Roman", serif;
  color:#0f172a;
}

.login-subtitle{
  margin:0 0 20px;
  color:#64748b;
  font-size:14px;
}

.error{
  background:#fff1f2;
  color:#9f1239;
  border:1px solid #fecdd3;
  padding:11px 12px;
  border-radius:12px;
  margin-bottom:14px;
  font-size:14px;
}

.field{ margin-bottom:14px; }
.field label{
  display:block;
  margin-bottom:7px;
  font-size:13px;
  font-weight:600;
  color:#334155;
}

.input-wrap{ position:relative; }
.input-wrap input{
  width:100%;
  border:1px solid #cbd5e1;
  border-radius:12px;
  padding:12px 14px;
  font-size:15px;
  background:#f8fafc;
  transition:all .18s ease;
}

.input-wrap input:focus{
  outline:none;
  border-color:#2563eb;
  background:#fff;
  box-shadow:0 0 0 4px rgba(37,99,235,.12);
}

.toggle{
  position:absolute;
  right:12px;
  top:50%;
  transform:translateY(-50%);
  border:0;
  background:transparent;
  color:#475569;
  font-size:12px;
  font-weight:600;
  cursor:pointer;
}

.login-btn{
  width:100%;
  margin-top:6px;
  border:0;
  border-radius:12px;
  padding:13px;
  background:linear-gradient(135deg,#1d4ed8,#1e3a8a);
  color:#fff;
  font-weight:600;
  font-size:15px;
  cursor:pointer;
}
.login-btn:disabled{opacity:.75; cursor:not-allowed;}

.signin-foot{
  margin-top:16px;
  text-align:center;
  color:#64748b;
  font-size:13px;
}
.signin-foot a{
  color:#1d4ed8;
  text-decoration:none;
  font-weight:600;
}

@media (max-width: 980px){
  .login-layout{ grid-template-columns:1fr; }
  .brand-panel{
    padding:30px 24px 20px;
    border-right:0;
    border-bottom:1px solid rgba(255,255,255,.12);
  }
  .brand-panel h1{ font-size:34px; }
}
</style>
</head>
<body>
<div class="login-layout">
    <aside class="brand-panel">
        <div>
            <span class="brand-chip">Delux Beauti • Since 2026</span>
            <h1>Classic care.<br>Modern experience.</h1>
            <p>Welcome back to your curated beauty store. Sign in to access your saved cart, order history, and personalized recommendations.</p>
        </div>
        <div class="brand-note">Elegant by tradition, innovative by design.</div>
    </aside>

    <main class="form-panel">
        <section class="login-card">
            <h2 class="login-title">Sign in</h2>
            <p class="login-subtitle">Use your account credentials to continue.</p>
            <?php if($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" id="loginForm">
                <div class="field">
                    <label for="username">Username</label>
                    <div class="input-wrap">
                        <input id="username" type="text" name="username" placeholder="Enter your username" required>
                    </div>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <button class="toggle" type="button" onclick="togglePassword()">Show</button>
                    </div>
                </div>

                <button class="login-btn" type="submit" id="loginBtn">Sign in</button>
            </form>

            <div class="signin-foot">
                New here? <a href="register.php">Create an account</a>
            </div>
        </section>
    </main>
</div>

<script>
function togglePassword(){
    const input = document.getElementById("password");
    const toggle = document.querySelector(".toggle");
    if(input.type==="password"){
        input.type="text";
        toggle.textContent="Hide";
    }else{
        input.type="password";
        toggle.textContent="Show";
    }
}

document.getElementById("loginForm").addEventListener("submit", function(){
    const btn = document.getElementById("loginBtn");
    btn.textContent="Signing in...";
    btn.disabled=true;
});
</script>
</body>
</html>
