<?php
/*
 * Written By: Ben Hultin & Nir Alfasi (alfasin)
 * Nov. 2012
 * 
 * This code is used to call an API (RESTful) service of Bluesnap that updates shopper-information (for example: address, email, name etc).
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
$shopperId = $_REQUEST['shopper-id'];
$firstName = $_REQUEST['first-name'];  
$lastName = $_REQUEST['last-name']; 
$email = $_REQUEST['email']; 
$companyName = $_REQUEST['company-name'];
$address1 = $_REQUEST['address1'];
$city = $_REQUEST['city'];
$state = $_REQUEST['state'];
$country = $_REQUEST['country'];
$zipCode = $_REQUEST['zip'];
$phone = $_REQUEST['phone'];


/**
 * Assemble the XML string with variables instantiated above
*/
$xmlToSend = '
<shopper xmlns="http://ws.plimus.com">
      <shopper-info>
            <shopper-contact-info>
                  <first-name>'. $firstName .'</first-name>
                  <last-name>'. $lastName .'</last-name>
                  <email>'. $email .'</email>
                  <company-name>'.$companyName.'</company-name>
                  <address1>'. $address1 .'</address1>
                  <city>'. $city .'</city>
                  <zip>'. $zipCode .'</zip>
                  <country>'. $country .'</country>
                  <state>'. $state .'</state>
                  <phone>'. $phone .'</phone>
            </shopper-contact-info>
            <locale>en</locale>
      </shopper-info>
      <web-info>
            <ip>209.128.64.62</ip>
            <remote-host>bzq-219-121-253.static.bezeqint.net.reinventhosting.com</remote-host>
            <user-agent>Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; GTB6.3; .NET CLR 2.0.50727</user-agent>
            <accept-language>en-us</accept-language>
      </web-info>

</shopper>';
	

/**
 * Initialize handle and set options
*/
$url = 'https://sandbox.plimus.com/services/2/shoppers/'.$shopperId;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/xml'));
curl_setopt($ch, CURLOPT_USERPWD, "$credentials");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlToSend);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
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
