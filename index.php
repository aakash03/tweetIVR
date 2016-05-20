<?php
session_start();
require_once("response.php");//include KooKoo library
ini_set('display_errors', 1);
require_once('TwitterAPIExchange.php');
$r=new Response(); //create a response object
$cd = new CollectDtmf();
$r->l=1;
$r->o=2000;
$r->speed=8;
$repeat=0;
$n=0;
$settings = array(
    'oauth_access_token' => "",
    'oauth_access_token_secret' => "",
    'consumer_key' => "",
    'consumer_secret' => ""
);
$servername = "";
$username = "";
$password = "";

// Create connection

//twilio settings
require_once 'Services/Twilio.php';
// Library version.
$version = "2010-04-01";
// Set your account ID and authentication token.
$sid = "";
$token = "";
$from_number = "";

if($_REQUEST['event']=="NewCall") //when a new call comes...
{
        $_SESSION['cid']=$_REQUEST['cid'];
		$no=$_REQUEST['cid'];
		$conn = mysqli_connect($servername, $username, $password,'tweetivr');
		$result = mysqli_query($conn,"select name,username from tweetivr where  number=$no ");
		$row_cnt = $result->num_rows;
		$row = mysqli_fetch_assoc($result);
		if ($row_cnt==0) 
		{ 
			 $r->addPlayText("Sorry. This number is not registered."); 
			 $r->addPlayText('Thank you for calling, have a nice day');
			 $to_number = "+91".$no;
			$message = "Register at http://tweetivr.azurewebsites.net/";
			// Create the call client.
			$client = new Services_Twilio($sid, $token, $version);
			// Send the SMS message.
			try
			{
				$client->account->messages->sendMessage($from_number, $to_number, $message);
			}
			catch (Exception $e) 
			{
				echo 'Error: ' . $e->getMessage();
			}
		
    		 $r->addHangup();
			 $r->send();
		}
		else
		{
				$name=$row['name'];
			$uname=$row['username'];
			$r->addPlayText("Welcome".$name);
			$r->addPlayText("Latest tweets from".$uname);
			
			
			$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
			$getfield = '?screen_name='.$uname.'&count=20';
			$requestMethod = 'GET';
			$twitter = new TwitterAPIExchange($settings);

				$response = $twitter->setGetfield($getfield)
									->buildOauth($url, $requestMethod)
									->performRequest();

		$tweets = json_decode($response,true);
		$items=array();
		foreach ($tweets as $tweet) {
			$items[]= $tweet['text'];
		}
		$n= sizeof($items);
		for($i=0;$i<$n;$i++)
		{
			$regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?).*$)@";
			$items[$i]= preg_replace($regex, ' ', $items[$i]);
			$items[$i]=preg_replace('/[^A-Za-z0-9\-\(\) ]/', '', $items[$i]);
			$r->addPlaytext($items[$i]);
		}
		$r->addPlayText('Thank you for calling, have a nice day');
		   $r->addHangup();
		$r->send();
		}
}
else
{
	$r->addPlayText('Thank you for calling, have a nice day');
       $r->addHangup();
	$r->send();
}
?>
