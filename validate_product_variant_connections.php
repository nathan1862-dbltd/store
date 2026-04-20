<?php

declare(strict_types=1);

/**
 * Product/Variant/Attribute relationship validator.
 *
 * Usage:
 *   php validate_product_variant_connections.php
 *
 * Optional environment overrides:
 *   DB_HOST, DB_USER, DB_PASS, DB_NAME
 */

mysqli_report(MYSQLI_REPORT_OFF);

$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbUser = getenv('DB_USER') ?: 'oizckhtwym_loyalty';
$dbPass = getenv('DB_PASS') ?: 'oizckhtwym_loyalty';
$dbName = getenv('DB_NAME') ?: 'oizckhtwym_ecom';

$mysqli = @new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($mysqli->connect_errno) {
    fwrite(
        STDERR,
        "Database connection failed ({$mysqli->connect_errno}): {$mysqli->connect_error}\n" .
        "Hint: set DB_HOST/DB_USER/DB_PASS/DB_NAME before running this validator.\n"
    );
    exit(2);
}

$errors = [];
$warnings = [];

function scalarInt(mysqli $db, string $sql): int
{
    $result = $db->query($sql);
    if (!$result) {
        throw new RuntimeException('Query failed: ' . $db->error . ' | SQL: ' . $sql);
    }

    $row = $result->fetch_row();
    return (int) ($row[0] ?? 0);
}

function tableExists(mysqli $db, string $table): bool
{
    $stmt = $db->prepare(
        'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ?'
    );
    $stmt->bind_param('s', $table);
    $stmt->execute();
    $count = (int) ($stmt->get_result()->fetch_row()[0] ?? 0);
    $stmt->close();

    return $count === 1;
}

function columnExists(mysqli $db, string $table, string $column): bool
{
    $stmt = $db->prepare(
        'SELECT COUNT(*)
         FROM information_schema.columns
         WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?'
    );
    $stmt->bind_param('ss', $table, $column);
    $stmt->execute();
    $count = (int) ($stmt->get_result()->fetch_row()[0] ?? 0);
    $stmt->close();

    return $count === 1;
}

function fkExists(mysqli $db, string $table, string $column, string $refTable, string $refColumn): bool
{
    $stmt = $db->prepare(
        'SELECT COUNT(*)
         FROM information_schema.key_column_usage
         WHERE table_schema = DATABASE()
           AND table_name = ?
           AND column_name = ?
           AND referenced_table_name = ?
           AND referenced_column_name = ?'
    );
    $stmt->bind_param('ssss', $table, $column, $refTable, $refColumn);
    $stmt->execute();
    $count = (int) ($stmt->get_result()->fetch_row()[0] ?? 0);
    $stmt->close();

    return $count >= 1;
}

$requiredTables = ['products', 'product_variants', 'variant_attributes'];
foreach ($requiredTables as $table) {
    if (!tableExists($mysqli, $table)) {
        $errors[] = "Missing table: {$table}";
    }
}

if (!$errors) {
    if (!columnExists($mysqli, 'product_variants', 'product_id')) {
        $errors[] = 'Missing column: product_variants.product_id';
    }
    if (!columnExists($mysqli, 'variant_attributes', 'variant_id')) {
        $errors[] = 'Missing column: variant_attributes.variant_id';
    }
    if (!columnExists($mysqli, 'products', 'has_variants')) {
        $warnings[] = 'Column products.has_variants not found (logical checks skipped).';
    }

    if (!fkExists($mysqli, 'product_variants', 'product_id', 'products', 'id')) {
        $warnings[] = 'Foreign key missing: product_variants.product_id -> products.id';
    }
    if (!fkExists($mysqli, 'variant_attributes', 'variant_id', 'product_variants', 'id')) {
        $warnings[] = 'Foreign key missing: variant_attributes.variant_id -> product_variants.id';
    }

    $orphanVariants = scalarInt(
        $mysqli,
        'SELECT COUNT(*)
         FROM product_variants pv
         LEFT JOIN products p ON p.id = pv.product_id
         WHERE p.id IS NULL'
    );
    if ($orphanVariants > 0) {
        $errors[] = "Orphan variants found: {$orphanVariants}";
    }

    $orphanAttributes = scalarInt(
        $mysqli,
        'SELECT COUNT(*)
         FROM variant_attributes va
         LEFT JOIN product_variants pv ON pv.id = va.variant_id
         WHERE pv.id IS NULL'
    );
    if ($orphanAttributes > 0) {
        $errors[] = "Orphan variant attributes found: {$orphanAttributes}";
    }

    if (columnExists($mysqli, 'products', 'has_variants')) {
        $flaggedWithoutVariants = scalarInt(
            $mysqli,
            'SELECT COUNT(*)
             FROM (
                SELECT p.id
                FROM products p
                LEFT JOIN product_variants pv ON pv.product_id = p.id
                WHERE p.has_variants = 1
                GROUP BY p.id
                HAVING COUNT(pv.id) = 0
             ) t'
        );
        if ($flaggedWithoutVariants > 0) {
            $warnings[] = "Products marked has_variants=1 but no variants: {$flaggedWithoutVariants}";
        }

        $variantsOnSimpleProducts = scalarInt(
            $mysqli,
            'SELECT COUNT(DISTINCT p.id)
             FROM products p
             INNER JOIN product_variants pv ON pv.product_id = p.id
             WHERE p.has_variants = 0'
        );
        if ($variantsOnSimpleProducts > 0) {
            $warnings[] = "Products marked has_variants=0 but variants exist: {$variantsOnSimpleProducts}";
        }
    }
}

echo "=== Product/Variant/Attribute Validation ===\n";

echo '\nErrors: ' . count($errors) . "\n";
foreach ($errors as $error) {
    echo " - {$error}\n";
}

echo '\nWarnings: ' . count($warnings) . "\n";
foreach ($warnings as $warning) {
    echo " - {$warning}\n";
}

if (!$errors && !$warnings) {
    echo "\nResult: PASS ✅\n";
    exit(0);
}

if (!$errors) {
    echo "\nResult: PASS WITH WARNINGS ⚠️\n";
    exit(0);
}

echo "\nResult: FAIL ❌\n";
exit(1);
