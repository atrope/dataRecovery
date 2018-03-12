<?php include "../functions.php";

$file = isset($_GET["file"])? urldecode($_GET["file"]):"";
if ($file){
  $path = __DIR__ ."/../files/" . $file;
  if (is_file($path)) unlink($path);
}
header("Location: reGenerate.php")

?>
