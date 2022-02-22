<?php
require_once(dirname(__FILE__).'/client.class.php');
require_once(dirname(__FILE__).'/admin.class.php');
require_once(dirname(__FILE__).'/utilisateur.class.php');
require_once(dirname(__FILE__)."/lieu.class.php");

class Reservation
{
  private int $idR;
  private Utilisateur $email;
  private Lieu $lieu;
  private string $type;
  private string $nom;
  private string $dateDeb;
  private string $dateFin;
  private int $nb_pers;
  private int $placeRest;
  private float $prixT;

  // Constructeur de la class client
  function __construct(int $idR, Utilisateur $email, Lieu $lieu, string $type,string $nom, string $dateDeb, string $dateFin, int $nb_pers, int $placeRest, float $prixT = 0.0)
  {
    $this->idR = $idR;
    $this->email = $email;
    $this->lieu = $lieu;
    $this->type = $type;
    $this->nom = $nom;
    $this->dateDeb = $dateDeb;
    $this->dateFin = $dateFin;
    $this->nb_pers = $nb_pers;
    $this->placeRest = $placeRest;
    $this->prixT = $prixT;

  }

  // Méthode magique qui implemente tous les getters
  function __get(string $attribut){
    return $this->$attribut;
  }

  // Méthode magique qui implemente tous les setters
  function __set(string $attribut, $valeur){
    $this->$attribut = $valeur;
  }

}



 ?>
