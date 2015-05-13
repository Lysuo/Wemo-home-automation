<?php

$xml = simplexml_load_string("db/devicesDB.xml");
$json = json_encode($xml);
$array = json_decode($json,TRUE);

?>