<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>L'Elan - Admin ajout article</title>

    <?php include_once(__DIR__."/../view/head.subview.html"); ?>
    <link rel="stylesheet" href="../view/style/styleAdmin.css">

  </head>

  <body>

    <?php include_once(__DIR__."/../view/header.subview.php"); ?>

    <div class="fil_ariane">
      <p><ion-icon name="home-outline"></ion-icon> > <a href="./accueil.ctrl.php">Accueil</a> > <a href="./admin.ctrl.php">Administration</a> > <a href="./adminAjoutArticle.ctrl.php">Ajout article</a></p>
    </div>

    <div id="adminAjouterArticle">


        <form action="adminAjoutArticleEntree.ctrl.php" method="post">

          <h3 class="display-5">Ajout d'un article</h3>

          <label for="sujet">Sujet : </label>
          <input id="sujet" type="text" name="sujet" placeholder="Les formations"
          title="Veuillez saisir un sujet" required>

          <label for="article">Ecrivez ci-dessous : </label>
          <textarea name="article" rows="8" cols="80">Ecrivez ici...</textarea>

          <input type="submit" value="Ajouter article">

        </form>

    </div>

    <?php include_once(__DIR__."/../view/footer.subview.html"); ?>

  </body>
</html>
