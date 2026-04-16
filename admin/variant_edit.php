<?php
require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/admin_header.php';

$id = (int)$_GET['id'];
$productId = (int)$_GET['product_id'];

$v = $mysqli->query("SELECT * FROM product_variants WHERE id=$id")->fetch_assoc();
$attrs = $mysqli->query("SELECT * FROM variant_attributes WHERE variant_id=$id");

if ($_SERVER['REQUEST_METHOD']==='POST') {

    $img = $v['image_path'];
    if (!empty($_FILES['image']['name'])) {
        $dir = __DIR__.'/../uploads/variants/';
        $name = time().'_'.basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'],$dir.$name);
        $img = 'uploads/variants/'.$name;
    }

    $stmt=$mysqli->prepare("
        UPDATE product_variants SET
        variant_name=?, sku=?, price=?, discount_price=?, stock=?, image_path=?
        WHERE id=?
    ");
    $stmt->bind_param(
        "ssddisi",
        $_POST['variant_name'], $_POST['sku'],
        $_POST['price'], $_POST['discount_price'] ?: null,
        $_POST['stock'], $img, $id
    );
    $stmt->execute();

    $mysqli->query("DELETE FROM variant_attributes WHERE variant_id=$id");

    if (!empty($_POST['attr_name'])) {
        $a=$mysqli->prepare("
            INSERT INTO variant_attributes (variant_id,attribute_name,attribute_value)
            VALUES (?,?,?)
        ");
        foreach($_POST['attr_name'] as $i=>$n){
            if(!$n) continue;
            $a->bind_param("iss",$id,$n,$_POST['attr_value'][$i]);
            $a->execute();
        }
    }

    header("Location: product_variants.php?product_id=$productId");
    exit;
}
?>

<div class="card">
<h3>Edit Variant</h3>

<form method="post" enctype="multipart/form-data">
<input name="variant_name" value="<?= e($v['variant_name']) ?>"><br><br>
<input name="sku" value="<?= e($v['sku']) ?>"><br><br>

<input type="number" step="0.01" name="price" value="<?= $v['price'] ?>"><br><br>
<input type="number" step="0.01" name="discount_price" value="<?= $v['discount_price'] ?>"><br><br>
<input type="number" name="stock" value="<?= $v['stock'] ?>"><br><br>

<?php if($v['image_path']): ?>
<img src="../<?= $v['image_path'] ?>" width="60"><br>
<?php endif; ?>
<input type="file" name="image"><br><br>

<?php while($a=$attrs->fetch_assoc()): ?>
<div class="form-row">
  <input name="attr_name[]" value="<?= e($a['attribute_name']) ?>">
  <input name="attr_value[]" value="<?= e($a['attribute_value']) ?>">
</div>
<?php endwhile; ?>

<button>Add Attribute</button><br><br>
<button>Update Variant</button>
</form>
</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>