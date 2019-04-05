<?php
require_once("class.chargeCard.php");
require_once("config.php");

// https://developers.braintreepayments.com/guides/credit-cards/testing-go-live/php

$customerDetails = array(
	'creditCardNumber' => '4111111111111111',
	'expiryMonth' => 02,
	'expiryYear' => 2020,
	'cvv' => 777,
	'amount' => 120
	);

//uncomment this block to charge the credit card without saving the payment details like last 4 digits and token. Refer to point for more details ??
/*$braintree = new chargeCard($configDetails);
$chargeCard = $braintree->chargeCardOneTime($customerDetails);

if($chargeCard->success)
{
	echo "Payment Received Succecssfully";
}
else
{
	echo "Payment Failed";
}*/

$customerDetails['cardHolderName'] = "tushar rane";
$customerDetails['firstName'] = "tushar";
$customerDetails['lastName'] = "rane";
$customerDetails['email'] = "tusharvr09@gmail.com";
$customerDetails['id'] = "9807";

//uncomment this block to save the payment details like last 4 digits and token for recurring payments. Refer to point for more details ??
/*$chargeCard = $braintree->chargeAndSaveCard($customerDetails);

if($chargeCard->success)
{
	echo "Payment Received Succecssfully <br/>";
	echo "Your payment token is ". $chargeCard->transaction->creditCard['token'];
}
else
{
	echo "Payment Failed";
}*/



//first charge the card using the save and charge card code and use the token which you will get there, paste the token here and again charge the card so this    //time you do not need any credit card number, expiration or cvv. Its the feature usually people call as save card for future use or auto charge for subscriptions
/*$existingChargeCard = array(
		'amount' => 1200,
		'token' => 'gpzkxn'
	);

$chargeCard = $braintree->chargeOnSavedCard($existingChargeCard);

if($chargeCard->success)
{
	echo "Payment Received Succecssfully <br/>";
	echo "Your payment token is ". $chargeCard->transaction->creditCard['token'];
}
else
{
	// if payment is failing is due to rejected payment gateway duplicate transaction, then try after sometime or else disable the setting
	// from braintree control panel
	echo "Payment Failed";
}*/

