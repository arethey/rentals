<?php
  $page = "dashboard";
  if(isset($_GET['page']) && !empty($_GET['page'])){
    $page = $_GET['page'];
  }
?>

<nav class="navbar navbar-expand-lg bg-white border-bottom mb-5">
  <div class="container-fluid">
    <a class="navbar-brand text-uppercase" href="../index.php"><?php echo $page ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <!-- <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li> -->
      </ul>
      <ul class="navbar-nav mb-2 mb-lg-0">
        <?php
          echo '
            <li class="nav-item">
              <a class="nav-link" href="../logout.php">Sign Out</a>
            </li>
          ';
        ?>
      </ul>
    </div>
  </div>
</nav>