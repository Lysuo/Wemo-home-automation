<?php

$IP = $_GET['ip'];
//$IP = '192.168.0.123';
$PORT = "49153";
$theurl = "http://$IP:$PORT/upnp/control/basicevent1";

$headers = 'SOAPACTION: "urn:Belkin:service:basicevent:1#SetBinaryState"';
$xml = '<?xml version="1.0" encoding="utf-8"?><s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:SetBinaryState xmlns:u="urn:Belkin:service:basicevent:1"><BinaryState>0</BinaryState></u:SetBinaryState></s:Body></s:Envelope>';

$return_arr = array();

	$ch = curl_init($theurl);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,3);
	curl_setopt($ch,CURLOPT_USERAGENT, '');
	curl_setopt($ch, CURLOPT_HEADER, TRUE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: ', 'Content-Length: ' . strlen($xml), 'Content-type: text/xml; charset="utf-8"', $headers]);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); // -X

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0); // -0

	$data = curl_exec($ch);
	$matches = array();

	if (curl_errno($ch)) { 
	  print "Error: " . curl_error($ch);
	} else { 	
		preg_match("/<BinaryState>[0-1]/", $data, $matches);
		$return_arr["State"] = substr($matches[0], 13) == 0 ? "OFF" : "ON";		  
		curl_close($ch); 
	}

echo json_encode($return_arr);

?>
