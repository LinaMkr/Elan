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

// Constuction du DAO
$dao = new DAO();

// Construction de la vue
$view = new View();

// Assignation des variables utilisateur et admin à la page
$view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
$view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

if($utilisateur!=null && isset($_GET['idR'])){ // Si quelqu'un de connecté et bien idR dans la query string

  // Récupération de l'idR de la réservation payée
  $idR = $_GET['idR'];

  // Récupération de la réservationpour récupérer le prix
  $reservation = $dao->getReservation($idR);

  // Appel du DAO pour enregistrer le paiement
  $code = $dao->payee($idR,$utilisateur->email,$reservation->prixT);

  if($code==1){ // $code = 1 : code de réussite

    // Assignation d'un message d'erreur
    $view->assign('message',"REUSSI : votre paiement a bien été enregistré.");

  }else{ // $code = -2 : code d'erreur inconnue

    // Assignation d'un message d'erreur
    $view->assign('message',"IMPOSSIBLE : votre paiement n'a pas pu être enregistré dans la base de donnée, erreur inconnue.");

  }

  // Affichage de la page message
  $view->display("message.view.php");

}else if($utilisateur!=null){ // Si quelqu'un de connecté mais pas d'idR
  // (Impossible normalement)

  // Assignation d'un message d'erreur
  $view->assign('message',"IMPOSSIBLE : lien invalide, pas d'idR.");

  // Affichage de la page message
  $view->display("message.view.php");



}else{ // Si personne de connecté

  header('Location: connexion.ctrl.php');  // Redirection sur la page d'inscription car personne connecté

}

?>
