<?php
require_once __DIR__ . '/init.php';

if ($currentUser) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $birthday = $_POST['birthday'] ?? '';
    $password = $_POST['password'] ?? '';

    /* ================= VALIDATION ================= */
    if (!$username) $errors[] = 'Username is required.';
    if (!$phone || !preg_match('/^09\d{7,9}$/', $phone))
        $errors[] = 'Phone number must start with 09.';
    if (!$birthday) $errors[] = 'Birthday is required.';
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    /* ================= CHECK USERNAME ================= */
    if (empty($errors)) {
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            $errors[] = 'Username already exists.';
        }
        $stmt->close();
    }

    /* ================= REGISTER USER ================= */
    if (empty($errors)) {

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // ---------- FAKE OTP (TEMPORARY) ----------
        $fakeOtp = '123456';                 // fixed fake OTP
        $otpExpires = date('Y-m-d H:i:s', time() + 300); // valid for 5 minutes

        $stmt = $mysqli->prepare("
            INSERT INTO users (
                username,
                phone,
                birthday,
                password_hash,
                otp_code,
                otp_expires_at,
                created_at
            ) VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");

        $stmt->bind_param(
            "ssssss",
            $username,
            $phone,
            $birthday,
            $passwordHash,
            $fakeOtp,
            $otpExpires
        );

        $stmt->execute();
        $userId = $stmt->insert_id;
        $stmt->close();

        /* ================= AUTO LOGIN ================= */
        $_SESSION['user_id'] = $userId;
        $_SESSION['cart'] = $_SESSION['cart'] ?? [];

        /* ================= REDIRECT ================= */
        header('Location: dashboard.php');
        exit;
    }
}

$pageTitle = "Register";
include __DIR__ . '/header.php';
?>

<style>
.bottom-nav{display:none}
.auth-register{
    min-height: calc(100vh - 150px);
    display:flex;
    align-items:center;
    justify-content:center;
    padding:40px 16px 56px;
    background:
      radial-gradient(circle at 18% 20%, rgba(37,99,235,.14), transparent 35%),
      radial-gradient(circle at 85% 8%, rgba(30,64,175,.1), transparent 36%),
      #f8fafc;
}

.register-card{
    width:min(460px,100%);
    background:#fff;
    border:1px solid #dbe3ef;
    border-radius:18px;
    box-shadow:0 14px 40px rgba(15,23,42,.08);
    padding:30px 28px;
}

.register-heading{
    margin:0;
    font-family:"Times New Roman",serif;
    font-size:30px;
    color:#0f172a;
    text-align:center;
}

.register-sub{
    text-align:center;
    margin:8px 0 22px;
    color:#64748b;
    font-size:14px;
}

.error{
    background:#fff1f2;
    color:#9f1239;
    border:1px solid #fecdd3;
    padding:10px 12px;
    border-radius:10px;
    margin-bottom:10px;
    font-size:14px;
}

.auth-field{ margin-bottom:14px; }
.auth-field label{
    display:block;
    margin-bottom:6px;
    font-size:13px;
    font-weight:600;
    color:#334155;
}
.auth-field input{
    width:100%;
    border:1px solid #cbd5e1;
    border-radius:11px;
    padding:12px 13px;
    font-size:14px;
    background:#f8fafc;
}
.auth-field input:focus{
    outline:none;
    border-color:#2563eb;
    box-shadow:0 0 0 4px rgba(37,99,235,.12);
    background:#fff;
}

.register-btn{
    width:100%;
    margin-top:4px;
    border:0;
    border-radius:12px;
    padding:13px;
    font-size:15px;
    font-weight:600;
    color:#fff;
    background:linear-gradient(135deg,#1d4ed8,#1e3a8a);
    cursor:pointer;
}

.signup-text {
    text-align:center;
    margin-top:16px;
    color:#64748b;
    font-size:14px;
}
.signup-link {
    color:#1d4ed8;
    font-weight:700;
    text-decoration:none;
}
</style>

<div class="auth-register">
    <div class="register-card">
        <h2 class="register-heading">Create account</h2>
        <p class="register-sub">Join Delux Beauti for a timeless and personalized shopping experience.</p>

        <?php foreach ($errors as $e): ?>
            <div class="error"><?php echo e($e); ?></div>
        <?php endforeach; ?>

        <form method="post">
            <div class="auth-field">
                <label>Username</label>
                <input type="text" name="username" placeholder="Choose a username" required>
            </div>

            <div class="auth-field">
                <label>Phone Number</label>
                <input type="tel" name="phone" placeholder="09xxxxxxxx" required>
            </div>

            <div class="auth-field">
                <label>Birthday</label>
                <input type="date" name="birthday" required>
            </div>

            <div class="auth-field">
                <label>Password</label>
                <input type="password" name="password" placeholder="Minimum 6 characters" required>
            </div>

            <button type="submit" class="register-btn">Create account</button>
        </form>

        <p class="signup-text">
            Already have an account?
            <a href="login.php" class="signup-link">Login</a>
        </p>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
