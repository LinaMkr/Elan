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

// Construction du DAO
$dao = new DAO();

// Construction de la vue
$view = new View();

if(isset($_POST['nom']) and isset($_POST['prenom']) and isset($_POST['telephone']) and isset($_POST['entreprise']) and
  isset($_POST['email']) and isset($_POST['mdp'])  and isset($_POST['mdpConfirm']) and isset($_POST['adresse']) and $utilisateur==null){
    // Si personne de connecté et tentative d'inscription

    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $tel = $_POST['telephone'];
    $entreprise = $_POST['entreprise'];
    $email = $_POST['email'];
    $adresse = $_POST['adresse'];
    $mdp = $_POST['mdp'];

    if($mdp!=$_POST['mdpConfirm']){ // Si le mot de passe de confirmation n'est pas bon

      // Affichage d'un message d'erreur explicite
      $view->assign('erreur','Erreur : mot de passe de confirmation incorrect'); // Affichage d'un message d'erreur

      // Assignation des variables utilisateur et admin à la page
      $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
      $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin
      $view->display('inscription.view.php');

    }else{ // Si mot de passe correct

      // Creation du utilisateur
      $code = $dao->creerClient($email,$nom,$prenom,trim($tel),$adresse,$entreprise,$mdp);


      if($code==-1 || $code==-3){ //Email déjà dans la bdd donc impossible de l'inscrire

        // Affichage d'un message d'erreur explicite
        $view->assign('erreur',"Erreur : email déjà inscrit, &nbsp;<a href=\"./connexion.ctrl.php\"> vous connectez ?</a>");

        // Assignation des variables utilisateur et admin à la page
        $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
        $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

        // Affichage de la page inscription
        $view->display('inscription.view.php');

      }else if($code==-2){ // Code d'erreur -2 = erreur inconnue

        // Affichage d'un message d'erreur
        $view->assign('erreur',"Erreur : réessayer plus tard, erreur inconnue</a>");

        // Assignation des variables utilisateur et admin à la page
        $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
        $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

        // Affichage de la page inscription
        $view->display('inscription.view.php');

      }else{ // Incription OK

        // Récupération de la personne inscrite
        $utilisateur = $dao->getUtilisateur($email);

        $_SESSION['utilisateur'] = $utilisateur; // Mise de l'utilisateur dans la session
        $_SESSION['admin'] =  $dao->estAdmin($utilisateur->email); // Récupération de son role dans la session

        header('Location: accueil.ctrl.php');// Redirection sur la page d'accueil car il est maintenant connecté

      }
    }
}else if($utilisateur==null){ // Si personne de connecté et pas de tentative d'inscription

  // Assignation des variables utilisateur et admin à la page
  $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
  $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

  $view->display("inscription.view.php");

}else{ // Si quelqu'un de déjà connecté

  header('Location: profil.ctrl.php');  // Redirection sur la page d'accueil car déjà connecté

}

// Fermeture de la session
session_write_close();
