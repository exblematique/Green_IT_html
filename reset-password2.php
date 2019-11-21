<?php






// Initialize the session
session_start();
 
// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Include config file
require_once "META-INF/config.php";
 
// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have atleast 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){

        //REDIRECTION Vers une page -> veillez confirmer la demande de mail

        // 1-Ajout demande dans la DDB + 2-envoie du mail pour confirmer

        //1 ON SUPPOSE QUE L ON A LE MAIL DE L USER !!!
        // Prepare an update statement
        $sql = "INSERT INTO `Verification`(`Done`, `Code`, `Email`, `Type`) VALUES (false,?,?,0)";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_code , $param_email);
            
            // Set parameters
            $param_code = hash('sha1',floor(microtime(true)*100));
            $param_email = $_SESSION["id"];
            
            // Attempt to execute the prepared statement

            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();

                //2- ENVOIE D'UN MAIL
                require 'PHPMailerAutoload.php';
                $mail = new PHPMailer;
                $mail->isSMTP();
                $mail->Host='smtp.gmail.com';
                $mail->Port=587;
                $mail->SMTPAuth=true;
                $mail->SMTPSecure='tls';
                $mail->Username='greenhightea@gmail.com';
                $mail->Password='7WjTgoLS7WjTgoLS';
                $mail->setFrom('greenhightea@gmail.com','ALED');
                $mail->addAddress('tdepreux.ir2021@esaip.org');//$mail->addAddress($param_email); A CHANGER 
                $mail->isHTML(true);
                $mail->Subject="Validation : Changement de mot de passe";
                $mail->Body='<p>Vous avez fait la demande de changement de mot de passe <L
                Pour completer l\'operation veuillez vous reconnecter via le lien suivant :</p><br>
                <p><a href="URL">Lien de validation du changement de mot de passe</a><p> 
                <p>Si vous n\'avez pas fait la demande de changement de mot de passe, nous vous conseillons de le changer dès que possible</p>
                '; //URL A MODIFIER
                $mail->send();
                //






                header("location: login.php");
                exit();
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
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
        <h2>Reset Password</h2>
        <?php
        echo  $_SESSION["username"];
        ?>
        <p>Please fill out this form to reset your password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                <span class="help-block"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link" href="welcome.php" >Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>