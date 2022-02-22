<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>L'Elan - contact</title>

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>
  <link rel="stylesheet" href="../view/style/styleContact.css">

</head>


<body>
  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>

  <div class="fil_ariane">
    <p>
      <ion-icon name="home-outline"></ion-icon> <a href="./accueil.ctrl.php">Accueil</a> > <a href="./contact.ctrl.php">Contact</a>
    </p>
  </div>

  <!-- container de grille  -->


  <div class="container mt-5" id="FormulaireContact">
    <h2>Nous contacter !</h2>
    <form action="contactentree.ctrl.php" method="post" class="row g-3">
      <div class="col-md-6">
        <label for="firstName" class="form-label">Prénom :</label>
        <input type="text" class="form-control" id="firstName" name="prenom" required>
      </div>
      <div class="col-md-6">
        <label for="lastName" class="form-label">Nom :</label>
        <input type="text" class="form-control" id="lastName" name="nom" required>
      </div>
      <div class="col-md-8">
        <label for="email" class="form-label">Email :</label>
        <input type="email" class="form-control" id="emailInfo" name="email" required>
      </div>
      <div class="col-md-4">
        <label for="phoneNumber" class="form-label"> Télephone : </label>
        <input type="text" class="form-control" id="phoneNumber" name="tel" placeholder="0769162506" pattern="[0-9]{10}">
      </div>
      <div class="col-md-12">
        <label for="comments" class="form-label"> Message :</label>
        <textarea class="form-control" id="comments" name="message" rows="3"></textarea>
      </div>
      <div class="col-md-12">
        <button type="submit" class="btn btn-primary">Envoyer</button>
      </div>

    </form>


  </div>

  <?php include_once(__DIR__ . "/../view/footer.subview.html"); ?>

</body>

</html>