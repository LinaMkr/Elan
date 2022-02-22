<?php

// Inclusion du framework
include_once(__DIR__."/../framework/view.class.php");

// Inclusion des classes
include_once(__DIR__."/../model/client.class.php");
include_once(__DIR__."/../model/admin.class.php");
include_once(__DIR__."/../model/utilisateur.class.php");
include_once(__DIR__."/../model/DAO.class.php");

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

  // Assignation des variables utilisateur et admin à la page
  $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
  $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

  // Assignation des services et des prestataire pour l'assignation
  $view->assign('services',$dao->getServices());
  $view->assign('prestataires',$dao->getPrestataires());

  // Si y'a un code et une suppresion effectué
  if(isset($_GET['action']) && isset($_GET['code']) && $_GET['action']=='supprimer'){

    $code = $_GET['code'];
    if($code==1){ // $code = 1 : code de réussite

      // Affichage d'un message de réussite
      $view->assign('message',"Vous avez bien supprimé le prestataire.");

    }else{ // $code = -2 : code d'erreur inconnue

      // Affichage d'un message d'erreur
      $view->assign('erreur',"Vous ne pouvez pas supprimer le prestataire : erreur inconnue.");

    }

  }else if(isset($_GET['action']) && isset($_GET['code']) && $_GET['action']=='ajouter'){
    // Si y'a un code et une insertion réalisé

    $code = $_GET['code'];
    if($code==1){ // $code = 1 : code de réussite

      // Affichage d'un message de réussite
      $view->assign('message',"Vous avez bien créé le prestataire.");

    }else if($code==-1){ // $code = -1 : code d'erreur parce que nom et prenom déjà assigné

      // Affichage d'un message d'erreur
      $view->assign('erreur',"Prestataire déjà existant.");

    }else{ // $code = -2 : code d'erreur inconnue

      // Affichage d'un message d'erreur
      $view->assign('erreur',"Vous ne pouvez pas créer le prestataire : erreur inconnue.");
    }

  }else if(isset($_GET['action']) && isset($_GET['code']) && $_GET['action']=='affecter'){
    // Si y'a un code et une affectation réalisé

    // Code est ici un boolean
    $code = $_GET['code'];

    if($code){ // $code = true : code de réussite

      // Affichage d'un message de réussite
      $view->assign('message',"Vous avez bien assigné le prestataire au service.");

    }else{ // $code = false : code d'erreur inconnue

      // Affichage d'un message d'erreur
      $view->assign('erreur',"Vous ne pouvez pas assigner le prestataire à ce service.");

    }
  }

  // Affichage de la page admin
  $view->display("adminGestionPrestataire.view.php");

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

 ?>
