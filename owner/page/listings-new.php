<?php
// Include config file
require_once "../config.php";
 
// Define variables and initialize with empty values
$name = $description = $price_per_day = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_FILES["thumbnail"]) && $_FILES["thumbnail"]["error"] == 0){
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["thumbnail"]["name"];
        $filetype = $_FILES["thumbnail"]["type"];
        $filesize = $_FILES["thumbnail"]["size"];
    
        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
    
        // Verify file size - 5MB maximum
        $maxsize = 5 * 1024 * 1024;
        if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
    
        // Verify MYME type of the file
        if(in_array($filetype, $allowed)){
            // Prepare an insert statement
            $sql = "INSERT INTO tbl_listings (user_id, name, description, price_per_day, thumbnail) VALUES (?, ?, ?, ?, ?)";
                
            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sssss", $user_id, $name, $description, $price_per_day, $thumbnail);
                
                $user_id = $_SESSION["id"];
                $name = trim($_POST["name"]);
                $description = trim($_POST["description"]);
                $price_per_day = trim($_POST["price_per_day"]);
                $thumbnail = $filename;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    move_uploaded_file($_FILES["thumbnail"]["tmp_name"], "../upload/listings/" . $filename);
                    
                    // Records created successfully. Redirect to landing page
                    header("location: index.php?page=listings");
                    exit();
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
            
            // Close statement
            mysqli_stmt_close($stmt);

            // Close connection
            mysqli_close($link);
        } else{
            $file_err = "Error: There was a problem uploading your file. Please try again."; 
        }
    } else{
        $file_err = "Error: " . $_FILES["thumbnail"]["error"];
    }
}
?>

<div class="col-md-6 mx-auto bg-white rounded p-3 shadow-sm">
    <h4 class="mb-3">New Ads</h4>
    <hr />

    <?php 
        if(!empty($file_err)){
            echo '<div class="alert alert-danger">' . $file_err . '</div>';
        }        
    ?>

    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required />
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" class="form-control" id="description" name="description" required />
        </div>
        <div class="mb-3">
            <label for="price_per_day" class="form-label">Price per day</label>
            <input type="number" class="form-control" id="price_per_day" name="price_per_day" required />
        </div>
        <div class="mb-3">
            <label for="thumbnail" class="form-label">Thumbnail</label>
            <input class="form-control" type="file" id="thumbnail" name="thumbnail" required />
            <p><strong>Note:</strong> Only .jpg, .jpeg, .gif, .png formats allowed to a max size of 5 MB.</p>
        </div>

        <div class="mt-5">
            <a href="index.php?page=listings" class="btn">Cancel</a>
            <button type="submit" class="btn btn-dark">Save</button>
        </div>
    </form>
</div>