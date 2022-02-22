<?php

// Inclusion du framework
include_once(__DIR__."/../framework/view.class.php");

// Inclusion des classes
include_once(__DIR__."/../model/client.class.php");
include_once(__DIR__."/../model/admin.class.php");
include_once(__DIR__."/../model/DAO.class.php");
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

    $impossible = true;

    // Assignation des variables utilisateur et admin à la page
    $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
    $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin


    if(isset($_GET['action'])){ // S'il y'a une action précisé

      // Récupération de l'action
      $action = $_GET['action'];

      if($action=='supprimer' && isset($_GET['nom'])){
        // Si volonté de supprimer et infos bien renseigné
        // lien valide avec les infos

          // Appel du DAO pour supprimer un prestataire
        $code = $dao->gestionPrestataire($_GET['nom'],0,'delete');

        // Appel du controleur principal pour afficher un message
        header("Location: adminGestionPrestataire.ctrl.php?action=supprimer&code=$code");

      }else if($action=='ajouter' && isset($_POST['nom']) && isset($_POST['prix'])){
        // Si volonté de supprimer et infos bien renseigné
        // Création possible

        // Appel du DAO pour créer un prestataire
        $code = $dao->gestionPrestataire($_POST['nom'],$_POST['prix'],'insert');

        // Appel du controleur principal pour afficher un message
        header("Location: adminGestionPrestataire.ctrl.php?action=ajouter&code=$code");

      }else if($action=='affecter' && isset($_POST['prestataire']) && isset($_POST['service'])){
        // Si volonté de supprimer et infos bien renseigné
        // Création possible

        // Appel du DAO pour assigner un équipement à une salle
        $code = $dao->assignerPrestataire($_POST['service'],$_POST['prestataire']);

        // Appel du controleur principal pour afficher un message
        header("Location: adminGestionPrestataire.ctrl.php?action=affecter&code=$code");

      }else{ // Pas possible normalement
        $impossible = true;
      }

    }else{ // Pas possible normalement
      $impossible = true;
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

?>
