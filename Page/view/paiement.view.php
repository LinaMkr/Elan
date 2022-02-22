<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>L'Elan - paiement</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script> <!-- Import de moment JS pour format de la date -->

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>
  <link rel="stylesheet" href="../view/style/stylePaiement.css">

</head>

<body>

  <script type='text/javascript' defer>
    var prixTotal = <?php echo json_encode($reservation->prixT); ?>;
    var idR = <?php echo json_encode($reservation->idR); ?>;
  </script>


  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>
  <div class="fil_ariane">
    <p>
      <ion-icon name="home-outline"></ion-icon> <a href="./accueil.ctrl.php">Accueil</a> > <a href="./lieu.ctrl.php">Les lieux</a> > <a href="./reservation.ctrl.php">Réservation</a> > <a href="./paiement.ctrl.php">Paiement</a>
    </p>
  </div>

  <main>

    <div class="paiement">
      <h3>Votre réservation n°<?= $reservation->idR ?> a bien été enregistré.</h3>

      <?php if ($reservation->type == "location") : ?>
        <div class="recap-commande">
          <h3>Récapitulatif de la réservation : </h3>
          <div>
            <strong>Date début : </strong> <?= $reservation->dateDeb ?> <br>
            <strong>Date fin : </strong> <?= $reservation->dateFin ?> <br>
            <strong>Salle : </strong> <?= $reservation->lieu->nomLocal ?> <br>
            <strong>Prix de la salle par jour : </strong> <?= $reservation->lieu->prixLieu ?>€ <br>
            <strong>Nombre de personne : </strong> <?= $reservation->nb_pers ?> <br>

            <strong>Services : </strong><br>
            <?php foreach ($services as $service) : ?>
              <?= $service->intitule ?> <br>
              <strong>Prix par personne : </strong> <?= $service->prix ?>€ <br>
              <strong>Prix total du service : </strong> <?= $service->prix * $reservation->nb_pers ?>€ <br>
            <?php endforeach; ?>
            <strong> Equipements : </strong> <br>
            <?php foreach ($equipements as $equipement) : ?>
              <strong><?= $equipement->nomEquip ?> </strong> <br>
              <strong>Prix de l'équiment : </strong> <?= $equipement->prixUnite ?>€ <br>
            <?php endforeach; ?>
            <strong>Prix TTC : </strong> <?= $reservation->prixT ?> <br>
          </div>

        </div>
      <?php endif; ?>

      <h3>Effectuer un paiement</h3>

      <section>
        <p>Paiement sur : </p>
        <div id="paypal-payment-container"></div>
      </section>

      <section>
        <p>Ou par chèque</p>
        <span>Pour effectuer le payement<br>par chèque contactez moi via<br> l'adresse mail suivante : <br><br></span>
        <a href="mailto:virginie.orsi@relationnelle.fr">virginie.orsi@relationnelle.fr</a>

      </section>

    </div>


  </main>

  <?php include_once(__DIR__ . "/../view/footer.subview.html"); ?>

  <!-- script JS PayPal, boutons credit et card en moins -->
  <script src="https://www.paypal.com/sdk/js?client-id=AYRv_u0lSMOGCyBeEPtRm6UC5RafJ6fbhQT_u6VYlREhkhgPAYXRftC_BAf6Pe6hQNfliaNkFz7A9i_K&disable-funding=credit,card&currency=EUR"></script>
  <!-- redirection script vers la page js -->
  <script src="../view/scripts/paypal.index.js"></script>

</body>

</html>
