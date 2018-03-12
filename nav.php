<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Search</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item<?php if ($active=="index") echo " active";?>">
        <a class="nav-link" href="/search/index.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item<?php if ($active=="admin") echo " active";?>">
        <a class="nav-link" href="/search/admin">Admin</a>
      </li>
    </ul>
    <form action="/search/index.php" method="post" class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="text"  name="term"  placeholder="Search" aria-label="Search">
      <input type="submit" class="btn-success btn" value="Search">
    </form>
  </div>
</nav>
