<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/header.php';

/* Fetch brands A–Z */
$result = $mysqli->query("SELECT id, name FROM brands ORDER BY name ASC");

if (!$result) {
    echo "<div class='container'><p>Unable to load brands.</p></div>";
    require_once __DIR__ . '/footer.php';
    exit;
}

/* Group brands by first letter */
$brandsByLetter = [];

while ($row = $result->fetch_assoc()) {

    $firstChar = strtoupper(mb_substr($row['name'], 0, 1));

    if (!ctype_alpha($firstChar)) {
        $firstChar = '#';
    }

    $brandsByLetter[$firstChar][] = $row;
}

/* Ensure full A–Z + # */
$letters = range('A', 'Z');
$letters[] = '#';
?>

<style>
.brand-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.brand-page-title {
    text-align: center;
    margin-bottom: 25px;
    font-size: 28px;
    font-weight: 700;
}

/* A–Z Nav */
.az-nav {
    text-align: center;
    margin-bottom: 35px;
}

.az-nav a,
.az-nav span {
    display: inline-block;
    margin: 4px 6px;
    font-size: 14px;
    text-decoration: none;
    color: #111;
}

.az-nav a:hover {
    text-decoration: underline;
}

.az-disabled {
    color: #ccc;
}

/* Brand Section */
.brand-group {
    margin-bottom: 40px;
}

.brand-letter {
    font-weight: 700;
    font-size: 20px;
    margin-bottom: 8px;
}

.brand-divider {
    border: none;
    border-top: 1px solid #eee;
    margin-bottom: 14px;
}

/* Brand List */
.brand-list {
    list-style: none;
    padding: 0;
    columns: 2;
}

@media (min-width: 768px) {
    .brand-list { columns: 3; }
}

@media (min-width: 1024px) {
    .brand-list { columns: 4; }
}

.brand-list li {
    margin-bottom: 8px;
}

.brand-list a {
    text-decoration: none;
    color: #111;
    font-size: 14px;
}

.brand-list a:hover {
    text-decoration: underline;
}

</style>


<div class="container brand-container">

    <h2 class="brand-page-title">All Brands</h2>

    <!-- A–Z Navigation -->
    <div class="az-nav">
        <?php foreach ($letters as $letter): ?>
            <?php if (!empty($brandsByLetter[$letter])): ?>
                <a href="#letter-<?= $letter ?>">
                    <?= $letter ?>
                </a>
            <?php else: ?>
                <span class="az-disabled"><?= $letter ?></span>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Brand Groups -->
    <?php foreach ($letters as $letter): ?>

        <?php if (!empty($brandsByLetter[$letter])): ?>

            <div class="brand-group" id="letter-<?= $letter ?>">

                <div class="brand-letter">
                    <?= $letter ?>
                </div>

                <hr class="brand-divider">

                <ul class="brand-list">
                    <?php foreach ($brandsByLetter[$letter] as $brand): ?>
                        <li>
                            <a href="brand.php?id=<?= (int)$brand['id'] ?>">
                                <?= htmlspecialchars($brand['name']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

            </div>

        <?php endif; ?>

    <?php endforeach; ?>

</div>
<?php require_once __DIR__ . '/footer.php'; ?>