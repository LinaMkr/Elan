<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>L'Elan - Réservations</title>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script> <!-- Import de moment JS pour le script d'agenda -->
  <script defer src="../view/scripts/scriptAgendaAdmin.js"></script>

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>
  <link rel="stylesheet" href="../view/style/styleAdmin.css">
  <link rel="stylesheet" href="../view/style/styleAgenda.css">

</head>

<body>

  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>

  <div class="fil_ariane">
    <p>
      <ion-icon name="home-outline"></ion-icon> <a href="./accueil.ctrl.php">Accueil</a> > <a href="./admin.ctrl.php">Administration</a> > <a href="./adminAfficherReservation.ctrl.php">Réservations</a>
    </p>
  </div>

  <form class="formAgenda" action="agendaEvenement.ctrl.php" method="post">
    <label for="lieu">Choisissez le planning d'une salle à afficher</label>
    <select id="lieu" name="lieu" required>
      <?php foreach ($lieux as $lieu) : ?>
        <option value="<?= $lieu->nomLocal ?>"><?= $lieu->nomLocal ?></option>
      <?php endforeach; ?>
    </select>
    <input type="submit" name="trie" value="Valider">
  </form>

  <h1>Salle : <?= $nomLieuAffiche ?></h1>

  <!-- Agenda -->

  <script type='text/javascript'>
    var tableauReservations = <?php echo json_encode($reservations); ?>;
    var nomLieuAffiche = <?php echo json_encode($nomLieuAffiche); ?>;
  </script>

  <main>
    <table>
      <thead>
        <tr>
          <th colspan="8">
            <div>
              <i class="prev-week fas fa-angle-left"></i>

              <div class="title"> Titre </div>

              <i class="next-week fas fa-angle-right"></i>
            </div>
          </th>
        </tr>
        <tr class="weekdays">
          <th>
            <i class="current-week fas fa-home"></i>
          </th>
        </tr>
      </thead>
      <tbody>

      </tbody>
    </table>
  </main>

  <?php include_once(__DIR__ . "/../view/footer.subview.html"); ?>

</body>

</html>