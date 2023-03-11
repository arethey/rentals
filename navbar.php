<nav class="navbar navbar-expand-lg bg-white border-bottom">
  <div class="container">
    <a class="navbar-brand" href="index.php">BAMASA</a>
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
        if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true){
          echo '
            <li class="nav-item">
              <a class="nav-link" href="'.$_SESSION["user_type"].'">My Account</a>
            </li>
          ';
        }else{
          echo '
            <li class="nav-item">
              <a class="nav-link" href="sign-in.php">Sign In</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Sign Up
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="sign-up.php?type=customer">Customer</a></li>
                <li><a class="dropdown-item" href="sign-up.php?type=owner">Owner</a></li>
              </ul>
            </li>
          ';
        }
        ?>
      </ul>
    </div>
  </div>
</nav>