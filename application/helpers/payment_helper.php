<?php
//For payments processing - We are using CardXecure
define('CONST_PAYMENT_MODE', 		'test'); //test | live
define('CONST_PAYMENT_API_KEY',		'28966ac8a0af1447d6f5bfba0e1428fc');
define('CONST_PAYMENT_TEST_URL',	'https://pay.icannpay.com/dev/index.php/');
define('CONST_PAYMENT_LIVE_URL',	'https://icannpay.com/index.php/');

function merchantSignup($_postParams=array())
{
	/*
		Params:
		
			$postParams = array();

				$postParams['api_key'] 		= '28966ac8a0af1447d6f5bfba0e1428fc';
				$postParams['email'] 		= 'umair.jaffar+1@gmail.com';
				$postParams['password'] 	= '123456';
				$postParams['rpassword'] 	= '123456';
				$postParams['fname'] 		= 'Umair';
				$postParams['lname'] 		= 'Jaffar';
				$postParams['cname'] 		= 'Umair Jaffar';
				$postParams['phone'] 		= '0987654321';
				$postParams['ccompany'] 	= 'TechNyx';
				$postParams['cdomain'] 		= 'TechNyx.com';
			
			
			Result - Success:
			{
				"response": 
				{
					"success": "success",
					"Authenticate Id": "f521db864807b9a09f4abb3deb828c29",
					"Authenticate Password": "2e9120d98798cb6b04eda313966604ad",
					"Secret key": "56e940c625a281.36647928",
					"mode": "Sandbox",
					"hash": "45e31e7a70479868110d16471f3795cd"
				}
			}
			
			Result - Fail:
			{
				"response":
				{
					"error": "The Phone field is required."
				}
			}
	*/
	
	$postParams['api_key']		= CONST_PAYMENT_API_KEY;
	$postParams['email'] 		= $_postParams['email'];
	$postParams['password'] 	= $_postParams['password'];
	$postParams['rpassword'] 	= $_postParams['password'];
	$postParams['fname'] 		= $_postParams['first_name'];
	$postParams['lname'] 		= $_postParams['last_name'];
	$postParams['cname'] 		= trim($_postParams['first_name'].' '.$_postParams['last_name']);
	$postParams['phone'] 		= '1112223334';		//Required Field: The Phone field is required.
	$postParams['ccompany'] 	= 'iCannPay';		//Required Field: The Company Name field is required.
	$postParams['cdomain'] 		= 'iCannPay.com';	//Required Field: The Domain Name field is required.
	$postParams['accname'] 		= 'Umair';			//Required Field: The Your bank account name field is required.
	$postParams['accno'] 		= '123';			//Required Field: The Your bank account number field is required.
	$postParams['bname'] 		= 'SCB';			//Required Field: The Name of bank field is required.
	$postParams['baddress'] 	= 'Abc Xyz Street';				//Required Field: The Address of your bank field is required.
	$postParams['swiftcode'] 	= '12345';			//Required Field: The Swift code field is required*/
	
	$apiResponse = sendRequestToPaymentGateway(CONST_PAYMENT_TEST_URL.'api/apiSignup', $postParams);
	
	//-->debug($apiResponse);
	
	$apiErrorMessage 	= '';
	$apiSuccessMessage 	= '';
	$apiData 			= array();
	if($apiResponse)
	{
		$apiResponse = (array) json_decode($apiResponse);
		
		if(isset($apiResponse['response']))
		{
			$apiResponse['response'] = (array) $apiResponse['response'];
			
			if(isset($apiResponse['response']['error']))
			{
				$apiErrorMessage = $apiResponse['response']['error'];
			}
			else if(isset($apiResponse['response']['success']))
			{
				$apiSuccessMessage = 1;
				
				$apiData = array(
									'authenticate_id' 			=> @$apiResponse['response']['Authenticate Id'],
									'authenticate_password' 	=> @$apiResponse['response']['Authenticate Password'],
									'secret_key' 				=> @$apiResponse['response']['Secret key'],
									'mode' 						=> @$apiResponse['response']['mode'],
									'hash' 						=> @$apiResponse['response']['hash']
					);
			}
		}
	}
	
	$apiResult = array();
	if($apiErrorMessage)
	{
		$apiResult['error'] = 'CardX: '.$apiErrorMessage;
	}
	else if($apiSuccessMessage)
	{
		$apiResult['success'] = $apiSuccessMessage;
		$apiResult['data'] = $apiData;
	}
	
	return $apiResult;
}

function getMerchantPaymentMode($postParams)
{
	/*
		Params:
		
			$postParams = array();
			$postParams['api_key'] 		= '28966ac8a0af1447d6f5bfba0e1428fc';
			$postParams['email'] 		= 'umair.jaffar+1@gmail.com';
			$postParams['password'] 	= '123456';
			
		
		Result:		
			{
				"response": {
					"success": "success",
					"Authenticate Id": "f521db864807b9a09f4abb3deb828c29",
					"Authenticate Password": "2e9120d98798cb6b04eda313966604ad",
					"Secret key": "56e940c625a281.36647928",
					"mode": "Sandbox",
					"hash": "45e31e7a70479868110d16471f3795cd"
				}
			}
	*/
	
	return sendRequestToPaymentGateway(CONST_PAYMENT_TEST_URL.'api/getPaymentMode', $postParams);
}

