// required: jQuery

var mktoREST = function(mode, tok, updateData, callback){					

	// client side REST for Marketo! 
	$.ajax({
		type: mode,
		crossDomain: true,
		data: updateData,
		url: "../../rest/jsonproxy.php"}).done(function (d) { 
			callback(d);
		}).fail(function(d) {

	});					
},

getToken = function(callBack){
					
	// gets a Marketo authentication token from a microservice
	$.ajax({
		type: "POST",
		crossDomain: true,
		url: "../../rest/auth.php"}).done(function (d) { 
			callBack(d);
		}).fail(function(d) {

	});
},

email = "fred.nerk@nerkynerknerk.com";



getToken(function(d){					
	var dataObj = $.parseJSON(d),
	access_token = dataObj.access_token,	
	updateData =
	{ 
		"marketo":[
				{
					"action": "updateOnly", 
					"lookupField": "email",
					"input": [{ 
			    		"email": email, 
						"firstName": "Fred",
						"lastName": "Nerk"
					}]
				}],
		"auth": access_token
	}; 	

	// Update field(s) for this Marketo lead
	mktoREST("POST", access_token, updateData, function(d){
		console.log(d);
	});
});	 		