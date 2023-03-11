<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true){
    if($_SESSION["user_type"] == "customer"){
        header("location: ".$_SESSION["user_type"]);
        exit;
    }
}else{
    header("location: ../sign-in.php");
    exit;
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Owner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  
    <style>
      .nav .nav-link{
        color: #fff;
      }
      .nav .nav-link:hover{
        background: #fff;
        color: #000;
      }
    </style>
  
  </head>
  <body class="bg-light">
    <div class="d-flex w-100 vh-100">
      <div class="bg-dark p-3" style="width: 300px">
        <h1 class="text-center text-white">BAMASA</h1>
        <hr class="text-white" />

        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link rounded" href="index.php?page=dashboard">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link rounded" href="index.php?page=bookings">Bookings</a>
          </li>
          <li class="nav-item">
            <a class="nav-link rounded" href="index.php?page=listings">Listings</a>
          </li>
          <li class="nav-item">
            <a class="nav-link rounded" href="index.php?page=messages">Messages</a>
          </li>
        </ul>
      </div>

      <div class="flex-grow-1">
        <?php include "navbar.php" ?>
        <div class="container-fluid">
          <?php
            if(isset($_GET['page']) && !empty($_GET['page'])){
              include "page/".$_GET['page'].".php";
            }else{
              include "page/dashboard.php";
            }
          ?>
        </div>
      </div>
    </div>
    
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>