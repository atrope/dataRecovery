<?php include "adminFunctions.php";
$active = "admin";
include "../nav.php";
$files = getFiles();

?>
<div class="container mt-4">
  <a class="btn btn-info" href="/search/admin/reGenerate.php">REGENERATE DB</a>
  <a class="btn btn-success" href="/search/admin/add.php">ADD FILES</a>
  <a class="btn btn-warning" href="/search/admin/indexFiles.php">INDEX FILES</a>

  <div class="container-fluid h-100">
  <table class="table mt-4">
    <thead>
      <tr>
        <th scope="col">File</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($files as $value) {?>
      <tr>
        <td><b><?php $tmp = explode("/",$value); echo $tmp[count($tmp)-1];?></b></td>
        <td> <a href="/search/admin/del.php?file=<?php echo urlencode($tmp[count($tmp)-1]);?>"class="btn btn-danger">Delete</a> </td>
      </tr>
    <?php } ?>

      </tbody>
  </table>
  </div>
</div>
