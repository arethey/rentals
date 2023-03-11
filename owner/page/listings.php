<a href="index.php?page=listings-new" class="btn btn-dark mb-3">Create Ads</a>

<?php
// Include config file
require_once "../config.php";

// Attempt select query execution
$sql = "SELECT * FROM tbl_listings WHERE user_id = ".$_SESSION["id"];
if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) > 0){
        echo '<div class="row">';
            while($row = mysqli_fetch_array($result)){
                echo "<div class='col-3 mb-3'>";
                    echo '
                        <div class="card">
                            <img src="../upload/listings/'.$row['thumbnail'].'" class="card-img-top" alt="'.$row['thumbnail'].'">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between mb-4">
                                    <div>
                                        <h5 class="card-title">'.$row['name'].'</h5>
                                        <p class="card-text">'.$row['description'].'</p>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="index.php?page=listing-change-photo&id='.$row['id'].'" class="dropdown-item">Change Thumbnail</a></li>
                                            <li><a href="index.php?page=listing-update&id='.$row['id'].'" class="dropdown-item">Update</a></li>
                                            <li><a onclick="return confirm(\'Are you sure you want delete this data?\')" href="page/listing-delete.php?id='. $row['id'] .'" class="dropdown-item">Delete</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <h3>₱ '.$row['price_per_day'].' /day</h3>
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