<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>L'Elan - actualité</title>

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>

</head>

<body>

  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>

  <div class="fil_ariane">
    <p>
      <ion-icon name="home-outline"></ion-icon> <a href="./accueil.ctrl.php">Accueil </a> > <a href="./actualite.ctrl.php">Actualités </a>
    </p>
  </div>

<div class="actuCard m-5 min-vh-100">

  <?php foreach ($articles as $a): ?>

    <div class="card bg-light border-info m-4" style="width=18rem;">
      <div class="card-header">
        <h4 class="display-6">Ecrit par : <?= $a->auteur->nom ?> <?= $a->auteur->prenom ?></h4>
      </div>
      <!-- <img class="card-img-top" src="../view/images/accompagnement-commercial.jpg" alt="actu1"> -->
      <div class="card-body m-4">
        <h3 class="card-title display-5"><?= $a->titre ?></h3>
        <p class="card-text"><?= $a->description ?></p>
      </div>

    </div>

  <?php endforeach; ?>

</div>


  <?php include_once(__DIR__ . "/../view/footer.subview.html"); ?>


</body>

</html>
