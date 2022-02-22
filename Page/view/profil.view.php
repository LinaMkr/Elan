<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>L'Elan - profil</title>

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>
  <link rel="stylesheet" href="../view/style/styleProfil.css">

</head>

<body>
  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>

  <div class="fil_ariane">
    <p>
      <ion-icon name="home-outline"></ion-icon> <a href="./accueil.ctrl.php">Accueil </a> > <a href="./profil.ctrl.php">Profil</a>
    </p>
  </div>

  <div class="profil">

    <div>
      <h3>Vos informations</h3>
      <section>

        <form action="profil.ctrl.php" method="post">
          <?php
          if (isset($erreur)) {
            echo "<output target='erreur'>$erreur</output>";
          } else if (isset($message)) {
            echo "<output target='message'>$message</output>";
          }
          ?>
          <label for="nom">Nom</label>
          <output name="nom"><?= $utilisateur->nom ?></output>

          <label for="prenom">Prénom</label>
          <output name="prenom"><?= $utilisateur->prenom ?></output>

          <label for="telephone">Numéro de Téléphone</label>
          <output name="telephone"><?= $utilisateur->tel ?></output>

          <?php if (!$admin) : ?>

            <label for="entreprise">Entreprise</label>
            <output name="entreprise"><?= $utilisateur->entreprise ?></output>

            <label for="adresse">Adresse</label>
            <output name="adresse"><?= $utilisateur->adresse ?></output>

          <?php endif; ?>

          <label for="email">Adresse e-mail</label>
          <output name="email"><?= $utilisateur->email ?></output>

        </form>

        <div>
          <a href="./profilModif.ctrl.php">
            <p>Pour modifiez vos informations, cliquez sur le logo suivant : </p>
          </a>
          <a href="./profilModif.ctrl.php">
            <ion-icon name="pencil-outline" title="modifier les informations"></ion-icon>
          </a>
        </div>
        <div>
          <p>Vous pouvez modifer votre mot de passe ici : </p>
          <a href="./profilModifierMDP.ctrl.php">Modification mot de passe</a>
        </div>

      </section>
    </div>
  </div>


  <div class="profilResa">
    <!--Affichage des reservations-->
    <!--faire un condition dans le cas où il n'y a aucune réservation -->
    <?php if ($nbResa == 0) : ?>
      <p>Vous n'avez aucune reservation</p>
      <a href='./reservation.ctrl.php'>Réserver ?</a>

    <?php else : ?>
      <h2>Mes Réservations</h2>
      <table>
        <thead>
          <tr>
            <th>Numéro de réservation</th>
            <!--idR-->
            <th>Type de la réservation</th>
            <!--nom-->
            <th>Nom du local</th>
            <!--nomLocal-->
            <th>Date et heure de début</th>
            <!--dateDeb et heureDeb-->
            <th>Date et heure de fin</th>
            <!--dateFin et heureFin-->
            <th>Nombre de personne(s)</th>
            <!--nb_pers-->
            <th>Prix</th>
            <!--prixT-->
          </tr>
        </thead>

        <tbody>
          <?php foreach ($reservations as $resa) : ?>
            <tr>
              <td data-label="Numero de reservation"> <?= $resa->idR ?></td>
              <td data-label="Type de la réservation"> <?= $resa->type ?></td>
              <td data-label="Nom du local"> <?= $resa->lieu->nomLocal ?></td>
              <td data-label="Date et heure de début"> <?= $resa->dateDeb ?></td>
              <td data-label="Date et heure de fin"> <?= $resa->dateFin ?></td>
              <td data-label="Nombre de personne(s)"> <?= $resa->nb_pers ?></td>
              <td data-label="Prix"> <?= $resa->prixT ?>€</td>
            </tr>
          <?php endforeach; ?>
        </tbody>


      </table>
    <?php endif; ?>

  </div>


  <?php include_once(__DIR__ . "/../view/footer.subview.html"); ?>

</body>

</html>
