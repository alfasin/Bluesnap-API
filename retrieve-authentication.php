<?php 
/*
 * Written By: Ben Hultin & Nir Alfasi (alfasin)
 * Nov. 2012
 * 
 * This code is used to call an API (RESTful) service of Bluesnap that retrieves an authentication token which can be used to "auto-login" the shopper into
 * one of the following pages: BuyNow - customer information page, Buynow - Billing info page and shopper control panel.
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
$desiredTime = $_REQUEST['desired-time'];
$desiredTime = '15';


/**
 * Initialize handle and set options
*/
$url = "https://sandbox.plimus.com/services/2/tools/auth-token?shopperId=".$shopperId."&expirationInMinutes=".$desiredTime;

$ch = curl_init();
curl_setopt($ch, CURLOPT_USERPWD, $credentials); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 


/**
 * Execute Curl call and extract header and body contant
*/
$result = curl_exec($ch);
curl_close($ch);

echo show_xml($result);
?>
