<?php
/**
 * functions.php (Schema-aligned)
 * DB: uwnktxcpef_newecommerce
 *
 * Fixes vs Archive 6:
 * - Aligns coupons table columns
 * - Adds get_shipping_fee()
 * - Adds loyalty helpers using loyalty_ledger (no loyalty_accounts table in SQL)
 * - Keeps CSRF + order_nonce
 */

declare(strict_types=1);

/* ================= SESSION ================= */
function ensureSessionStarted(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}
ensureSessionStarted();

/* ================= DB ================= */
if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}

if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_USER')) define('DB_USER', 'uwnktxcpef_newecommerce');
if (!defined('DB_PASS')) define('DB_PASS', 'uwnktxcpef_newecommerce');
if (!defined('DB_NAME')) define('DB_NAME', 'uwnktxcpef_newecommerce');

/** @var mysqli $mysqli */
$mysqli = $mysqli ?? new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    http_response_code(500);
    die('Database connection error.');
}
$mysqli->set_charset('utf8mb4');

/* ================= BASIC HELPERS ================= */
function e($v): string {
    return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
}

function redirectIfNotLoggedIn(): void {
    ensureSessionStarted();
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

/* ================= CSRF ================= */
function csrf_token(): string {
    ensureSessionStarted();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return (string)$_SESSION['csrf_token'];
}

function csrf_check(): void {
    ensureSessionStarted();
    $token = (string)($_POST['csrf_token'] ?? '');
    if ($token === '' || empty($_SESSION['csrf_token']) || !hash_equals((string)$_SESSION['csrf_token'], $token)) {
        http_response_code(400);
        die('Invalid request token.');
    }
}

/* ================= ORDER NONCE (IDEMPOTENCY) ================= */
function order_nonce(): string {
    ensureSessionStarted();
    if (empty($_SESSION['order_nonce'])) {
        $_SESSION['order_nonce'] = bin2hex(random_bytes(32));
    }
    return (string)$_SESSION['order_nonce'];
}

function order_nonce_check(): void {
    ensureSessionStarted();
    $nonce = (string)($_POST['order_nonce'] ?? '');
    if ($nonce === '' || empty($_SESSION['order_nonce']) || !hash_equals((string)$_SESSION['order_nonce'], $nonce)) {
        http_response_code(400);
        die('Invalid request.');
    }
    unset($_SESSION['order_nonce']);
}

/* ================= USER ================= */
function getUserById(mysqli $db, int $id): ?array {
    $stmt = $db->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
    if (!$stmt) return null;
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $row ?: null;
}

/* ================= SHIPPING ================= */
function get_regions(mysqli $mysqli): array {
    $rows = [];
    $res = $mysqli->query("SELECT id, name FROM shipping_states WHERE is_active=1 ORDER BY name ASC");
    if ($res) {
        while ($r = $res->fetch_assoc()) $rows[] = $r;
    }
    return $rows;
}

function get_townships_by_region(mysqli $mysqli, int $stateId): array {
    $rows = [];
    $stmt = $mysqli->prepare("SELECT id, state_id, name, shipping_fee FROM shipping_townships WHERE state_id=? AND is_active=1 ORDER BY name ASC");
    $stmt->bind_param('i', $stateId);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($t = $res->fetch_assoc()) $rows[] = $t;
    $stmt->close();
    return $rows;
}

function get_shipping_fee(mysqli $mysqli, int $townshipId): float {
    if ($townshipId <= 0) return 0.0;
    $stmt = $mysqli->prepare("SELECT shipping_fee FROM shipping_townships WHERE id=? AND is_active=1 LIMIT 1");
    if (!$stmt) return 0.0;
    $stmt->bind_param('i', $townshipId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $row ? (float)$row['shipping_fee'] : 0.0;
}

/* ================= COUPONS ================= */
/**
 * Returns [couponRowOrNull, discount]
 */
function coupon_discount(mysqli $mysqli, string $code, float $subtotal): array {
    $code = strtoupper(trim($code));
    if ($code === '') return [null, 0.0];

    $stmt = $mysqli->prepare("\
        SELECT id, code, type, value, min_order, max_discount, expires_at, is_active\
        FROM coupons\
        WHERE code=?\
        LIMIT 1\
    ");
    $stmt->bind_param('s', $code);
    $stmt->execute();
    $c = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$c || (int)$c['is_active'] !== 1) return [null, 0.0];
    if (!empty($c['expires_at']) && strtotime((string)$c['expires_at']) < time()) return [null, 0.0];
    if ($subtotal < (float)($c['min_order'] ?? 0)) return [null, 0.0];

    $discount = 0.0;
    if (($c['type'] ?? '') === 'percent') {
        $discount = $subtotal * ((float)$c['value'] / 100.0);
    } else {
        $discount = (float)$c['value'];
    }

    if (!empty($c['max_discount'])) {
        $discount = min($discount, (float)$c['max_discount']);
    }

    $discount = max(0.0, min($discount, $subtotal));
    return [$c, round($discount, 2)];
}

function couponUsageCounts(mysqli $mysqli, int $couponId, int $userId): array {
    // Schema has coupon_redemptions but coupons table has no limits.
    // Still useful to track counts for analytics.
    $stmt = $mysqli->prepare("SELECT COUNT(*) AS c FROM coupon_redemptions WHERE coupon_id=?");
    $stmt->bind_param('i', $couponId);
    $stmt->execute();
    $global = (int)($stmt->get_result()->fetch_assoc()['c'] ?? 0);
    $stmt->close();

    $stmt = $mysqli->prepare("SELECT COUNT(*) AS c FROM coupon_redemptions WHERE coupon_id=? AND user_id=?");
    $stmt->bind_param('ii', $couponId, $userId);
    $stmt->execute();
    $user = (int)($stmt->get_result()->fetch_assoc()['c'] ?? 0);
    $stmt->close();

    return [$global, $user];
}


function loyalty_balance(mysqli $mysqli, int $userId): int {
    $stmt = $mysqli->prepare("
        SELECT COALESCE(SUM(
            CASE
                WHEN status='approved' AND type IN ('earn','adjust') THEN points
                WHEN status='approved' AND type IN ('redeem','refund') THEN -points
                ELSE 0
            END
        ), 0) AS bal
        FROM user_points
        WHERE user_id = ?
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $bal = (int)$stmt->get_result()->fetch_assoc()['bal'];
    $stmt->close();
    return max(0, $bal);
}

/**
 * Add loyalty points (positive points).
 * $orderId can be NULL for non-order adjustments.
 */
function loyalty_add(mysqli $mysqli, int $userId, ?int $orderId, int $points, string $description, ?string $expiresAt = null): void {
    if ($orderId === null) {
        $stmt = $mysqli->prepare(
            "INSERT INTO loyalty_ledger (user_id, order_id, points, description, expires_at)
             VALUES (?, NULL, ?, ?, ?)"
        );
        $stmt->bind_param('iiss', $userId, $points, $description, $expiresAt);
    } else {
        $stmt = $mysqli->prepare(
            "INSERT INTO loyalty_ledger (user_id, order_id, points, description, expires_at)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('iiiss', $userId, $orderId, $points, $description, $expiresAt);
    }

    $stmt->execute();
    $stmt->close();
}

/**
 * Deduct loyalty points (stores as negative points).
 */
function loyalty_deduct(mysqli $mysqli, int $userId, ?int $orderId, int $points, string $description): void {
    $points = -abs($points);
    loyalty_add($mysqli, $userId, $orderId, $points, $description, null);
}
/* =====================================================
   FIFO LOYALTY EXPIRING SYSTEM
   Aligned strictly to your loyalty_ledger structure
===================================================== */

function loyalty_expiring_soon(mysqli $mysqli, int $days = 7): array
{
    $results = [];

    $today  = date('Y-m-d H:i:s');
    $future = date('Y-m-d H:i:s', strtotime("+{$days} days"));

    /* ==========================================
       STEP 1: Find users with upcoming expiry
    ========================================== */
    $stmtUsers = $mysqli->prepare("
        SELECT DISTINCT user_id
        FROM loyalty_ledger
        WHERE points > 0
          AND expires_at IS NOT NULL
          AND expires_at BETWEEN ? AND ?
    ");
    $stmtUsers->bind_param("ss", $today, $future);
    $stmtUsers->execute();
    $userRes = $stmtUsers->get_result();

    while ($userRow = $userRes->fetch_assoc()) {

        $userId = (int)$userRow['user_id'];

        /* ==========================================
           STEP 2: Get total redeemed
        ========================================== */
        $stmtRedeem = $mysqli->prepare("
            SELECT COALESCE(SUM(ABS(points)),0) AS total_redeemed
            FROM loyalty_ledger
            WHERE user_id = ?
              AND points < 0
        ");
        $stmtRedeem->bind_param("i", $userId);
        $stmtRedeem->execute();
        $redeemData = $stmtRedeem->get_result()->fetch_assoc();
        $stmtRedeem->close();

        $remainingToDeduct = (int)$redeemData['total_redeemed'];

        /* ==========================================
           STEP 3: Fetch all earn batches FIFO
        ========================================== */
        $stmtEarn = $mysqli->prepare("
            SELECT points, expires_at, created_at
            FROM loyalty_ledger
            WHERE user_id = ?
              AND points > 0
            ORDER BY created_at ASC
        ");
        $stmtEarn->bind_param("i", $userId);
        $stmtEarn->execute();
        $earnRes = $stmtEarn->get_result();

        while ($earn = $earnRes->fetch_assoc()) {

            $batchPoints = (int)$earn['points'];
            $expiry      = $earn['expires_at'];

            /* FIFO deduction */
            if ($remainingToDeduct > 0) {

                if ($remainingToDeduct >= $batchPoints) {
                    $remainingToDeduct -= $batchPoints;
                    continue;
                } else {
                    $batchPoints -= $remainingToDeduct;
                    $remainingToDeduct = 0;
                }
            }

            /* Check expiry window */
            if (
                $batchPoints > 0 &&
                $expiry !== null &&
                $expiry >= $today &&
                $expiry <= $future
            ) {
                $results[] = [
                    'user_id'         => $userId,
                    'expiring_points' => $batchPoints,
                    'expires_at'      => $expiry
                ];
            }
        }

        $stmtEarn->close();
    }

    $stmtUsers->close();

    return $results;
}

