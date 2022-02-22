<?php

//Affichage de la page du lieu sélectionner +

// Inclusion du framework
include_once(__DIR__ . "/../framework/view.class.php");

// Inclusion des classes
include_once(__DIR__ . "/../model/client.class.php");
include_once(__DIR__ . "/../model/admin.class.php");
include_once(__DIR__ . "/../model/DAO.class.php");
include_once(__DIR__ . "/../model/lieu.class.php");
include_once(__DIR__ . "/../model/utilisateur.class.php");

// Ouverture de la session
session_start();

// Récupération de l'utilisateur
if (isset($_SESSION['utilisateur'])) {
  $utilisateur = $_SESSION['utilisateur'];
} else {
  $utilisateur = null; // pas d'utilisateur connecté par défaut
}

// Récupération du role de l'utilisateur
if (isset($_SESSION['admin'])) {
  $admin = $_SESSION['admin'];
} else {
  $admin = false; // utilisateur non admin par défaut
}

// Fermeture de la session
session_write_close();

// Construction de la vue
$view = new View();

// Construction du DAO
$dao = new DAO();

// Récupération du local du formulaire
if (isset($_POST['local'])) {
  $nomLocal = $_POST['local'];
} else {
  $nomLocal = null; // Nom du local par défaut si il n'est pas présent
}

// Récupération d'un lieu choisi par l'utilisateur s'il existe
if (isset($_POST['local'])) {
  // On récupère les réservations de ce lieu grâce au DAO
  $reservations = $dao->getReservations($_POST['local']);
  $nomLieuAffiche =  $_POST['local'];
} else { // Pas de lieu choisi (par défaut)
  // On récupère les réservations du 1er lieu grâce au DAO
  $reservations = $dao->getReservations($lieux[0]->nomLocal);
  $nomLieuAffiche = $lieux[0]->nomLocal;
}

// Initialisation du tableau contenant les réservations
$tableauReservations = array();

// On parcourt l'ensemble des formations
foreach ($reservations as $r) {
  // Mise en format de la date "YYYY-MM-DD HH:mm:ss" vers le format "YYYY-MM-DD-HH-mm-ss" pour faciliter l'utilisation dans l'agenda
  $r->dateDeb = str_replace(array(':', ' '), '-', $r->dateDeb);
  $r->dateFin = str_replace(array(':', ' '), '-', $r->dateFin);
  //On met dans un tableau les réservation qui ont pour colonne le nom des attribut
  $reservation = array(
    'idR' => $r->idR,
    'email' => $r->email->email,
    'lieu' => $r->lieu->nomLocal,
    'typeReservation' => $r->type,
    'nomReservation' => $r->nom,
    'dateDebut' => $r->dateDeb,
    'dateFin' => $r->dateFin,
    'nbPersonne' => $r->nb_pers,
    'placeRestante' => $r->placeRest,
    'prixTTC' => $r->prixT,
  );
  // Ajoute à la fin de $tableauReservations, le tableau $reservation
  array_push($tableauReservations, $reservation);
}





