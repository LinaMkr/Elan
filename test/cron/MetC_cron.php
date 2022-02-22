<?php
/*
=======================================
Fichier executé automatiquement sur le serveur
à 7h30 chaque jour
Exécute les : - mails de Remerciement
              - mails de Rappel
              - séance automatique de Co-Working


30 8 * * * php /var/www/rendus/Page/data/MEtC.cron.php

=======================================
*/

include_once(__DIR__."/../model/DAO.class.php");

$dao = new DAO();

// Email de rappel
$emailsRappel = $dao->getEmailsRappel();
// S'il y a des emails envoyés par la méthode du DAO
if($emailsRappel!=null){
  // On parcourt les emails
  foreach ($emailsRappel as $email) {

    // Sujet du mail
    $subject = 'Rappel Elan : réservation dans 2 jours';

    // Message du mail
    $message = "Bonjour, \r\nn'oubliez pas votre réservation pour un local de l'Elan."."\r\n"."Dans 2 jours, vous avez votre réservation."."\r\n"."Cordialement,"."\r\n";
    $message = wordwrap($message, 70, "\r\n"); // Pour couper les lignes de plus de 70 caractères car impossible avec cette fonction
    // Header du mail avec le from (Qui l'envoie ?), le reply-to et la version de php
    $headers = 'From: newsletter@elan.fr' . "\r\n" . 'Reply-To: newsletter@elan.fr' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

    $reussi = mail($email,$subject,$message,$headers); // Envoie du mail
    if($reussi){ // boucle pas exécuté en automatique juste pour avoir une idée de l'exécution manuelle
      echo "Envoie de l'email de rappel à $email réussi \r\n";
    }
  }
}


// Email de remerciement
$emailsRemerciement = $dao->getEmailsRemerciement();
// S'il y a des emails envoyés par la méthode du DAO
if($emailsRemerciement!=null){
  // On parcourt les emails
  foreach ($emailsRemerciement as $email) {
    // Sujet du mail
    $subject = 'Avis Réservation Elan';

    // Message du mail
    $message = "Bonjour, \r\nmerci pour votre réservation dans un local de l'Elan."."\r\n"."Vous pouvez laissez votre avis sur : "."\r\n"."https://strawpoll.com/z35h6oz8v "."\r\n"."Nous espérons vous revoir bientôt dans nos locaux."."\r\n"."Cordialement,"."\r\n";
    $message = wordwrap($message, 70, "\r\n"); // Pour couper les lignes de plus de 70 caractères car impossible avec cette fonction
    // Header du mail avec le from (Qui l'envoie ?), le reply-to et la version de php
    $headers = 'From: newsletter@elan.fr' . "\r\n" . 'Reply-To: newsletter@elan.fr' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

    $reussi = mail($email,$subject,$message,$headers); // Envoie du mail
    if($reussi){ // boucle pas exécuté en automatique juste pour avoir une idée de l'exécution manuelle
      echo "Envoie de l'email de remerciement à $email réussi \r\n";
    }
  }
}
// On récupére les admins de la bdd
$admins = $dao->getAdmins();
if($admins){ // S'il y a bien des admins qui existent

  // On récupère l'ensemble des lieux de la bdd
  $lieux = $dao->getLieux();
  foreach ($lieux as $lieu) { // On parcourt l'ensemble des lieux

    /* On teste si chaque lieu est disponible dans les prochaines 24h,
       s'il est disponible, nous créons une session de co-working
       avec l'email $admin[0]*/
    $idR = $dao->coWorking($admins[0]->email,$lieu->nomLocal);

    if($idR>0){ // Si coWorking créé

      // Assignation du prix pour la réservation coWorking
      $dao->calculPrixTotal($idR);
    }

  }

}

$date = date("d-m-y_G:i", strtotime('now +1 Hour'));
$f = fopen(__DIR__."/".$date.".txt", "x+");
// écriture
fputs($f, "Fichier Cron exécuté le : ".$date);
// fermeture
fclose($f);

 ?>
