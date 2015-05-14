<?php

$fileContents = file_get_contents("../db/devicesDB.xml");
$xml = simplexml_load_string($fileContents);
$json = json_encode($xml);

//$array = json_decode($json,TRUE);
//print_r($array);

echo $json;

?>