<?php

$PORT = "49153";
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

for ($j=100; $j<115; $j++) {
	
	$IP = '192.168.0.' . $j;
	$theurl = "http://$IP:$PORT/upnp/control/basicevent1";
	echo $IP;
	
	$ch = curl_init($theurl);
	curl_setopt($ch, CURLOPT_TIMEOUT , 3);
	curl_setopt($ch, CURLOPT_USERAGENT, '');
	curl_setopt($ch, CURLOPT_HEADER, TRUE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: ', 'Content-Length: ' . strlen($xml[0]), 'Content-type: text/xml; charset="utf-8"', $headers[0]]);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); // -X

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml[0]);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0); // -0

	$data = curl_exec($ch);
	$matches = array();

	if (curl_errno($ch)) { 
		  echo " : error";
	} else { 
	  preg_match("/<BinaryState>[0-1]/", $data, $matches);
	  echo " : ";
	  echo substr($matches[0], 13) == 0 ? "OFF" : "ON";   
	} 
	
	curl_close($ch);	
	echo "<br />";
} 

?>