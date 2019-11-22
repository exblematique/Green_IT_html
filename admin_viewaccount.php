<?php
session_start();
require_once "META-INF/config.php";
$mysqli = new mysqli("localhost:3306", "GHT", "azerty1234", "GHT");
if($_SESSION["Username"] != "D4G2019"){
    header("location: accueil.php");
    exit;
}
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(!empty($_POST["ID"])){
        $stmt = $mysqli->prepare("UPDATE Account SET `Actif` = 1 WHERE ID = ?");
        $stmt->bind_param('s', $ID);
        $ID = $_POST["ID"];
        $stmt->execute();
        $stmt->close();
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
        $mail->addAddress(trim($_POST['Email']));
        $mail->isHTML(true);
        $mail->Subject="Validation : vérification du compte";
        $mail->Body="<p>Vous recevez ce mail car vôtre compte viens d'être validé par un administrateur. Vous pouvez desormais vous connecter et accéder au facture de votre loyé (par défaut vous n'en avez pas)</p>";
        $mail->send();
    };
}

$query = "SELECT ID,Username,Actif,Email FROM Account ";
if ($result = $mysqli->query($query)) {
    $map  ="";
    while ($row = $result->fetch_row()) {
        $tab ="<form action=\"/admin_viewaccount.php\" method=\"post\">";
        $tab .="<input type=\"text\" name=\"ID\" value=".$row[0].">  ".$row[1];
        if ($row[2] === "0"){
            $tab.="<input name=\"Email\" type=\"hidden\" value=\"".$row[3]."\"><input type=\"submit\" value=\"Submit\">";
        }
        $tab.="</form>";
        $map.=$tab;
    };
    /* Libère le résultat */
    $result->close();
}
/*Ferme la connexion */
$mysqli->close();

echo $map;
?>