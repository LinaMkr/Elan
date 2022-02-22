<?php

class Equipement
{
  private string $nomEquip;
  private int $nb_Disponible;
  private float $prixUnite;

  // Constructeur de la class client
  function __construct(string $nomEquip, int $nb_Disponible, float $prixUnite)
  {
    $this->nomEquip = $nomEquip;
    $this->nb_Disponible = $nb_Disponible;
    $this->prixUnite = $prixUnite;
  }

  // Méthode magique qui implemente tous les getters
  function __get(string $attribut){
    return $this->$attribut;
  }

}

 ?>
