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
    $password = $_POST['password_hash'] ?? '';

    /* ================= VALIDATION ================= */
    if (!$username) $errors[] = 'Username is required.';
    if (!$phone || !preg_match('/^09\d{7,9}$/', $phone))
        $errors[] = 'Phone number must start with 09.';
    if (!$birthday) $errors[] = 'Birthday is required.';
    if (strlen($password) < 6) {
    $error = 'Password must be at least 6 characters.';
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
.error{
    background:#ffecec;
    color:#b30000;
    padding:10px;
    border-radius:10px;
    margin-bottom:10px;
}


/* Container Card */
.card {
    background: #ffffff;
    width: 380px;
    padding: 40px 35px;
    border-radius: 22px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}

/* Heading */
.card h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 26px;
    color: #111; /* Secondary (30%) */
}

/* Labels */
label {
    font-size: 15px;
    font-weight: 600;
    color: #333;
}

/* Inputs */
input {
    width: 100%;
    padding: 14px;
    margin: 10px 0 20px;
    border: 1px solid #ddd;
    border-radius: 10px;
    outline: none;
    font-size: 14px;
    transition: 0.3s;
}

input:focus {
    border-color: #d62828; /* Accent (10%) */
}

/* Forgot Password Link */
.forgot {
    color: #d62828;
    font-size: 14px;
    float: right;
    margin-bottom: 25px;
}

/* Login Button */
.login-btn {
    width: 100%;
    padding: 14px;
    background: #000;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    color: #fff;
    cursor: pointer;
    transition: 0.3s;
}

.login-btn:hover {
    opacity: 0.85;
}

/* Signup Text */
.signup-text {
    text-align: center;
    margin-top: 25px;
    color: #333;
}

.signup-link {
    color: #d62828;
    font-weight: 600;
}


</style>

<div class="auth-wrapper">
    <div class="card">
        <h2>REGISTER</h2>

        <?php foreach ($errors as $e): ?>
            <div class="error"><?php echo e($e); ?></div>
        <?php endforeach; ?>

        <form method="post">
            <label>Username</label>
            <input type="text" name="username" required>

            <label>Phone Number</label>
            <input type="tel" name="phone" placeholder="09xxxxxxxx" required>

            <label>Birthday</label>
            <input type="date" name="birthday" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit" class="login-btn">Register</button>
        </form>

        <p class="signup-text">
            Already have an account?
            <a href="login.php" class="signup-link">Login</a>
        </p>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>