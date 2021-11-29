<?php

class Data extends Database 
{
    public function getAll(){
        $datas = $this->connect()->prepare("SELECT * FROM regions");
        $datas->execute();
        $allDatas = $datas->fetchAll();
        return $allDatas;
    }

    public function insert($code, $nom){
        $insert = $this->connect()->prepare("INSERT INTO regions (code, nom) VALUES (:code, :nom)");
        $insert->bindParam(":code", $code);
        $insert->bindParam(":nom", $nom);
        $insert->execute();
        $insert->debugDumpParams();

    }
}