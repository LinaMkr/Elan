<?php
/*
=======================================
Fichier executé automatiquement sur le serveur
le 1er jour de chaque mois à minuit
Exécute la mise à jour des statistiques admin


0 0 1 * * php /var/www/rendus/Page/data/UpdateStat.cron.php

=======================================
*/

include_once(__DIR__."/../model/DAO.class.php");

$dao = new DAO();

$dao->updateStats();

$date = date("d-m-y_G:i", strtotime('now +1 Hour'));
$f = fopen(__DIR__."/".$date.".txt", "x+");
// écriture
fputs($f, "Fichier Cron exécuté le : ".$date);
// fermeture
fclose($f);

 ?>
