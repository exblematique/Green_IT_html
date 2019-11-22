<?php
// Destruction de la session
session_start();
$_SESSION = array();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Login/Registration Form Transition</title>
  <link rel="stylesheet" href="./style_accueil.css">
</head>
<body>
  <div class="cont">
    <!-- Connexion -->
    <div class="form sign-in">
      <h2>Bienvenue</h2>
      <form action="login.php" method="POST">
        <label>
          <span>Identifiant</span>
          <input type="text" name="Username" />
        </label>
        <label>
          <span>Mot de Passe</span>
          <input type="Password" name="Password"/>
        </label>
        <button class="submit">Connexion</button>
      </form>
    </div>

    <!-- Translation Image -->
    <div class="sub-cont">
      <div class="img">
        <div class="img__text m--up">
          <h2>Nouveau ?</h2>
          <p>Inscrivez-vous et découvrez un grand nombre d'opportunités !</p>
        </div>
        <div class="img__text m--in">
          <h2>Déjà inscrit ?</h2>
          <p>Si vous avez déjà un compte, il vous suffit de vous connecter en cliquant ici !</p>
        </div>
        <div class="img__btn">
          <span class="m--up">S'inscrire</span>
          <span class="m--in">Se connecter</span>
        </div>
      </div>

      <!-- Sign-up -->
      <div class="form sign-up">
        <h2>Inscription</h2>
        <form action="register.php" method="POST">
          <label>
            <span>Identifiant</span>
            <input type="text" name="Username"/>
          </label>
          <label>
            <span>Mot de passe</span>
            <input type="password" name="Password"/>
          </label>
          <label>
            <span>Confirmer le mot de passe</span>
            <input type="password" name="confirm_Password"/>
          </label>
          <button class="submit">Inscription</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Javascript -->
  <script>
    document.querySelector('.img__btn').addEventListener('click', function() {
      document.querySelector('.cont').classList.toggle('s--signup');
    });
  </script>

</body>
</html>