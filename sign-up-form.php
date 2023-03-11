<?php
if(!isset($_GET["type"]) && empty($_GET["type"])){
    header("location: index.php");
}else{
    require_once "config.php";

    $email = $password = $name = $address = $contact = "";
    $email_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);
        $name = trim($_POST["name"]);
        $address = trim($_POST["address"]);
        $contact = trim($_POST["contact"]);
        $type = trim($_POST["type"]);

        $sql = "SELECT id FROM users WHERE email = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            $param_email = trim($_POST["email"]);
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already taken.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            
            mysqli_stmt_close($stmt);
        }

        if(empty($email_err)){
            $sql = "INSERT INTO users (email, password, name, address, contact, type) VALUES (?, ?, ?, ?, ?, ?)";
            
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "ssssss", $email, $param_password, $name, $address, $contact, $type);

                $param_password = password_hash($password, PASSWORD_DEFAULT);
                
                if(mysqli_stmt_execute($stmt)){
                    header("location: sign-in.php");
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }

                mysqli_stmt_close($stmt);
            }
        }

        mysqli_close($link);
    }
}
?>

<form class="col-md-6 mx-auto bg-white rounded shadow-sm p-3" action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" required />
    </div>
    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <input type="text" class="form-control" id="address" name="address" value="<?php echo $address; ?>" required />
    </div>
    <div class="mb-3">
        <label for="contact" class="form-label">Contact Number</label>
        <input type="number" class="form-control" id="contact" name="contact" value="<?php echo $contact; ?>" required />
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" id="email" name="email" required />
        <span class="invalid-feedback"><?php echo $email_err;?></span>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" value="<?php echo $password; ?>" required />
    </div>
    
    <input type="hidden" name="type" value="<?php echo $_GET["type"]; ?>" />
    <div>
        <input type="submit" class="btn btn-primary" value="Sign Up">
        <input type="reset" class="btn btn-secondary ml-2" value="Reset">
    </div>
</form>