<?php
session_start();

$client = new Google_Client();
$client->setAuthConfigFile('./config/googleOauth.json');
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/connexion.ctrl.php');
$client->setScopes(array(Google_Service_Plus::PLUS_ME));

if (! isset($_GET['code'])) {
  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

 ?>
