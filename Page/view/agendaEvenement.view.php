<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script defer src="../view/scripts/scriptAgendaEvenement.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
  <link rel="stylesheet" href="../view/style/styleAgenda.css">

  <title>L'Elan - agenda Evenement</title>

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>

</head>

<body>

  <script type='text/javascript'>
    var tableauReservationsEvenements = <?php echo json_encode($reservations); ?>;
    var nomLieuAffiche = <?php echo json_encode($nomLieuAffiche); ?>;
  </script>

  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>

  <div class="fil_ariane">
    <p>
      <ion-icon name="home-outline"></ion-icon> <a href="./accueil.ctrl.php">Accueil</a> > <a href="./agendaEvenement.ctrl.php">Evenements</a>
    </p>
  </div>

  <form class="formAgenda" action="agendaEvenement.ctrl.php" method="post">
    <label for="lieu">Choisissez le planning d'une salle à afficher</label>
    <select id="lieu" name="lieu" required>
      <?php foreach ($lieux as $lieu) : ?>
        <?php if ($lieu->nomLocal == $nomLieuAffiche) : ?>
          <option value="<?= $lieu->nomLocal ?>" selected><?= $lieu->nomLocal ?></option>
        <?php else : ?>
          <option value="<?= $lieu->nomLocal ?>"><?= $lieu->nomLocal ?></option>
        <?php endif; ?>
      <?php endforeach; ?>
    </select>
    <input type="submit" name="trie" value="Valider">
  </form>

  <h1>Salle : <?= $nomLieuAffiche ?></h1>

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
