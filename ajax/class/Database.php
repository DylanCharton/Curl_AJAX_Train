<?php

abstract class Database {

    public function connect() //fonction de connexion à la base
  {
      try
      {
          $bdd = new PDO('mysql:host=localhost;dbname=ajax;charset=utf8', 'root', '');
         return $bdd; 
       
      }
      catch(Exception $e)
      {
          die('Erreur : '.$e->getMessage());
      }
  }
     
 }