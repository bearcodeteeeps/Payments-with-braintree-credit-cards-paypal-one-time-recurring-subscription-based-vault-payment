<?php
error_reporting(-1);
ini_set('display_errors', 'On');

require_once("class.chargeCard.php");
require_once("config.php");

$chargeCard = new ChargeCard($configDetails);
$clientAuthorization = $chargeCard->getClientToken();

?>
<!DOCTYPE html>
<html>
<head>
	<title>PayPal With Braintree</title>
	<script src="js/jquery-3.3.1.min.js" type="text/javascript"></script>
	<script src="js/sweetalert.min.js" type="text/javascript"></script>
	<script src="https://www.paypalobjects.com/api/checkout.js" data-version-4 log-level="warn"></script>
	<script src="https://js.braintreegateway.com/web/3.44.0/js/client.min.js"></script>
	<script src="https://js.braintreegateway.com/web/3.44.0/js/paypal-checkout.min.js"></script>
</head>
<body>

	<div id="paypal-button"></div>

<script type="text/javascript">
		// Create a client.
braintree.client.create({
  authorization: '<?php echo $clientAuthorization; ?>'
}, function (clientErr, clientInstance) {

  // Stop if there was a problem creating the client.
  // This could happen if there is a network error or if the authorization
  // is invalid.
  if (clientErr) {
    console.error('Error creating client:', clientErr);
    return;
  }

  // Create a PayPal Checkout component.
  braintree.paypalCheckout.create({
    client: clientInstance
  }, function (paypalCheckoutErr, paypalCheckoutInstance) {

    // Stop if there was a problem creating PayPal Checkout.
    // This could happen if there was a network error or if it's incorrectly
    // configured.
    if (paypalCheckoutErr) {
      console.error('Error creating PayPal Checkout:', paypalCheckoutErr);
      return;
    }

    // Set up PayPal with the checkout.js library
    paypal.Button.render({
      env: 'sandbox', // or 'production'

      payment: function () {
        return paypalCheckoutInstance.createPayment({
          // Your PayPal options here. For available options, see
          // http://braintree.github.io/braintree-web/current/PayPalCheckout.html#createPayment

            flow: 'vault',
	        currency: 'USD',
	        amount: '10.00',
        });
      },

      onAuthorize: function (data, actions) {
        return paypalCheckoutInstance.tokenizePayment(data, function (err, payload) {
          // Submit `payload.nonce` to your server.

          $.ajax({
          		url : "<?php echo 'http://localhost/braintree-demo/paypal_index.php?type=chargeFirstTimeCards' ?>",
          		type : "POST",
          		data : {nonce : payload.nonce, amount : 10},
          		dataType : "json",
          		success : function(response)
          		{	
          			swal(response.status, response.message, response.status);
          			exit;
          		}
          });
        });
      },

      onCancel: function (data) {
        console.log('checkout.js payment cancelled', JSON.stringify(data, 0, 2));
      },

      onError: function (err) {
        console.error('checkout.js error', err);
      }
    }, '#paypal-button').then(function () {
      // The PayPal button will be rendered in an html element with the id
      // `paypal-button`. This function will be called when the PayPal button
      // is set up and ready to be used.
    });

  });

});
</script>

</body>
</html>