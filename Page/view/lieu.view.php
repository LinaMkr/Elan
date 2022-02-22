<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>L'Elan - choisir un lieu</title>

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>

</head>


<body>

  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>

  <!-- container de grille  -->
  <div class="fil_ariane">
    <p>
      <ion-icon name="home-outline"></ion-icon> <a href="./accueil.ctrl.php">Accueil</a> > <a href="./lieu.ctrl.php">Les lieux</a>
    </p>
  </div>

  <div class="container py-5">
    <div class="row">

      <?php foreach ($lieux as $lieu) : ?>
        <div class="col-md-4 col-sm-6 ">
          <div class="card mb-4 shadow-sm">
            <img src="../view/images/<?= $lieu->nomLocal ?>1.jpg" alt="Salle 1" class="w-100">
            <div class="card-body">
              <h3 class="card-title text-capitalize display-6"><?= $lieu->nomLocal ?></h3>
              <p class="card-text"><?= $lieu->description ?></p>
              <ul>
                <li>Surface : <?= $lieu->surface ?> m²</li>
                <li>Nombre de places : <?= $lieu->nb_place ?></li>
                <li>Prix : <?= $lieu->prixLieu ?>€/jour </li>
              </ul>
              <button type="button" class="btn btn-sm btn-outline-secondary" onclick="self.location.href='./reservation.ctrl.php?nomLocal=<?= $lieu->nomLocal ?>'">Réserver</button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

    </div>
  </div>


  <?php include_once(__DIR__ . "/../view/footer.subview.html"); ?>

</body>

</html>
