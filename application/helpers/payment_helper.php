<?php
//For payments processing - We are using CardXecure
define('CONST_PAYMENT_MODE', 		'test'); //test | live
define('CONST_PAYMENT_API_KEY',		'28966ac8a0af1447d6f5bfba0e1428fc');
define('CONST_PAYMENT_TEST_URL',	'https://pay.icannpay.com/dev/index.php/');
define('CONST_PAYMENT_LIVE_URL',	'https://icannpay.com/index.php/');

//products!
define('CONST_PRODUCT_ID_NUMPAD',	   -1);

//roles!
define('CONST_ROLE_ID_SUPER_ADMIN',		1);
define('CONST_ROLE_ID_BUSINESS_ADMIN',	2);
define('CONST_ROLE_ID_BUSINESS_STAFF',	3);

define('CONST_DEFAULT_COUNTRY',			'USA');
define('CONST_DEFAULT_CURRENCY',		'USD');

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
	
	$postParams					= array();
	
	if(!$_postParams['phone'])
	{
		$_postParams['phone'] = '111-222-333-4';
	}
	
	if(!$_postParams['store_name'])
	{
		$_postParams['store_name'] = $_postParams['first_name'].' Company';
	}
	
	$postParams['api_key']		= CONST_PAYMENT_API_KEY;
	$postParams['email'] 		= $_postParams['email'];
	$postParams['password'] 	= $_postParams['password'];
	$postParams['rpassword'] 	= $_postParams['password'];
	$postParams['fname'] 		= $_postParams['first_name'];
	$postParams['lname'] 		= $_postParams['last_name'];
	$postParams['cname'] 		= trim($_postParams['first_name'].' '.$_postParams['last_name']);
	$postParams['phone'] 		= $_postParams['phone'];				//Required Field: The Phone field is required.
	$postParams['ccompany'] 	= $_postParams['store_name'];			//Required Field: The Company Name field is required.
	$postParams['cdomain'] 		= 'iCannPay.com';						//Required Field: The Domain Name field is required.
	$postParams['accname'] 		= $_postParams['bank_account_title'];	//Required Field: The Your bank account name field is required.
	$postParams['accno'] 		= $_postParams['bank_account_number'];	//Required Field: The Your bank account number field is required.
	$postParams['bname'] 		= $_postParams['bank_name'];			//Required Field: The Name of bank field is required.
	$postParams['baddress'] 	= $_postParams['bank_address'];			//Required Field: The Address of your bank field is required.
	$postParams['swiftcode'] 	= $_postParams['bank_swift_code'];		//Required Field: The Swift code field is required
	
	$apiResponse = sendRequestToPaymentGateway(CONST_PAYMENT_TEST_URL.'api/apiSignup', $postParams);
	
	$apiErrorMessage 	= '';
	$apiSuccessMessage 	= '';
	$apiData 			= array();
	if($apiResponse)
	{
		if(isset($apiResponse['error']))
		{
			$apiErrorMessage = $apiResponse['error'];
		}
		else if(isset($apiResponse['success']))
		{
			$apiSuccessMessage = 1;
			
			$apiData = array(
								'authenticate_id' 			=> @$apiResponse['data']['Authenticate Id'],
								'authenticate_password' 	=> @$apiResponse['data']['Authenticate Password'],
								'secret_key' 				=> @$apiResponse['data']['Secret key'],
								'mode' 						=> @$apiResponse['data']['mode'],
								'hash' 						=> @$apiResponse['data']['hash']
				);
		}
	}
	
	$apiResult = array();
	if($apiErrorMessage)
	{
		$apiResult['error'] = $apiErrorMessage;
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

function getMerchantBankAccountStatus($_postParams=array())
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
	$postParams					= array();
	
	$postParams['api_key']		= CONST_PAYMENT_API_KEY;
	$postParams['email'] 		= $_postParams['email'];
	$postParams['password'] 	= $_postParams['password'];
	
	$apiResponse = sendRequestToPaymentGateway(CONST_PAYMENT_TEST_URL.'api/getBankAccStatus', $postParams);
	
	$apiErrorMessage 	= '';
	$apiSuccessMessage 	= '';
	$apiData 			= array();
	if($apiResponse)
	{
		if(isset($apiResponse['error']))
		{
			$apiErrorMessage = $apiResponse['error'];
		}
		else if(isset($apiResponse['success']))
		{
			$apiSuccessMessage = 1;
			
			$_apiResponse_Status = @$apiResponse['data']['status'];
			
			$status = 0;// Not Verified 
			if($_apiResponse_Status == 'Verified')
			{
				$status = 1;// Verified
			}
			
			$apiData = array(
								'status'	=> $status,
								'message'	=> $_apiResponse_Status
				);
		}
	}
	
	$apiResult = array();
	if($apiErrorMessage)
	{
		$apiResult['error'] = $apiErrorMessage;
	}
	else if($apiSuccessMessage)
	{
		$apiResult['success'] = $apiSuccessMessage;
		$apiResult['data'] = $apiData;
	}
	
	return $apiResult;
}

