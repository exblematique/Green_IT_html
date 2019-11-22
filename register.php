<?php// Include config file
require_once "META-INF/config.php";


// Define variables and initialize with empty values
$email = $Username = $Password = $confirm_Password = "";
$email_err =$Username_err = $Password_err = $confirm_Password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate Username
    if(empty(trim($_POST["Username"]))){
        $Username_err = "Please enter a Username.";
    }
    else{
        // Prepare a select statement
        $sql = "SELECT ID FROM Account WHERE Username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_Username);

            // Set parameters
            $param_Username = trim($_POST["Username"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
            /* store result */
            mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                $Username_err = "This Username is already taken.";
                } 
                else{
                    $Username = trim($_POST["Username"]);
                }
            } 
            else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        // Close statement
        mysqli_stmt_close($stmt);
        }
    }
    // Validate Password
    if(empty(trim($_POST["Password"]))){
        $Password_err = "Please enter a Password.";     
    } elseif(strlen(trim($_POST["Password"])) < 6){
        $Password_err = "Password must have atleast 6 characters.";
    } else{
        $Password = trim($_POST["Password"]);
    }
    // Validate confirm Password
    if(empty(trim($_POST["confirm_Password"]))){
        $confirm_Password_err = "Please confirm Password.";     
    } else{
        $confirm_Password = trim($_POST["confirm_Password"]);
        if(empty($Password_err) && ($Password != $confirm_Password)){
            $confirm_Password_err = "Password did not match.";
        }
    }
    //Validate Email
    if(empty(trim($_POST["Email"]))){
        $email_err = "Please enter an Email";
    }elseif(!filter_var($_POST["Email"], FILTER_VALIDATE_EMAIL)){
        $email_err = "Please type a valid email";
    }


    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)) {


        // Prepare an insert statement
        $sql = "INSERT INTO Account (Username, Password,Actif, Email) VALUES (?, ?, 0, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $param_email);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: accueil.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
            // Close statement
        mysqli_stmt_close($stmt);
        }

        // Close connection
        mysqli_close($link);

    }

}?>



<!DOCTYPE html>
<head>
	<html lang="fr-FR">
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        
            <div class="form-group <?php echo (!empty($Username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="Username" class="form-control" value="<?php echo $Username; ?>">
                <span class="help-block"><?php echo $Username_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="Email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div> 

            <div class="form-group <?php echo (!empty($Password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="Password" name="¨Password" class="form-control" value="<?php echo $Password; ?>">
                <span class="help-block"><?php echo $Password_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($confirm_Password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="Password" name="confirm_Password" class="form-control" value="<?php echo $confirm_Password; ?>">
                <span class="help-block"><?php echo $confirm_Password_err; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>

            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>