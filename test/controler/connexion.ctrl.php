<?php
require '/home/elan/Composer/vendor/autoload.php';

// Google OAuth 2.0
define('GOOGLE_ID', '499714263268-occ7kmc0rcnefhhbn4qnqrk3c3kcjqsp.apps.googleusercontent.com');
define('GOOGLE_SECRET', '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');

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

// Clefs Google à réutiliser dans la view
$view->assign('GOOGLE_ID', '499714263268-occ7kmc0rcnefhhbn4qnqrk3c3kcjqsp.apps.googleusercontent.com');
$view->assign('GOOGLE_SECRET', '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' );


if((isset($_POST['email']) and isset($_POST['mdp']) and $utilisateur==null)){ // Si personne de connecté et tentative de connexion

  // Récupération des données du formulaire
  $email = $_POST['email'];
  $mdp = md5($_POST['mdp']); // Hachage et récupération du mdp
  $utilisateur = $dao->getUtilisateur($email); // Récupération de l'utilisateur si il existe


  if($utilisateur == null){ // Login incorrect

    // Affichage d'un message d'erreur pour le login
    $view->assign('erreur',"Erreur : nom de login inconnu, &nbsp;<a href=\"./inscription.ctrl.php\"> vous inscrire ?</a>");

    // Assignation des variables utilisateur et admin à la page
    $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
    $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

    // Affichage de la page connexion
    $view->display('connexion.view.php');

  }else if ($utilisateur->mdp==$mdp){ // login et mdp correct

    $_SESSION['utilisateur'] = $utilisateur; // Mise de l'utilisateur dans la session
    $_SESSION['admin'] = $dao->estAdmin($utilisateur->email); // Récupération du role dans la session
    header('Location: accueil.ctrl.php'); // Redirection sur la page d'accueil car il est maintenant connecté

  }else{ // Mdp incorrect

    // Reinitialisation de l'utilisateur
    $utilisateur = null;

    // Affichage d'un message d'erreur pour le mot de passe
    $view->assign('erreur','Erreur : mot de passe inconnu');

    // Assignation des variables utilisateur et admin à la page
    $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
    $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

    // Affichage de la page connexion
    $view->display('connexion.view.php');

  }

}else if($utilisateur==NULL){ // Si personne de connecté et pas de tentative de connexion

  // Assignation des variables utilisateur et admin à la page
  $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
  $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

  // Affichage de la page connexion
  $view->display('connexion.view.php');

}else{ // Si quelqu'un de déjà connecté

  header('Location: profil.ctrl.php');  // Redirection sur la page d'accueil car déjà connecté

}

// API NON FONCTIONNEL

// Test de mise en place de l'API Google avec lien BDD
$client = new Google_Client();
$client->setAuthConfig('./config/googleOauth.json');
$client->setScopes(array(Google_Service_Plus::PLUS_ME));


if (isset($_SESSION['access_token']) && $_SESSION['access_token'])  {
  $client->setAccessToken($_SESSION['access_token']);
  $infos = new Google_Service_Plus($client);
  $user = creerUtilisateur($infos->email, "", "","");
} else {
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/connexionGoogle.ctrl.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

 ?>
