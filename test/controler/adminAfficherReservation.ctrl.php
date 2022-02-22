<?php

// Inclusion du framework
include_once(__DIR__."/../framework/view.class.php");

// Inclusion des classes
include_once(__DIR__."/../model/client.class.php");
include_once(__DIR__."/../model/admin.class.php");
include_once(__DIR__."/../model/utilisateur.class.php");
include_once(__DIR__."/../model/DAO.class.php");
include_once(__DIR__."/../model/reservation.class.php");

// Ouverture de la session
session_start();

// Récupération de l'utilisateur
if(isset($_SESSION['utilisateur'])){
  $utilisateur = $_SESSION['utilisateur'];
}else{
  $utilisateur = null; // pas d'utilisateur connecté par défaut
}

// Récupération du role de l'utilisateur
if(isset($_SESSION['admin'])){
  $admin = $_SESSION['admin'];
}else{
  $admin = false; // utilisateur non admin par défaut
}

// Fermeture de la session
session_write_close();

// Construction du DAO
$dao = new DAO();

// Appel du dao pour récupérer l'ensemble des lieux
$lieux = $dao->getLieux();

// Récupération d'un lieu choisi par l'utilisateur s'il existe
if(isset($_POST['lieu'])){
  // On récupère les réservations de ce lieu grâce au DAO
  $reservations = $dao->getReservations($_POST['lieu']);
  $nomLieuAffiche =  $_POST['lieu'];
}else{ // Pas de lieu choisi (par défaut)
  // On récupère les réservations du 1er lieu grâce au DAO
  $reservations = $dao->getReservations($lieux[0]->nomLocal);
  $nomLieuAffiche = $lieux[0]->nomLocal;
}

// Initialisation du tableau de formation final
$tableauReservations = array();
// Variable d'incrémentation pour le tableau
$i = 0;

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

  // Incrémentation de i
  $i++;
}

// Construction de la vue
$view = new View();

if($utilisateur!=null && $admin){ // Si quelqu'un de connecté et admin

  // Assignation de toutes les informations sur les lieux
  $view->assign('nomLieuAffiche', $nomLieuAffiche); // Envoie du nom du lieu choisi ou par défaut
  $view->assign('lieux', $lieux); // Envoie de l'ensemble des objets lieu
  $view->assign('reservations',$tableauReservations); // Envoie des réservations pour le lieu choisi

  // Assignation des variables utilisateur et admin à la page
  $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
  $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

  // Affichage de la page adminAfficherReservation
  $view->display("adminAfficherReservation.view.php");

}else if(!$admin){ // Si quelqu'un de connecté mais pas admin

  // Assignation des variables utilisateur et admin à la page
  $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
  $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

  // Assignation d'un message à afficher
  $view->assign('message',"IMPOSSIBLE : vous n'êtes pas administrateur.");

  // Affichage de la page message
  $view->display("message.view.php");
}else{ // Si personne de connecté

  header('Location: connexion.ctrl.php');  // Redirection sur la page d'inscription car personne connecté

}
