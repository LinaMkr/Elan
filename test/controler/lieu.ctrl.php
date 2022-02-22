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

// Assignation des variables utilisateur et admin à la page
$view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
$view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

// Assignation de l'ensemble des lieux pour l'affichage
$view->assign('lieux',$dao->getLieux());

// Affichage de la page lieu
$view->display("lieu.view.php");

 ?>
