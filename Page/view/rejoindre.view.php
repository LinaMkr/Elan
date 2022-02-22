<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>L'Elan - Réservation Choix</title>

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>
  <link rel="stylesheet" href="../view/style/styleRejoindre.css">


</head>

<body>

  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>

  <div class="reservation_choix">

    <h2 class="display-4">Rejoindre une réservation</h2>

    <?php if ($reservations) : ?>
      <table>
        <thead>
          <tr>
            <th>Type</th>
            <th>Nom du local</th>
            <th>Debut</th>
            <th>Fin</th>
            <th>Places restantes</th>
            <th>Prix</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($reservations as $r) : ?>
          <tr class="ligneC" onclick=" return ligneCliquable(<?= $r->idR ?>)">
            <td data-label="Type "><?= $r->type ?></td>
            <td data-label="Nom du local "><?= $r->lieu->nomLocal ?></td>
            <td data-label="Debut "><?= $r->dateDeb ?></td>
            <td data-label="Fin "><?= $r->dateFin ?></td>
            <td data-label="Places restantes "><?= $r->placeRest ?></td>
            <td data-label="Prix "><?= $r->prixT ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php else : ?>
      <p>Il n'y a actuellement aucune réservation pouvant être rejoint.</p>
    <?php endif; ?>

  </div>

  <!-- script qui permet de demander la confirmation au client et
    qui permet de faire le role d'une balise a (car impossible d'en mettre une pour 1 ligne précise) -->
  <script type="text/javascript" src="../view/scripts/scriptConfirmation.js"></script>

  <?php include_once(__DIR__ . "/../view/footer.subview.html"); ?>

</body>

</html>
