<?php
/**
 *
 */
require_once(dirname(__FILE__).'/utilisateur.class.php');

class Admin extends Utilisateur
{

  private string $mdp;

  // Constructeur de la class client
  function __construct(string $e, string $n, string $p, string $t, string $mdp)
  {
    parent::__construct($e,$n,$p,$t);
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
