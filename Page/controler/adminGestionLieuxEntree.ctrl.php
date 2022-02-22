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

      if($action=='supprimer' && isset($_GET['nomLocal'])){
        // Si volonté de supprimer et infos bien renseigné
        // lien valide avec les infos pour suppression d'un expert

        // Appel du DAO pour supprimer la ligne choisie
        $code = $dao->gestionLieux($_GET['nomLocal'],0,0,'',0,'delete');

        // Appel du controleur principal pour afficher un message
        header("Location: adminGestionLieux.ctrl.php?action=supprimer&code=$code");

      }else if($action=='ajouter' && isset($_POST['nomLocal']) && isset($_POST['surface']) && isset($_POST['prix']) && isset($_POST['nb_place']) && isset($_POST['description'])){
        // Si volonté de supprimer et infos bien renseigné
        // Création possible d'un expert

        // Appel du DAO pour créer un équipement
        $code = $dao->gestionLieux($_POST['nomLocal'],$_POST['surface'],$_POST['nb_place'],$_POST['description'],$_POST['prix'],'insert');

        // Appel du controleur principal pour afficher un message
        header("Location: adminGestionLieux.ctrl.php?action=ajouter&code=$code");
      }

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
