<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<?php

spl_autoload_register(function ($class_name) {
    include './class/'.$class_name . '.php';
});



if(isset($_GET['insertData'])){
    $regions = new Data();
    $regions->insertRegions();
    $department = new Data();
    $department->insertDepartment();
    $communes = new Data();
    $communes->insertCommune();
}

$regionsList = new Data();
$regions = $regionsList->getRegionsList();

?>



<div class="container">
    <form action="">


        <select name="region" id="lesregions" class="form-control">
            <option value="" disabled selected>Choisir une région</option>
            <?php foreach ($regions as $region) {
      echo '<option value="'.$region["nom"].'">'.$region["nom"].'</option>';
    }
?>

        </select>

        <select name="departement" id="lesdepartements" class="form-control" hidden>
            <option value="" disabled selected>Choisir un département</option>


        </select>






    </form>

    <?php 




echo '
<div class="container"><table class="table">
  <thead class="table-dark">
  <tr>
  <th scope="col">Commune</th>
  <th scope="col">Code Postal</th>
  <th scope="col">Population</th>
  <th scope="col">Département</th>
  <th scope="col">Région</th>
</tr>
  </thead>
  <tbody id="laliste">
  </tbody>
</table></div>'






?>


    <!-- <a href="?insertData">Insérer les données</a> -->
    <script src="js/script.js"></script>