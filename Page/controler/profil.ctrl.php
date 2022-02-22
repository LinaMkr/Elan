<?php

// Inclusion du framework
include_once(__DIR__."/../framework/view.class.php");

// Inclusion des classes
include_once(__DIR__."/../model/client.class.php");
include_once(__DIR__."/../model/DAO.class.php");
include_once(__DIR__."/../model/admin.class.php");
include_once(__DIR__."/../model/utilisateur.class.php");
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

// Construction de la vue
$view = new View();

if($utilisateur!=null){ // Si quelqu'un de connecté

  // Récupération des infos des réservation + du nombre de réservation
  $reservations = $dao->mesReservations($utilisateur->email);
  $nbResa = $dao->compteMesReservations($utilisateur->email);

  // Assignation des reservations du nbResa à la page profil pour l'affichage
  $view->assign('reservations',$reservations);
  $view->assign('nbResa',$nbResa);

  // Assignation des variables utilisateur et admin à la page
  $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
  $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

  // Affichage de la page profil
  $view->display("profil.view.php");

}else{ // Si personne de connecté

  header('Location: connexion.ctrl.php');  // Redirection sur la page d'inscription car personne connecté

}

 ?>
