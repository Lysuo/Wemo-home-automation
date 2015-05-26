<?php


function myprint_r($my_array) {
    if (is_array($my_array)) {
        echo "<table border=1 cellspacing=0 cellpadding=3 width=100%>";
        echo '<tr><td colspan=2 style="background-color:#333333;"><strong><font color=white>ARRAY</font></strong></td></tr>';
        foreach ($my_array as $k => $v) {
                echo '<tr><td valign="top" style="width:40px;background-color:#F0F0F0;">';
                echo '<strong>' . $k . "</strong></td><td>";
                myprint_r($v);
                echo "</td></tr>";
        }
        echo "</table>";
        return;
    }
    echo $my_array;
}

function parseMSearchResponse( $response ) 	{
	$responseArr = explode( "\r\n", $response );
	//print_r($responseArr);
	$parsedResponse = array();
	foreach( $responseArr as $row ) {
		if( stripos( $row, 'http' ) === 0 )
			$parsedResponse['http'] = $row;
		if( stripos( $row, 'cach' ) === 0 )
			$parsedResponse['cache-control'] = str_ireplace( 'cache-control: ', '', $row );
		if( stripos( $row, 'date') === 0 )
			$parsedResponse['date'] = str_ireplace( 'date: ', '', $row );
		if( stripos( $row, 'ext') === 0 )
			$parsedResponse['ext'] = str_ireplace( 'ext: ', '', $row );
		if( stripos( $row, 'loca') === 0 )
			$parsedResponse['location'] = str_ireplace( 'location: ', '', $row );
		if( stripos( $row, 'serv') === 0 )
			$parsedResponse['server'] = str_ireplace( 'server: ', '', $row );
		if( stripos( $row, 'st:') === 0 )
			$parsedResponse['st'] = str_ireplace( 'st: ', '', $row );
		if( stripos( $row, 'usn:') === 0 )
			$parsedResponse['usn'] = str_ireplace( 'usn: ', '', $row );
		if( stripos( $row, 'cont') === 0 )
			$parsedResponse['content-length'] = str_ireplace( 'content-length: ', '', $row );
	}
	return $parsedResponse;
}

$st = 'ssdp:all'; 
$st2 = 'urn:Belkin'; 
$mx = '5';
$man = 'ssdp:discover';
$from = null;
$port = null;
$sockTimout = '7';
$user_agent = 'Linux UPnP/1.1 crash/1.0';

// BUILD MESSAGE
$msg  = 'M-SEARCH * HTTP/1.1' . "\r\n";
$msg .= 'HOST: 239.255.255.250:1900' . "\r\n";
$msg .= 'MAN: "'. $man .'"' . "\r\n";
$msg .= 'MX: '. $mx ."\r\n";
$msg .= 'ST:' . $st ."\r\n";
//$msg .= 'ST:' . $st2 ."\r\n";
$msg .= 'USER-AGENT: '. $user_agent ."\r\n";
$msg .= '' ."\r\n";

// MULTICAST MESSAGE
$sock = socket_create( AF_INET, SOCK_DGRAM, 0 );
$opt_ret = socket_set_option( $sock, 1, 6, TRUE );
$send_ret = socket_sendto( $sock, $msg, strlen( $msg ), 0, '239.255.255.250', 1900);

// SET TIMEOUT FOR RECEIVE
socket_set_option( $sock, SOL_SOCKET, SO_RCVTIMEO, array( 'sec'=>$sockTimout, 'usec'=>'0' ) );

// RECIEVE RESPONSE
$response = array();
do {
	$buf = null;
	@socket_recvfrom( $sock, $buf, 1024, MSG_WAITALL, $from, $port );
	if( !is_null($buf) )$response[] = parseMSearchResponse( $buf );
} while( !is_null($buf) ); 

// CLOSE SOCKET
socket_close( $sock );
myprint_r($response);
		
?>