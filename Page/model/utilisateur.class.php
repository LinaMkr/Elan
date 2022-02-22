<?php

class Utilisateur
{
  protected string $email;
  protected string $nom;
  protected string $prenom;
  protected string $tel;


  // Constructeur de la class client
  function __construct(string $e, string $n = '', string $p = '', string $t ='')
  {
    $this->email = $e;
    $this->nom = $n;
    $this->prenom = $p;
    $this->tel = $t;
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
