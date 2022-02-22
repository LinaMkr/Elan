<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="utf-8">

  <title>L'Elan - Gestion des prestataires</title>

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>
  <link rel="stylesheet" href="../view/style/styleAdmin.css">

</head>

<body>

  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>

  <div class="fil_ariane">
    <p>
      <ion-icon name="home-outline"></ion-icon> <a href="./accueil.ctrl.php">Accueil</a> > <a href="./admin.ctrl.php">Administration</a> > <a href="./adminGestionPrestataire.ctrl.php">Gestion des prestataires</a>
    </p>
  </div>

  <div class="adminGestionEquipementServicePrestataireExpert">

    <!-- Message de confirmation de suppression et d'erreur de supression -->
    <?php
    if(isset($erreur)){
      echo "<output target='erreur'>$erreur</output>";
    }else if(isset($message)) {
      echo "<output target='message'>$message</output>";
    } ?>

    <h2>Gestion des prestataires</h2>


    <!-- affichages des services correspondant aux prestataires-->
    <?php if ($prestataires) : ?>
      <table>
        <thead>
          <tr>
            <th>Prestataire</th>
            <th>Prix</th>
          </tr>
        </thead>

        <tbody>
          <?php
          foreach ($prestataires as $p) : ?>
              <tr>
                <td><?= $p[0] ?></td>
                <td><?= $p[1] ?></td>
                <!-- appel du controleur pour supprimer une ligne en fonction du nom et du type -->
                <td><button onclick="self.location.href='./adminGestionPrestataireEntree.ctrl.php?action=supprimer&nom=<?= $p[0] ?>'">Supprimer</button></td>
              </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    <?php else : ?>
      <p>Il n'y a actuellement aucun prestataire affecté à un service</p>
    <?php endif; ?>


    <!-- formulaires de création prestataire et affectation prestataire à un service-->
    <section>
      <form action="adminGestionPrestataireEntree.ctrl.php?action=ajouter" method="post">

        <h3>Création d'un prestataire</h3>

        <label for="nom">Nom<span class="etoiles_obligatoire">*</span></label>
        <input id="nom" type="text" name="nom" placeholder="Bus FlixBus" title="Veuillez renseigner le nom du nouveau prestataire" required>

        <label for="prix">Prix<span class="etoiles_obligatoire">*</span></label>
        <input id="prix" type="number" name="prix" placeholder="0" min="0" step="0.01" title="Veuillez renseigner le prix du prestataire" required>

        <input type="submit" value="Ajouter" onclick="self.location.href='./adminGestionEquipement.ctrl.php">

      </form>
        <form action="adminGestionPrestataireEntree.ctrl.php?action=affecter" method="post">

          <h3>Affectation d'un prestataire à un service</h3>

          <!-- liste déroulante dynamique des prestataires -->
          <label for="prestataire">Prestataire<span class="etoiles_obligatoire">*</span></label>
          <select id="prestataire" name="prestataire" required>
            <?php foreach ($prestataires as $p) : ?>
              <option value="<?= $p[0] ?>"><?= $p[0]?></option>
            <?php endforeach; ?>
          </select>

          <!-- liste déroulante dynamique des services -->
          <label for="service">Service<span class="etoiles_obligatoire">*</span></label>
          <select id="service" name="service" required>
            <?php foreach ($services as $service) : ?>
              <option value="<?= $service->intitule ?>"><?= $service->intitule ?></option>
            <?php endforeach; ?>
          </select>

          <input type="submit" value="Affecter">
        </form>
      </section>

    <div>
      <p>Les champs avec '<span class="etoiles_obligatoire">*</span>' sont obligatoires</p>
    </div>
  </div>

  <?php include_once(__DIR__ . "/../view/footer.subview.html"); ?>

</body>

</html>
