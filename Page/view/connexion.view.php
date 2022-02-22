<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>L'Elan - se connecter</title>

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>
  <link rel="stylesheet" href="../view/style/styleConnexionInscription.css">

</head>

<body>

  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>

  <div class="fil_ariane">
    <p>
      <ion-icon name="home-outline"></ion-icon> <a href="./accueil.ctrl.php">Accueil</a> > <a href="./inscription.ctrl.php">Inscription</a> > <a href="./connexion.ctrl.php">Connexion</a>
    </p>
  </div>

  <div class="inscriptionEtConnexion">
    <!--On récupère le même style que inscription-->
    <div>
      <h3>Connectez-vous</h3>
      <section>

        <p>Vous ne possédez pas encore de compte ?</p>
        <a href="./inscription.ctrl.php">Inscription</a>

      </section>

      <section>
        <p>Connexion via : </p>
        <ul>
          <li>
            <!-- Connexion Google-->
            <a href="https://accounts.google.com/o/oauth2/v2/auth?scope=openid&access_type=online&response_type=code&redirect_uri=<?= urlencode('https://elan.ddns.net/controler/connexion.ctrl.php')?>&client_id=<?= GOOGLE_ID ?>">
              <ion-icon name="logo-google"></ion-icon>Google
            </a>
          </li>
        </ul>
      </section>

      <section>
        <p>Connexion Via</p>
        <form action="connexion.ctrl.php" method="post">
          <?php
          if (isset($erreur)) {
            echo "<output>$erreur</output>";
          }
          ?>
          <label for="email">Votre adresse e-mail<span class="etoiles_obligatoire">*</span></label>
          <input id="email" type="email" name="email" placeholder="email@email.fr" title="L'email doit contenir un '@' et doit se terminer par ' .fr',' .com',' .ca'..." required>

          <label for="mdp">Mot De Passe<span class="etoiles_obligatoire">*</span></label>
          <input id="mdp" type="password" name="mdp" placeholder="********" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,24}" title="Le mot de passe doit contenir au moins 8 caractères, une miniscule, une majuscule, un chiffre" required>

          <input type="submit" value="Connexion">
        </form>

        <p>Les champs avec '<span class="etoiles_obligatoire">*</span>' sont obligatoires</p>

      </section>
    </div>
  </div>

  <?php include_once(__DIR__ . "/../view/footer.subview.html"); ?>

</body>

</html>
