<?php

// Inclusion du framework
include_once(__DIR__ . "/../framework/view.class.php");

// Inclusion des classes
include_once(__DIR__ . "/../model/client.class.php");
include_once(__DIR__ . "/../model/admin.class.php");
include_once(__DIR__ . "/../model/utilisateur.class.php");
include_once(__DIR__ . "/../model/DAO.class.php");
include_once(__DIR__ . "/../model/reservation.class.php");

// Ouverture de la session
session_start();

// Récupération ds l'utilisateur et de son role
if (isset($_SESSION['utilisateur'])) {
  $utilisateur = $_SESSION['utilisateur'];
} else {
  $utilisateur = null;
}

if (isset($_SESSION['admin'])) {
  $admin = $_SESSION['admin'];
} else {
  $admin = false;
}

// Fermeture de la session
session_write_close();

$dao = new DAO();

$lieux = $dao->getLieux();
if (isset($_POST['lieu'])) {
  $reservations = $dao->getFormations($_POST['lieu']);
  $nomLieuAffiche =  $_POST['lieu'];
} else {
  $reservations = $dao->getFormations($lieux[0]->nomLocal);
  $nomLieuAffiche = $lieux[0]->nomLocal;
}

$tableauReservations = array();

// On parcourt l'ensemble des formations
foreach ($reservations as $r) {
  // Mise en format de la date "YYYY-MM-DD HH:mm:ss" vers le format "YYYY-MM-DD-HH-mm-ss" pour faciliter l'utilisation dans l'agenda
  $r->dateDeb = str_replace(array(':', ' '), '-', $r->dateDeb);
  $r->dateFin = str_replace(array(':', ' '), '-', $r->dateFin);
  //On met dans un tableau les réservation qui ont pour colonne le nom des attribut
  $reservation = array(
    'idR' => $r->idR,
    'email' => $r->email->email,
    'lieu' => $r->lieu->nomLocal,
    'typeReservation' => $r->type,
    'nomReservation' => $r->nom,
    'dateDebut' => $r->dateDeb,
    'dateFin' => $r->dateFin,
    'nbPersonne' => $r->nb_pers,
    'placeRestante' => $r->placeRest,
    'prixTTC' => $r->prixT,
  );
  // Ajoute à la fin de $tableauReservations, le tableau $reservation
  array_push($tableauReservations, $reservation);
}

// Construction de la vue
$view = new View();

$view->assign('nomLieuAffiche', $nomLieuAffiche);
$view->assign('lieux', $lieux);
$view->assign('reservations', $tableauReservations);
$view->assign('utilisateur', $utilisateur);
$view->assign('admin', $admin);
$view->display("agendaFormation.view.php");
