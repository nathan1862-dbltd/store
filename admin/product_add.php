<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/adminconfig.php';
require_once __DIR__ . '/admin_header.php';

/* ================= SLUG ================= */

function createSlug($string) {
    $slug = strtolower(trim($string));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    return trim($slug, '-');
}

function generateUniqueSlug($mysqli, $baseSlug) {
    $slug = $baseSlug;
    $counter = 1;

    while (true) {
        $stmt = $mysqli->prepare("SELECT id FROM products WHERE slug=? LIMIT 1");
        $stmt->bind_param("s", $slug);
        $stmt->execute();

        if ($stmt->get_result()->num_rows === 0) {
            return $slug;
        }

        $slug = $baseSlug . '-' . $counter++;
    }
}

/* ================= IMAGE UPLOAD ================= */

function uploadImage($file, $subdir = 'products') {
    if (empty($file['name'])) return null;
    if (!is_uploaded_file($file['tmp_name'])) return null;

    $allowed = ['image/jpeg','image/png','image/webp'];
    if (!in_array(mime_content_type($file['tmp_name']), $allowed)) return null;

    if ($file['size'] > 5 * 1024 * 1024) return null; // 5MB limit

    $baseDir = __DIR__ . '/../assets/uploads/' . $subdir . '/';
    if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);

    $safeName = preg_replace('/[^a-zA-Z0-9\._-]/', '_', basename($file['name']));
    $finalName = time() . '_' . $safeName;

    if (move_uploaded_file($file['tmp_name'], $baseDir . $finalName)) {
        return 'uploads/' . $subdir . '/' . $finalName;
    }
    return null;
}

/* ================= LOAD DATA ================= */

$brands = $mysqli->query("SELECT id, name FROM brands ORDER BY name ASC");
$cats = $mysqli->query("SELECT id, name FROM categories ORDER BY name ASC");
$highlights = $mysqli->query("SELECT id, title FROM highlights ORDER BY title ASC");

/* ================= HANDLE FORM ================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $mysqli->begin_transaction();

    try {

        $img1 = uploadImage($_FILES['image1'] ?? []);
        $img2 = uploadImage($_FILES['image2'] ?? []);
        $img3 = uploadImage($_FILES['image3'] ?? []);

        $slug = generateUniqueSlug($mysqli, createSlug($_POST['name']));

        $stmt = $mysqli->prepare("
            INSERT INTO products 
            (name, slug, description, ingredients, how_to_use, brand_id, image_path, image_path2, image_path3)
            VALUES (?,?,?,?,?,?,?,?,?)
        ");

        $stmt->bind_param(
            "ssssissss",
            $_POST['name'],
            $slug,
            $_POST['description'],
            $_POST['ingredients'],
            $_POST['how_to_use'],
            $_POST['brand_id'],
            $img1,
            $img2,
            $img3
        );

        $stmt->execute();
        $productId = $stmt->insert_id;
        $stmt->close();

        /* Categories */
        if (!empty($_POST['categories'])) {
            $pc = $mysqli->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?,?)");
            foreach ($_POST['categories'] as $cid) {
                $cid = (int)$cid;
                $pc->bind_param("ii", $productId, $cid);
                $pc->execute();
            }
            $pc->close();
        }

        /* Highlights */
        if (!empty($_POST['highlights'])) {
            $ph = $mysqli->prepare("INSERT INTO product_highlights (product_id, icon_id) VALUES (?,?)");
            foreach (array_slice($_POST['highlights'],0,6) as $hid) {
                $hid = (int)$hid;
                $ph->bind_param("ii", $productId, $hid);
                $ph->execute();
            }
            $ph->close();
        }

        /* Variants */
        if (!empty($_POST['variant_name'])) {
            foreach ($_POST['variant_name'] as $i => $name) {
                if (!$name) continue;

                $price = (int)$_POST['price'][$i];
                $discount = (int)$_POST['discount_price'][$i];
                $stock = (int)$_POST['stock'][$i];
                $on_sale = ($discount > 0 && $discount < $price) ? 1 : 0;

                $stmt = $mysqli->prepare("
                    INSERT INTO product_variants
                    (product_id, variant_name, price, discount_price, stock, on_sale)
                    VALUES (?,?,?,?,?,?)
                ");
                $stmt->bind_param("isiiii", $productId, $name, $price, $discount, $stock, $on_sale);
                $stmt->execute();
                $stmt->close();
            }
        }

        $mysqli->commit();
        header("Location: products.php");
        exit;

    } catch (Exception $e) {
        $mysqli->rollback();
        echo "<div style='color:red;padding:15px;'>Error: ".$e->getMessage()."</div>";
    }
}
?>

