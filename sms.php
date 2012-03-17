<?php
// Set up Pubnub
include_once('Pubnub.php');
$pubnub = new Pubnub( "pub-key", "sub-key" );


// Get the useful parameters
$Body = $_GET['Body'];
$From = $_GET['From'];

// If empty body
if(strlen($Body)==0) return;

// If body is a number
if(is_numeric($Body)){
	$action = "vote";
} else {
	$action = "entry";
}

$name = $From;


// Use PubNub to send the message to the host
$pubnub->publish(array(
    'channel' => "image",
    'message' => array( $action => $Body, "author"=>$name)
));

// Respond to the user
echo "<Response><Sms>Your $action has been received.</Sms></Response>";

?>