<?php
$active = "index";
include "functions.php";
include "nav.php";

$file = isset($_GET["file"])? urldecode($_GET["file"]):"";
$term = isset($_GET["term"])? urldecode($_GET["term"]):"";
$newterm = cleanString($term);
if ($file){
  $path = __DIR__ ."/files/" . $file;
  if (is_file($path)) $content = explode("\n",file_get_contents($path));
}


?>
<div class="container h-100 mt-4 text-center">
  <h1 class="text-center mb-5"><?php echo ucfirst(str_replace("-"," ",str_replace(".txt","",$file)));?></h1>
  <div class="row">

  <div class="col-6">
  <?php  foreach ($content as $lines){
    $words = explode(" ",$lines);
    echo "<p>";
    foreach ($words as $word) echo in_array(strtolower(preg_replace('/[^A-Za-z \-]/', '', $word)),$newterm)? "<mark>$word </mark>":$word." ";
    echo "</p>";
  }
  ?>
</div>
<div class="col-6 text-center">
  <iframe src="https://www.youtube.com/embed?listType=search&list=<?php echo preg_replace('/[^A-Za-z \-]/', '', $term);?>" width="80%" height="300"></iframe>
</div>  </div>


</div>
