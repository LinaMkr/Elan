<?php

// Inclusion du framework
include_once(__DIR__ . "/../framework/view.class.php");

// Inclusion des classes
include_once(__DIR__ . "/../model/client.class.php");
include_once(__DIR__ . "/../model/DAO.class.php");
include_once(__DIR__ . "/../model/reservation.class.php");
include_once(__DIR__ . "/../model/admin.class.php");
include_once(__DIR__ . "/../model/lieu.class.php");
include_once(__DIR__ . "/../model/utilisateur.class.php");

// Ouverture de la session
session_start();

// Récupération de l'utilisateur
if (isset($_SESSION['utilisateur'])) {
  $utilisateur = $_SESSION['utilisateur'];
} else {
  $utilisateur = null; // pas d'utilisateur connecté par défaut
}

// Récupération du role de l'utilisateur
if (isset($_SESSION['admin'])) {
  $admin = $_SESSION['admin'];
} else {
  $admin = false; // utilisateur non admin par défaut
}

// Fermeture de la session
session_write_close();

// Construction du DAO
$dao = new DAO();

// Construction de la vue
$view = new View();

// Assignation des variables utilisateur et admin à la page
$view->assign('utilisateur', $utilisateur); // envoie de l'objet utilisateur ou null
$view->assign('admin', $admin); // envoie du boolean si l'utilisateur qui est connecté, est un admin

// Si quelqu'un de connecté et un idR dans la query string
if ($utilisateur != null && isset($_GET['idR'])) {

  // On récupère l'idR de la query string
  $idR = $_GET['idR'];

  // On récupère la réservation d'id idR
  $resa = $dao->getReservation($idR);


  if ($resa != null) { // Si la reservation existe

    $view->assign('reservation', $resa);
    $view->assign('services', $dao->getServicesResa($resa->idR)); // Envoie de tous les services
    $view->assign('equipements', $dao->getEquipementsLocal($resa->lieu->nomLocal)); // Envoie de tous les equipements
    $view->display("paiement.view.php");
  } else { // Si la réservation n'existe pas

    // Assignation d'un mesage d'erreur explicite
    $view->assign('message', "ERREUR : votre réservation n'existe pas.");

    // Affichage de la page message
    $view->display("message.view.php");
  }
} else if ($utilisateur != null) { // Si quelqu'un de connecté mais pas d'idR dans la query string
  // (Cas impossible normalement)

  // Assignation d'un mesage d'erreur explicite
  $view->assign('message', "ERREUR : vous ne pouvez pas accéder à la page de paiement sans réserver.");

  // Affichage de la page message
  $view->display("message.view.php");
} else { // Si personne de connecté

  header('Location: connexion.ctrl.php');  // Redirection sur la page d'inscription car personne connecté

}

/*
// PayPal (à replacer)
curl -v POST 'https://api-m.sandbox.paypal.com/v1/oauth2/token' \

  -H "Accept: application/json" \
  -H "Accept-Language: fr_FR" \
  -u "AYRv_u0lSMOGCyBeEPtRm6UC5RafJ6fbhQT_u6VYlREhkhgPAYXRftC_BAf6Pe6hQNfliaNkFz7A9i_K:EMk4ZmAE_xRo-UTT5wuHijzac7w_QhQNOJvtvaKZFxt90OMckEGWh8kjqWxTEN5u0Tnx6jtlTyB8XGhc" \
  -d "grant_type=client_credentials"

curl -v -X POST 'https://api-m.sandbox.paypal.com/v2/checkout/orders' \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer Access-Token" \
  -d '{
    "intent": "CAPTURE",
    "purchase_units": [
      {
        "amount": {
          "currency_code": "USD",
          "value": "100.00"
        }
      }
    ]
  }'


//traduction du curl en php
  $token = curl_init("https://api-m.sandbox.paypal.com/v1/oauth2/token");
  $clientId = "AYRv_u0lSMOGCyBeEPtRm6UC5RafJ6fbhQT_u6VYlREhkhgPAYXRftC_BAf6Pe6hQNfliaNkFz7A9i_K";
  $secret = "EMk4ZmAE_xRo-UTT5wuHijzac7w_QhQNOJvtvaKZFxt90OMckEGWh8kjqWxTEN5u0Tnx6jtlTyB8XGhc";


  curl_setopt($token, CURLOPT_HEADER, false);
  curl_setopt($token, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($token, CURLOPT_POST, true);
  curl_setopt($token, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($token, CURLOPT_USERPWD, $clientId.":".$secret);
  curl_setopt($token, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

  $headers = array();
  $headers[] = 'Accept: application/json';
  $headers[] = 'Accept-Language: fr_FR';
  curl_setopt($token, CURLOPT_HTTPHEADER, $headers);

  $result = curl_exec($token);

  if(empty($result))die("Erreur: Pas de réponse.");
  else
  {
      $json = json_decode($result);
  }

  curl_close($token);

*/
