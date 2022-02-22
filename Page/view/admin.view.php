<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>L'Elan - Administration</title>

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>
  <link rel="stylesheet" href="../view/style/styleAdmin.css">

</head>

<body>

  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>

  <div class="fil_ariane">
    <p>
      <ion-icon name="home-outline"></ion-icon> <a href="./accueil.ctrl.php">Accueil</a> > <a href="./admin.ctrl.php">Administration</a>
    </p>
  </div>

  <div id="admin">
    <div>
      <ul>
        <li><a href="./adminAjoutArticle.ctrl.php">Ajout d'articles</a></li>

        <li><a href="./adminGestionEquipement.ctrl.php">Gestion des équipements</a></li>

        <li><a href="./adminGestionLieux.ctrl.php">Gestion des lieux</a></li>

        <li><a href="./adminGestionService.ctrl.php">Gestion des services</a></li>

        <li><a href="./adminAjoutEvenementFormation.ctrl.php?type=formation">Gestion des types de formations</a></li>

        <li><a href="./adminStat.ctrl.php">Statistiques</a></li>
      </ul>

      <ul>
        <li><a href="./adminAfficherReservation.ctrl.php">Consulter les réservations</a></li>

        <li><a href="./adminGestionExpert.ctrl.php">Gestion des experts</a></li>

        <li><a href="./adminGestionPrestataire.ctrl.php">Gestion des prestataires</a></li>

        <li><a href="./adminAjoutEvenementFormation.ctrl.php?type=evenement">Gestion des types d'événements</a></li>

        <li><a href="https://strawpoll.com/z35h6oz8v/r" target="_blank">Retour enquête de satisfaction</a></li>
      </ul>
    </div>
  </div>


  <?php include_once(__DIR__ . "/../view/footer.subview.html"); ?>

</body>

</html>