function chargePaymentFromCreditCard($userId=0, $_postParams=array())
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
			
			(
				[transactionid] => 500000119
				[status] => 1
				[errorcode] => 
				[errormessage] => 
				[amount] => 1300
				[currency] => USD
				[orderid] => 101
				[descriptor] => some txt
			)
	*/
	
	$CI =& get_instance();
	$merchantInfo = $CI->profile->checkUserMerchantDetails($userId);
	
	if(!$_postParams['customer_lname'])
	{
		$_postParams['customer_lname'] = 'Jaffar';
	}
	
	if($_postParams['cc_expiry_year'])
	{
		$_postParams['cc_expiry_year'] = substr($_postParams['cc_expiry_year'], -2);
		
	}
	
	$postParams = array();
	$postParams['currency'] 		= CONST_DEFAULT_CURRENCY;
	$postParams['amount'] 			= $_postParams['amount'];
	$postParams['authenticate_id'] 	= 'f521db864807b9a09f4abb3deb828c29'; //-->@$merchantInfo['cx_authenticate_id'];
	$postParams['authenticate_pw'] 	= '2e9120d98798cb6b04eda313966604ad'; //-->@$merchantInfo['cx_authenticate_password'];
	$postParams['ccn'] 				= $_postParams['cc_number'];
	$postParams['exp_month'] 		= $_postParams['cc_expiry_month'];
	$postParams['exp_year'] 		= $_postParams['cc_expiry_year'];
	$postParams['cvc_code'] 		= $_postParams['cc_code'];
	
	$postParams['firstname'] 		= $_postParams['customer_fname'];
	$postParams['lastname'] 		= $_postParams['customer_lname'];
	$postParams['email'] 			= $_postParams['customer_email'];
	$postParams['phone'] 			= $_postParams['customer_phone'];
	$postParams['country'] 			= $_postParams['customer_country'];
	$postParams['state'] 			= $_postParams['customer_state'];
	$postParams['city'] 			= $_postParams['customer_city'];
	$postParams['street'] 			= $_postParams['customer_address'];
	$postParams['zip'] 				= $_postParams['customer_zip'];
	
	$postParams['orderid']	 		= $_postParams['order_id'];
	$postParams['customerip'] 		= '127.0.0.1';
	$postParams['transaction_type'] = 'A';
	
	$signature = getCardXecureSignature($postParams);

	$postParams['signature'] = $signature;
	
	/**
	$postParams = array();

	$postParams['amount'] 			= $_postParams['amount'];
	$postParams['authenticate_id'] 	= 'f521db864807b9a09f4abb3deb828c29'; //-->@$merchantInfo['cx_authenticate_id'];
	$postParams['authenticate_pw'] 	= '2e9120d98798cb6b04eda313966604ad'; //-->@$merchantInfo['cx_authenticate_password'];
	$postParams['ccn'] 				= $_postParams['cc_number'];
	$postParams['city'] 			= $_postParams['customer_city'];
	$postParams['country'] 			= $_postParams['customer_country'];
	$postParams['currency'] 		= CONST_DEFAULT_CURRENCY;
	$postParams['customerip'] 		= '127.0.0.1';
	$postParams['cvc_code'] 		= $_postParams['cc_code'];
	$postParams['email'] 			= $_postParams['customer_email'];
	$postParams['exp_month'] 		= $_postParams['cc_expiry_month'];
	$postParams['exp_year'] 		= $_postParams['cc_expiry_year'];
	$postParams['firstname'] 		= $_postParams['customer_fname'];
	$postParams['lastname'] 		= $_postParams['customer_lname'];
	$postParams['orderid']	 		= $_postParams['order_id'];
	$postParams['phone'] 			= $_postParams['customer_phone'];
	$postParams['state'] 			= $_postParams['customer_state'];
	$postParams['street'] 			= $_postParams['customer_address'];
	$postParams['transaction_type'] = 'A';
	$postParams['zip'] 				= $_postParams['customer_zip'];
	
	$signature = getCardXecureSignature($postParams);

	$postParams['signature'] = $signature;
	**/
	
	$apiResponse = sendRequestToPaymentGateway(CONST_PAYMENT_TEST_URL.'authorize', $postParams, 'query_string');
	
	$apiErrorMessage 	= '';
	$apiSuccessMessage 	= '';
	$apiData 			= array();
	if($apiResponse)
	{
		if(isset($apiResponse['error']))
		{
			$apiErrorMessage = $apiResponse['error'];
		}
		else if(isset($apiResponse['success']))
		{
			$apiSuccessMessage = 1;
			
			$apiData = array(
								'transaction_id'	=> @$apiResponse['data']['transactionid'],
								'amount'			=> @$apiResponse['data']['amount']
				);
		}
	}
	
	$apiResult = array();
	if($apiErrorMessage)
	{
		$apiResult['error'] = $apiErrorMessage;
	}
	else if($apiSuccessMessage)
	{
		$apiResult['success'] = $apiSuccessMessage;
		$apiResult['data'] = $apiData;
	}
	
	return $apiResult;
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
	
	$apiResponse = sendRequestToPaymentGateway(CONST_PAYMENT_TEST_URL.'refund', $postParams, 'query_string');
	
	my_debug($apiResponse);
	exit;
	
	return $apiResponse;
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

