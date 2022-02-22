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

// Construction de la vue
$view = new View();

if($utilisateur!=null){ // Si quelqu'un de connecté

  if(isset($_POST['mdp']) && isset($_POST['mdpConfirm'])){

    $mdp = $_POST['mdp'];

    if($mdp!=$_POST['mdpConfirm']){ // Mot de passe de confirmation incorrect

      // Affichage d'un message d'erreur explicite
      $view->assign('erreur','Erreur : mot de passe de confirmation incorrect.');

      // Assignation des variables utilisateur et admin à la page
      $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
      $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

      // Affichage de la page profilModifierMDP
      $view->display('profilModifierMDP.view.php');

    }else if(md5($mdp)==$utilisateur->mdp){ // Mot de passe déja actif
      // md5 permet de hacher le mot de passe

      // Affichage d'un message d'erreur explicite
      $view->assign('erreur','Erreur : mot de passe déjà actif.');

      // Assignation des variables utilisateur et admin à la page
      $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
      $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

      // Affichage de la page profilModifierMDP
      $view->display('profilModifierMDP.view.php');

    }else{ // Mot de passe correct

      // Construction du DAO
      $dao = new DAO();

      // Modification du mot de passe dans la bdd avec le DAO
      $reussi = $dao->setMdpClient($utilisateur,$mdp,$admin);

      if($reussi){ // Si modification dans la bdd réussi

        // Modification de l'objet utilisateur
        $utilisateur->mdp = $mdp;
        // Mise dans la session de l'utilisateur avec les modifications
        $_SESSION['utilisateur'] = $utilisateur;

        // Assignation des variables utilisateur et admin à la page
        $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
        $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

        // Récupération des infos des réservation + du nombre de réservation
        $reservations = $dao->mesReservations($utilisateur->email);
        $nbResa = $dao->compteMesReservations($utilisateur->email);

        // Assignation des reservations du nbResa à la page profil pour l'affichage
        $view->assign('reservations',$reservations);
        $view->assign('nbResa',$nbResa);

        // Affichage d'un message de réussite
        $view->assign('message',"Vous venez de modifer votre mot de passe.");

        // Affichage de la page profil
        $view->display("profil.view.php");

      }else{ // Si la modification dans la bdd n'a pas eu lieu

        // Assignation des variables utilisateur et admin à la page
        $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
        $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

        // Affichage d'un message d'erreur
        $view->assign('erreur','Erreur : la modification n\'a pas été prise en compte.');

        // Affichage de la page profilModifierMDP
        $view->display('profilModifierMDP.view.php');

      }
    }

  }else{ // Pas d'entrée (impossible normalement)

    // Assignation des variables utilisateur et admin à la page
    $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
    $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

    // Affichage de la page profilModifierMDP
    $view->display('profilModifierMDP.view.php');

  }

}else{ // Si personne de connecté

  header('Location: connexion.ctrl.php');  // Redirection sur la page d'inscription car personne connecté

}

// Fermeture de la session
session_write_close();

 ?>
