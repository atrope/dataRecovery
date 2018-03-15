<?php include "adminFunctions.php";

$files = isset($_GET["file"])? urldecode($_GET["file"]):"";
$files = explode(",",$files);
foreach ($files as $file) {
  if ($file){
    $path = __DIR__ ."/../storage/" . $file;
    if (is_file($path)) copy($path,str_replace("/storage/","/files/",$path));
  }
}
header("Location: reGenerate.php")
?>
