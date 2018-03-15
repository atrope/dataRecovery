<?php
include "functions.php";
include "admin/adminFunctions.php";
$active = "index";
include "nav.php";

//$string = "A || (B && F && G) && (D || E)";
//$string = "let || (be && jude)";
if (isset($_REQUEST["term"]) && $_REQUEST["term"]!=""){
  $search = normalizeString($_REQUEST["term"]);
  $searchArr = [];
  $translate = translate($search);
  $arrString = makeArray($search);
  processTerms($arrString,$translate,$searchArr);
  $response = finalSearch($arrString,$translate,$searchArr);
?>
<div class="container h-100 mt-4">
  <h1 class="text-center"><?php echo $_REQUEST["term"];?></h1>
<table class="table mt-4">
  <thead>
    <tr>
      <th scope="col">File</th>
      <th scope="col">Download</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($response["files"] as $value) { ?>
    <tr>
      <td><?php echo $value["file"]; ?></td>
      <td> <a href='<?php echo "./files/".$value["file"];?>' class="btn btn-warning" download>Click Here</a> </td>
    </tr>
  <?php } ?>
    </tbody>
</table>
</div>
<?php
}else {
  ?>
<div class="container-fluid h-100 mt-4">
    <div class="row justify-content-center align-items-center h-100">
        <div class="col col-sm-6 col-md-6 col-lg-4 col-xl-3">
            <form action="/search/index.php" method="post" class="text-center">
              <h1> SEARCH FOR IT!</h1>
                <div class="form-group">
                  <input type="text" name="term" value="" placeholder="let || (be && jude)" class="p-3 w-100">
                </div>

                <div class="form-group">
                  <input type="submit"  class="btn-success btn p-3 w-100" value="Search">
                </div>
                <h6 class="text-right"> <?php echo count(getFiles());?> indexed files</h6>

            </form>
        </div>
    </div>
</div>
  <?php
}


?>
