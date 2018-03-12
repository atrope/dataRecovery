<?php include "../functions.php";
$active = "admin";
include "../nav.php";
?>

<div class="container mt-4">
  <?php if(!isset($_FILES['files'])){?>
<form action="" method="post" enctype="multipart/form-data">
  <div class="form-group text-center">
                      <h1 class="col-12 control-label">Attach multiple files</h1>
                      <div class="col-12">
                          <span class="btn btn-default btn-file">
                              <input name="files[]" type="file" class="file" multiple data-show-upload="true" data-show-caption="true">
                          </span>
                      </div>
                      <div class="col-12 mt-4">

                      <input type="submit"  class="btn-success btn p-3 w-50" value="Send">
</div>
                  </div>
      </form>
<?php } else {
  $total = count($_FILES['files']['name']);
  for($i=0; $i<$total; $i++) {
  $tmpFilePath = $_FILES['files']['tmp_name'][$i];
  if ($tmpFilePath != ""){
    $newFilePath = __DIR__ ."/../files/" . $_FILES['files']['name'][$i];
    if(!move_uploaded_file($tmpFilePath, $newFilePath)) $waserror=true;
  }
}
if (isset($waserror)) echo "There was an error in your upload :(";
else header("Location: reGenerate.php");
}
?>
</div>
