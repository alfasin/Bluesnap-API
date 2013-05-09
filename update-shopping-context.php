<?php
/*
 * Written By: Ben Hultin & Nir Alfasi (alfasin)
 * Nov. 2012
 * 
 * This code is used to call an API (RESTful) service of Bluesnap that updates a shopping-context
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

$shopperId = $_REQUEST['shopper-id'];
$shoppingId = $_REQUEST['shopping-id'];
$firstName = $_REQUEST['first-name'];  
$amount = $_REQUEST['amount'];
$skuId = $_REQUEST['sku-id'];
$quantity = $_REQUEST['quantity'];

        
/**
 * Assemble the XML string with variables instantiated above
*/    
$xmlToSend = ' 
<shopping-context xmlns="http://ws.plimus.com">
  <step>PLACED</step>
  <web-info>
    <ip>62.219.121.253</ip>
    <remote-host>
    bzq-219-121-253.static.bezeqint.net.reinventhosting.com</remote-host>
    <user-agent>Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1;
    SV1; GTB6.3; .NET CLR 2.0.50727)</user-agent>
    <accept-language>en-us</accept-language>
  </web-info>
  <order-details>
    <order>
      <ordering-shopper>
        <shopper-id>'.$shopperId.'</shopper-id>
      </ordering-shopper>
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
          <quantity>1</quantity>
          <sku-parameter>
            <param-name>name</param-name>
            <param-value>'.$firstName.'</param-value>
          </sku-parameter>
          <sku-parameter>
            <param-name>instructions</param-name>
            <param-value>Please follow the
            instructions</param-value>
          </sku-parameter>
          <sku-parameter>
            <param-name>terms and conditions</param-name>
            <param-value>Y</param-value>
          </sku-parameter>
          <sku-parameter>
            <param-name>color</param-name>
            <param-value>red</param-value>
          </sku-parameter>
          <item-sub-total>'.$amount.'</item-sub-total>
        </cart-item>
        <coupons>
          <coupons-total>0.00</coupons-total>
        </coupons>
        <tax>0.00</tax>
        <total-cart-cost>'.$amount.'</total-cart-cost>
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
$url = 'https://sandbox.plimus.com/services/2/shopping-context/'.$shoppingId;


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
