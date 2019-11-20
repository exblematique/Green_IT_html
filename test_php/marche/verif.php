<?php
session_start();
if(isset($_POST['username']) && isset($_POST['password']))
{
    // connexion à la base de données
  $db_username = 'GHT';
  $db_password = 'azerty1234';
  $db_name     = 'GHT';
  $db_host     = 'localhost:3306';
  $db = mysqli_connect($db_host, $db_username, $db_password,$db_name)
  or die('could not connect to database');

    // on applique les deux fonctions mysqli_real_escape_string et htmlspecialchars
    // pour éliminer toute attaque de type injection SQL et XSS
    $username = mysqli_real_escape_string($db,htmlspecialchars($_POST['username'])); 
    $password = mysqli_real_escape_string($db,htmlspecialchars($_POST['password']));
  if($username !== "" && $password !== "")
  {
    $requete = "SELECT count(*) FROM Account where username = '".$_POST['username']."' and password = '".$_POST['password']."' ";
    $exec_requete = mysqli_query($db,$requete);
    $reponse      = mysqli_fetch_array($exec_requete);
    $count = $reponse['count(*)']; // utilisateur ou mot de passe incorrect

    
        if($count==1) // nom d'utilisateur et mot de passe correctes
        {
         $_SESSION['username'] = $username;
         header('Location: main.php');
       }
       else
       {
           header('Location: login.php?erreur=1'); // utilisateur ou mot de passe incorrect
         }
       }
       else
       {
       header('Location: login.php?erreur=2'); // utilisateur ou mot de passe vide
     }
   }
   else
   {
     header('Location: login.php');
   }
mysqli_close($db); // fermer la connexion
?>