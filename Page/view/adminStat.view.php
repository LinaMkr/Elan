<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>L'Elan - Statistiques</title>

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>
  <link rel="stylesheet" href="../view/style/styleAdmin.css">

</head>

<body>

  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>

  <div class="fil_ariane">
    <p>
      <ion-icon name="home-outline"></ion-icon> <a href="./accueil.ctrl.php">Accueil</a> > <a href="./admin.ctrl.php">Administration</a> > <a href="./adminStat.ctrl.php">Statistiques</a>
    </p>
  </div>

  <div id="adminStatistiques">

    <h3 class="display-5">Statistiques de ce mois</h3>

    <table>
      <tr>
        <th>Nombre de locations</th>
        <td><?= $stats[0]['nblocations'] ?></td>
      </tr>
      <tr>
        <th>Nombre de formations</th>
        <td><?= $stats[0]['nbformations'] ?></td>
      </tr>
      <tr>
        <th>Nombre d'évènements</th>
        <td><?= $stats[0]['nbevenements'] ?></td>
      </tr>
      <tr>
        <th>Nombre de coworking</th>
        <td><?= $stats[0]['nbcoworkings'] ?></td>
      </tr>
      <tr>
        <th>Nombre de clients</th>
        <td><?= $stats[0]['nbclients'] ?></td>
      </tr>
      <tr>
        <th>Bénéfice (en euros)</th>
        <td><?= $stats[0]['benefices'] ?></td>
      </tr>
    </table>

    <button id="b1" type="button" onclick="">Précédentes Statistiques</button>

    <div id="hidethat">
      <h3 class="display-5">Statiques du mois précedent</h3>
      <table>
        <tr>
          <th>Nombre de locations</th>
          <td><?= $stats[1]['nblocations'] ?></td>
        </tr>
        <tr>
          <th>Nombre de formations</th>
          <td><?= $stats[1]['nbformations'] ?></td>
        </tr>
        <tr>
          <th>Nombre d'évènements</th>
          <td><?= $stats[1]['nbevenements'] ?></td>
        </tr>
        <tr>
          <th>Nombre de coworking</th>
          <td><?= $stats[1]['nbcoworkings'] ?></td>
        </tr>
        <tr>
          <th>Nombre de clients</th>
          <td><?= $stats[1]['nbclients'] ?></td>
        </tr>
        <tr>
          <th>Bénéfice (en euros)</th>
          <td><?= $stats[1]['benefices'] ?></td>
        </tr>
      </table>

    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            $(document).ready(function(){
              $('#b1').click(function(){
                  $('#hidethat').fadeToggle('500');
              });
            });
        </script>

  </div>


  <?php include_once(__DIR__ . "/../view/footer.subview.html"); ?>

</body>

</html>
