<?php

class Article
{
  private string $titre;
  private string $description;
  private Utilisateur $auteur;

  // Constructeur de la class client
  function __construct(string $titre, string $description, Utilisateur $auteur)
  {
    $this->titre = $titre;
    $this->description = $description;
    $this->auteur = $auteur;
  }

  // Méthode magique qui implemente tous les getters
  function __get(string $attribut){
    return $this->$attribut;
  }

}

 ?>
