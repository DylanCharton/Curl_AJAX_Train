<?php

// phpinfo();
// die;

spl_autoload_register(function ($class_name) {
    include './class/'.$class_name . '.php';
});

$curl = curl_init("https://geo.api.gouv.fr/regions");
// Stocking the result of the curl in a variable
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$regions = curl_exec($curl);


// If the curl is not executed, show me the error
if($regions === false){
    echo "<pre>";
    var_dump(curl_error($curl));
    echo "</pre>";
} else{
    // Otherwise, decode the json
    $regions = json_decode($regions, true);
    // If the url contains insertData, fill the table
    if(isset($_GET['insertData'])){
        foreach ($regions as $region) {
            $regionainserer = new Data;
            $regionainserer->insert($region['code'], $region['nom']);
        }
      
    }
    else{
        // Otherwise display the content of the table in a list
        $regions = new Data;
        $datas = $regions->getAll(); 
        echo '<select name="regionsList">';
        foreach ($datas as $region) {
            echo "<option value=".$region['id'].">".$region['code']." - ".$region['nom']."</option>";
        }
        echo '</select>';
    }
}


curl_close($curl);
?>
