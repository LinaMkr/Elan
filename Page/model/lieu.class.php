<?php
/**
 *
 */
class Lieu
{
  private string $nomLocal;
  private int $surface;
  private int $nb_place;
  private string $description;
  private float $prixLieu;


  // Constructeur de la class client
  function __construct(string $n,int $s,int $nb_place,string $description,float $prixLieu)
  {
    $this->nomLocal = $n;
    $this->surface = $s;
    $this->nb_place = $nb_place;
    $this->description = $description;
    $this->prixLieu = $prixLieu;
  }

  // Méthode magique qui implemente tous les getters
  function __get(string $attribut){
    return $this->$attribut;
  }

}



 ?>
