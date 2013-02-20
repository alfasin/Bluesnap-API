<?php

  // 02-19-2012 
	// written by: alfasin
	// 
	// This PHP script demonstrates how to use the API in order to GET a subscription information and
	// then use the received XML in order to update (HTTP-PUT) the subscription, for example, to cancel/activate it
	// or to change the next payment date. This 
	// You can read more about this API web-service here: 
	// http://www.bluesnap.com/helpcenter/Static/update_subscription.htm
	// A link to the help-center: http://home.plimus.com/DocumentationCenter/Static/default.htm
	// 
	// Important:
	// in order to get the $subscriptionId you should enable the IPN (Instant payment notification)
	// you can read an overview about it here: 
  // http://www.bluesnap.com/helpcenter/Static/instant_notifications_(ipn).htm
  //
	// but I also recommend reading the full tutorials in the help-center: 
  // http://home.plimus.com/DocumentationCenter/Static/default.htm
  //
	// in which you'll find a PHP code example that shows how to receive an IPN: 
  // http://www.bluesnap.com/helpcenter/Static/ipn_code_examples.htm
	//
	// an example of how this PHP script works can be viewed here: 
  // http://alfasin.com/update-subscription.php?subscriptionId=5845292 
	// (use "view-source" in order to see the XMLs)


	// either get the creds via POST params
	$username   = htmlentities($_REQUEST["username"]);   // vendor API username - not to confuse with the control-panel credentials
	$password   = htmlentities($_REQUEST["password"]);   // vendor API password

	// or get the credentials from the DB/external file which is unreachable from the web
	require_once(../../creds.php);
	// it should be in the following format
	$credentials = "username:password";
	
	// get the subscription ID
	$subscriptionId = htmlentities($_REQUEST["subscriptionId"]);  
	
	//for demo
	$subscriptionId = 5857120;
	
	$url = "https://sandbox.plimus.com/services/2/subscriptions/$subscriptionId";
	$headers = array("Content-Type: application/xml");
	
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");  
	curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch,CURLOPT_USERPWD,"$credentials");    
	

	$xml = curl_exec($ch); 
	if(!curl_errno($ch)){ 
	  echo "<br>Subscription: $xml<br>"; 
	} 
	else { 
	  echo "<br>Curl error: " . curl_error($ch); 
	} 
	curl_close($ch); 
	

	// we'll use the response as "base-xml" for the next request
	$new_xml = new SimpleXMLElement($xml);	
	
	// now we can update the XML with the wanted: 
	// cancel the subscription (change its status from 'A' to 'C'):
	$new_xml->{"status"} = "C";
	// "change the next payment date:
	$new_xml->{"next-charge-date"} = "20-Feb-13";
	// etc
	$new_xml_to_send = $new_xml->asXML();
	echo "goint to send: $new_xml_to_send<br><br>";

	$ch = curl_init();
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); 
	curl_setopt($ch, CURLOPT_URL, $url); 	
    curl_setopt($ch, CURLOPT_POSTFIELDS, $new_xml_to_send);  
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);     
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_USERPWD,"$credentials");
	
	// Execute the request and example code to time the transaction
    $result_exec = curl_exec($ch);
    echo "<br>result: $result_exec<br><br>";


	// Check for errors
    if ( curl_errno($ch) ) {
        $result = "HTTP ERROR -> " . curl_errno($ch) . ": " . curl_error($ch);
    } else {
        $result = "HTTP CODE = " . (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    }	
	// print result (should be HTTP code 204)
    echo "<br>$result<br><br><br>";
	
?>
