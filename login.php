<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/header.php';

ensureSessionStarted();

if (isset($_SESSION['user_id'])) {
    header("Location: account.php");
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
?> <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>

<style>
*{
box-sizing:border-box;
margin:0;
padding:0;
font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;
}

body{
min-height:100vh;
display:flex;
background:linear-gradient(135deg,#0f172a,#1e293b);
}

/* ================= WRAPPER ================= */

.wrapper{
display:flex;
flex-direction:column;
width:100%;
}

/* ================= BRAND SIDE ================= */

.brand-side{
flex:1;
display:flex;
align-items:center;
justify-content:center;
color:#fff;
padding:40px;
text-align:center;
}

.brand-side h1{
font-size:32px;
letter-spacing:1px;
margin-bottom:15px;
}

.brand-side p{
opacity:.8;
font-size:14px;
}

/* ================= FORM SIDE ================= */

.form-side{
flex:1;
display:flex;
align-items:center;
justify-content:center;
padding:20px;
}

.login-card{
width:100%;
max-width:420px;
background:rgba(255,255,255,0.95);
backdrop-filter:blur(14px);
border-radius:22px;
padding:35px 30px;
box-shadow:0 30px 70px rgba(0,0,0,0.35);
animation:fadeIn .4s ease;
}

.login-card h2{
text-align:center;
margin-bottom:25px;
color:#111827;
}

/* ERROR */
.error{
background:#fee2e2;
color:#991b1b;
padding:10px 14px;
border-radius:10px;
margin-bottom:15px;
font-size:14px;
}

/* FORM */
.form-group{
position:relative;
margin-bottom:16px;
}

input{
width:100%;
padding:14px 15px;
border-radius:12px;
border:1px solid #e5e7eb;
font-size:15px;
transition:.2s ease;
}

input:focus{
outline:none;
border-color:#cc2230;
box-shadow:0 0 0 3px rgba(204,34,48,0.12);
}

/* PASSWORD TOGGLE */
.toggle{
position:absolute;
right:12px;
top:50%;
transform:translateY(-50%);
cursor:pointer;
font-size:13px;
color:#6b7280;
}

/* BUTTON */
button{
width:100%;
padding:14px;
border:none;
border-radius:12px;
background:#cc2230;
color:#fff;
font-weight:600;
font-size:15px;
cursor:pointer;
transition:.2s ease;
}

button:hover{
background:#a61c27;
}

button:disabled{
opacity:.7;
cursor:not-allowed;
}

/* FOOTER */
.footer-text{
text-align:center;
margin-top:20px;
font-size:13px;
color:#6b7280;
}

/* ANIMATION */
@keyframes fadeIn{
from{opacity:0;transform:translateY(10px)}
to{opacity:1;transform:translateY(0)}
}

/* ================= MOBILE ================= */

@media(max-width:768px){

body{
flex-direction:column;
}

.brand-side{
padding:30px 20px 10px 20px;
}

.brand-side h1{
font-size:24px;
}

}

/* ================= DESKTOP ================= */

@media(min-width:768px){

.wrapper{
flex-direction:row;
}

.brand-side{
background:linear-gradient(135deg,#111827,#1f2937);
}

}
</style>
</head>
<body>

<div class="wrapper">

    <!-- BRAND PANEL -->
    <div class="brand-side">
        <div>
            <h1>DELUX BEAUTI</h1>
            <p>Premium Beauty & Skincare Experience</p>
        </div>
    </div>

    <!-- FORM PANEL -->
    <div class="form-side">

        <div class="login-card">

            <h2>Account Login</h2>

            <?php if($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>

                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <span class="toggle" onclick="togglePassword()">Show</span>
                </div>

                <button type="submit" id="loginBtn">Login</button>

            </form>

            <div class="footer-text">
                Secure Access Portal
            </div>

        </div>

    </div>

</div>

<script>
function togglePassword(){
const input=document.getElementById("password");
const toggle=document.querySelector(".toggle");
if(input.type==="password"){
input.type="text";
toggle.textContent="Hide";
}else{
input.type="password";
toggle.textContent="Show";
}
}

document.querySelector("form").addEventListener("submit",function(){
const btn=document.getElementById("loginBtn");
btn.textContent="Signing in...";
btn.disabled=true;
});
</script>

</body>
</html>
<?php require_once __DIR__ . '/footer.php'; ?>
