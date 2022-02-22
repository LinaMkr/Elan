<?php

// Inclusion du framework
include_once(__DIR__."/../framework/view.class.php");

// Inclusion des classes
include_once(__DIR__."/../model/client.class.php");
include_once(__DIR__."/../model/admin.class.php");
include_once(__DIR__."/../model/utilisateur.class.php");
include_once(__DIR__."/../model/DAO.class.php");
include_once(__DIR__."/../model/typeReservation.class.php");
include_once(__DIR__."/../model/lieu.class.php");

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

// Assignation des variables utilisateur et admin à la page
$view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
$view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin


if($utilisateur!=null && $admin){ // Si quelqu'un de connecté et admin

  // Page commune aux évenements et aux formations
  // car ce sont dans les 2 cas des réservations
  if(isset($_GET['type'])){ // Affichage page ajout si type valable

    $view->assign('type',$_GET['type']); // envoie du type dans la page

    //======================================================
    // Gestion de l'affichage d'un message d'erreur ou de réussite
    //======================================================
    // si l'action était une suppression et qu'il y a bien un code
    if(isset($_GET['action']) && isset($_GET['code']) && $_GET['action']=='supprimer'){

      $code = $_GET['code'];
      if($code==1){ // $code = 1 : code de réussite

        // Affichage d'un message de réussite
        $view->assign('message',"Vous avez bien supprimé la ligne.");

      }else if($code==-1){ // $code = -1 : code d'erreur parce que typeReservation attribué

        // Affichage d'un message d'erreur
        $view->assign('erreur',"Vous ne pouvez pas supprimer la ligne car le type de réservation est attribué.");

      }else{ // $code = -2 : code d'erreur inconnue

        // Affichage d'un message d'erreur
        $view->assign('erreur',"Vous ne pouvez pas supprimer la ligne : erreur inconnue.");

      }

    }else if(isset($_GET['action']) && isset($_GET['code']) && $_GET['action']=='ajouter'){

      $code = $_GET['code'];
      if($code==1){ // $code = 1 : code de réussite

        // Affichage d'un message de réussite
        $view->assign('message',"Vous avez bien créé l'évenement/formation");

      }else if($code==-1){ // $code = -1 : code d'erreur parce que typeReservation attribué

        // Affichage d'un message d'erreur
        $view->assign('erreur',"Nom de l'évenement/formation déjà existantes.");

      }else{ // $code = -2 : code d'erreur inconnue

        // Affichage d'un message d'erreur
        $view->assign('erreur',"Vous ne pouvez pas créé l'évenement/formation : erreur inconnue.");

      }
    }



    // Récupération des lieux
    $lieux = $dao->getLieux();

    if($_GET['type']=='formation'){
      $typeResa = $dao->getGestionFormation();
    }else{
      $typeResa = $dao->getGestionEvenement();
    }
    // Assignation de tous les lieux
    $view->assign('lieux',$lieux);
    // Assignation de toutes les formations ou les evenements à la page
    $view->assign('typeResa',$typeResa);

    //var_dump($typeResa);
    // Affichage de la page adminAjoutEvenementFormation
    $view->display("adminAjoutEvenementFormation.view.php");
  }else{ // sinon page admin

    // Affichage de la page admin
    $view->display("admin.view.php");
  }



}else if(!$admin){ // Si quelqu'un de connecté mais pas admin

  // Assignation d'un message à afficher
  $view->assign('message',"IMPOSSIBLE : vous n'êtes pas administrateur.");

  // Affichage de la page message
  $view->display("message.view.php");

}else{ // Si personne de connecté

  header('Location: connexion.ctrl.php');  // Redirection sur la page d'inscription car personne connecté

}

 ?>
