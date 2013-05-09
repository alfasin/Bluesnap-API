<?php
/*
 * Written By: Ben Hultin & Nir Alfasi (alfasin)
 * Nov. 2012
 * 
 * This code is used to call an API (RESTful) service of Bluesnap that creates a new shopper while making a purchase on the new account in a single call.
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
$skuId = $_REQUEST['sku-id'];
$cardNumber = $_REQUEST['card-number'];     		 
$cardType = $_REQUEST['card-type'];
$amount = $_REQUEST['amount'];
$expMonth = $_REQUEST['expiration-month'];
$expYear = $_REQUEST['expiration-year'];
$expCode = $_REQUEST['security-code'];    
        
        
/**
 * Assemble the XML string with variables instantiated above
*/     
$xmlToSend = '       
<batch-order xmlns="http://ws.plimus.com">
    <shopper>
        <web-info>
            <ip>62.219.121.253</ip>        
            <remote-host>bzq-219-121-253.static.bezeqint.net.reinventhosting.com</remote-host>
            <user-agent>Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; GTB6.3; .NET CLR 2.0.50727)</user-agent>
            <accept-language>en-us</accept-language>
        </web-info>
    <seller-shopper-id></seller-shopper-id>
    <shopper-info>
        <shopper-contact-info>
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
                    <vat-code></vat-code>
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
    </shopper-info>
</shopper>
    <order>
        <soft-descriptor>SoftDesc</soft-descriptor>
        <ordering-shopper>
            <seller-shopper-id></seller-shopper-id>
            <web-info>
                <ip>'.$ipAddress.'</ip>        
                <remote-host>bzq-219-121-253.static.bezeqint.net.reinventhosting.com</remote-host>
                <user-agent>Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; GTB6.3; .NET CLR 2.0.50727)</user-agent>
                <accept-language>en-us</accept-language>
            </web-info>
        </ordering-shopper>
        <cart>
            <cart-item>
                <sku>
                    <sku-id>'.$skuId.'</sku-id>          
                    <soft-descriptor>descTest</soft-descriptor>
                </sku>
                <quantity>1</quantity>
                    </cart-item>
            </cart>
        <expected-total-price>
            <amount>'.$amount.'</amount>
            <currency>USD</currency>
        </expected-total-price>
    </order>
</batch-order>';	


/**
 * Initialize handle and set options
*/
$url = 'https://sandbox.plimus.com/services/2/batch/order-placement';

$ch = curl_init();
curl_setopt($ch, CURLOPT_USERPWD, "$credentials");
curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/xml"));
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlToSend);
    
    
/**
 * Execute Curl call and display XML response
*/
$result = curl_exec($ch);
curl_close($ch);

echo show_xml($result);
?>
