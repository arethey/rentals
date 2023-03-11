<?php
// Include config file
require_once "../config.php";
 
// Define variables and initialize with empty values
$thumbnail = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
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
            move_uploaded_file($_FILES["thumbnail"]["tmp_name"], "../upload/listings/" . $filename);

            // Get hidden input value
            $id = $_POST["id"];
            $curr_thumbnail = $_POST["curr_thumbnail"];

            unlink("../upload/listings/" . $curr_thumbnail);
            
            // Prepare an update statement
            $sql = "UPDATE tbl_listings SET thumbnail=? WHERE id=?";
                
            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "si", $thumbnail, $id);

                $thumbnail = $filename;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Records updated successfully. Redirect to landing page
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
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM tbl_listings WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $thumbnail = $row["thumbnail"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: index.php?page=listings");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: index.php?page=listings");
        exit();
    }
}
?>

<div class="col-md-6 mx-auto bg-white rounded p-3 shadow-sm">
    <h4 class="mb-3">Change Thumbnail</h4>
    <hr />

    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="thumbnail" class="form-label">Thumbnail</label>
            <input class="form-control" type="file" id="thumbnail" name="thumbnail" required />
            <p><strong>Note:</strong> Only .jpg, .jpeg, .gif, .png formats allowed to a max size of 5 MB.</p>
        </div>

        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
        <input type="hidden" name="curr_thumbnail" value="<?php echo $thumbnail; ?>"/>

        <div class="mt-3">
            <a href="index.php?page=listings" class="btn">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>