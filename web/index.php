hello there

<?php

$IP = "192.168.0.126";
$PORT = "49153";
$theurl = "http://$IP:$PORT/upnp/control/basicevent1";
$xml = '<?xml version="1.0" encoding="utf-8"?><s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:GetBinaryState xmlns:u="urn:Belkin:service:basicevent:1"><BinaryState>1</BinaryState></u:GetBinaryState></s:Body></s:Envelope>';

$ch = curl_init($theurl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); // -X
curl_setopt($ch,CURLOPT_USERAGENT, ''); 
curl_setopt($ch, CURLOPT_HEADER, TRUE); 
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: ', 'Content-Length: ' . strlen($xml), 'Content-type: text/xml; charset="utf-8"', 'SOAPACTION: \"urn:Belkin:service:basicevent:1#GetBinaryState\"']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0); // -0

$data = curl_exec($ch);
//$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 

//echo "http code";
//echo $httpCode

if (curl_errno($ch)) { 
  print "Error: " . curl_error($ch); 
} else { 
  // Show me the result 
  var_dump($data); 
  curl_close($ch); 
} 

?>
