<?php include "adminFunctions.php";
$active = "admin";
include "../nav.php";
$files = getStorageFiles();
?>
<div class="container mt-4">
  <div class="container-fluid h-100">
<?php if (!$files ) { ?>
  <div class="text-center col-12">
  <h1>All files Indexed :)</h1>
    </div>
<?php } else { ?>
  <table class="table mt-4">
    <thead>
      <tr>
        <th scope="col">Check</th>
        <th scope="col">File</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
    <?php
    foreach ($files as $value) {
      $tmp = explode("/",$value); $fname = $tmp[count($tmp)-1];
      ?>
      <tr>
        <td> <input type="checkbox" name="files" value="<?php echo $fname; ?>"> </td>
        <td><b><?php echo $fname; ?></b></td>
        <td> <a href="/search/admin/indexIt.php?file=<?php echo urlencode($fname);?>"class="btn btn-success">Index</a> </td>
      </tr>
    <?php } ?>
      </tbody>
  </table>
  <a href="#" class="btn btn-success float-right goIndex"> Index Selected</a>
  </div>

  <script>
  $(".goIndex").on("click", function(e) {
    e.preventDefault();
    var selected  = $("input:checkbox[name=files]:checked").map(function(){return $(this).val()}).get();
    if (!selected) alert("No Files selected");
    else window.location = "/search/admin/indexIt.php?file="+ encodeURIComponent(selected.join(","));
});
  </script>
</div>

<?php } ?>
