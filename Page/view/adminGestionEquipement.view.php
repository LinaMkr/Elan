<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="utf-8">

  <title>L'Elan - Gestion des équipements</title>

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>
  <link rel="stylesheet" href="../view/style/styleAdmin.css">

</head>

<body>

  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>

  <div class="fil_ariane">
    <p>
      <ion-icon name="home-outline"></ion-icon> <a href="./accueil.ctrl.php">Accueil</a> > <a href="./admin.ctrl.php">Administration</a> > <a href="./adminGestionEquipement.ctrl.php">Gestion des équipements</a>
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


    <h2>Gestion des équipements</h2>


    <!-- affichages des équipements déjà existants avec les attributs nécesssaires-->
    <?php if ($equipements) : ?>
      <table>
        <thead>
          <tr>
            <th>Nom équipement</th>
            <th>Nombre disponible</th>
            <th>Prix à l'unité</th>
          </tr>
        </thead>

        <tbody>
          <?php
          foreach ($equipements as $equipement) : ?>
              <tr>
                <td data-label="Nom équipement "><?= $equipement->nomEquip ?></td>
                <td data-label="Nombre disponible"><?= $equipement->nb_Disponible ?></td>
                <td data-label="Prix à l'unité"><?= $equipement->prixUnite ?>€</td>
                <!-- appel du controleur pour supprimer une ligne en fonction du nom et du type -->
                <td><button onclick="self.location.href='./adminGestionEquipementEntree.ctrl.php?action=supprimer&nom=<?= $equipement->nomEquip?>'">Supprimer</button></td>
              </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    <?php else : ?>
      <p>Il n'y a actuellement aucun équipement</p>
    <?php endif; ?>


    <!-- formulaires de création equipement et affectation equipemet à une salle-->
    <section>
      <form action="adminGestionEquipementEntree.ctrl.php?action=ajouter" method="post">

        <h3>Création d'un équipement</h3>

        <label for="nomService">Nom équipement<span class="etoiles_obligatoire">*</span></label>
        <input id="nomService" type="text" name="nomService" placeholder="video-projecteur" title="Veuillez renseigner le nom du nouvel équipement" required>

        <label for="nb_disponible">Nombre disponible<span class="etoiles_obligatoire">*</span></label>
        <input id="nb_disponible" type="number" name="nb_disponible" placeholder="1" min="1" title="Veuillez renseigner la quantité disponible du nouvel équipement" required>

        <label for="prixunite">Prix à l"unité<span class="etoiles_obligatoire">*</span></label>
        <input id="prixunite" type="number" name="prixunite" placeholder="0" min="0" step="0.01" title="Veuillez renseigner le prix à l'unité du nouvel équipement" required>

        <input type="submit" value="Ajouter" onclick="self.location.href='./adminGestionEquipement.ctrl.php">

      </form>
      <form action="adminGestionEquipementEntree.ctrl.php?action=affecter" method="post">

        <h3>Affectation d'un équipement à une salle</h3>

        <!-- liste déroulante dynamique des équipements -->
        <label for="equipement">Equipement<span class="etoiles_obligatoire">*</span></label>
        <select id="equipement" name="equipement" required>
          <?php foreach ($equipements as $equipement) : ?>
            <option value="<?= $equipement->nomEquip ?>"><?= $equipement->nomEquip ?></option>
          <?php endforeach; ?>
        </select>

        <!-- liste déroulante dynamique des salles -->
        <label for="salle">Salle<span class="etoiles_obligatoire">*</span></label>
        <select id="salle" name="salle" required>
          <?php foreach ($salles as $salle) : ?>
            <option value="<?= $salle->nomLocal ?>"><?= $salle->nomLocal ?></option>
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
