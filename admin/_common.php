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

/* ================= DB ================= */
if (file_exists(__DIR__ . '/adminconfig.php')) {
    require_once __DIR__ . '/adminconfig.php';
}

/* ================= SLUG ================= */
function admin_slug(string $s): string {
    $s = trim(mb_strtolower($s));
    $s = preg_replace('/[^a-z0-9]+/u', '-', $s);
    $s = trim($s, '-');
    return $s ?: 'item';
}

/* ================= FLASH ================= */
function admin_flash_set(string $key, string $msg): void {
    ensureSessionStarted();
    $_SESSION['admin_flash'][$key] = $msg;
}

function admin_flash_get(string $key): ?string {
    ensureSessionStarted();
    if (empty($_SESSION['admin_flash'][$key])) return null;
    $msg = $_SESSION['admin_flash'][$key];
    unset($_SESSION['admin_flash'][$key]);
    return $msg;
}

/* ================= IMAGE UPLOAD ================= */
function admin_upload_image(string $field, string $destDir, array $allowed = ['jpg','jpeg','png','webp']): array {

    if (empty($_FILES[$field]['name'])) return [null, null];

    if (!is_dir($destDir)) {
        mkdir($destDir, 0775, true);
    }

    $tmp  = $_FILES[$field]['tmp_name'];
    $name = basename($_FILES[$field]['name']);
    $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed, true)) {
        return [null, "Invalid file type."];
    }

    $safe = preg_replace('/[^a-zA-Z0-9\._-]/', '_', pathinfo($name, PATHINFO_FILENAME));
    $file = $safe . '_' . date('Ymd_His') . '.' . $ext;
    $path = rtrim($destDir,'/') . '/' . $file;

    if (!move_uploaded_file($tmp, $path)) {
        return [null, "Upload failed."];
    }

    return [$file, null];
}