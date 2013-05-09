<?php
/*
 * Written By: Ben Hultin & Nir Alfasi (alfasin)
 * Nov. 2012
 * 
 * This code is used to call an API (RESTful) service of Bluesnap that creates a subscription charge.
 * We chose using CURL to place the API request in this demo, but we urge anyone who implements an API client 
 * to work with a RESTful API client framework in order to have a full support of all the properties of REST, such as: 
 * set HTTP method, get return-code, read/write headers, full XML support etc.
 * 
 * The API manual is available under "Developer Toolbox" section: 
 * http://www.bluesnap.com/helpcenter/Static/default.htm?bluesnap_web_services.htm
 * 
 */

//here we define $credentials = $username . ":" . $password;
include_once('../credentials.php'); 

/**
 * Retrieve data from input fields
*/
$subscriptionId = $_REQUEST['ondemand-subscription-id'];
$chargeDesc = $_REQUEST['charge-description']; 
$fromDate = $_REQUEST['from-date'];
$toDate = $_REQUEST['to-date'];
$amount = $_REQUEST['amount'];
$currency = $_REQUEST['currency'];
        
/**
 * Assemble the XML string with variables instantiated above
*/
$xmlToSend = ' 
    <subscription-charge xmlns="http://ws.plimus.com">
        <charge-info>
           <charge-description>'.$chargeDesc.'</charge-description>
           <from-date>'.$fromDate.'</from-date>
           <to-date>'.$toDate.'</to-date>
        </charge-info>
        <sku-charge-price>
           <amount>'.$amount.'</amount>
           <currency>'.$currency.'</currency>
        </sku-charge-price>
        <expected-total-price>
           <amount>'.$amount.'</amount>
           <currency>'.$currency.'</currency>
        </expected-total-price>
    </subscription-charge>';



/**
 * Initialize handle and set options
*/
$url= 'https://sandbox.plimus.com/services/2/subscriptions/'.$subscriptionId.'/subscription-charges/';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/xml"));
curl_setopt($ch, CURLOPT_USERPWD, "$credentials");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlToSend);	
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);

/**
 * Execute Curl call and extract header and body contant
*/
$result = curl_exec($ch);
$info = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
curl_close($ch);
$header = substr($result, 0, $info);
$body = substr($result, $info);

echo format_header($header, $body);
?>

