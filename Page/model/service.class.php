<?php

class Service{
  private string $intitule;
  private float $prix;

  function __construct(string $intitule, string $prix)
  {
    $this->intitule = $intitule;
    $this->prix = $prix;
  }

  function __get(string $attribut){
    return $this->$attribut;
  }

  function __set(string $attribut, $valeur){
    $this->$attribut = $valeur;
  }
}
 ?>
