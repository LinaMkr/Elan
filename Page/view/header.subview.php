<header>
  <a href="./accueil.ctrl.php" id="logo"><img src="../view/images/logo.png" alt="logo l'elan"></a>
  <div id="hamburger">
    <div id="hamburger-content">
      <nav>
        <ul>
          <?php if ($admin) : ?>
            <li id="adminilog"><a href="./admin.ctrl.php">Administration</a></li>
          <?php endif; ?>
          <li id="reserverlog"><a href="./lieu.ctrl.php">Réserver</a></li>
          <?php if (!$admin) : ?>
            <li id="rejoindrelog"><a href="./rejoindre.ctrl.php">Rejoindre</a></li>
          <?php endif; ?>
          <li id="formationlog"><a href="./agendaFormation.ctrl.php">Formations</a></li>
          <li id="evenementlog"><a href="./agendaEvenement.ctrl.php">Evenements</a></li>
          <li id="actualitelog"><a href="./actualite.ctrl.php">Actualités</a></li>
        </ul>
      </nav>
      <?php if ($utilisateur == null) : ?>
        <a href="./inscription.ctrl.php" class="button button-sign-up">S'inscrire</a>
        <a href="./connexion.ctrl.php" class="button button-sign-in">Se connecter</a>
      <?php else : ?>
        <a id="btnDeco" href="./deconnexion.ctrl.php" class="button button-sign-up">Se Deconnecter</a>
        <a id="btnProfil" href="./profil.ctrl.php" class="button button-sign-in">Profil</a>
      <?php endif; ?>
    </div>
    <button id="hamburger-button">&#9776;</button>
    <div id="hamburger-sidebar">
      <div id="hamburger-sidebar-header"></div>
      <div id="hamburger-sidebar-body"></div>
    </div>
    <div id="hamburger-overlay"></div>
  </div>

</header>

<script type="text/javascript" src="../view/scripts/menustyle.js"></script>
