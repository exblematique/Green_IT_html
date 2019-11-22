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
$Password = $cPassword = "";
$Username = $Email = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    //TEST PHASE
    
    //Password length
    if(strlen(trim($_POST["Password"])) < 6){
        session_destroy();
        header("location: accueil.php");
        exit;
    }else{
        $Password = trim($_POST["Password"]);
    }
    if( trim($_POST["cPassword"]) != trim($_POST["Password"]) ){
        session_destroy();
        header("location: accueil.php");
        exit;
    }
    if(!filter_var(trim($_POST["Email"]), FILTER_VALIDATE_EMAIL)){
        session_destroy();
        header("location: accueil.php");
        exit;
    }else{
        $Email = trim($_POST["Email"]);
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
            if(mysqli_stmt_num_rows($stmt) !=0 ){session_destroy();
                header("location: accueil.php");
                exit;
            }else {$Username = trim($_POST["Username"]);}
        }
    }
    else{
        //LETS GO
        $sql = "INSERT INTO Account (Username, Password, Actif, Email) VALUES (?, SHA1(?), 0, ?)";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $param_email);
            // Set parameters
            $param_username = $Username;
            $param_password = $Password;
            $param_email = $Email;

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
                $mail->setFrom('greenhightea@gmail.com,"GreenHighTea"');
                $mail->addAddress($Email);
                $mail->isHTML(true);
                $mail->Subject="Validation : Création de compte";
                $mail->Body='<p>Vous venez de créer un compte. Pour profiter du reste des fonctionnalités du site, vous devez attendre que vôtre compte soit manuellement vérifié par un administrateur.</p>';
                $mail->send();

                header("location: accueil.php");
                exit;
            } else{session_destroy();
                header("location: accueil.php");
                exit;
            }
        }
    }



};
?>