function changeMerchantPaymentMode($postParams)
{
	/*
		Params:
		
			$postParams = array();
			$postParams['api_key'] 		= '28966ac8a0af1447d6f5bfba0e1428fc';
			$postParams['email'] 		= 'umair.jaffar+1@gmail.com';
			$postParams['password'] 	= '123456';
			$postParams['payment_mode'] = 'sandbox'; //sandbox | live
			
		
		Result:		
			{
				"response": {
					"success": "success",
					"Authenticate Id": "f521db864807b9a09f4abb3deb828c29",
					"Authenticate Password": "2e9120d98798cb6b04eda313966604ad",
					"Secret key": "56e940c625a281.36647928",
					"mode": "Sandbox",
					"hash": "45e31e7a70479868110d16471f3795cd"
				}
			}
	*/
	
	return sendRequestToPaymentGateway(CONST_PAYMENT_TEST_URL.'api/changePaymentMode', $postParams);
}

function getMerchantBankAccountStatus($postParams)
{
	/*
		Params:
		
			$postParams = array();
			$postParams['api_key'] 		= '28966ac8a0af1447d6f5bfba0e1428fc';
			$postParams['email'] 		= 'umair.jaffar+1@gmail.com';
			$postParams['password'] 	= '123456';
			
		
		Result:		
			{
				"response": {
					"error": "No Information found!"
				}
			}
	*/
	
	return sendRequestToPaymentGateway(CONST_PAYMENT_TEST_URL.'api/getBankAccStatus', $postParams);
}

function chargePaymentFromCreditCard($postParams)
{
	/*
		https://cardxecure.com/pay/authorize  TO DEV Interface URL: https://cardxecure.com/dev/authorize

		If use pay.icannpay.com

		https://pay.icannpay.com/pay/authorize  TO DEV Interface URL:https://pay.icannpay.com/dev/authorize
		
		
		Params:
		
			$postParams = array();
			$postParams['amount'] = '1.00';
			$postParams['authenticate_id'] = 'f521db864807b9a09f4abb3deb828c29';
			$postParams['authenticate_pw'] = '2e9120d98798cb6b04eda313966604ad';
			$postParams['ccn'] = '4111111111111111';
			$postParams['city'] = 'Mountain View';
			$postParams['country'] = 'USA';
			$postParams['currency'] = 'USD';
			$postParams['customerip'] = '127.0.0.1';
			$postParams['cvc_code'] = '123';
			$postParams['email'] = 'jhon@gmail.com';
			$postParams['exp_month'] = '12';
			$postParams['exp_year'] = '16';
			$postParams['firstname'] = 'Jhon';
			$postParams['lastname'] = 'Smith';
			$postParams['orderid'] = 'ORD-5001';
			$postParams['phone'] = '16502530000';
			$postParams['state'] = 'CA';
			$postParams['street'] = '1600 Amphitheatre Parkway';
			$postParams['transaction_type'] = 'A';
			$postParams['zip'] = '94043';

			$signature = cardXecureSignatureCalculation($postParams);

			$postParams['signature'] = $signature; //-->'7a1caa0a09f139d6fb2cf7271cde36f120972ed3';
			
		
		Result:		
			transactionid=500000115&status=1&errorcode=&errormessage=&amount=1.00&currency=USD&orderid=ORD-5001&descriptor=some txt		
	*/
	
	return sendRequestToPaymentGateway(CONST_PAYMENT_TEST_URL.'authorize', $postParams);
}

function refundPayment($postParams)
{
	/*
		https://cardxecure.com/pay/refund  TO DEV Interface URL: https://cardxecure.com/dev/refund

		If use pay.icannpay.com

		https://pay.icannpay.com/pay/refund  TO DEV Interface URL:https://pay.icannpay.com/dev/refund
		
		Params:
		
			$postParams = array();
			$postParams['amount'] = '1.00';
			$postParams['authenticate_id'] = 'f521db864807b9a09f4abb3deb828c29';
			$postParams['authenticate_pw'] = '2e9120d98798cb6b04eda313966604ad';
			$postParams['currency'] = 'USD';
			$postParams['customerip'] = '127.0.0.1';
			$postParams['transaction_id'] = 500000115;
			$postParams['transaction_type'] = 'R';

			$signature = cardXecureSignatureCalculation($postParams);

			$postParams['signature'] = $signature;
			
		
		Result:			
			status=0&errorcode=432&errormessage=Invalid transaction id

	*/
	
	return sendRequestToPaymentGateway(CONST_PAYMENT_TEST_URL.'refund', $postParams);
}

/*************************************************************************/

function getCardXecureSignature($postParams=array())
{
	if(!$postParams)
	{
		$postParams = array();
	}
	
	$signature = '';
	
	ksort($postParams);
	
	foreach( $postParams as $key => $val ) 
	{
		if($key != 'signature')
		{
			$signature .= $val;
		}
	}
	
	$signature .= '56e940c625a281.36647928'; //secret key!

	$signature = strtolower(sha1($signature));	
	
	return $signature;
}

function sendRequestToPaymentGateway($urlToRequest, $postParams=array())
{
	if(!$postParams)
	{
		$postParams = array();
	}
	
	$postParams = http_build_query($postParams, '', '&');
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $urlToRequest);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
	
	$result = curl_exec ($ch);
	$info   = curl_getinfo($ch);
	
	/* -- 
	echo "urlToRequest: --".$urlToRequest."--<br />";
	echo "Result: --";
	my_debug($result);
	
	echo "--<br />";
	
	echo "postParams: --";
	my_debug($postParams);
	
	echo "--<br />";
	
	echo "Info: --";
	my_debug($info);
	exit;*/
	
	$return = array();
	
	/*
	if($result)
	{
		$return = json_decode($result, true);
	}
	*/
	
	return $result;
}

function splitName($name)
{
    $name = trim($name);
    $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
    $first_name = trim( preg_replace('#'.$last_name.'#', '', $name ) );
    return array('first_name'=>$first_name, 'last_name'=>$last_name);
}