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

$reussi = -5; // Code d'erreur si pas d'email

if(isset($_POST['NewsLetter_Email'])){ // Si bien un email envoyé

  // Récupération de l'email
  $email = $_POST['NewsLetter_Email'];

  // Construction du DAO
  $dao = new DAO();
  // Ajout dans la bdd de son email
  $reussi = $dao->ajoutNL($email);
}


// Construction de la vue
$view = new View();

// Assignation des variables utilisateur et admin à la page
$view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
$view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin


if($reussi==1){ // Code de réussite 1 = ajout réussit
  // Ajout dans la bdd réussi

  // Sujet du mail
  $subject = 'NewsLetter Elan';

  // Message de l'email
  $message = 'Bonjour,\n vous venez de vous inscrire à la newsletter de l\'Elan,\r\n merci et à bientôt !';

  // Header de l'email
  $headers = 'From: newsletter@elan.fr' . "\r\n" . 'Reply-To: newsletter@elan.fr' . "\r\n" . 'X-Mailer: PHP/' . phpversion();

  mail($email,$subject,$message,$headers); // Envoie d'un mail au nouvel inscrit

  // Affichage d'un message de réussite
  $view->assign('message',"REUSSI : vous venez de vous inscrire à la NewsLetter.");

}else if($reussi==-1){ // Code d'erreur -1 = email déjà présent dans la NewsLetter

  // Affichage d'un message d'erreur explicite
  $view->assign('message',"IMPOSSIBLE : votre mail est déjà dans la NewsLetter.");

}else if($reussi==-5) { // Code d'erreur -5 = pas d'email

  // Affichage d'un message d'erreur explicite
  $view->assign('message',"IMPOSSIBLE : veuillez saisir un email.");

}else{ // Code d'erreur -2 = erreur inconnue

  // Affichage d'un message d'erreur explicite
  $view->assign('message',"IMPOSSIBLE : veuillez réessayer plus tard, erreur inconnue.");

}

// Affichage de la page message
$view->display("message.view.php");

 ?>
