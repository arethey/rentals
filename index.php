<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BAMASA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  </head>
  <body class="bg-light">
    <?php include "navbar.php" ?>

    <div class="container mt-5">
      <?php
      // Include config file
      require_once "config.php";

      // Attempt select query execution
      $sql = "SELECT * FROM tbl_listings";
      if($result = mysqli_query($link, $sql)){
          if(mysqli_num_rows($result) > 0){
              echo '<div class="row">';
                  while($row = mysqli_fetch_array($result)){
                      echo "<div class='col-3 mb-3'>";
                          echo '
                              <div class="card">
                                  <img src="upload/listings/'.$row['thumbnail'].'" class="card-img-top" alt="'.$row['thumbnail'].'">
                                  <div class="card-body">
                                      <div class="d-flex align-items-start justify-content-between mb-4">
                                          <div>
                                              <h5 class="card-title">'.$row['name'].'</h5>
                                              <p class="card-text">'.$row['description'].'</p>
                                          </div>
                                      </div>
                                      <h3>₱ '.$row['price_per_day'].' /day</h3>
                                      <a href="listing.php?id='.$row['id'].'" class="mt-3 btn btn-primary w-100">Start Booking</a>
                                  </div>
                              </div>
                          ';
                      echo "</div>";
                  }                           
              echo "</div>";
              // Free result set
              mysqli_free_result($result);
          } else{
              echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
          }
      } else{
          echo "Oops! Something went wrong. Please try again later.";
      }

      // Close connection
      mysqli_close($link);
      ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>