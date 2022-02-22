<?php
/**
 *
 */
require_once(dirname(__FILE__).'/utilisateur.class.php');

class Client extends Utilisateur
{

  private string $entreprise;
  private string $adresse;
  private string $mdp;

  // Constructeur de la class client
  function __construct(string $e, string $n, string $p, string $t,string $a ,string $ent,string $mdp)
  {
    Parent::__construct($e,$n,$p,$t);
    $this->entreprise = $ent;
    $this->adresse = $a;
    $this->mdp = $mdp;
  }

  // Méthode magique qui implemente tous les getters
  function __get(string $attribut){
    return $this->$attribut;
  }

  function __set(string $attribut, $valeur){
    $this->$attribut = $valeur;
  }

}

?>
