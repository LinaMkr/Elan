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

if($utilisateur!=null && $admin){ // Si quelqu'un de connecté et admin

  // Assignation des variables utilisateur et admin à la page
  $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
  $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

  if(isset($_POST['sujet']) && isset($_POST['article'])){

    $code = $dao->gestionArticle($_POST['sujet'],$_POST['article'],$utilisateur->email,'insert');

    if($code==1){ // Code d'erreur 1 = insertion réussi

      // Affichage d'un message de réussite
      $view->assign('message',"REUSSI : vous venez de créer un nouvel article.");

    }else if($code==-1){ // Code d'erreur -1 = titre déjà existant

      // Affichage d'un message d'erreur explicite
      $view->assign('message',"IMPOSSIBLE : un article a déjà ce titre.");

    }else{ // Code d'erreur -2 = erreur inconnue

      // Affichage d'un message d'erreur explicite
      $view->assign('message',"IMPOSSIBLE : veuillez réessayer plus tard, erreur inconnue.");

    }

    $view->display('message.view.php');

  }else{ // Pas d'entrée donc pas de création d'article

    // Affichage de la page adminAjoutArticle
    $view->display("adminAjoutArticle.view.php");

  }

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
