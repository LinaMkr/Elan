<?php

// Inclusion du framework
include_once(__DIR__."/../framework/view.class.php");

// Inclusion des classes
include_once(__DIR__."/../model/client.class.php");
include_once(__DIR__."/../model/DAO.class.php");
include_once(__DIR__."/../model/admin.class.php");
include_once(__DIR__."/../model/utilisateur.class.php");


$dao = new DAO();

// Construction de la vue
$view = new View();

/* Fonction modification qui permet de compacter le code de modification d'un admin et d'un client
   Elle effectue les modifications en fonction de la précédente string et des infos passés en paramètres
   Si y'a des modifications, elle assigne un mesage à la page profil. */
function modification(string $nom, string $prenom, string $tel, string $entreprise,string $adresse, Utilisateur $utilisateur,bool $estAdmin){

  // Récupération du DAO et de la view du programme principal
  global $dao;
  global $view;

  // Initialisation du texte pour les modifications
  $texte = 'Vous venez de modifier ';
  // Initialisation de la variable de modification à false
  $modification = false;

  // ================================
  // Commun a tous les utilisateurs
  // ================================

  // Si le nom a été modifié
  if($utilisateur->nom != $nom){
    // Modification dans la bdd avec l'appel du DAO
    $reussi = $dao->setNomClient($utilisateur,$nom);
    if($reussi){ // Si modification dans la bdd réussi
      $utilisateur->nom = $nom; // Modification de l'objet utilisateur
      $modification = true; // Mise de la variable de modification à true
      $texte = $texte."votre nom, "; // Mise à jour du message à afficher
    }
  }
  // Si le prenom a été modifié
  if($utilisateur->prenom != $prenom){
    // Modification dans la bdd avec l'appel du DAO
    $reussi = $dao->setPrenomClient($utilisateur,$prenom);
    if($reussi){ // Si modification dans la bdd réussi
      $utilisateur->prenom = $prenom; // Modification de l'objet utilisateur
      $modification = true; // Mise de la variable de modification à true
      $texte = $texte."votre prenom, "; // Mise à jour du message à afficher
    }
  }
  // Si le tel a été modifié
  if($utilisateur->tel != $tel){
    // Modification dans la bdd avec l'appel du DAO
   $reussi = $dao->setTelClient($utilisateur,$tel);
   if($reussi){ // Si modification dans la bdd réussi
     $utilisateur->tel = $tel; // Modification de l'objet utilisateur
     $modification = true; // Mise de la variable de modification à true
     $texte = $texte."votre téléphone, "; // Mise à jour du message à afficher
   }
  }

  // Juste pour ceux qui ne sont pas admin donc client
  if(!$estAdmin){ // Si pas admin

    // Si l'entreprise a été modifié
    if($utilisateur->entreprise != $entreprise){ // Si pas d'erreur et changement d'entreprise
      // Modification dans la bdd avec l'appel du DAO
      $reussi = $dao->setEntrepriseClient($utilisateur,$entreprise);
      if($reussi){ // Si modification dans la bdd réussi
        $utilisateur->entreprise = $entreprise; // Modification de l'objet utilisateur
        $modification = true; // Mise de la variable de modification à true
        $texte = $texte."votre entreprise, "; // Mise à jour du message à afficher
      }
    }
    // Si l'adresse a été modifié
    if($utilisateur->adresse != $adresse){ // Si pas d'erreur et changement d'adresse
      // Modification dans la bdd avec l'appel du DAO
      $reussi = $dao->setAdresseClient($utilisateur,$adresse);
      if($reussi){ // Si modification dans la bdd réussi
        $utilisateur->adresse = $adresse; // Modification de l'objet utilisateur
        $modification = true; // Mise de la variable de modification à true
        $texte = $texte."votre adresse, "; // Mise à jour du message à affichers
      }
    }

  }

  if($modification){ // Si modifications effectuées

    // Assignation d'un message pour savoir les modifications effectuées
    $view->assign('message',$texte);

    // Mise dans la session de l'utilisateur avec les modifications
    $_SESSION['utilisateur'] = $utilisateur;
  }
}
// ========================================================================

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


if(isset($_POST['nom']) and isset($_POST['prenom']) and isset($_POST['telephone']) and
   isset($_POST['entreprise']) and isset($_POST['adresse']) and $utilisateur!=null and !$admin){
  // Si un client est connecté et qu'il veut modifer quelque chose

  // Récupération des données du formulaire
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $tel = $_POST['telephone'];
  $adresse = $_POST['adresse'];
  $entreprise = $_POST['entreprise'];

  // On appelle la fonction modification commune à un admin et à un client
  modification($nom,$prenom,$tel,$entreprise,$adresse,$utilisateur,false);

  // Assignation des variables utilisateur et admin à la page
  $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
  $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

  // Récupération des infos des réservation + du nombre de réservation
  $reservations = $dao->mesReservations($utilisateur->email);
  $nbResa = $dao->compteMesReservations($utilisateur->email);

  // Assignation des reservations du nbResa à la page profil pour l'affichage
  $view->assign('reservations',$reservations);
  $view->assign('nbResa',$nbResa);

  // Affichage de la page profil
  $view->display("profil.view.php");

}else if(isset($_POST['nom']) and isset($_POST['prenom']) and isset($_POST['telephone']) and $utilisateur!=null and $admin){
  // Si un admin est connecté et qu'il veut modifer quelque chose

  // Récupération des données du formulaire
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $tel = $_POST['telephone'];

  // On appelle la fonction modification commune à un admin et à un client
  modification($nom,$prenom,$tel,'','',$utilisateur,true);

  // Assignation des variables utilisateur et admin à la page
  $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
  $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

  // Récupération des infos des réservation + du nombre de réservation
  $reservations = $dao->mesReservations($utilisateur->email);
  $nbResa = $dao->compteMesReservations($utilisateur->email);

  // Assignation des reservations du nbResa à la page profil pour l'affichage
  $view->assign('reservations',$reservations);
  $view->assign('nbResa',$nbResa);

  // Affichage de la page profil
  $view->display("profil.view.php");

}else if($utilisateur!=null){ // Un utilisateur est déjà connecté et pas de tentative de connexion

  // Assignation des variables utilisateur et admin à la page
  $view->assign('utilisateur',$utilisateur); // envoie de l'objet utilisateur ou null
  $view->assign('admin',$admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

  // Affichage de la page profilModif
  $view->display("profilModif.view.php");

}else{ // Aucun utilisateur de connecté

  header('Location: connexion.ctrl.php');  // Redirection sur la page d'accueil car déjà connecté

}

// Fermeture de la session
session_write_close();


 ?>