if ($utilisateur != null) { // Si quelqu'un de connecté
  //Faire local
  if (
    isset($_POST['local']) && isset($_POST['type_jour']) && isset($_POST['nb_places'])
    && isset($_POST['dateDeb'])
  ) {

    // Récupération des valeurs
    $local = $_POST['local'];
    $dateDeb = $_POST['dateDeb'];
    $dateFin = $dateDeb;
    $tJ = $_POST['type_jour'];
    $nbPlace = $_POST['nb_places'];
    $possible = true;



    // Gestion des horaires de la réservation
    if ($tJ == 'matin') {

      // Horaires du matin
      $hD = '09:00:00';
      $hF = '12:30:00';
    } else if ($tJ == 'aprem') {

      // Horaires de l'après-midi
      $hD = '14:00:00';
      $hF = '17:30:00';
    } else { // un jours ou plusieurs jours

      // Horaires d'une journée
      $hD = '09:00:00';
      $hF = '17:30:00';

      if ($tJ == 'jours') {
        // Si le client a choisi plusieurs jours
        // On récupère la date de fin
        if (isset($_POST['dateFin'])) {
          $dateFin = $_POST['dateFin'];
        } else { // Si il n'y a pas de date de fin
          $possible = false; // Reservation impossible
          $erreur = 'Erreur : vous devez choisir une date de fin';
        }
      }
    }

    // Permet de savoir si les dates sont valides ou pas
    $code = $dao->valideDates(($dateDeb . ' ' . $hD), ($dateFin . ' ' . $hF), $local);

    // Affichage d'un message adapté en fonction de l'erreur
    if ($code < 0) {

      $possible = false; // Reservation impossible

      if ($code == -1) { // Code d'erreur -1 = dateDeb < dateFin
        // Affichage d'un message d'erreur explicite
        $erreur = 'Erreur : date de début inférieur à la date de fin.';
      } else if ($code == -3) { // Code d'erreur -3 = déjà une réservation à cette date

        // Affichage d'un message d'erreur explicite
        $erreur = 'Erreur : il y a déjà une réservation à cette date.';
      } else if ($code == -2) { // Code d'erreur -2 = erreur inconnue

        // Affichage d'un message d'erreur
        $erreur = 'Erreur : réessayer plus tard, erreur inconnue.';
      }
    }



    // Assignation des variables utilisateur et admin à la page
    $view->assign('utilisateur', $utilisateur); // envoie de l'objet utilisateur ou null
    $view->assign('admin', $admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

    // Assignation de tous les services et du lieu s'il y a une erreur et affichage dans la page reservation
    $view->assign('services', $dao->getServices());
    $view->assign('typeResas',$dao->getTypesResa()); // Envoie des types de réservation si admin
    $view->assign('local', $dao->getLieu($local));
    $view->assign('reservations', $tableauReservations); // pour l'affichage de l'agenda de réservation

    if ($possible) { // Si pas d'erreur

      // Si c'est un admin, il doit donner un typeResa
      if(isset($_POST['typeResa'])){
        $typeResa =explode('_',$_POST['typeResa']); // On coupe la chaine de caractère avec le symbole '_'
        $type = $typeResa[0]; // Récupération du type
        $nom = $typeResa[1]; // Récupération du nom
      }else{ // Valeur par défaut du type et du nom
        $type = "location";
        $nom = "";
      }
      // Création de la réservation
      $idR = $dao->creerReservation(
        $utilisateur->email,
        $local,
        $type,
        $nom,
        $nbPlace,
        ($dateDeb . ' ' . $hD),
        ($dateFin . ' ' . $hF)
      );

      if ($idR == -1) { // Code d'erreur -1 = trop de participant

        // Ecriture d'un message d'erreur explicite
        $erreur = 'Erreur : trop de participant lors d\'une réservation ce jour.';
        // Assignation du message d'erreur
        $view->assign('erreur', $erreur);
        // Affichage de la page reservation
        $view->display("reservation.view.php");
      } else if ($idR == -2) { // Code d'erreur -2 = erreur inconnue

        // Ecriture d'un message d'erreur explicite
        $erreur = 'Erreur inconnue : Veuillez réessayer plus tard.';
        // Assignation du message d'erreur
        $view->assign('erreur', $erreur);
        // Affichage de la page reservation
        $view->display("reservation.view.php");
      } else { // Si réservation OK

        // Récuperation de tous les services
        $services = $dao->getServices();

        // Parcourt de tous les services
        foreach ($services as $service) {
          //Verification si la chechbox de ce service à été coché
          if (isset($_POST["{$service->intitule}"])) {
            // Si elle a été coché, on ajoute le service à la réservation d'id idR
            $dao->ajoutService($idR, $service->intitule);
          }
        }
        //Ajout du prix total à la réservation et à l'organisateur (si client)
        $insertPrix = $dao->calculPrixTotal($idR);

        if ($insertPrix == -2) { // Code d'erreur -2 = erreur inconnue

          // Ecriture d'un message d'erreur explicite
          $erreur = 'Erreur inconnue : Veuillez réessayer plus tard.';
          // Assignation du message d'erreur
          $view->assign('erreur', $erreur);
          // Affichage de la page reservation
          $view->display("reservation.view.php");
        } else if(!$admin){ // Reservation reussi avec le bon calcul du prix

          header("Location: paiement.ctrl.php?idR=$idR");  // Redirection sur la page de paiement

        }else{
          $view->assign('message',"REUSSI : vous avez bien créé un/une $type de nom $nom et d'id $idR.");
          $view->display("message.view.php");
        }
      }
    } else { // erreur detecté

      // Assignation du message d'erreur
      $view->assign('erreur', $erreur);
      // Affichage de la page reservation
      $view->display("reservation.view.php");
    }
  }
} else { // Si personne de connecté

  header('Location: connexion.ctrl.php');  // Redirection sur la page d'inscription car personne connecté

}
