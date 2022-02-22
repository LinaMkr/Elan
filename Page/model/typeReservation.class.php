<?php
class typeReservation
{

  private string $type;
  private string $nom;
  private float $prix;
  private string $description;

  // Constructeur de la class client
  function __construct(string $t, string $n, float $p, string $d){
    $this->type = $t;
    $this->nom = $n;
    $this->prix = $p;
    $this->description = $d;
  }

  // Méthode magique qui implemente tous les getters
  function __get(string $attribut){
    return $this->$attribut;
  }

}


 ?>
