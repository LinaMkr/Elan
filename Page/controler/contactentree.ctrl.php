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

$view->assign('utilisateur',$utilisateur);
$view->assign('admin',$admin);

if(isset($_POST['prenom']) && isset($_POST['nom']) && isset($_POST['tel']) && isset($_POST['email']) && isset($_POST['message'])){
  // Si bien toutes les entrées sont bien présentes

  // Récupération des informations
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $tel = $_POST['tel'];
  $email = $_POST['email'];
  $message = $_POST['message'];

  // Récupération des emails de tous les admins
  $admins = $dao->getAdmins();

  // Envoies ddu formulaire de contact à tous les admins
  // S'il y a bien des admins
  if($admins!=null){
    $TOemail = '';
    for($i = 0; $i < count($admins) ; $i++){ // Permet de convertir la liste des adresse eamil en chaine de caractère séparer par des virgules
      if($i!=(count($admins)-1)){ // Si ce n'est pas le dernier élément
        $TOemail = $TOemail . $admins[$i] . ', ';
      }else{
        $TOemail = $TOemail . $admins[$i];
      }
    }

    // Message de l'email
    $message = 'Voici un nouveau message de '.$nom.' '.$prenom.' d\'adresse email : '.$email.' et de numero de téléphone : '.$tel." : \t". $message;
    $message = wordwrap($message, 70, "\r\n"); // Pour couper les lignes de plus de 70 caractères car impossible avec cette fonction

    // Header de l'email
    $header = 'From: '. $email . "\r\n" .
     'Reply-To: '. $email . "\r\n" .
     'X-Mailer: PHP/' . phpversion();

     // Envoie du mail
    $reussi = mail($TOemail,'Contact client Elan',$message,$header);

    if(!$reussi){ // Si l'email pas envoyé

      // Assignation d'un mesage d'erreur explicite
      $view->assign('message',"ERREUR : votre message n'a pas été envoyé aux administrateurs.");

    }else{ // Si email réussi

      // Assignation d'un message de réussite
      $view->assign('message',"REUSSI : votre message a été envoyé aux administrateurs.");
    }

  }else{ // Pas d'admin

    // Assignation d'un mesage d'erreur explicite
    $view->assign('message',"ERREUR : il n'y a pas d'administrateur a qui envoyer le message.");

  }

  // Affichage de la page message
  $view->display("message.view.php");

}else{ // Si pas de saisie

  header('Location: contact.ctrl.php');  // Redirection sur la page de contact

}

 ?>
