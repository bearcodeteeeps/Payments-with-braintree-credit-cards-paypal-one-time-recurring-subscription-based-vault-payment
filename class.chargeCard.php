<?php
require_once("vendor/autoload.php");

class ChargeCard{
	
	private $gateway;
	private $configDetails = array();
	private $cardDetails = array();
	private $responseData;
	private $token;

	function __construct($configDetails)
	{
		// https://developers.braintreepayments.com/start/hello-server/php
		$this->configDetails = $configDetails;
		$this->gateway = new Braintree_Gateway($configDetails);
	}

	public function chargeCardOneTime($customerDetails)
	{
		// https://developers.braintreepayments.com/reference/request/transaction/sale/php
		return $this->gateway->transaction()->sale([
				'amount' => $customerDetails['amount'],
				'creditCard' => array(
						'number' => $customerDetails['creditCardNumber'],
						'expirationMonth' => $customerDetails['expiryMonth'],
						'expirationYear' => $customerDetails['expiryYear'],
						'cvv' => $customerDetails['cvv']
					),
				'options' => array(
						'submitForSettlement' => true
					)
			]);
	}


	public function chargeAndSaveCard($customerDetails)
	{
		// https://developers.braintreepayments.com/reference/request/transaction/sale/php
		return $this->gateway->transaction()->sale([
				'amount' => $customerDetails['amount'],

				'creditCard' => array(
						'cardholderName' => $customerDetails['cardHolderName'],
						'number' => $customerDetails['creditCardNumber'],
						'expirationMonth' => $customerDetails['expiryMonth'],
						'expirationYear' => $customerDetails['expiryYear'],
						'cvv' => $customerDetails['cvv']
					),

				'customer' => array(
						'email' => $customerDetails['email'],
						'firstName' => $customerDetails['firstName'],
						'lastName' => $customerDetails['lastName'],
						'id' => $customerDetails['id']
					),

				'options' => array(
						'submitForSettlement' => true,
						'storeInVaultOnSuccess' => true
					)
			]);		
	}

	public function chargeOnSavedCard($cardDetails)
	{
		return $this->gateway->transaction()->sale([
				'amount' => $cardDetails['amount'],
				'paymentMethodToken' => $cardDetails['token']
			]);
	}

	public function getClientToken()
	{
		return $this->gateway->clientToken()->generate();
	}


	public function chargeAndSavePaypal($nonce, $amount)
	{
		return $this->gateway->transaction()->sale([
					'amount' => $amount,
					'paymentMethodNonce' => $nonce,
					'options' => array(
							'submitForSettlement' => true,
							'storeInVaultOnSuccess' => true
						)
			]);
	}

}