<?php

class Data extends Database
{

    public function insertRegions(){
        $delete = $this->connect()->prepare("Delete from regions");
        $delete->execute();
        // Specify the URL of the API
        $curl = curl_init("https://geo.api.gouv.fr/regions");
        // Set options
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
        // Execute the curl
        $regions = curl_exec($curl);
        // Decode to JSON
        $regions = json_decode($regions, true);
        // Fill the table with a foreach
        foreach($regions as $region){
            $insert = $this->connect()->prepare("INSERT INTO regions (code, nom) VALUES (:code, :nom)");
            $insert->bindParam(":code", $region["code"]);
            $insert->bindParam(":nom", $region["nom"]);
            $insert->execute();
            // $insert->debugDumpParams();
        }
        curl_close($curl);
    }

    public function insertDepartment(){
        $delete = $this->connect()->prepare("DELETE FROM departements");
        $delete->execute();
        $curl = curl_init("https://geo.api.gouv.fr/departements");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $departments = curl_exec($curl);
        $departments = json_decode($departments, true);
        foreach($departments as $department){
            $insert = $this->connect()->prepare("INSERT INTO departements (code, nom, codeRegion) VALUES (:code, :nom, :code_region)");
            $insert->bindParam(":code", $department['code']);
            $insert->bindParam(":nom", $department['nom']);
            $insert->bindParam(":code_region", $department['codeRegion']);
            $insert->execute();
        }
        curl_close($curl);
    }

    public function insertCommune(){
        $delete = $this->connect()->prepare("DELETE FROM communes");
        $delete->execute(); 
        $curl = curl_init("https://geo.api.gouv.fr/communes");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $communes = curl_exec($curl);
        $communes = json_decode($communes, true);
        foreach($communes as $commune){
            $cp = implode(",", $commune['codesPostaux']);
            $insert = $this->connect()->prepare("INSERT INTO communes (code, nom, codePostaux, population, codeDepartement) VALUES (:code, :nom, :code_post, :population, :code_depart)");
            $insert->bindParam(":code", $commune['code']);
            $insert->bindParam(":nom", $commune['nom']);
            $insert->bindParam(":code_post", $cp);
            $insert->bindParam(":population", $commune['population']);
            $insert->bindParam(":code_depart", $commune['codeDepartement']);
            $insert->execute();

        }
    }

    public function getRegionsList(){
        $select = $this->connect()->prepare("SELECT nom FROM regions LIMIT 100");
        $select->execute();
        $datas = $select->fetchAll();
        return $datas;
    }

    public function getDepartmentsList(){
        $select = $this->connect()->prepare("SELECT nom FROM departements LIMIT 100");
        $select->execute();
        $datas = $select->fetchAll();
        return $datas;
    }

    public function getCommunesList(){
        $select = $this->connect()->prepare("SELECT nom, codesPostaux FROM departements LIMIT 100");
        $select->execute();
        $datas = $select->fetchAll();
        return $datas;
    }

    public function getAll(){
        $datas = $this->connect()->prepare(
            "SELECT
                    communes.nom AS nom_commune,
                    communes.codesPostaux AS cp_commune,
                    communes.population AS population_commune,
                    departement.nom AS nom_departement,
                    regions.nom AS nom_region
                FROM
                regions,
                departements,
                communes
                WHERE
                regions.code = departements.codeRegion
                AND
                departements.code = communes.codeDepartement
                LIMIT 10"
        );
        $datas->execute();
        $allDatas = $datas->fetchAll();
        $allDatasJSON = json_encode($allDatas);
        echo $allDatasJSON;
            
        
    }

    public function searchDepartment($what){
        $datas = $this->connect()->prepare(
            "SELECT 
                    departements.nom AS nom_departement,
                    regions.nom AS nom_region
                FROM
                regions,
                departements
                WHERE
                regions.code = departements.codeRegion
                AND regions.nom = '$what'
                GROUP BY nom_departement
                "
        );
        $datas->execute();
        $allDatas = $datas->fetchAll();
        $allDatasJSON = json_encode($allDatas);
        echo $allDatasJSON;
        
    }
    public function displayCommunes($dequeldepartement){
        $datas = $this->connect()->prepare(
            "SELECT
                    communes.nom AS nom_commune,
                    communes.codePostaux AS cp_commune,
                    communes.population AS population_commune,
                    departements.nom AS nom_departement,
                    regions.nom AS nom_region
                FROM
                regions,
                departements,
                communes
                WHERE
                regions.code = departements.codeRegion
                AND
                departements.code = communes.codeDepartement
                AND
                departements.nom = '$dequeldepartement'
                LIMIT 100
                "
            
        );
        $datas->execute();
        $allDatas = $datas->fetchAll();
        $allDatasJSON = json_encode($allDatas);
        echo $allDatasJSON;
    }
}

    