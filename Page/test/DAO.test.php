<?php

require_once(dirname(__FILE__) . '/../model/DAO.class.php');
require_once(dirname(__FILE__) . '/../model/client.class.php');
require_once(dirname(__FILE__) . '/../model/admin.class.php');


$db = new DAO();
$test = $db->creerReservation('admin@gmail.com', 'Salle de formation', 'formation', 'Communication', 12, '2022-02-01 09:00:00', '2022-02-01 17:00:00');
var_dump($test);
// $types = $db->getTypesResa();
// var_dump($types);
/*
$equipements = $db->getEquipementsLocal('bureau');
var_dump($equipements);

$article = $db->gestionArticle('Sujet t2','Une Description khghgkjhgkjhgjkhgjgkhhgkgggg gyygu yguygu yfu yfuyfu ygu yg ygi ygiyg uy y yf y','a@fr','insert');
var_dump($article);
/*
$gestion = $db->gestionFormation('economie',0.0,'','delete');
var_dump($gestion);
$gestion = $db->gestionFormation('economie',0.0,'','insert');
var_dump($gestion);
/*var_dump($db->getArticles());
$reservations = $db->mesReservations('c@fr');
var_dump($reservations);
$nbResa = $db->compteMesReservations('c@fr');
var_dump($nbResa);
$resas = $db->getReservations();
var_dump($resas);*/
/*
//Test création utilisateur, client et administrateur
echo "==================================================\n";
echo "TEST CREATION UTILISATEUR, CLIENT ET ADMININISTRATEUR\n";
echo "==================================================\n";

echo "Création d'un utilisateur utilisateur@gmail.com \n";
$creationU = $db->creerUtilisateur('utilisateur@gmail.com');
$utilisateur = $db->getUtilisateur('utilisateur@gmail.com');
echo "L'email de l'utilisateur doit être égale à utilisateur@gmail.com : " . $utilisateur->email . "\n";

echo "Création d'un client à partir de l'utilisateur utilisateur@gmail.com \n";
$creationC = $db->creerClient('utilisateur@gmail.com',"Josserarabe","Jordan","0123456789","Quelque part","indé","MotDePasse1");
$client = $db->getUtilisateur('utilisateur@gmail.com');
echo"Le prénom du client doit être Jordan : " . ($client->prenom) . "\n";

echo "Création d'un administrateur sans utilisateur préalable \n";
$creationA = $db->creerAdmin("administrateur@gmail.com","Le","Boss","0123456789","MotDePasse1");
$admin = $db->getUtilisateur('administrateur@gmail.com');
echo "L'administrateur a du être inséré parmi les utilisateurs, son email est administrateur@gmail.com : " . ($admin->email) . "\n";
echo"Le prénom de l'administrateur doit être Boss : " . ($admin->prenom) . "\n";

echo "\n\n";


//Test ajout NewsLetter

echo "==================================================\n";
echo "TEST AJOUT NEWSLETTER\n";
echo "==================================================\n";

echo "Essayons d'ajouter deux utilisateurs (utilisateur@gmail.com et un nouveau) dans la newsLetter \n";
$newsLetterU = $db->ajoutNL("utilisateur@gmail.com");
$newsLetterN = $db->ajoutNL("NL@gmail.com");
$listeNL = $db->getAbonnesNL();
foreach ($listeNL as $util) {
  echo "Présence de l'utilisateur " . $util->email . " dans la NewsLetter \n";
}
echo "\n\n";

//Test Méthodes gestion administrateur

echo "==================================================\n";
echo "TEST GESTION LIEUX\n";
echo "==================================================\n";

echo "Essayons de créer un lieux\n";

$creaLieu = $db->gestionLieux("bureau",300,25,"Accueil de la société avec salle de réunion",60.0,"insert");
echo "Le résultat devrait être 1 : " . $creaLieu . "\n";
echo "Le lieu à été crée.\nMaintenant changeons la description.\n";
$creaLieu = $db->gestionLieux("bureau",300,25,"Accueil de la société avec plusieurs salles de réunion",60.0,"update");
echo "Le résultat devrait être 1 : " . $creaLieu . "\n";
$lieu = $db->getLieu("bureau");
echo "La description du rez-de-chaussez est : " . $lieu->description . "\n";

echo "\n\n";

echo "==================================================\n";
echo "TEST GESTION EQUIPEMENT\n";
echo "==================================================\n";

echo "Essayons de créer un équipement\n";

$creaLieu = $db->gestionEquipement("projecteur",1,7.5,"insert");
echo "Le résultat devrait être 1 : " . $creaLieu . "\n";
echo "Le lieu à été crée.\nMaintenant changeons la description.\n";
$creaLieu = $db->gestionLieux("bureau",300,25,"Accueil de la société avec plusieurs salles de réunion",60.0,"update");
echo "Le résultat devrait être 1 : " . $creaLieu . "\n";
$lieu = $db->getLieu("bureau");
echo "La description du rez-de-chaussez est : " . $lieu->description . "\n";

echo "\n\n";


echo "==================================================\n";
echo "TEST GESTION SERVICES\n";
echo "==================================================\n";

echo "Création d'un service de petit-déjeuner et de transport \n";
$creationS = $db->gestionService("Petit-déjeuner",10.0,"insert");
$creationS = $db->gestionService("transport",25.0,"insert");
$services = $db->getServices();
foreach ($services as $service) {
  echo "Présence du service " . $service->intitule . " dans les services existants ayant pour prix " . $service->prix . "\n";
}
echo "Modification du prix du transport \n";
$creationS = $db->gestionService("transport",5.0,"update");
$services = $db->getServices();
foreach ($services as $service) {
  if($service->intitule == "transport")echo "Présence du service " . $service->intitule . " dans les services existants ayant pour prix " . $service->prix . "\n";
}

echo "Ajout d'un prestataire pour le service transport \n";
$creationPresta = $db->gestionPrestataire("transport","Didier",20.0,"insert");
echo "Test " . $creationPresta . " \n";

echo " Test assignation prestataire service : " . $db->assignerPrestataire('transport','Bosh') . "\n";

echo "\n\n";

echo "==================================================\n";
echo "TEST CREATION TYPE RESERVATION\n";
echo "==================================================\n";

echo "Création d'une formation sur la géographie \n";
$geo = $db->gestionFormation('géographie',2.29,'cours de géographie','insert');
echo "La création doit être faite (1) : " . $geo . "\n";

echo "Modifions sa description et son prix \n";
$geo = $db->gestionFormation('géographie',22.9,'cours de géo politique','update');
echo "La modification doit être faite (1) : " . $geo . "\n";

echo "\n\n";




echo "==================================================\n";
echo "TEST CREATION RESERVATION\n";
echo "==================================================\n";

echo "Création d'une réservation par le client Jordan Josserarabe \n";

$creationResa = $db->creerReservation("utilisateur@gmail.com","bureau","formation","economie",5,'2020-01-01 16:00:00','2020-01-01 17:00:00',70.5);
$resa = $db->getReservation($creationResa);
echo "La réservation de numéro d'identification " . $resa->idR . " est présente dans la base\n";

echo "Ajout d'un service de transport pour la réservation d'utilisateur@gmail.com \n";
$ajoutService = $db->ajoutService($creationResa,"transport");
$services = $db->getServicesResa($creationResa);
foreach ($services as $service) {
  echo "Présence du service " . $service . " dans les services demandés pour la reservation n°" . $creationResa . "\n";
}

echo "Ajout d'un prix à la réservation de jordan\n";
$prix = $db->calculPrixTotal($creationResa);
$resa = $db->getReservation($creationResa);
echo "Le prix est Maintenant de : " . $resa->prixT . "\n";

echo "Tentative d'ajout du client c@fr dans la réservation de jordan\n";
$rejClient = $db->rejoindreReservation("c@fr",$creationResa);
$clients = $db->getClients($creationResa);
foreach ($clients as $client) {
  echo "Présence du client " . $client . " dans la reservation " . $creationResa . "\n";
}

echo "Voyons les réservations d'utilisateur@gmail.com\n";
$reservations = $db->mesReservations('utilisateur@gmail.com');
foreach ($reservations as $resa) {
  echo "Jordan a réservée " . $resa->lieu->nomLocal . " du " . $resa->dateDeb . " au " . $resa->dateFin ." \n";
}

echo "\n\n";

echo "==================================================\n";
echo "TEST METHODES AGENDAS\n";
echo "==================================================\n";

echo "Voyons toutes les réservations du bureau"
$formations = $db->getFormations("bureau");

foreach ($formations as $formation) {
  echo " Une formation aura lieu du  " . $formation->Datedeb . "\n";
}

echo "\n\n";
/*echo "TEST PREPARE \n";
$test = $db->testPrepare();
echo "Result : " . $test[0][0] . "\n";*/
