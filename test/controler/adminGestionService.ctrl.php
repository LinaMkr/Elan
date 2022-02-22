<?php

// Inclusion du framework
include_once(__DIR__."/../framework/view.class.php");

// Inclusion des classes
include_once(__DIR__."/../model/client.class.php");
include_once(__DIR__."/../model/admin.class.php");
include_once(__DIR__."/../model/utilisateur.class.php");
include_once(__DIR__."/../model/DAO.class.php");
include_once(__DIR__."/../model/service.class.php");

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

if($utilisateur!=null && $admin){ // Si quelqu'un de connecté et admin

  // Si y'a un code et une suppresion effectué
  if(isset($_GET['action']) && isset($_GET['code']) && $_GET['action']=='supprimer'){

    $code = $_GET['code'];
    if($code==1){ // $code = 1 : code de réussite

      // Affichage d'un message de réussite
      $view->assign('message',"Vous avez bien supprimé le service.");

    }else if($code==-3){ // $code = -3 : code d'erreur parce que typeReservation attribué

      // Affichage d'un message d'erreur
      $view->assign('erreur',"Vous ne pouvez pas supprimer le service car une réservation utilise ce service.");

    }else{ // $code = -2 : code d'erreur inconnue

      // Affichage d'un message d'erreur
      $view->assign('erreur',"Vous ne pouvez pas supprimer le service : erreur inconnue.");

    }

  }else if(isset($_GET['action']) && isset($_GET['code']) && $_GET['action']=='ajouter'){
    // Si y'a un code et une insertion réalisé

    $code = $_GET['code'];
    if($code==1){ // $code = 1 : code de réussite

      // Affichage d'un message de réussite
      $view->assign('message',"Vous avez bien créé le service.");

    }else if($code==-1){ // $code = -1 : code d'erreur parce que typeReservation attribué

      // Affichage d'un message d'erreur
      $view->assign('erreur',"Nom du service déjà existant.");

    }else{ // $code = -2 : code d'erreur inconnue

      // Affichage d'un message d'erreur
      $view->assign('erreur',"Vous ne pouvez pas créer le service : erreur inconnue.");

    }
  }

  // Récupération de tous els services
  $services = $dao->getServices();

  $view->assign('services',$services);
  // Assignation des variables utilisateur et admin à la page
  $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
  $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

  $view->display("adminGestionService.view.php");

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
