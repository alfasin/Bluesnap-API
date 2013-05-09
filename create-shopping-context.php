<?php
/*
 * Written By: Ben Hultin & Nir Alfasi (alfasin)
 * Nov. 2012
 * 
 * This code is used to call an API (RESTful) service of Bluesnap that creates a shopping-context
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
$title = $_REQUEST['title'];
$firstName = $_REQUEST['first-name'];  
$lastName = $_REQUEST['last-name'];   
$email = $_REQUEST['email'];  
$companyName = $_REQUEST['company-name'];
$address1 = $_REQUEST['address1'];
$address2 = $_REQUEST['address2'];
$city = $_REQUEST['city'];
$state = $_REQUEST['state'];
$country = $_REQUEST['country'];
$zipCode = $_REQUEST['zip'];
$phone = $_REQUEST['phone'];
$fax = $_REQUEST['fax'];
$cardNumber = $_REQUEST['card-number'];     		 
$cardType = $_REQUEST['card-type'];
$amount = $_REQUEST['amount'];
$expMonth = $_REQUEST['expiration-month'];
$expYear = $_REQUEST['expiration-year'];
$expCode = $_REQUEST['security-code']; 
$skuId = $_REQUEST['sku-id'];
$amount = $_REQUEST['amount'];
$quantity = $_REQUEST['quantity'];
      
        
/**
 * Assemble the XML string with variables instantiated above
*/    
$xmlToSend = '     
<shopping-context xmlns="http://ws.plimus.com">
  <web-info>
    <ip>'.$ipAddress.'</ip>
    <remote-host>
    bzq-219-121-253.static.bezeqint.net.reinventhosting.com</remote-host>
    <user-agent>Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1;
    SV1; GTB6.3; .NET CLR 2.0.50727)</user-agent>
    <accept-language>en-us</accept-language>
  </web-info>
  <shopper-details>
    <shopper>
      <shopper-info>
        <shopper-contact-info>
          <title>Mr</title>
          <first-name>'. $firstName .'</first-name>
            <last-name>'. $lastName .'</last-name>
            <company-name>'.$companyName.'</company-name>
            <email>'. $email .'</email>
            <address1>'. $address1 .'</address1>
            <city>'. $city .'</city>
            <zip>'. $zipCode .'</zip>
            <country>'. $country .'</country>
            <state>'. $state .'</state>
            <phone>'. $phone .'</phone>
            <fax>'. $fax .'</fax>
        </shopper-contact-info>
        <shipping-contact-info>
          <first-name>'. $firstName .'</first-name>
            <last-name>'. $lastName .'</last-name>
            <company-name>'.$companyName.'</company-name>
            <email>'. $email .'</email>
            <address1>'. $address1 .'</address1>
            <city>'. $city .'</city>
            <zip>'. $zipCode .'</zip>
            <country>'. $country .'</country>
            <state>'. $state .'</state>
        </shipping-contact-info>
        <invoice-contacts-info>
          <invoice-contact-info>
            <default>true</default>
            <company-name>BlueSnap UK</company-name>
            <vat-code></vat-code>
            <title>'.$title.'</title>
            <first-name>'. $firstName .'</first-name>
            <last-name>'. $lastName .'</last-name>
            <company-name>'.$companyName.'</company-name>
            <email>'. $email .'</email>
            <address1>'. $address1 .'</address1>
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
                    <city>'. $city .'</city>
                    <zip>'. $zipCode .'</zip>
                    <country>'. $country .'</country>
                    <state>'. $state .'</state>
                </billing-contact-info>
                <credit-card>     
                    <card-number>'.$cardNumber.'</card-number>
                    <card-type>'.$cardType.'</card-type>
                    <expiration-month>'.$expMonth.'</expiration-month>
                    <expiration-year>'.$expYear.'</expiration-year>
                    <security-code>'.$expCode.'</security-code> 
              </credit-card>
            </credit-card-info>
          </credit-cards-info>
        </payment-info>
        <store-id>'.$storeId.'</store-id>
        <vat-code></vat-code>
        <shopper-currency>USD</shopper-currency>
        <locale>en</locale>
      </shopper-info>
    </shopper>
  </shopper-details>
  <order-details>
    <order>
      <cart>
        <cart-item>
          <sku>
            <sku-id>'.$skuId.'</sku-id> 
            <sku-charge-price>
              <charge-type>initial</charge-type>
              <amount>'.$amount.'</amount>
              <currency>USD</currency>
            </sku-charge-price>
          </sku>
          <quantity>'.$quantity.'</quantity>
        </cart-item>
      </cart>
      <expected-total-price>
        <amount>'.$amount.'</amount>
        <currency>USD</currency>
      </expected-total-price>
    </order>
  </order-details>
</shopping-context>';


/**
 * Initialize handle and set options
*/    
$url = 'https://sandbox.plimus.com/services/2/shopping-context';

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



