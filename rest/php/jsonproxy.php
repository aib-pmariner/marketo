<?php
// proxy for Marketo
// accepts posted JSON object from client side, e.g. $.ajax, returns JSON

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

/***  Example JSON POST:

{
	"marketo":[
			{
			"action": "updateOnly", 
			"lookupField": "id",
			"input": [{ 
	    		"id": leadId, 
				"lastName": "Plusssssyplusplus"						
			}]
		}],
	"auth": access_token
}

/***  Example JSON GET:

{ 
	"marketo": "&filterType=cookie&filterValues="<cookie>"&fields=cookies,email",
	"auth": accessToken
}

*/



function jsonRequest($url, $data, $method){

	$content = json_encode($data);
	//var_dump($content);

	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);     
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); 
	curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));

	if($method == "POST"){
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
	}

	if($method == "GET"){
		curl_setopt($curl, CURLOPT_HTTPGET, true);
		//curl_setopt($curl, CURLOPT_GETFIELDS, $content);
	}			

	$json_response = curl_exec($curl);
	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	if ( $status != 200 ) {
	    die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
	}

	curl_close($curl);
	$response = json_decode($json_response, true);
	return $response;
}


if(stristr($_SERVER['HTTP_HOST'], "192.168.38") > -1 || stristr($_SERVER['HTTP_HOST'], "aib.edu.au") > -1 ){	

	if(isset($_POST["auth"])){
		$endpoint = "https://AAA-BBB-CCC.mktorest.com/rest/v1/leads.json?access_token=".$_POST["auth"];
		$marketo = $_POST['marketo'][0];
		$resp = jsonRequest($endpoint, $marketo, "POST");
	}elseif(isset($_GET["auth"])){		
		
		$endpoint = "https://AAA-BBB-CCC.mktorest.com/rest/v1/leads.json?access_token=".$_GET["auth"].$_GET["marketo"];	
		$resp = jsonRequest($endpoint, '', "GET");
	}
	
	print json_encode($resp);

}else{
	"External calls to this script are not allowed.";
}

?>