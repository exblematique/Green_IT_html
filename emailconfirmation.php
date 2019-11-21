<?php
// Check if the user is logged in, otherwise redirect to login page
// if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
//     header("location: login.php");
//     exit;
// }
 
// Include config file
require_once "META-INF/config.php";


// Prepare an update statement as a function
function PasswordReset($user_id,$nouveau_password) {
    $sql = "UPDATE Account SET Password = ? WHERE ID= ?";
            
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
        
        // Set parameters
        $param_password = $nouveau_password;
        $param_id = $user_id;
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Password updated successfully. Destroy the session, and redirect to login page
            session_destroy();
            header("location: login.php");
            exit();
        }else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
}
function CloseVerification($verification_id) {
    $sql = "UPDATE Verification SET Done = false WHERE ID = ?";
            
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "si", $truc_id);
        
        // Set parameters
        $truc_id = $verification_id;
        
        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);
    }
}

// Query pour savoir si le code en argument est bon
$sql = "SELECT `ID`, `Done`, `Code`, `UserID`, `Password`, `Type` FROM `Verification` WHERE Code = ?";

if($stmt = mysqli_prepare($link, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "s", $_GET["q"]);
    
    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
        // Store result
        mysqli_stmt_store_result($stmt);
        
        // Check if the code matches with an existing one
        if(mysqli_stmt_num_rows($stmt) == 1){                    
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $ID, $Done, $Code, $UserID, $Password , $Type);
            mysqli_stmt_fetch($stmt);

            //UPDATE DE LA BD + ACTION Demandée

            if ($Done === false){ //L'action est à faire
                CloseVerification();
                if($Type === 0){ // Changement de mot de passe
                    PasswordReset($UserID,$Password);
                }
                else{ // Reset de mot de passe (mise en place d'un mot de passe générique)
                    PasswordReset($UserID,"temporarypassword");
                }
            }
            else{
                //RIEN L'action a déjà été réalisée
                // redirection vers accueil
            }
        else {
            //ERREUR
        }
    }else{
        echo "Oops! Something went wrong. Please try again later.";
    }
}

// Close statement
mysqli_stmt_close($stmt);




?>


<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta charset="UTF-8">
    <title>Email confirmation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Email confirmation </h2>
        <?php
        echo 'Bonjour ' . $_GET["q"];
        ?>
        <br>
        <?php
        echo  $ID . $Done;
        ?>
        </div>    
</body>
</html>