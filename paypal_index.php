<?php
require_once("class.chargeCard.php");
require_once("config.php");
error_reporting(-1);
ini_set('display_errors', 'On');

if(!isset($_GET['type']) || empty($_GET['type']))
{
	$redirectViewUrl = $_SERVER['HOST'].'/braintree-demo/paypal_view.php';
	header("Location: ".$redirectViewUrl);
	exit;
}	

if($_GET['type'] == "chargeFirstTimeCards")
{
	$nonce = $_POST['nonce'];
	$amount = $_POST['amount'];
	$paypalObject = new chargeCard($configDetails);
	$paypalPaymentResult = $paypalObject->chargeAndSavePaypal($nonce, $amount);

//try to print the above result, you will get alot of data and information about the payment and i believe it will help you to store some of those details in your database

//i am passing token in output view just as a part of education purpose, in real world production, do not ever do it 
	if($paypalPaymentResult->success)
	{
		echo json_encode(array('status' => 'success', 'message' => "Payment has been done successfully, Your valuted payment token is -: ".$paypalPaymentResult->transaction->paypal['token']." . **** CAUTION : DO NOT PASS THIS TOKEN IN PUBLIC OR IN VIEW, I HAVE GIVEN OUT THIS TOKEN JUST FOR THE PURPOSE OF THIS TUTORIAL. IN PRODUCTION MAKE SURE YOU NEVER STORE THIS TOKEN ON VIEW"));
		exit;
	}
	else
	{
		echo json_encode(array('status' => 'error', 'message' => 'Payment Failed'));
	}
}

// To charge the paypal account for recurring payment or valuted payment the use the below code and url
// http://localhost/braintree-demo/paypal_index.php?type=chargeSavedPaypalAccount
if($_GET['type'] == "chargeSavedPaypalAccount")
{
	$paymentDetails = array(
			'amount' => 200,
			'token' => '7t7pqc' //paste the token which i gave out in the alert message
		);

	$paypalObject = new chargeCard($configDetails);
	$paypalPaymentResult = $paypalObject->chargeOnSavedCard($paymentDetails);

	// echo "<pre>";
	// print_r($paypalPaymentResult);
	// die();	

	if($paypalPaymentResult->success)
	{
		echo "Payment charged successfully";
		exit;
	}

	echo "Payment failed";
}

