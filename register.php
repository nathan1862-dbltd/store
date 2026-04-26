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
<div class="login-layout">
   <!-- RIGHT FORM PANEL -->

    <div class="form-panel">

        <div class="login-card">

            <h2 class="login-title">Create account</h2>

            <p class="login-subtitle">

                Join Delux Beauti for a personalized experience.

            </p>

            <?php foreach ($errors as $e): ?>

                <div class="error"><?php echo e($e); ?></div>

            <?php endforeach; ?>

            <form method="post">

                <!-- USERNAME -->

                <div class="field">

                    <label>Username</label>

                    <div class="input-wrap">

                        <input type="text" name="username" required>

                    </div>

                </div>

                <!-- PHONE -->

                <div class="field">

                    <label>Phone Number</label>

                    <div class="input-wrap">

                        <input type="tel" name="phone" placeholder="09xxxxxxxx" required>

                    </div>

                </div>

                <!-- BIRTHDAY -->

                <div class="field">

                    <label>Birthday</label>

                    <div class="input-wrap">

                        <input type="date" name="birthday" required>

                    </div>

                </div>

                <!-- PASSWORD -->

                <div class="field">

                    <label>Password</label>

                    <div class="input-wrap">

                        <input type="password" name="password" id="password" required>

                        <button type="button" class="toggle" onclick="togglePassword()">Show</button>

                    </div>

                </div>

                <button type="submit" class="login-btn">

                    Create Account

                </button>

            </form>

            <p class="signin-foot">

                Already have an account?

                <a href="login.php">Login</a>

            </p>

        </div>

    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
