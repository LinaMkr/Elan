<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>L'Elan - Réservation</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="style/style.css">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script> <!-- Import de moment JS pour le script d'agenda -->
  <script defer src="../view/scripts/scriptAgendaReservation.js"></script>
  <script defer src="../view/scripts/scriptModalAgendaReservation.js"></script>

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>
  <link rel="stylesheet" href="../view/style/styleReservation.css">

</head>

<body>
  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>

  <div class="fil_ariane">
    <p>
      <ion-icon name="home-outline"></ion-icon> > <a href="./accueil.ctrl.php">Accueil</a> > <a href="./lieu.ctrl.php">Les lieux</a> > <a href="./reservation.ctrl.php">Réservation</a>
    </p>
  </div>

  <div class="div_reservation">

    <section>
      <div>
        <div id="arrow-left" class="arrow"></div>
        <div class="slide slide1"></div>
        <div class="slide slide2"></div>
        <div class="slide slide3"></div>
        <div id="arrow-right" class="arrow"></div>

        <script src="../view/scripts/scriptChoixReservation.js"></script>
      </div>


      <div>
        <ul>
          <li>Local : <?= $local->nomLocal ?></li>
          <li>Description : <?= $local->description ?></li>
          <li>Surface : <?= $local->surface ?> m²</li>
          <li>Nombre de places : <?= $local->nb_place ?></li>
          <li>Prix : <?= $local->prixLieu ?>€/jour </li>
        </ul>
      </div>

    </section>


    <!-- Modal Agenda -->

    <!-- Script passage des valeurs php à l'Agenda -->

    <script type='text/javascript'>
      var tableauReservations = <?php echo json_encode($reservations); ?>;
    </script>

    <div class="modal" id="modal1">
      <div class="modal-content">
        <span class="modal-close">&times;</span>
        <div class="agenda">
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
        </div>

      </div>
    </div>
    <div class="container">
      <div>

        <?php if (isset($erreur)) {
          echo "<output target='erreur'>$erreur</output>";
        } ?>

        <form action="reservationEntree.ctrl.php" method="post">

          <h1>Sélection de services</h1>
          <?php if ($admin) : ?>
            <div>
              <!-- liste déroulante dynamique des types de reservation -->
              <label for="typeResa">Choisissez un type :<span class="etoiles_obligatoire">*</span></label>
              <select id="typeResa" name="typeResa" required>
                <?php foreach ($typeResas as $tr) : ?>
                  <option value="<?= $tr->type ?>_<?= $tr->nom ?>"><?= $tr->type ?> - <?= $tr->nom ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          <?php endif; ?>

          <br>

          <p>Plage horaire:</p>

          <button data-modal-target="modal1"> <i class="far fa-calendar-alt"></i> Afficher les plages horaires disponibles</button>

          <div>
            <label for="horaire-select">Horaire réservation :<span class="etoiles_obligatoire">*</span></label>
            <select id="horaire-select" name="type_jour" onclick="griserDateFin()">
              <option value="matin" selected>Matin</option>
              <option value="aprem">Après-midi</option>
              <option value="journee">Une Journée</option>
              <option value="jours">Sur plusieurs jours</option>
            </select>
          </div>

          <!-- lien de la fonction griserDateFin() -->
          <script type="text/javascript" src="../view/scripts/scriptReservation.js"></script>

          <div>
            <div>
              <label for="dateDebut">Date de début :<span class="etoiles_obligatoire">*</span></label>
              <input id="dateDebut" type="date" name="dateDeb" value="<?= date('Y-m-d', strtotime(' + 2 days')) ?>" min="<?= date('Y-m-d', strtotime(' + 2 days')) ?>" max="2030-12-31" required>
            </div>

            <div>
              <label for="dateFin">Date de fin :<span class="etoiles_obligatoire">*</span></label>
              <input id="dateFin" type="date" name="dateFin" value="<?= date('Y-m-d', strtotime(' + 2 days')) ?>" min="<?= date('Y-m-d', strtotime(' + 2 days')) ?>" max="2030-12-31" required disabled>
            </div>

          </div>

          <div>
            <label for="nb_places">Nombre de participants :<span class="etoiles_obligatoire">*</span></label>
            <?php if($admin) : ?>
              <input id="nb_places" type="number" name="nb_places" min="0" max="<?= $local->nb_place ?>" placeholder="0" required>
            <?php else : ?>
              <input id="nb_places" type="number" name="nb_places" min="1" max="<?= $local->nb_place ?>" placeholder="1" required>
            <?php endif; ?>
          </div>

          <p>Saisissez un ou plusieurs services:</p>

          <div>

            <?php foreach ($services as $service) : ?>
              <input type="checkbox" id="<?= $service->intitule ?>" name="<?= $service->intitule ?>">
              <label for="<?= $service->intitule ?>"><?= $service->intitule ?> (<?= $service->prix ?> €)</label>
              <br>
            <?php endforeach; ?>

          </div>

          <input type="submit" value="Enregistrer">

          <input type="hidden" name="local" value=<?= $local->nomLocal ?>>
        </form>
      </div>

      <div>
        <h1>Localisation</h1>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2815.592289201467!2d5.722918014835443!3d45.11433226474247!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x478a8ba684e438b7%3A0x4f4cd60f50e10ae8!2s19%20Chem.%20du%20Piollier%2C%2038800%20Champagnier!5e0!3m2!1sfr!2sfr!4v1637328128336!5m2!1sfr!2sfr" width="450" height="500" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
      </div>
    </div>

  </div>

  <?php include_once(__DIR__ . "/../view/footer.subview.html"); ?>


</body>

</html>