<style>
body {background:#f3f4f6;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;}
.card {background:#fff;padding:20px;margin:20px;border-radius:12px;box-shadow:0 5px 20px rgba(0,0,0,0.05);}
input,select,textarea{width:100%;padding:12px;margin-bottom:12px;border-radius:10px;border:1px solid #ddd;}
textarea{min-height:100px;}
button{padding:12px;border:none;border-radius:10px;font-weight:600;cursor:pointer;}
.primary{background:#cc2230;color:#fff;}
.secondary{background:#111827;color:#fff;}
.variant-row{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;background:#f9fafb;padding:10px;border-radius:8px;}
.image-preview{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:15px;}
.image-preview img{width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #ddd;}
.highlight-counter{font-size:13px;margin-bottom:8px;color:#555;}
@media(min-width:768px){.variant-row{grid-template-columns:2fr 1fr 1fr 1fr auto;}}
</style>

<div class="card">
<form method="post" enctype="multipart/form-data">

<h3>Product Info</h3>
<input name="name" placeholder="Product name" required>

<select name="brand_id" required>
<option value="">Select brand</option>
<?php while($b=$brands->fetch_assoc()): ?>
<option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name']) ?></option>
<?php endwhile; ?>
</select>

<textarea name="description" placeholder="Description"></textarea>
<textarea name="ingredients" placeholder="Ingredients"></textarea>
<textarea name="how_to_use" placeholder="How to use"></textarea>

<hr>

<h4>Categories</h4>
<?php while($c=$cats->fetch_assoc()): ?>
<label><input type="checkbox" name="categories[]" value="<?= $c['id'] ?>"> <?= htmlspecialchars($c['name']) ?></label><br>
<?php endwhile; ?>

<hr>

<h4>Highlights (Max 6)</h4>
<div class="highlight-counter">Selected: <span id="hlCount">0</span> / 6</div>
<?php while($h=$highlights->fetch_assoc()): ?>
<label>
<input type="checkbox" class="hlCheck" name="highlights[]" value="<?= $h['id'] ?>">
<?= htmlspecialchars($h['title']) ?>
</label><br>
<?php endwhile; ?>

<hr>

<h4>Images</h4>
<div class="image-preview" id="preview"></div>
<input type="file" name="image1" onchange="previewImage(this)">
<input type="file" name="image2" onchange="previewImage(this)">
<input type="file" name="image3" onchange="previewImage(this)">

<hr>

<h4>Variants</h4>
<div id="variant-container">
<div class="variant-row">
<input name="variant_name[]" placeholder="Size" required>
<input name="price[]" type="number" placeholder="Price" required oninput="validateDiscount(this)">
<input name="discount_price[]" type="number" placeholder="Discount">
<input name="stock[]" type="number" placeholder="Stock" required>
</div>
</div>

<button type="button" class="secondary" onclick="addVariant()">+ Add Variant</button>

<hr>

<button type="submit" class="primary">Save Product</button>

</form>
</div>

<script>
function previewImage(input){
 if(input.files && input.files[0]){
  const reader=new FileReader();
  reader.onload=e=>{
   const img=document.createElement('img');
   img.src=e.target.result;
   document.getElementById('preview').appendChild(img);
  };
  reader.readAsDataURL(input.files[0]);
 }
}

function addVariant(){
 const container=document.getElementById('variant-container');
 const div=document.createElement('div');
 div.className='variant-row';
 div.innerHTML=`
 <input name="variant_name[]" placeholder="Size" required>
 <input name="price[]" type="number" placeholder="Price" required>
 <input name="discount_price[]" type="number" placeholder="Discount">
 <input name="stock[]" type="number" placeholder="Stock" required>
 <button type="button" onclick="this.parentElement.remove()">✕</button>
 `;
 container.appendChild(div);
}

document.querySelectorAll('.hlCheck').forEach(cb=>{
 cb.addEventListener('change',()=>{
  const checked=document.querySelectorAll('.hlCheck:checked');
  if(checked.length>6){cb.checked=false;return;}
  document.getElementById('hlCount').innerText=checked.length;
 });
});
</script>

<?php require_once __DIR__ . '/admin_footer.php'; ?>