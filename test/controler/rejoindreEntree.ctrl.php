<?php

// Inclusion du framework
include_once(__DIR__."/../framework/view.class.php");

// Inclusion des classes
include_once(__DIR__."/../model/client.class.php");
include_once(__DIR__."/../model/DAO.class.php");
include_once(__DIR__."/../model/admin.class.php");
include_once(__DIR__."/../model/utilisateur.class.php");

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



if(isset($_GET['idR']) && $utilisateur!=null){
  // Si quelqu'un de connecté et un id de réservation sélectionné par l'utilisateur

  // Récupération de l'idR de la query string
  $idR = $_GET['idR'];

  // Appel du $dao pour rejoindre la réservation d'id idR
  $reussi = $dao->rejoindreReservation($utilisateur->email,$idR); // Enregistrement dans la bdd

  if($reussi==1){ // Client qui a bien rejoint la réservation
    // Si le client a bien rejoint la réservation
    header("Location: paiement.ctrl.php?idR=$idR");

  }else{ // Gestion de l'erreur

    $view->assign('utilisateur',$utilisateur);
    $view->assign('admin',$admin);
    // Gestion des erreurs lorsque le client
    if($reussi==-1){ // Code d'erreur -1 = déjà inscrit

      // Assignation d'un message d'erreur explicite
      $view->assign('message','IMPOSSIBLE : vous êtes déjà inscrit à la réservation.');

    }else if($reussi==-3){ // Code d'erreur -3 = manque de place

      // Assignation d'un message d'erreur explicite
      $view->assign('message','IMPOSSIBLE : vous ne pouvez pas rejoindre cette réservation, il n\'y a plus de place.');

    }else{ // Code d'erreur -2 = erreur inconnue

      // Assignation d'un message d'erreur explicite
      $view->assign('message','IMPOSSIBLE : vous ne pouvez pas rejoindre cette réservation, erreur inconnue.');
    }

    // Affichage de la page message
    $view->display("message.view.php");
  }

}else if(!isset($_GET['idR'])){ // Pas d'id de la réservation donc on redirige vers la page rejoindre
  // (Impossible normalement)

  header('Location: rejoindre.ctrl.php');  // Redirection sur la page pour rejoindre car pas d'idR dans la query string

}else if($utilisateur==null){ // Si personne de connecté

  header('Location: connexion.ctrl.php');  // Redirection sur la page d'inscription car personne connecté

}else{ // (pas possible normalement) si quelqu'in de connecté et pas d'idR

  header('Location: accueil.ctrl.php');  // Redirection sur la page d'accueil car pas possible

}

 ?>