function sendRequestToPaymentGateway($urlToRequest, $postParams=array(), $resultType='json')
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
	
	if($result)
	{
		$apiResponse 		= $result;
		$apiErrorMessage 	= '';
		$apiSuccessMessage 	= '';
		$apiData 			= array();
	
		if($resultType == 'json')
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
					
					$apiData = $apiResponse['response'];
				}
			}
		}
		else if($resultType == 'query_string')
		{
			parse_str($result, $apiResponse);
			
			if(is_array($apiResponse) && count($apiResponse) > 0)
			{
				if(isset($apiResponse['status']))
				{
					$_apiResponse_Status = $apiResponse['status'];
					
					if($_apiResponse_Status == 1) //success!
					{
						$apiSuccessMessage = 1;					
						$apiData = $apiResponse;
					}
					else //error!
					{
						if(isset($apiResponse['errorcode']))
						{
							if(isset($apiResponse['errormessage']))
							{
								if($apiResponse['errormessage'])
								{
									$apiErrorMessage = $apiResponse['errormessage'];
								}
								
								if($apiResponse['errorcode'])
								{
									$apiErrorMessage .= ' ('. $apiResponse['errorcode'] .')';
								}
							}
						}
					}
				}
			}
		}
		else //take care for other formats!
		{
		}
		
		$apiResult = array();
		if($apiErrorMessage)
		{
			$apiResult['error'] = 'API: '.$apiErrorMessage;
		}
		else if($apiSuccessMessage)
		{
			$apiResult['success'] = $apiSuccessMessage;
			$apiResult['data'] = $apiData;
		}
		
		return $apiResult;
	}
	
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
	
	return $result;
}

function splitName($name)
{
    $name = trim($name);
    $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
    $first_name = trim( preg_replace('#'.$last_name.'#', '', $name ) );
    return array('first_name'=>$first_name, 'last_name'=>$last_name);
}