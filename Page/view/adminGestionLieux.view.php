<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>L'Elan - Gestion des lieux</title>
    <?php include_once(__DIR__."/../view/head.subview.html"); ?>
    <link rel="stylesheet" href="../view/style/styleAdmin.css">
  </head>
  <body>

    <?php include_once(__DIR__."/../view/header.subview.php"); ?>

    <div class="fil_ariane">
      <p>
        <ion-icon name="home-outline"></ion-icon> <a href="./accueil.ctrl.php">Accueil</a> > <a href="./admin.ctrl.php">Administration</a> > <a href="./adminGestionLieux.ctrl.php">Gestion des lieux</a>
      </p>
    </div>

    <div id="adminGestionLieux">

      <!-- Message de confirmation de suppression et d'erreur de supression -->
      <?php
      if(isset($erreur)){
        echo "<output target='erreur'>$erreur</output>";
      }else if(isset($message)) {
        echo "<output target='message'>$message</output>";
      } ?>

      <h2 class="display-4">Lieux existants</h2>

        <table>
            <thead>
              <tr>
                <th>Nom Local</th>
                <th>Surface</th>
                <th>Nombre de place</th>
                <th>Description</th>
                <th>Prix</th>
              </tr>
            </thead>

          <tbody>
            <?php
                foreach ($lieux as $l): ?>
            <tr>
              <td data-label="Nom Local :"><?= $l->nomLocal ?></td>
              <td data-label="Surface :"><?= $l->surface ?></td>
              <td data-label="Nombre de place :"><?= $l->nb_place ?></td>
              <td data-label="Description :"><?= $l->description ?></td>
              <td data-label="Prix :"><?= $l->prixLieu ?>€</td>
              <!-- appel du controleur pour supprimer une ligne en fonction du nom et du type -->
              <td><button
                onclick="self.location.href='./adminGestionLieuxEntree.ctrl.php?action=supprimer&nomLocal=<?= $l->nomLocal ?>'">Supprimer</button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <h3 class="display-5">Ajout d'un lieu</h3>

        <form action="adminGestionLieuxEntree.ctrl.php?action=ajouter" method="post">

        <label for="nomLocal">Nom du Lieu :<span class="etoiles_obligatoire">*</span></label>
        <input id="nomLocal" type="text" name="nomLocal" placeholder="Bureau1"
          title="Veuillez saisir un nom de lieu" required>

        <label for="surface">Surface :<span class="etoiles_obligatoire">*</span></label>
        <input id="surface" type="text" name="surface" placeholder="175"
          title="Veuillez saisir une surface" required>

        <label for="prix">Prix :<span class="etoiles_obligatoire">*</span></label>
        <input id="prix" type="text" name="prix" placeholder="175" step="0.01"
          title="Veuillez saisir un prix" required>

        <label for="nb_place">Nombre de place :<span class="etoiles_obligatoire">*</span></label>
        <input id="nb_place" type="number" name="nb_place" min="1" max="100" placeholder="0" required>


        <label for="description">Description :<span class="etoiles_obligatoire">*</span></label>
        <input id="description" type="text" name="description" placeholder="courte description">

        <input type="submit" value="Ajouter Lieu">

      </form>

      <div>
        <p>Les champs avec '<span class="etoiles_obligatoire">*</span>' sont obligatoires</p>
      </div>
    </div>

  </body>

  <?php include_once(__DIR__."/../view/footer.subview.html"); ?>

</html>
