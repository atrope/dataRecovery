<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Search</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="/search">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/search/admin">Admin</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/search/reGenerate.php">REGENERATE</a>
      </li>

    </ul>
    <form action="/search/index.php" method="post" class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="text"  name="term"  placeholder="Search" aria-label="Search">
      <input type="submit" class="btn-success btn" value="Search">

    </form>
  </div>
</nav>

<?php

include "functions.php";

//$string = "A || (B && F && G) && (D || E)";
//$string = "let || (be && jude)";
if (isset($_REQUEST["term"]) && $_REQUEST["term"]!=""){
  $search = strtolower($_REQUEST["term"]);
  $searchArr = [];
  $translate = translate($search);
  $arrString = makeArray($search);
  processTerms($arrString,$translate,$searchArr);
  $response = finalSearch($arrString,$translate,$searchArr);
?>
<div class="container h-100">

<table class="table mt-4">
  <thead>
    <tr>
      <th scope="col">File</th>
      <th scope="col">Download</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($response["files"] as $value) {

    ?>
    <tr>
      <td><?php $tmp = explode("/",$value["file"]); echo $tmp[count($tmp)-1];?></td>
      <td> <a href="/search/files/<?php echo $tmp[count($tmp)-1];?>">Click Here</a> </td>
    </tr>
  <?php } ?>

    </tbody>
</table>
</div>
<?php
}else {
  ?>
<div class="container-fluid h-100">
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
            </form>
        </div>
    </div>
</div>
  <?php
}


?>
