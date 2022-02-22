<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="utf-8">

  <title>L'Elan - s'inscrire</title>

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>
  <link rel="stylesheet" href="../view/style/styleConnexionInscription.css">

</head>


<body>

  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>

  <div class="fil_ariane">
    <p>
      <ion-icon name="home-outline"></ion-icon> <a href="./accueil.ctrl.php">Accueil</a> > <a href="./inscription.ctrl.php">Inscription</a>
    </p>
  </div>

  <div class="inscriptionEtConnexion">
    <div>
      <h3>Prêt à vous inscrire?</h3>
      <section>

        <p>Déjà un compte ?</p>
        <a href="./connexion.ctrl.php">Connexion</a>

      </section>

      <section>
        <p>Entrez les informations suivantes :</p>
        <form action="inscription.ctrl.php" method="post">
          <?php
          if (isset($erreur)) {
            echo "<output>$erreur</output>";
          }
          ?>

          <label for="nom">Nom<span class="etoiles_obligatoire">*</span></label>
          <input id="nom" type="text" name="nom" placeholder="Duchamps" title="Veuillez saisir votre nom" required>

          <label for="prenom">Prénom<span class="etoiles_obligatoire">*</span></label>
          <input id="prenom" type="text" name="prenom" placeholder="Jean" title="Veuillez saisir votre prenom" required>

          <label for="telephone">Télephone<span class="etoiles_obligatoire">*</span></label>
          <input id="telephone" type="tel" name="telephone" placeholder="0610788486" pattern="[0-9]{10}" title="Veuillez saisir votre numéro de téléphone contenant 10 chiffres le tout collé" required>

          <label for="entreprise">Entreprise<span class="etoiles_obligatoire">*</span></label>
          <input id="entreprise" type="text" name="entreprise" placeholder="Nom de l'entreprise" title="Veuillez saisir le nom de votre entreprise" required>

          <label for="adresse">Adresse<span class="etoiles_obligatoire">*</span></label>
          <input id="adresse" type="text" name="adresse" placeholder="12 Rue Victor Basch 69100 Villeurbanne" title="Veuillez saisir votre adresse postale" required>

          <label for="email">Adresse e-mail<span class="etoiles_obligatoire">*</span></label>
          <input id="email" type="email" name="email" placeholder="email@gmail.com" title="Veuillez saisir un email contenant un '@' et se terminant par ' .fr',' .com',' .ca'..." required>

          <label for="mdp">Mot de passe<span class="etoiles_obligatoire">*</span></label>
          <input id="mdp" type="password" name="mdp" placeholder="********" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,24}" title="Veuillez saisir un mot de passe doit contenant au moins 8 caractères, une miniscule, une majuscule et un chiffre" required>

          <label for="mdpConfirm">Confirmez le mot de passe<span class="etoiles_obligatoire">*</span></label>
          <input id="mdpConfirm" type="password" name="mdpConfirm" placeholder="********" title="Veuillez réécrire votre mot de passe" required>

          <input type="submit" value="S'inscrire">
        </form>

        <p>Les champs avec '<span class="etoiles_obligatoire">*</span>' sont obligatoires</p>

      </section>
    </div>
  </div>

  <?php include_once(__DIR__ . "/../view/footer.subview.html"); ?>

</body>

</html>
