<?php
declare(strict_types=1);
session_start();

/* ================= CONFIG ================= */
const BASE_DIR = __DIR__ . '/../';
const USERNAME = 'nathan';
const PASSWORD_HASH = '$2b$12$Jh0qlDdBJq/9imQfaM4egO9vVDQAsHStpl/WtBk7XdRk5ihyoa.mC';

/* ================= HELPERS ================= */
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES); }

function base_real(){
    $b = realpath(BASE_DIR);
    if(!$b) die("Invalid BASE_DIR");
    return rtrim($b,'/');
}

function safe_join($rel){
    $base = base_real();
    $rel  = ltrim((string)$rel,'/');
    $full = realpath($base.'/'.$rel);
    if(!$full) return $base.'/'.$rel;
    if(strpos($full,$base)!==0) die("Access denied");
    return $full;
}

/* ================= AUTH ================= */
if(!isset($_SESSION['user'])){
if($_SERVER['REQUEST_METHOD']==='POST'){
if($_POST['username']===USERNAME &&
password_verify($_POST['password'],PASSWORD_HASH)){
$_SESSION['user']=USERNAME;
header("Location: ?");
exit;
}}
?>
<form method="post" style="font-family:Arial;padding:40px">
<h2>FM Login</h2>
<input name="username"><br><br>
<input name="password" type="password"><br><br>
<button>Login</button>
</form>
<?php exit; }

/* ================= PATH ================= */
$path=$_GET['p']??'';
$cwd=safe_join($path);

/* ================= CREATE ================= */
if(isset($_POST['create_folder'])){
$name=trim($_POST['folder_name']);
if($name) mkdir($cwd.'/'.$name);
}

if(isset($_POST['create_file'])){
$name=trim($_POST['file_name']);
if($name) file_put_contents($cwd.'/'.$name,'');
}

/* ================= UPLOAD ================= */
if(isset($_POST['upload_files'])){
foreach($_FILES['files']['tmp_name'] as $k=>$tmp){
$name=basename($_FILES['files']['name'][$k]);
move_uploaded_file($tmp,$cwd.'/'.$name);
}
}

/* ================= RENAME ================= */
if(isset($_POST['rename_file'])){
$old=safe_join(trim($path.'/'.$_POST['old_name'],'/'));
$new=$cwd.'/'.basename($_POST['new_name']);
rename($old,$new);
}

/* ================= DELETE ================= */
if(isset($_POST['delete_file'])){
$file=safe_join(trim($path.'/'.$_POST['delete_name'],'/'));
if(is_dir($file)){
$r=new RecursiveIteratorIterator(
new RecursiveDirectoryIterator($file,FilesystemIterator::SKIP_DOTS),
RecursiveIteratorIterator::CHILD_FIRST
);
foreach($r as $f){
$f->isDir()? rmdir($f):unlink($f);
}
rmdir($file);
}else unlink($file);
}

/* ================= EXTRACT ZIP ================= */
if(isset($_POST['extract_zip'])){
$file=safe_join(trim($path.'/'.$_POST['zip_name'],'/'));
$zip=new ZipArchive();
if($zip->open($file)===TRUE){
$zip->extractTo($cwd);
$zip->close();
}
}

/* ================= ZIP SELECTED ================= */
if(isset($_POST['zip_selected'])){

$zipName="archive_".date("Ymd_His").".zip";
$zipPath=sys_get_temp_dir()."/".$zipName;

$zip=new ZipArchive();
$zip->open($zipPath,ZipArchive::CREATE);

foreach($_POST['selected'] as $item){

$full=safe_join(trim($path.'/'.$item,'/'));

if(is_dir($full)){

$files=new RecursiveIteratorIterator(
new RecursiveDirectoryIterator($full,FilesystemIterator::SKIP_DOTS),
RecursiveIteratorIterator::LEAVES_ONLY
);

foreach($files as $file){
if(!$file->isDir()){
$zip->addFile($file->getRealPath(),
substr($file->getRealPath(),strlen($cwd)+1));
}}

}else{
$zip->addFile($full,basename($full));
}
}

$zip->close();

header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="'.$zipName.'"');
readfile($zipPath);
unlink($zipPath);
exit;
}

