<?php

$IP = $_GET['ip'];
$PORT = $_GET['port'];
$theurl = "http://$IP:$PORT/upnp/control/basicevent1";

$headers = [
	'SOAPACTION: "urn:Belkin:service:basicevent:1#GetBinaryState"',
	'SOAPACTION: "urn:Belkin:service:basicevent:1#GetSignalStrength"',
	'SOAPACTION: "urn:Belkin:service:basicevent:1#GetFriendlyName"' 
	];

$xml = [
	'<?xml version="1.0" encoding="utf-8"?><s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:GetBinaryState xmlns:u="urn:Belkin:service:basicevent:1"><BinaryState>1</BinaryState></u:GetBinaryState></s:Body></s:Envelope>',
	'<?xml version="1.0" encoding="utf-8"?><s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:GetSignalStrength xmlns:u="urn:Belkin:service:basicevent:1"><SignalStrength>0</SignalStrength></u:GetSignalStrength></s:Body></s:Envelope>',
	'<?xml version="1.0" encoding="utf-8"?><s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:GetFriendlyName xmlns:u="urn:Belkin:service:basicevent:1"><FriendlyName>0</FriendlyName></u:GetFriendlyName></s:Body></s:Envelope>' 
	];

$return_arr = array();
$return_arr["IP"] = $IP;
	
for ($i=0; $i<3; $i++) {

	$ch = curl_init($theurl);
	curl_setopt($ch, CURLOPT_TIMEOUT , 3);
	curl_setopt($ch, CURLOPT_USERAGENT, '');
	curl_setopt($ch, CURLOPT_HEADER, TRUE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: ', 'Content-Length: ' . strlen($xml[$i]), 'Content-type: text/xml; charset="utf-8"', $headers[$i]]);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); // -X

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml[$i]);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0); // -0

	$data = curl_exec($ch);
	$matches = array();

	if (curl_errno($ch)) { 
		  //$return_arr["Error"] = "Error: " . curl_error($ch);
		  $return_arr["State"] = "Unreachable";
		  $return_arr["Signal strength"] = "N/A";
		  $return_arr["Friendly name"] = "N/A";
		  break;
	} else { 
	    
	  if ($i == 0) {
		  preg_match("/<BinaryState>[0-1]/", $data, $matches);
		  $return_arr["State"] = substr($matches[0], 13) == 0 ? "OFF" : "ON";
	  } else if ($i == 1) {
		  preg_match("/<SignalStrength>[0-9]*/", $data, $matches);
		  $return_arr["Signal strength"] = substr($matches[0], 16);
	  } else if ($i == 2) {
		  preg_match("/<FriendlyName>([0-9]|[A-Z]|[a-z]| )*/", $data, $matches);
		  $return_arr["Friendly name"] = substr($matches[0], 14);
	  }	  
	  curl_close($ch); 
	} 

}

echo json_encode($return_arr);

?>
