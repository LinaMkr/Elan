<?php

// Inclusion du framework
include_once(__DIR__."/../framework/view.class.php");

//Destruction de la session et donc du client pour cette session
session_start();
session_destroy();

// Redirection vers la page d'accueil
header('Location: accueil.ctrl.php');

 ?>
