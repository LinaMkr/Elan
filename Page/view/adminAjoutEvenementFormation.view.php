<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>L'Elan - Gestion des <?= $type ?>s</title>

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>
  <link rel="stylesheet" href="../view/style/styleAdmin.css">
</head>

<body>

  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>

  <div class="fil_ariane">
    <p>
      <ion-icon name="home-outline"></ion-icon> <a href="./accueil.ctrl.php">Accueil</a> > <a href="./admin.ctrl.php">Administration</a> > <a href="./adminAjoutEvenementFormation.ctrl.php?type=<?= $type ?>">Gestion des types <?= $type ?>s</a>
    </p>
  </div>

  <div id="adminAjoutEvenementFormation">

    <!-- Message de confirmation de suppression et d'erreur de supression -->
    <?php
    if(isset($erreur)){
      echo "<output target='erreur'>$erreur</output>";
    }else if(isset($message)) {
      echo "<output target='message'>$message</output>";
    } ?>

    <!--  Affichage des evenement existants -->
    <!-- pour le moment c'est un squelette -->
    <h2 class="display-3">Gestion des types <?= $type ?>s </h2>

    <?php if ($typeResa) : ?>
      <table id="tableau">
        <thead>
          <tr>
            <th>Type</th>
            <th>Nom <?= $type ?></th>
            <th>Prix</th>
            <th>Description</th>
          </tr>
        </thead>

        <tbody>
          <?php
          foreach ($typeResa as $t) : ?>
            <tr>
              <td data-label="Type"><?= $t->type ?></td>
              <td data-label="Nom"><?= $t->nom ?></td>
              <td data-label="Prix"><?= $t->prix ?></td>
              <td data-label="Description"><?= $t->description ?></td>
              <!-- appel du controleur pour supprimer une ligne en fonction du nom et du type -->
              <td><button onclick="self.location.href='./adminAjoutEvenementFormationEntree.ctrl.php?action=supprimer&type=<?= $type ?>&nom=<?= $t->nom ?>&prix=<?= $t->prix ?>&description=<?= $t->description ?>'">Supprimer</button></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else : ?>
      <p>Il n'y a actuellement aucun <?= $type ?></p>
    <?php endif; ?>

    <!-- formulaire de création d'un evenement -->
    <h3>Création type <?= $type ?></h3>

    <form action="adminAjoutEvenementFormationEntree.ctrl.php?type=<?=$type?>&action=ajouter" method="post" class="formEventForma">

      <label for="nom">Nom <?= $type ?><span class="etoiles_obligatoire">*</span></label>
      <input id="nom" type="text" name="nom" placeholder="nom" required>

      <label for="prix">Prix<span class="etoiles_obligatoire">*</span></label>
      <input id="prix" type="number" name="prix" placeholder="1" min="1" step="0.01" required>

      <label for="description">Description<span class="etoiles_obligatoire">*</span></label>
      <input id="description" type="text" name="description" placeholder="courte description" required>

      <input type="submit" value="Enregistrer">

    </form>


    <div>
      <p>Les champs avec '<span class="etoiles_obligatoire">*</span>' sont obligatoires</p>
    </div>

  </div>

  <?php include_once(__DIR__ . "/../view/footer.subview.html"); ?>

</body>

</html>
