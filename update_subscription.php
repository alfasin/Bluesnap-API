
<?php
/*
 * Written By: Ben Hultin & Nir Alfasi (alfasin)
 * Nov. 2012
 * 
 * This code is used to call an API (RESTful) service of Bluesnap that updates a subscription.
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

function format_header($str, $body)
{
    $response = '<div class="http_header" ><h3>Headers:</h3><br /><div class="header_responses" ><b>Status:</b> ';
    $str = str_replace("Date:","<br /><b>Date:</b>",$str);
    $str = str_replace("Server:","<br /><b>Server:</b>",$str);
    $str = str_replace("Location:","<br /><b>Location:</b>",$str);
    $str = str_replace("Content-Length:","<br /><b>Content-Length:</b>",$str);
    $str = str_replace("Connection:","<br /><b>Connection:</b>",$str);
    $str = str_replace("Content-Type:","<br /><b>Content-Type:</b>",$str);
    $response .= $str.'</div>';
    if(!empty($body)){
        //$response .= '<h3>Body:</h3><br />';
        $response .= '<p>'.$body.'</p>';
    }
    else{
        $response .= 'No body information to return';
    }
    $response .= '</div>';
    return $response;
}

/**
 * Retrieve data from input fields
*/
$subscriptionId = $_REQUEST['subscription-id'];
$shopperId = $_REQUEST['shopper-id'];
$skuId = $_REQUEST['subscribe-sku-id']; 
$amount = $_REQUEST['amount']; 
$nextChargeDate = $_REQUEST['next-charge-date']; 
$lastFourCC = $_REQUEST['card-last-four-digits'];
$cardType = $_REQUEST['card-type'];


/**
 * Assemble the XML string with variables instantiated above
*/
$xmlToSend = '
<subscription xmlns="http://ws.plimus.com">
      <status>A</status>
      <underlying-sku-id>'.$skuId.'</underlying-sku-id>
      <shopper-id>'.$shopperId.'</shopper-id>
      <override-recurring-charge>
            <currency>USD</currency>
            <amount>'.$amount.'</amount>
      </override-recurring-charge>
      <next-charge-date>'.$nextChargeDate.'</next-charge-date>
      <credit-card>
            <card-last-four-digits>'.$lastFourCC.'</card-last-four-digits>
            <card-type>'.$cardType.'</card-type>
      </credit-card>
</subscription>';


/**
 * Initialize handle and set options
*/
$url = 'https://sandbox.plimus.com/services/2/subscriptions/'.$subscriptionId;

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
