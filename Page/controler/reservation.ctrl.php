<?php

// Inclusion du framework
include_once(__DIR__ . "/../framework/view.class.php");

// Inclusion des classes
include_once(__DIR__ . "/../model/client.class.php");
include_once(__DIR__ . "/../model/admin.class.php");
include_once(__DIR__ . "/../model/DAO.class.php");
include_once(__DIR__ . "/../model/lieu.class.php");
include_once(__DIR__ . "/../model/utilisateur.class.php");
include_once(__DIR__ . "/../model/typeReservation.class.php");

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

// Construction ddu DAO
$dao = new DAO();

// Appel du dao pour récupérer l'ensemble des lieux
$lieux = $dao->getLieux();

// Récupération du nom du local de la query string
if (isset($_GET['nomLocal'])) {
  $nomLocal = $_GET['nomLocal'];
} else {
  $nomLocal = null; // Nom du nomLocal par défaut si il n'est pas présent
}

// Récupération d'un lieu choisi par l'utilisateur s'il existe
if (isset($_GET['nomLocal'])) {
  // On récupère les réservations de ce lieu grâce au DAO
  $reservations = $dao->getReservations($_GET['nomLocal']);
  $nomLieuAffiche =  $_GET['nomLocal'];
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

// Construction de la vue
$view = new View();

// Assignation des variables utilisateur et admin à la page
$view->assign('utilisateur', $utilisateur); // envoie de l'objet utilisateur ou null
$view->assign('admin', $admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

if ($utilisateur != null && $nomLocal != null) { // Si quelqu'un de connecté et lieu sélectionné
  // nomLieu valide

  // Récupération du Lieu de nom nomLocal
  $local = $dao->getLieu($nomLocal);

  if ($local != null) { // Si local valide

    // Assignation des variables nécessaires à la page réservation
    $view->assign('local', $local); // Envoie du lieu à la page réservation
    $view->assign('services', $dao->getServices()); // Envoie de tous les services
    $view->assign('reservations', $tableauReservations); // pour l'affichage de l'agenda de réservation
    $view->assign('typeResas',$dao->getTypesResa()); // Envoie des types de réservation si admin

    // Affichage de la page reservation
    $view->display("reservation.view.php");
  } else { // Lieu qui n'existe pas

    // Assignation d'un message d'erreur
    $view->assign('message', "IMPOSSIBLE : vous devez cliquer sur un lien valide.");

    // Affichage de la page message
    $view->display("message.view.php");
  }
} else if ($nomLocal == null) { // Lieu incorrect

  // Assignation d'un message d'erreur
  $view->assign('message', "IMPOSSIBLE : vous devez cliquer sur un lien valide.");

  // Affichage de la page message
  $view->display("message.view.php");
} else { // Si personne de connecté

  header('Location: connexion.ctrl.php');  // Redirection sur la page d'inscription car personne connecté

}
