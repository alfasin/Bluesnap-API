<?php

/*
 * Written By: Ben Hultin & Nir Alfasi (alfasin)
 * Nov. 2012
 * 
 * This code is used to call an API (RESTful) service of Bluesnap that creates a shopper entity on Bluesnaps' backend.
 * The shopper-entity can be used, later-on, for one-click purchases, to automate charges, to retrieve orders history and more.
 * We chose using CURL to place the API request in this demo, but we urge anyone who implements an API client 
 * to work with a RESTful API client framework in order to have a full support of all the properties of REST, such as: 
 * set HTTP method, get return-code, read/write headers, full XML support etc.
 * 
 * Documentation on "create shopper" service can be found here: 
 * http://home.plimus.com/DocumentationCenter/Static/Plimus%20Web%20Services_create_shopper.htm
 * 
 * The API manual is available under "BuyAnyware" section: 
 * http://home.plimus.com/DocumentationCenter/Static/default.htm
 * 
 * This code example is also available from: 
 * http://alfasin.com/api/create_shopper.html
 *
 * and if you want to see it "in action": 
 * http://alfasin.com/create_shopper.php
 * 
 */
    

    // In the response of "create-shopper" API call we'll receive the 
    // newly created shopper-id which we should extract from the Location header. 
    // An example for such header: 
    // 
    // ...
    // Location: https://sandbox.plimus.com/services/2/shoppers/19372564
    // ...
    function get_shopper_from_header($ch, $string) {
        global $shopper_id;       
        //looking for the "Location" header - but since it's case insensitive...
        if(strpos($string, "ocation") > -1){ 
            $tokens = explode("/", $string);
            //the shopper-id will always be the last token
            $shopper_id = trim($tokens[count($tokens)-1]); 
        }  
        return strlen($string);
    }

   // TODO: Change the following URL to our rackspace machine
   // An example of how to call this PHP code:
   // http://alfasin.com/blog/code/create_shopper.php?firstName=bob&lastName=Smith&email=bob.Smith@gmail.com&address1=123 Main Street&address2=Apt K-9&city=Parkville&state=TN&country=us&phone=411-555-1212&zipcode=37027

   // read the request parameters and handle special chars 
   $firstName = htmlspecialchars($_REQUEST['firstName']);     	 
   $lastName  = htmlspecialchars($_REQUEST['lastName']);     		 
   $email     = htmlspecialchars($_REQUEST['email']);                    
   $address1  = htmlspecialchars($_REQUEST['address1']);
   $address2  = htmlspecialchars($_REQUEST['address2']);
   $city      = htmlspecialchars($_REQUEST['city']);
   $state     = htmlspecialchars($_REQUEST['state']);
   $country   = htmlspecialchars($_REQUEST['country']);
   $zipCode   = htmlspecialchars($_REQUEST['zipcode']);
   $phone     = htmlspecialchars($_REQUEST['phone']);
      
   // EXAMPLE INPUT
   // -------------
 $firstName = 'Bob';   		 
 $lastName = 'Smith';     		 
 $email = "bob.smith@plimus.com";      
 $address1 = "123 Main Street";
 $address2 = "Apt K-9";
 $city = "Parkville";
 $state = "TN";
 $country = "us";
 $zipCode = "37027";
 $phone = "411-555-1212";
   

   // In the following XML (which will be embedded in the BODY of the HTTP request) 
   // the <web-info> element supposed to hold customers' information (IP, browser type etc) 
    $xmlToSend = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>
    <shopper xmlns=\"http://ws.plimus.com\">
          <shopper-info>
                <shopper-contact-info>
                      <first-name>". $firstName ."</first-name>
                      <last-name>". $lastName ."</last-name>
                      <email>". $email ."</email>
                      <address1>". $address1 ."</address1>
                      <city>". $city ."</city>
                      <zip>". $zipCode ."</zip>
                      <country>". $country ."</country>
                      <state>". $state ."</state>
                      <phone>". $phone ."</phone>
                </shopper-contact-info>
                <locale>en</locale>
          </shopper-info>          
          <web-info> 
                <ip>62.219.121.253</ip>
                <remote-host>bzq-219-121-253.static.bezeqint.net.reinventhosting.com</remote-host>
                <user-agent>Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; GTB6.3; .NET CLR 2.0.50727</user-agent>
                <accept-language>en-us</accept-language>
          </web-info>
    </shopper>";

    // Set values for the POST HEADERS:
    // The URL sets the REST resource which is being called 
    $service = 'https://sandbox.plimus.com/services/2/shoppers';
    // for the sandbox testing account TODO: include this parameter from another file and remove explicit credentials from the code
    $credentials = 'XXX:YYY'; 
    $contentType = array('Content-type: application/xml');
    
    // Initialize handle and set options
    $ch = curl_init();
    // more info about setopt options can be found here: http://www.php.net/manual/en/function.curl-setopt.php
    curl_setopt($ch, CURLOPT_URL, $service); 
    curl_setopt($ch, CURLOPT_USERPWD, $credentials); // authentication (credentials) string encoded in base-64 
    
    curl_setopt($ch, CURLOPT_HEADER, true);          // include the headers in the output
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // don't output the response to screen (default behavior)
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlToSend);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $contentType);    
    curl_setopt($ch, CURLOPT_HEADERFUNCTION, 'get_shopper_from_header');
    
    // The following switches are needed only when running in development-mode on localhost
    //    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // follow redirects recursively 
    //    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // default value is "2": "check the existence of a common name and also verify that it matches the hostname provided" - we need to turn it off
    //    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // "stop cURL from verifying the peer's certificate"
    
    // For debugging purposes - we can ask the remote server to include all the request-headers in the response
    //    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    

    // Execute the request and example code to time the transaction
    $response = curl_exec($ch);           
    // if a shopper was successfully created we should receive "201 created" response code
    // and the shopper-id will be extracted into $shopper_id by get_shopper_from_header() which will iterate the response headers
     
    // Check for errors
    if ( curl_errno($ch) ) {
        echo 'HTTP error code: ' . curl_errno($ch) . '<br>error-message: "' . curl_error($ch) . '"';
        return;
    } 

    // SUCCESS
    if (is_numeric($shopper_id)) {
	echo '<br>
              A new shopper entity was created on our servers with shopper-id: '
              . $shopper_id .
              '<br><br>';
    }
    // FAIL
    else {
        echo '<br><br>
              <font color="red"><b>Something went wrong!</b></font>
              <br>
              Server reponse:
              <br><br>
              <pre style="display: block; font-family: monospace; white-space: pre; margin: 1em 0px;">'
              . $response .
              '</pre><br>';
    }
?>
