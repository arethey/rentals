<?php
// Include config file
require_once "../config.php";
 
// Define variables and initialize with empty values
$name = $description = $price_per_day = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Prepare an update statement
    $sql = "UPDATE tbl_listings SET name=?, description=?, price_per_day=? WHERE id=?";
         
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sssi", $name, $description, $price_per_day, $id);

        $name = trim($_POST["name"]);
        $description = trim($_POST["description"]);
        $price_per_day = trim($_POST["price_per_day"]);
        
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
                    $name = $row["name"];
                    $description = $row["description"];
                    $price_per_day = $row["price_per_day"];
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
    <h4 class="mb-3">Update Ads</h4>
    <hr />

    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" required />
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" class="form-control" id="description" name="description" value="<?php echo $description; ?>" required />
        </div>
        <div class="mb-3">
            <label for="price_per_day" class="form-label">Price per day</label>
            <input type="number" class="form-control" id="price_per_day" name="price_per_day" value="<?php echo $price_per_day; ?>" required />
        </div>

        <input type="hidden" name="id" value="<?php echo $id; ?>"/>

        <div class="mt-3">
            <a href="index.php?page=listings" class="btn">Cancel</a>
            <button type="submit" class="btn btn-dark">Save</button>
        </div>
    </form>
</div>