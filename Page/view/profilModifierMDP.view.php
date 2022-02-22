<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>L'Elan - choisir un lieu</title>
  <link rel="stylesheet" href="/style/style.css">

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>
  <link rel="stylesheet" href="../view/style/styleProfil.css">

</head>


<body>

  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>

  <div class="fil_ariane">
    <p>
      <ion-icon name="home-outline"></ion-icon> <a href="./accueil.ctrl.php">Accueil</a> > <a href="./profil.ctrl.php">Profil</a> > <a href="./profilModiferMDP.ctrl.php">Modifier mot de passe</a>
    </p>
  </div>

  <div class="profil">
    <div>
      <h3>Modifiez votre mot de passe</h3>
      <section>

        <form action="profilModifierMDPEntree.ctrl.php" method="post">
          <?php
          if (isset($erreur)) {
            echo "<output target='erreur'>$erreur</output>";
          }
          ?>

          <label for="mdp">Mot De Passe<span class="etoiles_obligatoire">*</span></label>
          <input id="mdp" type="password" name="mdp" placeholder="********" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,24}" title="Veuillez saisir un mot de passe doit contenant au moins 8 caractères, une miniscule, une majuscule et un chiffre" required>

          <label for="mdpConfirm">Confirmez le mot de passe<span class="etoiles_obligatoire">*</span></label>
          <input id="mdpConfirm" type="password" name="mdpConfirm" placeholder="********" title="Veuillez réécrire votre mot de passe" required>

          <input type="submit" value="Modifier le mot de passe">

          <input type="button" value="Annuler" onclick="history.back()">

        </form>
        <p>les champs avec '<span class="etoiles_obligatoire">*</span>' sont obligatoires</p>

      </section>

    </div>
  </div>

  <?php include_once(__DIR__ . "/../view/footer.subview.html"); ?>

</body>

</html>