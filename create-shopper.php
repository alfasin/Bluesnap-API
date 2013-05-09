<?php
/*
 * Written By: Ben Hultin & Nir Alfasi (alfasin)
 * Nov. 2012
 * 
 * This code is used to call an API (RESTful) service of Bluesnap that creates a shopper entity on Bluesnaps' backend.
 * The shopper-entity can be used, later-on, for one-click purchases, automate charges, retrieve orders history and more.
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
$title      = htmlspecialchars($_REQUEST['title']);
$firstName  = htmlspecialchars($_REQUEST['first-name']);   		 
$lastName   = htmlspecialchars($_REQUEST['last-name']);     		 
$email      = htmlspecialchars($_REQUEST['email']);                    
$address1   = htmlspecialchars($_REQUEST['address1']);
$address2   = htmlspecialchars($_REQUEST['address2']);
$companyName = htmlspecialchars($_REQUEST['company-name']);
$city       = htmlspecialchars($_REQUEST['city']);
$state      = htmlspecialchars($_REQUEST['state']);
$country    = htmlspecialchars($_REQUEST['country']);
$zipCode    = htmlspecialchars($_REQUEST['zip']);
$phone      = htmlspecialchars($_REQUEST['phone']);
$cardNumber = htmlspecialchars($_REQUEST['card-number']);
$cardType   = htmlspecialchars($_REQUEST['card-type']);
$expMonth   = htmlspecialchars($_REQUEST['expiration-month']);
$expYear    = htmlspecialchars($_REQUEST['expiration-year']);
$secCode    = htmlspecialchars($_REQUEST['security-code']);
$currency   = htmlspecialchars($_REQUEST['currency']);
   

/**
 * Assemble the XML string with variables instantiated above
*/
$xmlToSend = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<shopper xmlns="http://ws.plimus.com">
      <shopper-info>
            <shopper-contact-info>
                    <title>'.$title.'</title>
                  <first-name>'. $firstName .'</first-name>
                  <last-name>'. $lastName .'</last-name>
                  <email>'. $email .'</email>
                  <address1>'. $address1 .'</address1>
                  <city>'. $city .'</city>
                  <zip>'. $zipCode .'</zip>
                  <country>'. $country .'</country>
                  <state>'. $state .'</state>
                  <phone>'. $phone .'</phone>
            </shopper-contact-info>
            <shipping-contact-info>
                    <first-name>'. $firstName .'</first-name>
                      <last-name>'. $lastName .'</last-name>
                      <address1>'. $address1 .'</address1>
                      <address2/>
                      <city>'. $city .'</city>
                      <state>'. $state .'</state>
                      <zip>'. $zipCode .'</zip>
                      <country>'. $country .'</country>
                </shipping-contact-info> 
                <invoice-contacts-info>
                    <invoice-contact-info>
                       <default>true</default>
                       <company-name>'.$companyName.'</company-name>
                       <vat-code></vat-code>
                       <title>'.$title.'</title>
                       <first-name>'. $firstName .'</first-name>
                        <last-name>'. $lastName .'</last-name>
                        <company-name>'.$companyName.'</company-name>
                        <email>'. $email .'</email>
                        <address1>'. $address1 .'</address1>
                        <address2/>
                        <city>'. $city .'</city>
                        <zip>'. $zipCode .'</zip>
                        <country>'. $country .'</country>
                        <state>'. $state .'</state>
                        <phone>'. $phone .'</phone>
                        <fax>'. $fax .'</fax>
                     </invoice-contact-info>
                 </invoice-contacts-info>
                 <payment-info> 
                    <credit-cards-info>  
                      <credit-card-info>
                           <billing-contact-info>
                               <first-name>'. $firstName .'</first-name>
                                <last-name>'. $lastName .'</last-name>
                                <address1>'. $address1 .'</address1>
                                <address2/>
                                <city>'. $city .'</city>
                                <state>'. $state .'</state>
                                <zip>'. $zipCode .'</zip>
                                <country>'. $country .'</country>
                           </billing-contact-info>
                           <credit-card>              
                              <card-number>'.$cardNumber.'</card-number>
                               <card-type>'.$cardType.'</card-type>
                               <expiration-month>'.$expMonth.'</expiration-month>
                               <expiration-year>'.$expYear.'</expiration-year>
                               <security-code>'.$secCode.'</security-code>                             
                               </credit-card>
                       </credit-card-info>
                     </credit-cards-info>
                   </payment-info>
                   <store-id>'.$storeId.'</store-id>
                   <vat-code></vat-code>
                   <shopper-currency>'.$currency.'</shopper-currency>
            <locale>en</locale>
      </shopper-info>          
      <web-info> 
            <ip>'.$ipAddress.'</ip>
            <remote-host>bzq-219-121-253.static.bezeqint.net.reinventhosting.com</remote-host>
            <user-agent>Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; GTB6.3; .NET CLR 2.0.50727</user-agent>
            <accept-language>en-us</accept-language>
      </web-info>
</shopper>';

  

/**
 * Initialize handle and set options
*/
$url = 'https://sandbox.plimus.com/services/2/shoppers';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_USERPWD, $credentials);        
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlToSend);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/xml'));    
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);


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
