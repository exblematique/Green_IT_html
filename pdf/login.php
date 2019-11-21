<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}

// Include config file
require_once "META-INF/config.php";

// Define variables and initialize with empty values
$Username = $Password = "";
$Username_err = $Password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if Username is empty
    if(empty(trim($_POST["Username"]))){
        $Username_err = "Please enter Username.";
    } else{
        $Username = trim($_POST["Username"]);
    }
    
    // Check if Password is empty
    if(empty(trim($_POST["Password"]))){
        $Password_err = "Please enter your Password.";
    } else{
        $Password = trim($_POST["Password"]);
    }
    
    // Validate credentials
    if(empty($Username_err) && empty($Password_err)){
        // Prepare a select statement
        $sql = "SELECT ID, Username, Foyer, Password FROM Account WHERE Username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_Username);
            
            // Set parameters
            $param_Username = $Username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if Username exists, if yes then verify Password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id , $Username , $Foyer , $hashed_Password);
                    if(mysqli_stmt_fetch($stmt)){
                        if($Password === $hashed_Password){
                            // Password is correct, so start a new session
                            if($Username == "D4G2019"){
                                session_start();
                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["ID"] = $id;
                                $_SESSION["Username"] = $Username;
                                // Redirect user to welcome page
                                header("location: admin_view.php");
                            }
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["ID"] = $id;
                            $_SESSION["Username"] = $Username;
                            $_SESSION["Foyer"] = $Foyer;     
                            
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Display an error message if Password is not valid
                            $Password_err = "The Password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if Username doesn't exist
                    $Username_err = "No account found with that Username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($Username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="Username" class="form-control" value="<?php echo $Username; ?>">
                <span class="help-block"><?php echo $Username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($Password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="Password" name="Password" class="form-control">
                <span class="help-block"><?php echo $Password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>    
</body>
</html>