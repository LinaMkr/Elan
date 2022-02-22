<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>L'Elan - Gestion des services</title>

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>
  <link rel="stylesheet" href="../view/style/styleAdmin.css">

</head>

<body>

  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>

  <div class="fil_ariane">
    <p>
      <ion-icon name="home-outline"></ion-icon> <a href="./accueil.ctrl.php">Accueil</a> > <a href="./admin.ctrl.php">Administration</a> > <a href="./adminGestionService.ctrl.php">Gestion des services</a>
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

    <h2 class="display-4">Gestion des services</h2>

    <!--  Affichage des services existants avec attributs -->
    <?php if ($services) : ?>
      <table>
        <thead>
          <tr>
            <th>Nom service</th>
            <th>Prix</th>
          </tr>
        </thead>

        <tbody>
          <?php
          foreach ($services as $s) : ?>
              <tr>
                <td data-label="Nom Service"><?= $s->intitule ?></td>
                <td data-label="Prix"><?= $s->prix ?>€</td>
                <!-- appel du controleur pour supprimer une ligne en fonction du nom et du type -->
                <td><button onclick="self.location.href='./adminGestionServiceEntree.ctrl.php?action=supprimer&intitule=<?=$s->intitule?>'">Supprimer</button></td>
              </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else : ?>
      <p>Il n'y a actuellement aucun service</p>
    <?php endif; ?>

    <!--======================================
    II. Création d'un service
    input text = nomService
          entier = prix (voir input adapté)
    =======================================-->
    <h3>Création d'un service</h3>

    <form action="adminGestionServiceEntree.ctrl.php?action=ajouter" method="post">
      <!--pas encore fonctionnel-->

      <!-- entrée du nom du nouveau service -->
        <label for="nomService">Service<span class="etoiles_obligatoire">*</span></label>
        <input id="nomService" type="text" name="nomService" placeholder="Train" title="Veuillez renseigner le nom du nouveau service" required>

      <!-- entrée du prix du nouveau service -->
        <label for="prix">Prix<span class="etoiles_obligatoire">*</span></label>
        <input id="prix" type="number" name="prix" placeholder="0" min="0" step="0.01" title="Veuillez renseigner le prix du nouveau service" required>

      <input type="submit" value="Ajouter">

    </form>

    <div>
      <p>Les champs avec '<span class="etoiles_obligatoire">*</span>' sont obligatoires</p>
    </div>

  </div>

  <?php include_once(__DIR__ . "/../view/footer.subview.html"); ?>

</body>

</html>
