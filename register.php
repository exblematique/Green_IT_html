<?php

// Include config file
require_once "META-INF/config.php";
session_start();
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}

// Define variables and initialize with empty values

$error_message = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //TEST PHASE
    
    //Password length
    if(strlen(trim($_POST["Password"])) < 6){
        $error_message.="Mot de passe trop court.\n";
    }
    if( $_POST["cPassword"] != $_POST["Password"] ){
        $error_message.="Les mot de passe ne sont pas identiques. \n";
    }
    if(!filter_var($_POST["Email"], FILTER_VALIDATE_EMAIL)){
        $error_message.="L'adresse mail fournie n'est pas valide.\n";
    }
    //Test de l'username
    $sql = "SELECT ID FROM Account WHERE Username = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_Username);

        // Set parameters
        $param_Username = trim($_POST["Username"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) !=0 ){
                $error_message.="Nom d'utilisateur déjà pris";
            }
        }
    }
    if(strlen($error_message) > 0){
        //STOP
    }
    else{
        //LETS GO
        $sql = "INSERT INTO Account (Username, Password, Actif, Email) VALUES (?, SHA1(?), 0, ?)";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $param_email);
            // Set parameters
            $param_username = $_POST['Username'];
            $param_password = $_POST['Password'];
            $param_email = $_POST['Email'];

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page

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
                $mail->addAddress($_POST['Email']);
                $mail->isHTML(true);
                $mail->Subject="Validation : Création de compte";
                $mail->Body='<p>Vous venez de créer un compte. Pour profiter du reste des fonctionnalités du site, vous devez attendre que vôtre compte soit manuellement vérifié par un administrateur.</p>';
                $mail->send();

                header("location: accueil.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
    }



};

?>

<!DOCTYPE html>
<head>
	<html lang="fr-FR">
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <?php echo $error_message ?>
        <form action='/register.php' method="post">
            Username:<br>
            <input type="text" name="Username" value="">
            <br>
            Email:<br>
            <input type="text" name="Email" value="">
            <br>
            Password:<br>
            <input type="Password" name="Password" value="">
            <br>
            Password confirmation:<br>
            <input type="Password" name="cPassword" value="">
            <br><br>
            <input type="submit" value="Submit">
        </form>
    </div>    
</body>
</html>