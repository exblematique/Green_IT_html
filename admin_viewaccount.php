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
        echo $_POST["ID"];
        $stmt = $mysqli->prepare("UPDATE Account SET `Actif` = 1 WHERE ID = ?");
        $stmt->bind_param('s', $ID);
        $ID = $_POST["ID"];
        $stmt->execute();
        //printf("%d Ligne modifiée .\n", $stmt->affected_rows);
        $stmt->close();
    };
}

$query = "SELECT ID,Username,Actif FROM Account ";
if ($result = $mysqli->query($query)) {
    $map  ="";
    while ($row = $result->fetch_row()) {
        $tab ="<form action=\"/admin_viewaccount.php\" method=\"post\">";
        $tab .="<input type=\"text\" name=\"ID\" value=".$row[0].">  ".$row[1];
        if ($row[2] === "0"){
            $tab.="<input type=\"submit\" value=\"Submit\">";
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