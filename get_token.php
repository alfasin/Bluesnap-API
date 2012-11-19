<?php
//
// 22-08-2012 
// written by: alfasin
// 
// This PHP script demonstrates how simple it is to use the API in order to fetch an authentication token
// and then use it to log the customer into Plimus platform. It can be used to create a one-click purchase (set: target=step2)
// to log the customer into the customer-control panel so he/she could manage their subscription etc.
//
// example for *real* usage: http://alfasin.com/get_token.php?shopperId=19363122&expiration=60&username=[username]&password=[password]&contractId=2121730&target=step2
// to see how it works: http://alfasin.com/blog/code/get_token.php?target=step2 (try to replace step2 with: cp,step1)

// -------- PART I - Get the token through API call --------

//receive shopper-id and expiration-in-minutes from the caller
$shopperId  = htmlentities($_REQUEST["shopperId"]);  // the customer account-id, can be found in "order-locator" page for example
$expiration = htmlentities($_REQUEST["expiration"]); // the token will remain valid for number 'expiration' number of minutes 
$username   = htmlentities($_REQUEST["username"]);   // vendor API username - not to confuse with the control-panel credentials
$password   = htmlentities($_REQUEST["password"]);   // vendor API password
$contractId = htmlentities($_REQUEST["contractId"]); // contract to buy
$target     = htmlentities($_REQUEST["target"]);     // target - the Plimus page to redirect the customer: cp,step1,step2,paypal

//we'll use the sandbox but in order to use the live API replace sandbox.plimus.com with ws.plimus.com
$URL = "https://sandbox.plimus.com/services/2/tools/auth-token?shopperId=$shopperId&expirationInMinutes=$expiration";

// use base64 to encode the credentials
$authorization = base64_encode($username.':'.$password); 

$ch = curl_init();
// set URL
curl_setopt_array($ch, array(CURLOPT_URL => $URL));
// set headers
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Basic $authorization", "Content-type: application/xml")); // This line is mandatory for every API call!
// set  HTTP request to GET
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); // This service (get token) is implement via RESTful GET, other services might use POST and PUT
// stop output of curl_exec to standard output (don't send output to screen)
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
// make HTTP call and read the response into an XML object
$xml = new SimpleXMLElement(curl_exec($ch));

// -------- PART II - Log the customer into Plimus (according to $target) --------

// construct plimus URL
$plimus = "https://sandbox.plimus.com/jsp/entrance.jsp?contractId=$contractId&target=$target&token={$xml->token}";
// redirect
header("Location: $plimus");
?>