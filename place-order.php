<?php
/*
 * Written By: Ben Hultin & Nir Alfasi (alfasin)
 * Nov. 2012
 * 
 * This code is used to call an API (RESTful) service of Bluesnap that places an order on Bluesnaps' backend.
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
$cardLastFour = $_REQUEST['card-last-four-digits'];     		 
$cardType = $_REQUEST['card-type'];
$skuId = $_REQUEST['sku-id'];
$amount = $_REQUEST['amount'];
$currency = $_REQUEST['currency'];
  

/**
 * Assemble the XML string with variables instantiated above
*/  
$xmlToSend = '<order xmlns="http://ws.plimus.com">
 <soft-descriptor></soft-descriptor>
   <ordering-shopper>
    <credit-card>
                  <card-last-four-digits>'.$cardLastFour.'</card-last-four-digits>
                  <card-type>'.$cardType.'</card-type>
    </credit-card>
    <shopper-id>'.$shopperId.'</shopper-id>
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
         </sku>
         <quantity>1</quantity>
        </cart-item>
   </cart>
   <expected-total-price>
      <amount>'.$amount.'</amount>
      <currency>'.$currency.'</currency>
   </expected-total-price>
</order>';
        
        
/**
 * Initialize handle and set options
*/
$url = 'https://sandbox.plimus.com/services/2/orders/';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/xml"));
curl_setopt($ch, CURLOPT_USERPWD, "$credentials");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlToSend);	
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);


/**
 * Execute Curl call and display XML response
*/
$response = curl_exec($ch);
curl_close($ch);

echo show_xml($response);
?>