/* ================= EDITOR ================= */
if(isset($_GET['edit'])){

$editName=$_GET['edit'];
$file=safe_join(trim($path.'/'.$editName,'/'));

if(isset($_POST['save'])){
file_put_contents($file,$_POST['content']);
}

$content=file_get_contents($file);
$lines=substr_count($content,"\n")+1;
?>

<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width,initial-scale=1">

<style>
body{margin:0;font-family:monospace;background:#0f1115;color:#e6edf3}

a{ text-decoration:none; color:#fff; }

.sticky{
position:fixed;
top:0;
left:0;
right:0;
background:#111827;
padding:10px;
display:flex;
justify-content:space-between;
align-items:flex-start;
z-index:999;
}

.header-left{
display:flex;
flex-direction:column;
max-width:70%;
}

.path-text{
font-size:12px;
color:#ccc;
word-break:break-all;
}

.editor-wrap{
display:flex;
margin-top:70px;
height:calc(100vh - 70px);
}

.lines{
width:60px;
background:#000;
color:#666;
padding:12px 8px;
text-align:right;
border-right:1px solid #222;
}

textarea{
flex:1;
background:#0b0f14;
color:#e6edf3;
border:0;
padding:12px;
font-size:13px;
line-height:1.5;
resize:none;
outline:none;
}
</style>

</head>
<body>

<form method="post">

<div class="sticky">

<div class="header-left">
<a href="?p=<?=h($path)?>">← Back</a>
<div class="path-text"><?=BASE_DIR.$path.'/'.$editName?></div>
</div>

<div>
<button name="save">Save</button>
</div>

</div>

<div class="editor-wrap">

<div class="lines">
<?php for($i=1;$i<=$lines;$i++) echo $i."<br>"; ?>
</div>

<textarea name="content"><?=h($content)?></textarea>

</div>

</form>
</body>
</html>

<?php exit; }

/* ================= LIST ================= */

/* folders first sorting */
$scan=scandir($cwd);

$folders=[];
$files=[];

foreach($scan as $item){

if($item=='.'||$item=='..') continue;

if(is_dir($cwd.'/'.$item)){
$folders[]=$item;
}else{
$files[]=$item;
}

}

$items=array_merge($folders,$files);

$parent=dirname($path);
if($parent=='.')$parent='';
?>

<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width,initial-scale=1">

<style>

body{font-family:Arial;background:#f5f6f8;margin:0;padding:20px}

a{
text-decoration:none;
color:#000;
}

.grid{
display:grid;
grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
gap:12px;
}

.card{
background:#fff;
padding:12px;
border-radius:10px;
}

.file-row{
display:flex;
align-items:center;
gap:8px;
}

</style>

</head>
<body>

<h3>File Manager</h3>

<div style="margin-bottom:10px">
<a href="?p=<?=h($parent)?>" style="padding:6px 10px;background:#111;color:#fff;border-radius:6px">← Back</a>
</div>

<div style="margin-bottom:10px">
Path: <?=BASE_DIR.$path?>
</div>

<form method="post">

<button name="zip_selected">Create ZIP</button>

<div class="grid">

<?php foreach($items as $i): ?>

<div class="card file-row">

<input type="checkbox" name="selected[]" value="<?=$i?>">

<?php if(is_dir($cwd.'/'.$i)): ?>

📁 <a href="?p=<?=trim($path.'/'.$i,'/')?>"><?=$i?></a>

<?php else: ?>

📄 <a target="_blank" href="?p=<?=$path?>&edit=<?=$i?>"><?=$i?></a>

<?php if(str_ends_with($i,'.zip')): ?>

<button name="extract_zip" onclick="this.form.zip_name.value='<?=$i?>'">Extract</button>

<?php endif; ?>

<?php endif; ?>

<button name="delete_file" onclick="this.form.delete_name.value='<?=$i?>'">Delete</button>

<button name="rename_file"
onclick="var n=prompt('New name','<?=$i?>'); if(n){this.form.old_name.value='<?=$i?>'; this.form.new_name.value=n;}">
Rename
</button>

</div>

<?php endforeach; ?>

</div>

<input type="hidden" name="zip_name">
<input type="hidden" name="delete_name">
<input type="hidden" name="old_name">
<input type="hidden" name="new_name">

</form>

<hr>

<form method="post">
<input name="folder_name" placeholder="Folder name">
<button name="create_folder">Create Folder</button>
</form>

<form method="post">
<input name="file_name" placeholder="File name">
<button name="create_file">Create File</button>
</form>

<form method="post" enctype="multipart/form-data">
<input type="file" name="files[]" multiple>
<button name="upload_files">Upload Files</button>
</form>

</body>
</html>