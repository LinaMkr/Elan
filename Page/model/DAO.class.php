<?php

/*Liste des fonctions sql

creerUtilisateur(emailU string, nomU string,prenomU string, telU string) returns boolean = vrai si l'utilisateur est créer
creerClient(newEmail string,newNom string,newPrenom string,newTel string,newAdresse string,newEnt string,newMdp string) returns boolean  = vrai si le client est créer
creerAdmin(newEmail string,newNom string,newPrenom string,newTel string,newMdp string) returns boolean = vrai si l'admin est créer

*/

require_once(dirname(__FILE__).'/client.class.php');
require_once(dirname(__FILE__).'/admin.class.php');
require_once(dirname(__FILE__).'/utilisateur.class.php');
require_once(dirname(__FILE__)."/typeReservation.class.php");
require_once(dirname(__FILE__)."/reservation.class.php");
require_once(dirname(__FILE__)."/lieu.class.php");
require_once(dirname(__FILE__).'/service.class.php');
require_once(dirname(__FILE__).'/equipement.class.php');
require_once(dirname(__FILE__).'/article.class.php');

Class DAO {
    private PDO $db;
    // Le constructeur ouvre l'acces à la BD
    public function __construct() {

        try {
          $this->db = new PDO("pgsql:host=localhost;port=5432;dbname=elan;",'elan','elanm3301',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
          if (!$this->db) {
              die ("Database error: cannot open \n");
          }
        } catch (PDOException $e) {
        die("PDO Error :".$e->getMessage()."\n");
        }
    }

    // FINI
    public function testPrepare(){
      $sql = "Select * from Utilisateur";
      $sth = $this->db->prepare($sql);
      $sth->execute(array());
      $result = $sth->fetchAll();
      return $result;
    }


    //PARTIE CREATION + GETTERS OBJETS UTILISATEUR LIEUX RESERVATION


    // Retourne un code d'erreur ou de réussite pour l'inscription d'un client
    // -1 email présent dans la table Admin / -3 email présent dans la table client / -2 erreur inconnu / 1 réussite
    public function creerClient(string $email, string $nom,string $prenom, string $tel,string $adresse, string $ent, string $mdp) : int{
        //On hache le mot de passe pour ne pas stocker de mot de passe en clair
        $mdp_hache = md5($mdp);
        //Appelle de la fonction creerClient qui insère les n-uplets dans la table client (et table utilisateur si pas connu de la base)
        $request = "SELECT creerClient('$email','$nom','$prenom','$tel','$adresse','$ent','$mdp_hache')";
        $query = $this->db->query($request);
        //Vérification du bon fonctionnement de la méthode (!= NULL)
        if($query){
          //Retourne le code d'éxécution (-1,-2,-3 ou 1)
          return $query->fetchAll(PDO::FETCH_NUM)[0][0];
        }
        //Erreur
        return -2;
    }

    // Retourne un code d'erreur ou de réussite pour l'inscription d'un administrateur
    // -1 email présent dans la table Client / -3 email présent dans la table Admin / -2 erreur inconnu / 1 réussite
    public function creerAdmin(string $email, string $nom,string $prenom, string $tel,string $mdp) : int{
        //On hache le mot de passe pour ne pas stocker de mot de passe en clair
        $mdp_hache = md5($mdp);
        //Appelle de la fonction creerAdmin qui insère les n-uplets dans la table admin (et table utilisateur si pas connu de la base)
        $request = "SELECT creerAdmin('$email','$nom','$prenom','$tel','$mdp_hache')";
        $query = $this->db->query($request);
        //Vérification du bon fonctionnement de la méthode (!= NULL)
        if($query){
          //Retourne le code d'éxécution (-1,-2,-3 ou 1)
          return $query->fetchAll(PDO::FETCH_NUM)[0][0];
        }
        return -2;
    }

    // Retourne un code d'erreur ou de réussite pour l'inscription d'un utilisateur
    // -1 email présent dans la table Utilisateur / -2 erreur inconnu / 1 réussite
    public function creerUtilisateur(string $email,string $nom = NULL, string $prenom = NULL, string $tel = NULL) : int{
      //Appelle de la fonction SQL creerUtilisateur qui inère les n-uplets dans la table Utilisateur
      $request = "Select creerUtilisateur('$email','$nom','$prenom','$tel')";
      $query = $this->db->query($request);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        //Retourne le code d'éxécution (-1,-2 ou 1)
        return $query->fetchAll(PDO::FETCH_NUM)[0][0];
      }
      return -2;
    }

    //{$email appartient à un utilisateur} => retourne un client ou un administrateur
    public function getUtilisateur(string $email) : ?Utilisateur {
        //Procédure si l'utilisateur est un client
        if ($this->estClient($email)){
          //Appelle de la fonction SQL getClient qui retourne les infos joints de client et utilisateur pour l'email $email
          $request = "SELECT * from getClient('$email')";
          $st = $this->db->query($request);
          //Initialisation du résultat à NULL
          $out = null;
          //Vérification du bon fonctionnement de la méthode (!= NULL)
          if($st){
            $result = $st->fetchAll(PDO::FETCH_NUM)[0]; // Probleme avec le FETCH_CLASS avec Postgres
            // Creation du client si il existe
            if($result==NULL){
              //Aucun client n'a été récupérer
              return $out;
            }
            // Stock les n-uplets dans un objet Client
            $out = new Client($result[0],$result[1],$result[2],$result[3],$result[4],$result[5],$result[6]);
          }
          // renvoie le client ou null
          return $out;
        }
        //Procédure si l'utilisateur est un administrateur
        else if ($this->estAdmin($email)){
          //Appelle de la fonction SQL getAdmin qui retourne les infos joints d'admin et utilisateur pour l'email $email
          $request = "SELECT * from getAdmin('$email')";
          $st = $this->db->query($request);
          //Initialisation du résultat à NULL
          $out = null;
          //Vérification du bon fonctionnement de la méthode (!= NULL)
          if($st){
            $result = $st->fetchAll(PDO::FETCH_NUM)[0]; // Probleme avec le FETCH_CLASS avec Postgres
            // Aucun n-uplets n'est lié à l'émail $email
            if($result==NULL){
              //Renvoie null
              return $out;
            }
            // Stock les n-uplets dans un objet Admin
            $out = new Admin($result[0],$result[1],$result[2],$result[3],$result[4]);
          }
          // renvoie l'Admin ou null
          return $out;
        }
        //L'email n'appartient ni à un compte client ni à un compte admin
        else{
          //On récupère les informations liés à l'émail $email dans la table Utilisateur
          $request = "SELECT * from Utilisateur where email = '$email'";
          $st = $this->db->query($request);
          //Initialisation du résultat à NULL
          $out = null;
          //Vérification du bon fonctionnement de la méthode (!= NULL)
          if($st){

            $result = $st->fetchAll(PDO::FETCH_NUM)[0]; // Probleme avec le FETCH_CLASS avec Postgres
            // Aucun n-uplets n'est lié à l'émail $email
            if($result==NULL){
              return $out;
            }
            //On stock les n-uplets dans un objet Utilisateur
            $out = new Utilisateur($result[0],$result[1],$result[2],$result[3]);
          }
          //renvoie l'utilisateur ou null
          return $out;
        }
    }

    // Renvoie un objet Lieu de nomLocal $local
    public function getLieu(string $local) : ?Lieu{
        //Récupération des n-uplets du lieu pour le lieu $local
        $request = "SELECT * from Lieux where nomLocal = '$local'";
        $query = $this->db->query($request);
        //Vérification du bon fonctionnement de la méthode (!= NULL)
        if($query){
          $result = $query->fetchAll(PDO::FETCH_NUM)[0];
          //Le lieu existe
          if($result){
            //On renvoie les n-uplets dans un objet Lieu
            return new Lieu($result[0],$result[1],$result[2],$result[3],$result[4]);
          }
        }
        //Renvoie null si aucun résultat ou si mauvais fonctionnement
        return NULL;
    }
    // Renvoie la liste de tous les lieux de la table Lieux
    public function getLieux() : array{
      //Initialisation d'un tableau pour le retour
      $tab = array();
      //On récupère tous les lieux
      $request = "Select * from Lieux";
      $query = $this->db->query($request);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        $lieux = $query->fetchAll();
        //Des lieux sont présents dans la table Lieux
        if($lieux){
          //On parcours les lignes de retour pour pouvoir créer des objets Lieu
          foreach($lieux as $lieu){
              //On stock dans $tab des objets lieu grâce à la méthode getLieu
              $tab[] = $this->getLieu($lieu[0]);
          }
        }
      }
      //Renvoie un tableau d'objets Lieu ou un tableau vide
      return $tab;
    }

    // Renvoie un objet Reservation d'idR $idr
    public function getReservation(int $idr) : ?Reservation{
        //Récupération des n-uplets de la réservation $idr
        $request = "Select * from reservation where idr = '$idr'";
        $query = $this->db->query($request);
        //Vérification du bon fonctionnement de la méthode (!= NULL)
        if($query){
          $resa = $query->fetchAll(PDO::FETCH_NUM)[0];
          //Présence d'un résultat (!= NULL)
          if($resa){
            //On récupère l'objet Utilisateur correspondant à l'émail $resa[1]
            $client = $this->getUtilisateur($resa[1]);
            //On récupère l'objet Lieu correspondant au nomLocal $resa[2]
            $local = $this->getLieu($resa[2]);
            //On renvoie l'objet Reservation contenant kes objets Client et Lieu ainsi que les n-uplets
            return new Reservation($idr,$client,$local,$resa[3],$resa[4],$resa[5],$resa[6],$resa[7],$resa[8],$resa[9]);
          }
        }
        //Retourne null si aucun résultat ou mauvais fonctionnement
        return NULL;
    }

    //Retourne un objet Article en fonction du titre ou null
    public function getArticle(string $titre) : ?Article{
      //Récupération des n-uplets de l'article $titre
      $request = "Select * from article where titre = '$titre'";
      $query = $this->db->query($request);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        $art = $query->fetchAll(PDO::FETCH_NUM)[0];
        //Présence d'un résultat (!= NULL)
        if($art){
          //On récupère l'objet Utilisateur correspondant à l'émail $art[2]
          $auteur = $this->getUtilisateur($art[2]);
          //On renvoie l'objet Reservation contenant kes objets Client et Lieu ainsi que les n-uplets
          return new Article($titre,$art[1],$auteur);
        }
      }
      //Retourne null si aucun résultat ou mauvais fonctionnement
      return NULL;
    }

    //Retourne la liste de tous les articles
    public function getArticles() : array{
      //Initialisation d'un tableau pour le retour
      $tab = array();
      $requete = "Select titre from Article";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        $articles = $query->fetchAll(PDO::FETCH_NUM);
        //On parcours les résultats pour créer un tableau d'objets reservation (seulement si $reservations != NULL)
        foreach($articles as $article){
            //Création et insertion d'un objet reservation dans le tableau grâce à la methode getReservation
            $tab[] = $this->getArticle($article[0]);
        }
      }
      //Retourne le tableau d'objets reservation ou tableau vide
      return $tab;
    }


    // ===============================================
    // METHODES DES AGENDAS
    // ===============================================

    //Retourne la liste de toutes les réservations du lieu $local
    public function getReservations(string $local) : array{
      //Initialisation d'un tableau pour le retour
      $tab = array();
      //On récupère les réservations
      $request = "SELECT * from reservation where nomLocal = '$local'";
      $query = $this->db->query($request);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        $reservations = $query->fetchAll(PDO::FETCH_NUM);
        //On parcours les résultats pour créer un tableau d'objets reservation (seulement si $reservations != NULL)
        foreach($reservations as $reservation){
            //Création et insertion d'un objet reservation dans le tableau grâce à la methode getReservation
            $tab[] = $this->getReservation($reservation[0]);
        }
      }
      //Retourne le tableau d'objets reservation ou tableau vide
      return $tab;
    }

    //Retourne la liste de toutes les formations pour un lieu
    public function getFormations(string $lieu) : array{
      //Initialisation d'un tableau pour le retour
      $tab = array();
      //Récupération de toutes les réservations de type formation du lieu
      $request = "SELECT * from reservation where type = 'formation' and nomLocal = '$lieu'";
      $query = $this->db->query($request);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        $formations = $query->fetchAll(PDO::FETCH_NUM);
        //On parcours les résultats pour créer un tableau d'objets reservation (seulement si $formations != NULL)
        foreach($formations as $formation){
          //Création et insertion d'un objet reservation dans le tableau grâce à la methode getReservation
          $tab[] = $this->getReservation($formation[0]);
        }
      }
      //Retourne le tableau d'objets reservation ou tableau vide
      return $tab;
    }

    //Retourne la liste de tous les évenements pour un lieu
    public function getEvenements(string $lieu) : array{
      //Initialisation d'un tableau pour le retour
      $tab = array();
      //Récupération de toutes les réservations de type evenement du lieu
      $request = "SELECT * from reservation where type = 'evenement' and nomLocal = '$lieu'";
      $query = $this->db->query($request);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        $evenements = $query->fetchAll(PDO::FETCH_NUM);
        //On parcours les résultats pour créer un tableau d'objets reservation (seulement si $evenements != NULL)
        foreach($evenements as $evenement){
          //Création et insertion d'un objet reservation dans le tableau grâce à la methode getReservation
          $tab[] = $this->getReservation($evenement[0]);
        }
      }
      //Retourne le tableau d'objets reservation ou tableau vide
      return $tab;
    }

    // Liste des clients inscrits à la reservation idr
    public function getClientsResa(int $idr) : array {
      //Initialisation d'un tableau pour le retour
      $tab = array();
      //Appelle de la fonction SQL getClientsResa retournant une liste d'emails pour la réservation $idr
      $request = "SELECT getClientsResa('$idr')";
      $query = $this->db->query($request);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        $emails = $query->fetchAll();
        //Initialisation du tableau de retour avec les emails
        foreach($emails as $client){
            $tab[] = $client[0];
        }
      }
      //Retourne le tableau d'emails ou tableau vide
      return $tab;
    }

    // ===============================================
    // METHODES EXECUTEES AUTOMATIQUEMENT
    // ===============================================

    //Retourne une liste d'emails pour envoi d'un mail de rappel
    public function getEmailsRappel() : array{
      //Appelle de la fonction SQL getEmailsRappel() qui retourne une liste d'emails ayant une réservation dans 2 jours
      $query = $this->db->query("Select * from getEmailsRappel()");
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        //Retourne la liste des emails
        return $query->fetchAll();
      }
      //Retourne une liste vide si mauvais fonctionnement
      return array();
    }

    //Retourne une liste d'emails pour envoi d'un mail pour enquête de satistaction
    public function getEmailsRemerciement() : array{
      //Appelle de la fonction SQL getEmailsRemerciement() qui retourne une liste d'emails ayant assisté à une réservation 1 jours avant
      $query = $this->db->query("Select * from getEmailsRemerciement()");
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        //Retourne la liste des emails
        return $query->fetchAll();
      }
      //Retourne une liste vide si mauvais fonctionnement
      return array();
    }

    // Retourne un code d'erreur ou l'idR pour la création d'une réservation de coWorking
    public function coWorking(string $email, string $nomLocalCW) : int {
      //Appelle de la fonction SQL coWorking qui créer une réservation de type co-working
      $query = $this->db->query("Select * from coWorking('$email','$nomLocalCW')");
      if($query){
        //Retourne le code d'éxécution
        return $query->fetchAll(PDO::FETCH_NUM)[0][0];
      }
      //Erreur
      return -2;
    }

    // ===============================================
    // METHODES POUR GESTION CLIENT
    // ===============================================

    //Retourne une liste de réservations où le client $email est inscrit
    public function mesReservations(string $email) : array {
      //Initialisation d'un tableau pour le retour
      $tab = array();
      //Récupération des n-uplets des réservations du client $email
      $request = "SELECT Reservation.idr from Reservation, ClientReservation where Reservation.idR = ClientReservation.idR and emailC = '$email'";
      $query = $this->db->query($request);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        $reservations = $query->fetchAll(PDO::FETCH_NUM);
        //On parcours les résultats pour créer un tableau d'objets reservation (seulement si $reservations != NULL)
        foreach($reservations as $resa){
          //Création et insertion d'un objet reservation dans le tableau grâce à la methode getReservation
          $tab[] = $this->getReservation($resa[0]);
        }
      }
      //Retourne le tableau d'objets reservation ou tableau vide
      return $tab;
    }

    //Retourne une liste d'objets Reservation que le client $email peut rejoindre
    //Possibilité de trier les résultats en fonction d'une valeur spécifique à un type (ex : type = 'formation')
    public function getReservationsRejoignables(string $email,string $type = NULL, string $value = NULL) : array{
      //Initialisation d'un tableau pour le retour
      $tab = array();
      //Vérification de la présence des valeurs
      if($type && $value){
        //Requête avec condition supplémentaire demandé par l'utilisateur
        $request = "Select idr from reservation where idr not in (select idr from ClientReservation where emailC = '$email') and dateDeb >= current_date and type != 'location' and placerest>0 and $type = $value";
      }
      //Requête sans conditions supplémentaires
      $request = "Select idr from reservation where idr not in (select idr from ClientReservation where emailC = '$email') and dateDeb >= current_date and type != 'location' and placerest>0";

      $query = $this->db->query($request);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        $reservations = $query->fetchAll(PDO::FETCH_NUM);
        //On parcours les résultats pour créer un tableau d'objets reservation (seulement si $reservations != NULL)
        foreach($reservations as $resa){
          //Création et insertion d'un objet reservation dans le tableau grâce à la methode getReservation
          $tab[] = $this->getReservation($resa[0]);
        }
      }
      //Retourne le tableau d'objets reservation ou tableau vide
      return $tab;
    }

    //Retourne le nombre de réservation dont est inscrit le client $email
    public function compteMesReservations(string $email) : int{
      $requete = "Select count(*) from ClientReservation where emailC = '$email'";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if ($query){
        //Retourne le nombre trouvé
        return $query->fetchAll()[0][0];
      }
      //Retourne 0 si mauvais fonctionnement
      return 0;
    }

    //Retourne la liste de tous les équipements sous forme d'objet Equipement
    public function getEquipements() : array{
      //Initialisation d'un tableau pour le retour
      $tab = array();
      $request = "Select * from equipement";

      $query = $this->db->query($request);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        $equipements = $query->fetchAll(PDO::FETCH_NUM);
        //On parcours les résultats pour créer un tableau d'objets equipement (seulement si $equipements != NULL)
        foreach($equipements as $equipement){
            //Création et insertion d'un objet equipement dans le tableau
            $tab[] = new Equipement($equipement[0], $equipement[1], $equipement[2]);
        }
      }
      //Retourne le tableau d'objets equipement ou tableau vide
      return $tab;
    }

    //Retourne la liste de tous les équipements sous forme d'objet Equipement de la salle $nomLocal
    public function getEquipementsLocal(string $nomLocal) : array{
      //Initialisation d'un tableau pour le retour
      $tab = array();
      $request = "Select * from equipement where nomEquip in (select nomEquip from LieuxEquipements where nomLocal = '$nomLocal')";

      $query = $this->db->query($request);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        $equipements = $query->fetchAll(PDO::FETCH_NUM);
        //On parcours les résultats pour créer un tableau d'objets equipement (seulement si $equipements != NULL)
        foreach($equipements as $equipement){
            //Création et insertion d'un objet equipement dans le tableau
            $tab[] = new Equipement($equipement[0], $equipement[1], $equipement[2]);
        }
      }
      //Retourne le tableau d'objets equipement ou tableau vide
      return $tab;
    }

    //Retourne un code d'erreur ou de réussite sur la possibilité de créer une réservation aux dates demandés pour le lieu voulu
    public function valideDates(string $dateDebut, string $dateFin, string $lieu) : int{
      //Appelle de la fonction SQL valideDates qui vérifie la présence d'une réservation au lieu et date demandés
      $requete = "Select valideDates('$dateDebut','$dateFin','$lieu')";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        //Retourne le code d'éxécution (-1,-2 ou 1)
        return $query->fetchAll(PDO::FETCH_NUM)[0][0];
      }
      //Erreur
      return -2;
    }

    // Retourne un code d'erreur ou de réussite si la réservation à été créer
    // => -1 trop de participant / -2 erreur inconnu / valeur de l'idR si réussite
    public function creerReservation(string $email, string $local,string $type,string $nom,int $nbpart, string $dateD, string $dateF) : int{
        //Appelle la fonction SQL creerReservation qui créer la réservation et renvoie le code de retour
        $request = "SELECT creerReservation('$email','$local','$type','$nom',$nbpart,'$dateD','$dateF')";
        $query = $this->db->query($request);
        //Vérification du bon fonctionnement de la méthode (!= NULL)
        if($query){
          //Retourne le code d'éxécution (-1,-2 ou 1)
          return $query->fetchAll(PDO::FETCH_NUM)[0][0];
        }
        //Erreur
        return -2;
    }

    // Retourne un code d'erreur ou de réussite si le client $email a bien rejoint la réservation $idr
    // => -1 déjà inscrit / -3 manque de place / -2 erreur inconnu / 1 réussite
    public function rejoindreReservation(string $email, int $idr) : int{
        //Appelle la fonction SQL
        $request = "SELECT rejoindreReservation('$email','$idr')";
        $query = $this->db->query($request);
        //Vérification du bon fonctionnement de la méthode (!= NULL)
        if($query){
          //Retourne le code d'éxécution (-1,-2,-3 ou 1)
          return $query->fetchAll(PDO::FETCH_NUM)[0][0];
        }
        //Erreur
        return -2;
    }

    //Retourne le code d'éxécution de l'ajout d'un service pour une réservation
    // -2 erreur inconnu / 1 réussite
    public function ajoutService(int $idr,string $service) : int{
      //Appelle de la fonction SQL ajoutService qui lie le service à la reservation et retourne le code de retour
      $requete = "Select ajoutService($idr,'$service')";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        return $query->fetchAll()[0][0];
      }
      //Erreur
      return -2;
    }

    //Retourne le code d'éxécution du calcul et de l'affectation du prix pour une réservation
    // -2 erreur inconnu / 1 réussite
    public function calculPrixTotal(int $idr) : int{
      $requete = "Select calculPrixTotal($idr)";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        return $query->fetchAll()[0][0];
      }
      //Erreur
      return -2;
    }

    //Retourne le code d'éxécution du paiement d'un client pour une réservation
    // -2 erreur inconnu / 1 réussite
    public function payee(int $idr, string $email, float $somme) : int{
      //Appelle de la fonction SQL payee qui mets à jour la table de paiement
      $requete = "Select payee($idr,'$email',$somme)";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        return $query->fetchAll()[0][0];
      }
      //Erreur
      return -2;
    }

    // Retourne un code d'erreur ou de réussite pour l'ajout d'un email dans la table NewsLetter
    // -1 email présent dans la table NewsLetter / -2 erreur inconnu / 1 réussite
    public function ajoutNL(string $email) : int{
        //Appelle de la fonction SQL ajoutNewsLetter qui ajoute l'email dans la table
        $request = "SELECT ajoutNewsLetter('$email')";
        $query = $this->db->query($request);
        //Vérification du bon fonctionnement de la méthode (!= NULL)
        if($query){
          //Retourne le code d'éxécution (-1,-2 ou 1)
          return $query->fetchAll(PDO::FETCH_NUM)[0][0];
        }
        return -2;
    }

    //Retourne une liste d'Utilisateurs abonnés à la newsLetter
    public function getAbonnesNL() : array{
      //Initialisation d'un tableau pour le retour
      $utilisateurs = array();
      $request = "Select * from NewsLetter";
      $query = $this->db->query($request);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        $listeNL = $query->fetchAll(PDO::FETCH_NUM);
        //On parcours les résultats pour créer un tableau d'objets Utilisateur (seulement si $listeNL != NULL)
        foreach ($listeNL as $util) {
          //Création et insertion d'un objet utilisateur dans le tableau grâce à la méthode getUtilisateur
          $utilisateurs[] = $this->getUtilisateur($util[0]);
        }
      }
      //Retourne le tableau d'objets equipement ou tableau vide
      return $utilisateurs;
    }

    // Retourne un booléen sur la présence de l'email $email dans la table Admin
    public function estAdmin(string $email) : bool{
      //Appelle de la fonction estAdmin qui vérifie la présence de l'email dans la table Admin
      $request = "SELECT estAdmin('$email')";
      $query = $this->db->query($request);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        //Renvoie le booléen
        return $query->fetchAll()[0][0];
      }
      // On renvoie false par défaut
      return false;
    }

    // Retourne un booléen sur la présence de l'email $email dans la table Client
    public function estClient(string $email) : bool{
      //Appelle de la fonction estClient qui vérifie la présence de l'email dans la table Client
      $request = "SELECT estClient('$email')";
      $query = $this->db->query($request);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        return $query->fetchAll()[0][0];
      }
      //On renvoie false par défaut
      return false;
    }




    // ===============================================
    // METHODE DE MODIFICATION DES INFOS UTILISATEURS
    // ===============================================

    // {$utilisateur->nom a une valeur} => {retourne vrai si udpate réalisé}
    public function setNomClient(Utilisateur $utilisateur,string $nom) : bool{
      $requete = "UPDATE utilisateur SET nom='$nom' WHERE email='$utilisateur->email'";
      $compteur = $this->db->exec($requete);
      //Vérifie l'éxécution de l'update (true ou false)
      return ($compteur==1);
    }

    // {$utilisateur->prenom a une valeur} => {retourne vrai si udpate réalisé}
    public function setPrenomClient(Utilisateur $utilisateur,string $prenom) : bool{
      $requete = "UPDATE utilisateur SET prenom='$prenom' WHERE email='$utilisateur->email'";
      $compteur = $this->db->exec($requete);
      //Vérifie l'éxécution de l'update (true ou false)
      return ($compteur==1);
    }

    // {$utilisateur->tel a une valeur} => {retourne vrai si udpate réalisé}
    public function setTelClient(Utilisateur $utilisateur,string $tel) : bool{
      $requete = "UPDATE utilisateur SET telephone='$tel' WHERE email='$utilisateur->email'";
      $compteur = $this->db->exec($requete);
      //Vérifie l'éxécution de l'update (true ou false)
      return ($compteur==1);
    }

    // {$utilisateur est un client et $utilisateur->ent a une valeur} => {retourne vrai si udpate réalisé}
    public function setEntrepriseClient(Utilisateur $utilisateur,string $entreprise) : bool{
      $requete = "UPDATE client SET entreprise='$entreprise' WHERE emailC='$utilisateur->email'";
      $compteur = $this->db->exec($requete);
      //Vérifie l'éxécution de l'update (true ou false)
      return ($compteur==1);
    }

    // {L'utilisateur est un client et $utilisateur->nom a une valeur} => {retourne vrai si udpate réalisé}
    public function setAdresseClient(Utilisateur $utilisateur,string $adresse) : bool{
      $requete = "UPDATE client SET adresse='$adresse' WHERE emailC='$utilisateur->email'";
      $compteur = $this->db->exec($requete);
      //Vérifie l'éxécution de l'update (true ou false)
      return ($compteur==1);
    }

    // {$utilisateur->mdp a une valeur} => {retourne vrai si udpate réalisé}
    public function setMdpClient(Utilisateur $utilisateur,string $mdp,bool $estAdmin) : bool{
      $mdp_hache = md5($mdp);
      if($estAdmin){
        $requete = "UPDATE ADMIN SET motdepasse='$mdp_hache' WHERE emaila='$utilisateur->email'";
      }else{
        $requete = "UPDATE CLIENT SET motdepasse='$mdp_hache' WHERE emailC='$utilisateur->email'";
      }

      $compteur = $this->db->exec($requete);
      //Vérifie l'éxécution de l'update (true ou false)
      return ($compteur==1);

    }

    // Retourne la liste des admins
    public function getAdmins() : ?array{
      //Initialisation d'un tableau pour le retour
      $reponse = array();
      $requete = "SELECT emailA FROM admin";
      $query = $this->db->query($requete);
      //Problème d'éxécution
      if(!$query){
        return null;
      }
      $admins = $query->fetchAll(PDO::FETCH_NUM);
      //On parcours les résultats pour créer un tableau d'objets Utilisateur (seulement si $listeNL != NULL)
      foreach($admins as $admin){
        //Création et insertion d'un objet administrateur dans le tableau grâce à la méthode getUtilisateur
        $reponse[] = $this->getUtilisateur($admin[0]);
      }
      return $reponse;

    }

    //Retourne la liste des services
    public function getServices() : array{
      //Initialisation d'un tableau pour le retour
      $services = array();
      $requete = "Select * from service";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        $result = $query->fetchAll(PDO::FETCH_NUM);
        //On parcours les résultats pour créer un tableau d'objets Service (seulement si $result != NULL)
        foreach ($result as $service) {
          $services[] = new Service($service[0],$service[1]);
        }
      }
      return $services;
    }

    //Retourne la liste des prestataires
    public function getPrestataires() : array{
      $requete = "Select * from prestataire";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        return $query->fetchAll(PDO::FETCH_NUM);
      }
      return array();
    }

    //Retourne la liste des services pour une réservation
    public function getServicesResa(int $idr) : array{
      //Initialisation d'un tableau pour le retour
      $services = array();
      //Appelle de la fonction SQL getServicesResa qui retourne la liste des services de la résa
      $query = $this->db->query("Select * from getServicesResa($idr)");
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        $result = $query->fetchAll();
        //On parcours les résultats pour créer un tableau intitules de services (seulement si $result != NULL)
        foreach ($result as $service) {
          $services[] = new Service($service[0],$service[1]);
        }
      }
      return $services;
    }

    //Retourne le code d'éxécution de la mise à jour (insertion) de la table statistiques
    // -2 erreur inconnu / 1 réussite
    public function updateStats() : int{
      //Appelle de la fonction SQL updateStats qui insert les stats du mois courant dans la table et retourne le code de retour
      $requete = "Select updateStats()";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        return $query->fetchAll()[0][0];
      }
      //Erreur
      return -2;
    }

    public function getStats() : array{
      //Initialisation d'un tableau pour le retour
      $stats = array();
      $query = $this->db->query("Select * from statistiques order by dateStockage desc");
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        $stats = $query->fetchAll();
      }
      return $stats;
    }

    //Retourne le code d'éxécution de la modification (insert update ou delete) de la table service
    // -1 présence du service dans une réservation / -2 erreur inconnu / 1 réussite
    public function gestionService(string $service, float $prix, string $action) : int{
      //Appelle de la fonction SQL gestionService qui modifie la table service et retourne le code de retour
      $requete = "Select gestionService('$service','$prix','$action')";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        return $query->fetchAll()[0][0];
      }
      //Erreur
      return -2;
    }

    //Retourne le code d'éxécution de la modification (insert update ou delete) de la table Prestataire
    // -2 erreur inconnu / 1 réussite
    public function gestionPrestataire(string $nomPresta, float $prix, string $action) : int{
      //Appelle de la fonction SQL gestionPrestataire qui modifie la table prestataire
      $requete = "Select gestionPrestataire('$nomPresta',$prix,'$action')";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        return $query->fetchAll()[0][0];
      }
      return -2;
    }

    //Retourne un booléen sur le succés de l'association service prestataire
    public function assignerPrestataire(string $service, string $nomPresta) : bool{
      try{
        // Préparation de la requête d'insertion
        $insert = $this->db->prepare("Insert into ServicePrestataires values (?,?)");
        // Exécution de la requête d'insertion avec les paramètres de la fonction
        $insert->execute(array($service,$nomPresta));
        return true;
      }
      catch(PDOException $e){
        return false;
      }
    }

    //Retourne le code d'éxécution de la modification (insert update ou delete) de la table Lieux
    // -2 erreur inconnu / 1 réussite
    public function gestionLieux(string $nomLieu, int $surface, int $nbPlace, string $description , float $prix, string $action) : int{
      //Appelle de la fonction SQL gestionLieux qui modifie la table Lieux
      $requete = "Select gestionLieux('$nomLieu',$surface,$nbPlace,'$description',$prix,'$action')";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        return $query->fetchAll()[0][0];
      }
      //Erreur
      return -2;
    }

    //Retourne le code d'éxécution de la modification (insert update ou delete) de la table Equipement
    // -2 erreur inconnu / 1 réussite
    public function gestionEquipement(string $nomEq, int $nbDispo, float $prixU, string $action) : int{
      //Appelle de la fonction SQL gestionEquipement qui modifie la table Equipement
      $requete = "Select gestionEquipement('$nomEq',$nbDispo,$prixU,'$action')";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        return $query->fetchAll()[0][0];
      }
      //Erreur
      return -2;
    }

    //Retourne un booléen sur le succés de l'association lieux equipement
    public function assignerEquipement(string $nomLocal, string $nomEquip) : bool{
      try{
        // Préparation de la requête d'insertion
        $insert = $this->db->prepare("Insert into LieuxEquipements values (?,?)");
        // Exécution de la requête d'insertion avec les paramètres de la fonction
        $insert->execute(array($nomLocal,$nomEquip));
        return true;
      }
      catch(PDOException $e){
        return false;
      }
    }

    //Retourne le code d'éxécution de la modification (insert update ou delete) de la table TypeReservation
    // -1 Ne peut pas être supprimé / -2 erreur inconnu / 1 réussite
    public function gestionFormation(string $nomForma, float $prix, string $descriptionForma, string $action) : int{
      //Appelle de la fonction SQL gestionFormation qui modifie la table TypeReservation
      $requete = "Select gestionFormation('$nomForma',$prix,'$descriptionForma','$action')";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        return $query->fetchAll()[0][0];
      }
      //Erreur
      return -2;
    }

    //Retourne la liste de toutes les formations présente dans la table typeReservation
    public function getGestionFormation() : array{
      //Initialisation d'un tableau pour le retour
      $formations = array();
      $requete = "Select * from typeReservation where type = 'formation'";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        $result = $query->fetchAll();
        //On parcours les résultats pour créer un tableau de typeReservation (seulement si $result != NULL)
        foreach ($result as $formation) {
          $formations[] = new TypeReservation($formation[0],$formation[1],$formation[2],$formation[3]);
        }
      }
      return $formations;
    }

    //Retourne le code d'éxécution de la modification (insert update ou delete) de la table TypeReservation
    // -1 Ne peut pas être supprimé / -2 erreur inconnu / 1 réussite
    public function gestionEvenement(string $nomEvent, float $prix, string $descriptionEvent, string $action) : int{
      //Appelle de la fonction SQL gestionEvenement qui modifie la table TypeReservation
      $requete = "Select gestionEvenement('$nomEvent',$prix,'$descriptionEvent','$action')";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        return $query->fetchAll()[0][0];
      }
      //Erreur
      return -2;
    }

    //Retourne la liste de tous les évenements présente dans la table typeReservation
    public function getGestionEvenement() : array{
      //Initialisation d'un tableau pour le retour
      $evenements = array();
      $requete = "Select * from typeReservation where type = 'evenement'";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        $result = $query->fetchAll();
        //On parcours les résultats pour créer un tableau de typeReservation (seulement si $result != NULL)
        foreach ($result as $evenement) {
          $evenements[] = new TypeReservation($evenement[0],$evenement[1],$evenement[2],$evenement[3]);
        }
      }
      return $evenements;
    }

    //Retourne la liste de tous les types et noms présents dans la table typeReservation (sauf location et co-working)
    public function getTypesResa() : array{
      //Initialisation d'un tableau pour le retour
      $typesResa = array();
      $requete = "Select * from typeReservation where type != 'location' and type != 'CoWorking'";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        $result = $query->fetchAll();
        //On parcours les résultats pour créer un tableau de typeReservation (seulement si $result != NULL)
        foreach ($result as $typeResa) {
          $typesResa[] = new TypeReservation($typeResa[0],$typeResa[1],$typeResa[2],$typeResa[3]);
        }
      }
      return $typesResa;
    }

    //Retourne le code d'éxécution de la modification (insert update ou delete) de la table Expert
    // -2 erreur inconnu / 1 réussite
    public function gestionExpert(string $nomExp, string $prenomExp, int $joursDispoExp, float $prixExp, string $action) : int{
      //Appelle de la fonction SQL gestionExpert qui modifie la table Expert
      $requete = "Select gestionExpert('$nomExp','$prenomExp',$joursDispoExp,$prixExp,'$action')";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        return $query->fetchAll()[0][0];
      }
      //Erreur
      return -2;
    }

    //Retourne un booléen sur le succés de l'association typeRéservation Expert
    public function assignerExpert(string $nomExp,string $prenomExp, string $type, string $nomType) : bool{
      try{
        // Préparation de la requête d'insertion
        $insert = $this->db->prepare("Insert into TypeExperts values (?,?,?,?)");
        // Exécution de la requête d'insertion avec les paramètres de la fonction
        $insert->execute(array($nomExp,$prenomExp,$type,$nomType));
        return true;
      }
      catch(PDOException $e){
        return false;
      }
    }

    //Retourne la liste des experts
    public function getExperts() : array{
      $requete = "Select * from expert";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        return $query->fetchAll(PDO::FETCH_NUM);
      }
      return array();
    }

    //Retourne le code d'éxécution de la modification (insert update ou delete) de la table Article
    // -2 erreur inconnu / 1 réussite
    public function gestionArticle(string $titre, string $description,string $auteur, string $action) : int{
      //Appelle de la fonction SQL gestionArticle qui modifie la table Article
      $requete = "Select gestionArticle('$titre','$description','$auteur','$action')";
      $query = $this->db->query($requete);
      //Vérification du bon fonctionnement de la méthode (!= NULL)
      if($query){
        return $query->fetchAll()[0][0];
      }
      //Erreur
      return -2;
    }
}
