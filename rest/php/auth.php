<?php
// returns Marketo access token for REST API calls

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// First part of URL is client endpoint

$url = "https://AAA-BBB-CCC.mktorest.com/identity/oauth/token?grant_type=client_credentials&client_id=<CLIENT-ID>&client_secret=<CLIENT-SECRET>";

// check if request is from aib.net.au, otherwide reject

if(stristr($_SERVER['HTTP_HOST'], "192.168.38") > -1 || stristr($_SERVER['HTTP_HOST'], "aib.edu.au") > -1 ){	
	$json = file_get_contents($url);
	header('Content-Type: application/json');
	echo json_encode($json);
}else{
	"External calls to this script are not allowed.";
}


?>