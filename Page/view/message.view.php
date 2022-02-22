<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>L'Elan - message</title>

  <?php include_once(__DIR__ . "/../view/head.subview.html"); ?>
  <link rel="stylesheet" href="../view/style/styleMessage.css">

</head>

<body>

  <?php include_once(__DIR__ . "/../view/header.subview.php"); ?>

  <div class="fil_ariane">
    <p>
      <ion-icon name="home-outline"></ion-icon> <a href="./accueil.ctrl.php">Accueil</a> > <a href="./message.ctrl.php">Message</a>
    </p>
  </div>

  <div class="message">
    <h1>Message</h1>
    <output name="message"><?= $message ?></output>

    <input type="button" value="Retour à l'accueil" onclick="self.location.href='./accueil.ctrl.php'">
  </div>

  <?php include_once(__DIR__ . "/../view/footer.subview.html"); ?>


</body>

</html>