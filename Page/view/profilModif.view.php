<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>L'Elan - modification des informations</title>

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>
  <link rel="stylesheet" href="../view/style/styleProfil.css">

</head>

<body>

  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>

  <div class="fil_ariane">
    <p>
      <ion-icon name="home-outline"></ion-icon> <a href="./accueil.ctrl.php">Accueil </a> > <a href="./profil.ctrl.php">Profil</a> > <a href="./profilModif.ctrl.php">Modification de profil</a>
    </p>
  </div>

  <div class="profil">
    <div>
      <h3>Modifiez vos informations</h3>
      <section>

        <form action="profilModif.ctrl.php" method="post">

          <label for="nom">Nom<span class="etoiles_obligatoire">*</span></label>
          <input id="nom" type="text" name="nom" value="<?= $utilisateur->nom ?>" required>

          <label for="prenom">Prénom<span class="etoiles_obligatoire">*</span></label>
          <input id="prenom" type="text" name="prenom" value="<?= $utilisateur->prenom ?>" required>

          <label for="telephone">Telephone<span class="etoiles_obligatoire">*</span></label>
          <input id="telephone" type="tel" name="telephone" value="<?= $utilisateur->tel ?>" pattern="[0-9]{10}" title="Les 10 chiffres doivent être collés" required>

          <?php if (!$admin) : ?>

            <label for="entreprise">Entreprise<span class="etoiles_obligatoire">*</span></label>
            <input id="entreprise" type="text" name="entreprise" value="<?= $utilisateur->entreprise ?>" required>

            <label for="adresse">Adresse<span class="etoiles_obligatoire">*</span></label>
            <input id="adresse" type="text" name="adresse" value="<?= $utilisateur->adresse ?>" required>

          <?php endif; ?>

          <input type="submit" value="Enregistrer">

          <input type="button" value="Annuler" onclick="history.back()">

        </form>

        <p>les champs avec '<span class="etoiles_obligatoire">*</span>' sont obligatoires</p>

      </section>
    </div>
  </div>

  <?php include_once(__DIR__ . "/../view/footer.subview.html"); ?>

</body>

</html>