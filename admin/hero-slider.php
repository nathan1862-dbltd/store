<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/admin_auth.php'; // must block non-admin

// Ensure row exists
$mysqli->query("INSERT INTO hero_slider (id) VALUES (1) ON DUPLICATE KEY UPDATE id=id");

$msg = "";

// Fetch current images
$res = $mysqli->query("SELECT * FROM hero_slider WHERE id=1 LIMIT 1");
$current = $res->fetch_assoc();

function uploadHeroImage($fileKey, $uploadDirAbs, $uploadDirWeb) {
    if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) return null;

    $tmp = $_FILES[$fileKey]['tmp_name'];
    $name = $_FILES[$fileKey]['name'];

    // Validate extension
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp'];
    if (!in_array($ext, $allowed)) {
        throw new Exception("Invalid file type for {$fileKey}. Allowed: JPG, PNG, WEBP.");
    }

    // Validate it's actually an image
    $info = @getimagesize($tmp);
    if ($info === false) {
        throw new Exception("File {$fileKey} is not a valid image.");
    }

    // Size limit (3MB)
    if ($_FILES[$fileKey]['size'] > 3 * 1024 * 1024) {
        throw new Exception("{$fileKey} is too large. Max 3MB.");
    }

    // Unique filename
    $newName = "hero_" . $fileKey . "_" . date("Ymd_His") . "_" . bin2hex(random_bytes(4)) . "." . $ext;
    $destAbs = rtrim($uploadDirAbs, "/") . "/" . $newName;

    if (!move_uploaded_file($tmp, $destAbs)) {
        throw new Exception("Upload failed for {$fileKey}.");
    }

    return rtrim($uploadDirWeb, "/") . "/" . $newName; // store web path in DB
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $uploadDirAbs = __DIR__ . '/../assets/uploads/hero';
        $uploadDirWeb = 'assets/uploads/hero';

        if (!is_dir($uploadDirAbs)) {
            mkdir($uploadDirAbs, 0755, true);
        }

        $updates = [];
        $params = [];
        $types = "";

        for ($i=1; $i<=4; $i++) {
            $key = "image{$i}";
            $uploadedPath = uploadHeroImage($key, $uploadDirAbs, $uploadDirWeb);
            if ($uploadedPath) {
                $updates[] = "{$key} = ?";
                $params[] = $uploadedPath;
                $types .= "s";
            }
        }

        if (!empty($updates)) {
            $sql = "UPDATE hero_slider SET " . implode(", ", $updates) . " WHERE id=1";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $stmt->close();
            $msg = "Hero slider updated successfully.";
        } else {
            $msg = "No files uploaded. Nothing changed.";
        }

        // Refresh
        $res = $mysqli->query("SELECT * FROM hero_slider WHERE id=1 LIMIT 1");
        $current = $res->fetch_assoc();

    } catch (Exception $e) {
        $msg = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Admin - Hero Slider</title>
<style>
body{font-family:Arial,Helvetica,sans-serif;background:#f6f6f6;margin:0;padding:0}
.wrap{max-width:980px;margin:30px auto;background:#fff;border-radius:14px;padding:22px;box-shadow:0 8px 24px rgba(0,0,0,.06)}
h1{margin:0 0 14px}
.note{color:#555;margin:0 0 18px}
.msg{padding:12px 14px;background:#f1f7ff;border:1px solid #cfe4ff;border-radius:10px;margin:0 0 16px}
.grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
.card{border:1px solid #eee;border-radius:12px;padding:14px;background:#fafafa}
.card label{font-weight:700;display:block;margin:0 0 8px}
.preview{width:100%;height:180px;border-radius:10px;border:1px solid #e6e6e6;object-fit:cover;background:#fff}
input[type=file]{margin-top:10px}
.actions{margin-top:18px;display:flex;gap:10px}
button{border:0;background:#111;color:#fff;padding:12px 16px;border-radius:10px;font-weight:700;cursor:pointer}
small{color:#777}
@media(max-width:720px){.grid{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="wrap">
  <h1>Hero Slider Manager</h1>
  <p class="note">Upload up to 4 hero images. This will power the homepage slider.</p>

  <?php if ($msg): ?>
    <div class="msg"><?php echo htmlspecialchars($msg); ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="grid">
      <?php for ($i=1; $i<=4; $i++):
        $k = "image{$i}";
        $src = !empty($current[$k]) ? "../" . $current[$k] : "";
      ?>
      <div class="card">
        <label><?php echo strtoupper($k); ?></label>
        <?php if ($src): ?>
          <img class="preview" src="<?php echo htmlspecialchars($src); ?>" alt="<?php echo $k; ?>">
        <?php else: ?>
          <div class="preview" style="display:flex;align-items:center;justify-content:center;color:#777;">
            No image uploaded
          </div>
        <?php endif; ?>
        <input type="file" name="<?php echo $k; ?>" accept=".jpg,.jpeg,.png,.webp" />
        <small>Allowed: JPG/PNG/WEBP • Max 3MB</small>
      </div>
      <?php endfor; ?>
    </div>

    <div class="actions">
      <button type="submit">Save Slider</button>
    </div>
  </form>
</div>

<script>
(function(){
  const root = document.getElementById('heroSlider');
  if(!root) return;

  const slides = Array.from(root.querySelectorAll('.hero-slide'));
  const dots = Array.from(root.querySelectorAll('.dot'));
  const prev = root.querySelector('.hero-nav.prev');
  const next = root.querySelector('.hero-nav.next');

  let idx = 0;
  let timer = null;

  function show(i){
    idx = (i + slides.length) % slides.length;
    slides.forEach((s,n)=>s.classList.toggle('active', n===idx));
    dots.forEach((d,n)=>d.classList.toggle('active', n===idx));
  }

  function autoplay(){
    clearInterval(timer);
    timer = setInterval(()=>show(idx+1), 4500);
  }

  prev?.addEventListener('click', ()=>{ show(idx-1); autoplay(); });
  next?.addEventListener('click', ()=>{ show(idx+1); autoplay(); });
  dots.forEach((d,n)=>d.addEventListener('click', ()=>{ show(n); autoplay(); }));

  // Start
  show(0);
  autoplay();
})();
</script>
</body>
</html>