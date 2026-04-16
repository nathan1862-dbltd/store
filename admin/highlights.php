<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/admin_header.php';

/* -----------------------------------------
   SVG UPLOAD HELPER
----------------------------------------- */
function uploadHighlightSvg(array $file): ?string
{
    if (
        empty($file['name']) ||
        empty($file['tmp_name']) ||
        !is_uploaded_file($file['tmp_name'])
    ) {
        return null;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($ext !== 'svg') {
        return null;
    }

    $dir = __DIR__ . '/../assets/uploads/highlights/';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $safe = preg_replace('/[^a-zA-Z0-9\._-]/', '_', basename($file['name']));
    $name = time() . '_' . $safe;

    if (move_uploaded_file($file['tmp_name'], $dir . $name)) {
        return 'assets/uploads/highlights/' . $name;
    }

    return null;
}

/* -----------------------------------------
   ADD HIGHLIGHT
----------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');

    if ($title === '') {
        die('Title required');
    }

    $svgPath = uploadHighlightSvg($_FILES['svg_icon'] ?? []);

    $stmt = $mysqli->prepare("
        INSERT INTO highlights (title, svg_icon)
        VALUES (?, ?)
    ");
    $stmt->bind_param("ss", $title, $svgPath);
    $stmt->execute();
    $stmt->close();

    header("Location: highlights.php");
    exit;
}

/* -----------------------------------------
   FETCH HIGHLIGHTS
----------------------------------------- */
$highlights = $mysqli->query("
    SELECT id, title, svg_icon
    FROM highlights
    ORDER BY id DESC
");
?>

<!-- ================= UI ================= -->

<div class="topbar">
    <div class="title">Highlights</div>
</div>

<div class="card">
    <h3>Add Highlight</h3>

    <form method="post" enctype="multipart/form-data" class="form-row">
        <input
            type="text"
            name="title"
            placeholder="e.g. Best Seller, Cruelty Free"
            required
            style="flex:1"
        >

        <input
            type="file"
            name="svg_icon"
            accept=".svg"
            required
        >

        <button type="submit">Add</button>
    </form>

    <div class="small">
        SVG only • Stored in <code>assets/uploads/highlights/</code>
    </div>
</div>

<div class="card">
    <h3>All Highlights</h3>

    <table class="table">
        <tr>
            <th>ID</th>
            <th>Icon</th>
            <th>Title</th>
        </tr>

        <?php while ($h = $highlights->fetch_assoc()): ?>
        <tr>
            <td><?= (int)$h['id'] ?></td>
            <td>
                <?php if (!empty($h['svg_icon'])): ?>
                    <img
                        src="../<?= htmlspecialchars($h['svg_icon']) ?>"
                        width="24"
                        height="24"
                        alt=""
                    >
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($h['title']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>