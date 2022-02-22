<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="utf-8">

  <title>L'Elan - Gestion des experts</title>

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>
  <link rel="stylesheet" href="../view/style/styleAdmin.css">

</head>

<body>

  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>

  <div class="fil_ariane">
    <p>
      <ion-icon name="home-outline"></ion-icon> <a href="./accueil.ctrl.php">Accueil</a> > <a href="./admin.ctrl.php">Administration</a> > <a href="./adminGestionExpert.ctrl.php">Gestion des experts</a>
    </p>
  </div>

  <div class="adminGestionEquipementServicePrestataireExpert">

    <?php
    if(isset($erreur)){
      echo "<output target='erreur'>$erreur</output>";
    }else if(isset($message)) {
      echo "<output target='message'>$message</output>";
    } ?>

    <h2 class="display-2">Gestion des experts </h2>

    <!-- affichages des services correspondant aux experts-->
    <?php if ($experts) : ?>
      <table>
        <thead>
          <tr>
            <th>Nom de l'expert</th>
            <th>Prenom de l'expert</th>
            <th>Nombre de jours disponible</th>
            <th>Prix de l'expert</th>
          </tr>
        </thead>

        <tbody>
          <?php
          foreach ($experts as $e) : ?>
              <tr>
                <td data-label="Nom de l'expert "><?= $e[0] ?></td>
                <td data-label="Prenom de l'expert "><?= $e[1]?></td>
                <td data-label="Nombre de jours disponible "><?= $e[2] ?></td>
                <td data-label="Prix de l'expert "><?= $e[3]?></td>
                <!-- appel du controleur pour supprimer une ligne en fonction du nom et du type -->
                <td><button onclick="self.location.href='./adminGestionExpertEntree.ctrl.php?action=supprimer&nomexpert=<?= $e[0]?>&prenomexpert=<?=$e[1]?>'">Supprimer</button></td>
              </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    <?php else : ?>
      <p>Il n'y a actuellement aucun expert affecté à un type de réservation</p>
    <?php endif; ?>


    <!-- formulaires de création expert et affectation expert à un type de reservation-->
      <section>
      <form action="adminGestionExpertEntree.ctrl.php?action=ajouter" method="post">

        <h3>Création d'un expert</h3>

        <label for="nom">Nom<span class="etoiles_obligatoire">*</span></label>
        <input id="nom" type="text" name="nom" placeholder="Dupont" title="Veuillez renseigner le nom du nouvel expert" required>

        <label for="prenom">Prenom<span class="etoiles_obligatoire">*</span></label>
        <input id="prenom" type="text" name="prenom" placeholder="Jeanne" title="Veuillez renseigner le prenom du nouvel expert" required>

        <label for="nbjoursdispo">Nombre de jours disponible<span class="etoiles_obligatoire">*</span></label>
        <input id="nbjoursdispo" type="number" name="nbjoursdispo" placeholder="0" min="0" title="Veuillez renseigner le nombre de jours disponible du nouvel expert" required>

        <label for="prix">Prix<span class="etoiles_obligatoire">*</span></label>
        <input id="prix" type="number" name="prix" placeholder="0" min="0" step="0.01" title="Veuillez renseigner le prix de l'expert" required>

        <input type="submit" value="Ajouter" onclick="self.location.href='./adminGestionEquipement.ctrl.php">

      </form>
      <form action="adminGestionExpertEntree.ctrl.php?action=affecter" method="post">

        <h3>Affectation d'un expert à un type de réservation</h3>

        <!-- liste déroulante dynamique des experts -->
        <label for="expert">Expert<span class="etoiles_obligatoire">*</span></label>
        <select id="expert" name="expert" required>
          <?php foreach ($experts as $e) : ?>
            <option value="<?= $e[0] ?>_<?= $e[1] ?>"><?= $e[0]?> <?= $e[1]?></option>
            <!--faudrait pouvoir mettre dans value le nom ET le prenom  ou bien faire deux select-->
          <?php endforeach; ?>
        </select>

        <!-- liste déroulante dynamique des types de reservation -->
        <label for="typeResa">Type de réservation<span class="etoiles_obligatoire">*</span></label>
        <select id="typeResa" name="resa" required>
          <?php foreach ($typeResas as $tr) : ?>
            <option value="<?= $tr->type ?>_<?= $tr->nom ?>"><?= $tr->type ?> - <?= $tr->nom?></option>
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
