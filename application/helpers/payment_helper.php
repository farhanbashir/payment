<?php
//For payments processing - We are using CardXecure
define('CONST_PAYMENT_MODE', 				'test'); //test | live
define('CONST_PAYMENT_API_KEY',				'28966ac8a0af1447d6f5bfba0e1428fc');
define('CONST_PAYMENT_TEST_URL',			'https://pay.icannpay.com/dev/'); //-->https://pay.icannpay.com/dev/index.php/
define('CONST_PAYMENT_LIVE_URL',			'https://icannpay.com/index.php/'); //-->https://icannpay.com/index.php/
define('CONST_CC_PAYMENT_SUCCESS_NOTICE',	'<strong>IMPORTANT:</strong> This purchase will appear as <strong>"descriptor"</strong> on your credit card statement or online transaction detail. As with any international transaction of this nature, the final posted transaction on your statement or transaction detail may vary depending on your credit card issuer. It is not uncommon for some credit card issuers to impose a small currency conversion fee.');

//Products!
define('CONST_PRODUCT_ID_NUMPAD',	   -1);

//Device Type!
define('CONST_DEVICE_ID_IPHONE',	   1);
define('CONST_DEVICE_ID_ANDROID',	   2);

//Roles!
define('CONST_ROLE_ID_SUPER_ADMIN',		1);
define('CONST_ROLE_ID_BUSINESS_ADMIN',	2);
define('CONST_ROLE_ID_BUSINESS_STAFF',	3);

define('CONST_DEFAULT_COUNTRY',			'USA');
define('CONST_DEFAULT_CURRENCY',		'USD');
define('CONST_DATE_FORMAT',				'm/d/Y');
define('CONST_TIME_FORMAT',				'H:i:s'); //H -> 24-hour format of an hour with leading zeros

//Transaction Types
define('CONST_TRANSACTION_TYPE_PAYMENT',	1);
define('CONST_TRANSACTION_TYPE_REFUND',		2);

//Bank Status
define('CONST_BANK_STATUS_VERIFIED',		1);
define('CONST_BANK_STATUS_NOT_VERIFIED',	2);

define('CONST_TXT_BANK_STATUS_VERIFIED',	'verified');
define('CONST_TXT_BANK_STATUS_NOT_VERIFIED','not verified');
define('CONST_TXT_BANK_STATUS_NO_DETAIL',	'no detail');

//Merchant Mode Ids
define('CONST_MERCHANT_MODE_LIVE',			1);
define('CONST_MERCHANT_MODE_SANDBOX',		2);

//Merchant Mode Text
define('CONST_TXT_MERCHANT_MODE_LIVE',		'live');
define('CONST_TXT_MERCHANT_MODE_SANDBOX',	'sandbox');




global $merchan_service_start_time;

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
	
	if(!@$_postParams['phone'])
	{
		$_postParams['phone'] = '111-222-333-4'; //because its required field while SignUp!
	}
	
	if(!@$_postParams['store_name'])
	{
		$_postParams['store_name'] = @$_postParams['first_name'].' Company'; //because its required field while SignUp!
	}
	
	if(!@$_postParams['website'])
	{
		$_postParams['website'] = 'iCannPay.com'; //because its required field while SignUp!
	}
	
	$postParams['api_key']		= CONST_PAYMENT_API_KEY;
	$postParams['email'] 		= @$_postParams['email'];
	$postParams['password'] 	= @$_postParams['password'];
	$postParams['rpassword'] 	= @$_postParams['password'];
	$postParams['fname'] 		= @$_postParams['first_name'];
	$postParams['lname'] 		= @$_postParams['last_name'];
	$postParams['cname'] 		= trim(@$_postParams['first_name'].' '.@$_postParams['last_name']);
	$postParams['phone'] 		= @$_postParams['phone'];
	$postParams['ccompany'] 	= @$_postParams['store_name'];
	$postParams['cdomain'] 		= @$_postParams['website'];
	$postParams['accname'] 		= @$_postParams['bank_account_title'];
	$postParams['accno'] 		= @$_postParams['bank_account_number'];
	$postParams['bname'] 		= @$_postParams['bank_name'];
	$postParams['baddress'] 	= @$_postParams['bank_address'];
	$postParams['swiftcode'] 	= @$_postParams['bank_swift_code'];
	
	$userId = 0;
	
	$apiResponse = sendRequestToPaymentGateway($userId, 'signupMerchant', $postParams);
	
	$apiErrorMessage 	= '';
	$apiSuccessMessage 	= 0;
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
								'mode' 						=> _getPaymentModeIdByText(@$apiResponse['data']['mode']),
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

function editMerchantDetails($userId=0, $_postParams=array())
{
	/*
		Params:
		
			$postParams = array();

				$postParams['api_key'] 		= '28966ac8a0af1447d6f5bfba0e1428fc';
				$postParams['email'] 		= 'umair.jaffar+1@gmail.com';
				$postParams['password'] 	= '123456';
				$postParams['fname'] 		= 'Umair';
				$postParams['lname'] 		= 'Jaffar';
				$postParams['cname'] 		= 'Umair Jaffar';
				$postParams['phone'] 		= '0987654321';
				$postParams['ccompany'] 	= 'TechNyx';
				$postParams['cdomain'] 		= 'TechNyx.com';
				$postParams['accname'] 		= 'Umair Account Title';
				$postParams['accno'] 		= '11112223334456';
				$postParams['bname'] 		= 'SCB';
				$postParams['baddress'] 	= 'Karachi';
				$postParams['swiftcode'] 	= 'XXSCB-KHI12345';
			
			
			Result - Success:
			{
				"response": {
					"success": "success",
					"hash": "64cff32a79174936d2d7c203f7cf0f50"
				}
			}
			
			Result - Fail:
			{
				"response": {
					"error": "Nothing get updated!"
				}
			}
	*/
	
	$CI =& get_instance();
	$CI->load->model('profile');
	$merchantInfo = $CI->profile->checkUserMerchantDetails($userId);
	
	$postParams					= array();
	$postParams['api_key']		= CONST_PAYMENT_API_KEY;
	$postParams['email'] 		= @$merchantInfo['email'];
	$postParams['password'] 	= @$merchantInfo['password'];
	
	if(@$_postParams['first_name'])
	{
		$postParams['fname'] 	= @$_postParams['first_name'];
	}
	
	if(@$_postParams['last_name'])
	{
		$postParams['lname'] 	= @$_postParams['last_name'];
	}
	
	if(@$_postParams['first_name'] || @$_postParams['last_name'])
	{
		$postParams['cname'] 	= trim(@$_postParams['first_name'].' '.@$_postParams['last_name']);
	}
	
	if(@$_postParams['phone'])
	{
		$postParams['phone'] 	= @$_postParams['phone'];
	}
	
	if(@$_postParams['store_name'])
	{
		$postParams['ccompany'] = @$_postParams['store_name'];
	}
	
	if(@$_postParams['website'])
	{
		$postParams['cdomain'] 	= @$_postParams['website'];
	}
	
	if(@$_postParams['bank_account_title'])
	{
		$postParams['accname'] 	= @$_postParams['bank_account_title'];
	}
	
	if(@$_postParams['bank_account_number'])
	{
		$postParams['accno'] 	= @$_postParams['bank_account_number'];
	}
	
	if(@$_postParams['bank_name'])
	{
		$postParams['bname'] 	= @$_postParams['bank_name'];
	}
	
	if(@$_postParams['bank_address'])
	{
		$postParams['baddress'] = @$_postParams['bank_address'];
	}
	
	if(@$_postParams['bank_swift_code'])
	{
		$postParams['swiftcode'] = @$_postParams['bank_swift_code'];
	}
	
	$apiResponse = sendRequestToPaymentGateway($userId, 'editMerchantDetails', $postParams);
	
	$apiErrorMessage 	= '';
	$apiSuccessMessage 	= 0;
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
			
			$apiData = $apiResponse['data'];
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

function getMerchantPaymentMode($userId=0, $_postParams=array())
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
	
	$CI =& get_instance();
	$CI->load->model('profile');
	$merchantInfo = $CI->profile->checkUserMerchantDetails($userId);

	$postParams					= array();
	
	$postParams['api_key']		= CONST_PAYMENT_API_KEY;
	$postParams['email'] 		= @$merchantInfo['email'];
	$postParams['password'] 	= @$merchantInfo['password'];
	
	$apiResponse = sendRequestToPaymentGateway($userId, 'getPaymentMode', $postParams);
	
	$apiErrorMessage 	= '';
	$apiSuccessMessage 	= 0;
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
			
			$_apiResponse_Mode	= @$apiResponse['data']['mode'];
			$mode				= _getPaymentModeIdByText($_apiResponse_Mode);
			
			$apiData = array(
								'mode'		=> $mode,
								'message'	=> $_apiResponse_Mode
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

function changeMerchantPaymentMode($userId=0, $_postParams=array())
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
	
	$CI =& get_instance();
	$CI->load->model('profile');
	$merchantInfo = $CI->profile->checkUserMerchantDetails($userId);

	$postParams					= array();
	
	$postParams['api_key']		= CONST_PAYMENT_API_KEY;
	$postParams['email'] 		= @$merchantInfo['email'];
	$postParams['password'] 	= @$merchantInfo['password'];
	$postParams['payment_mode'] = @$_postParams['payment_mode'];
	
	$apiResponse = sendRequestToPaymentGateway($userId, 'changePaymentMode', $postParams);
	
	$apiErrorMessage 	= '';
	$apiSuccessMessage 	= 0;
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
			
			$_apiResponse_Mode	= @$apiResponse['data']['mode'];
			$mode				= _getPaymentModeIdByText($_apiResponse_Mode);
			
			$apiData = array(
								'mode'		=> $mode,
								'message'	=> $_apiResponse_Mode
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

function getMerchantBankAccountStatus($userId=0, $_postParams=array())
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
	
	$CI =& get_instance();
	$CI->load->model('profile');
	$merchantInfo = $CI->profile->checkUserMerchantDetails($userId);

	$postParams					= array();
	
	$postParams['api_key']		= CONST_PAYMENT_API_KEY;
	$postParams['email'] 		= @$merchantInfo['email'];
	$postParams['password'] 	= @$merchantInfo['password'];
	
	$apiResponse = sendRequestToPaymentGateway($userId, 'getBankAccountStatus', $postParams);
	
	$apiErrorMessage 	= '';
	$apiSuccessMessage 	= 0;
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
			
			$_apiResponse_Status	= @$apiResponse['data']['status'];			
			$status					= _getBankStatusIdByText($_apiResponse_Status);
			
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
	$CI->load->model('profile');
	$merchantInfo = $CI->profile->checkUserMerchantDetails($userId);
	
	if(!$_postParams['customer_fname'])
	{
		$_postParams['customer_fname'] = 'F';
	}
	
	if(!$_postParams['customer_lname'])
	{
		$_postParams['customer_lname'] = 'L';
	}
	
	if(!$_postParams['customer_email'])
	{
		$_postParams['customer_email'] = 'test@gmail.com';
	}
	
	if(!$_postParams['customer_phone'])
	{
		$_postParams['customer_phone'] = '111-222-333-4';
	}
	
	if(!$_postParams['customer_country'])
	{
		$_postParams['customer_country'] = CONST_DEFAULT_COUNTRY;
	}
	
	if(!$_postParams['customer_state'])
	{
		$_postParams['customer_state'] = 'NY';
	}
	
	if(!$_postParams['customer_city'])
	{
		$_postParams['customer_city'] = 'NYC';
	}
	
	if(!$_postParams['customer_address'])
	{
		$_postParams['customer_address'] = 'Test';
	}
	
	if(!$_postParams['customer_zip'])
	{
		$_postParams['customer_zip'] = '12345';
	}
	
	if($_postParams['cc_expiry_year'])
	{
		$_postParams['cc_expiry_year'] = substr($_postParams['cc_expiry_year'], -2);
	}
	
	$postParams = array();
	$postParams['currency'] 		= CONST_DEFAULT_CURRENCY;
	$postParams['amount'] 			= $_postParams['amount'];
	$postParams['authenticate_id'] 	= @$merchantInfo['cx_authenticate_id'];
	$postParams['authenticate_pw'] 	= @$merchantInfo['cx_authenticate_password'];
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
	
	$signature = getCardXecureSignature($postParams, $merchantInfo);

	$postParams['signature'] = $signature;
	
	$apiResponse = sendRequestToPaymentGateway($userId, 'paymentAuthorize', $postParams, 'query_string');
	
	$apiErrorMessage 	= '';
	$apiSuccessMessage 	= 0;
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
								'amount'			=> @$apiResponse['data']['amount'],
								'descriptor'		=> @$apiResponse['data']['descriptor']
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

function refundPayment($userId=0, $_postParams=array())
{
	/*
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
	
	$CI =& get_instance();
	$CI->load->model('profile');
	$merchantInfo = $CI->profile->checkUserMerchantDetails($userId);
	
	$postParams = array();
	$postParams['currency'] 		= CONST_DEFAULT_CURRENCY;
	$postParams['amount'] 			= $_postParams['amount'];
	$postParams['authenticate_id'] 	= @$merchantInfo['cx_authenticate_id'];
	$postParams['authenticate_pw'] 	= @$merchantInfo['cx_authenticate_password'];
	$postParams['customerip'] 		= '127.0.0.1';
	$postParams['transaction_id']	= $_postParams['transaction_id'];	
	$postParams['transaction_type'] = 'R';
	
	$signature = getCardXecureSignature($postParams, $merchantInfo);

	$postParams['signature'] = $signature;
	
	$apiResponse = sendRequestToPaymentGateway($userId, 'paymentRefund', $postParams, 'query_string');
	
	$apiErrorMessage 	= '';
	$apiSuccessMessage 	= 0;
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

/*************************************************************************/

function getCardXecureSignature($postParams=array(), $merchantInfo=array())
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
	
	if(is_array($merchantInfo) && count($merchantInfo) > 0)
	{
		if(isset($merchantInfo['cx_secret_key']))
		{
			$signature .= $merchantInfo['cx_secret_key']; //secret key!
		}
	}

	$signature = strtolower(sha1($signature));	
	
	return $signature;
}

function sendRequestToPaymentGateway($userId=0, $callFor, $postParams=array(), $resultType='json')
{
	$urlToRequest 	= '';
	$apiURL			= CONST_PAYMENT_TEST_URL;
	
	/* //We will active this when we go to LIVE with CardXecure!
	if($userId)
	{
		$CI =& get_instance();
		$merchantInfo = $CI->profile->checkUserMerchantDetails($userId);
		
		$paymentMode = @$merchantInfo['cx_mode'];
		
		if($paymentMode == CONST_MERCHANT_MODE_LIVE)
		{
			$apiURL			= CONST_PAYMENT_LIVE_URL;
		}
	}
	*/
	
	if($callFor == 'signupMerchant')
	{
		$urlToRequest = $apiURL.'api/apiSignup';
	}
	else if($callFor == 'editMerchantDetails')
	{
		$urlToRequest = $apiURL.'api/apiEditMerchantDetails';
	}
	else if($callFor == 'getPaymentMode')
	{
		$urlToRequest = $apiURL.'api/getPaymentMode';
	}
	else if($callFor == 'changePaymentMode')
	{
		$urlToRequest = $apiURL.'api/changePaymentMode';
	}
	else if($callFor == 'getBankAccountStatus')
	{
		$urlToRequest = $apiURL.'api/getBankAccStatus';
	}
	else if($callFor == 'paymentAuthorize')
	{
		/*
			https://cardxecure.com/pay/authorize  TO DEV Interface URL: https://cardxecure.com/dev/authorize

			If use pay.icannpay.com

			https://pay.icannpay.com/pay/authorize  TO DEV Interface URL:https://pay.icannpay.com/dev/authorize
		*/
		
		$urlToRequest = $apiURL.'authorize';
	}
	else if($callFor == 'paymentRefund')
	{
		/*
			https://cardxecure.com/pay/refund  TO DEV Interface URL: https://cardxecure.com/dev/refund

			If use pay.icannpay.com

			https://pay.icannpay.com/pay/refund  TO DEV Interface URL:https://pay.icannpay.com/dev/refund
		*/
		
		$urlToRequest = $apiURL.'refund';
	}
	else
	{
		$urlToRequest = $callFor;
	}
	
	$logId = _createMerchantLog($userId, $callFor, $urlToRequest, $postParams);
	
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
	
	if($logId)
	{
		_updateMerchantLogResponse($logId, $result);
	}
	
	if($result)
	{
		$apiResponse 		= $result;
		$apiErrorMessage 	= '';
		$apiSuccessMessage 	= 0;
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
			$apiResult['error'] = 'CardX: '.$apiErrorMessage;
		}
		else if($apiSuccessMessage)
		{
			$apiResult['success'] = $apiSuccessMessage;
			$apiResult['data'] = $apiData;
		}
		
		return $apiResult;
	}
	
	return $result;
}

function _createMerchantLog($userId=0, $callFor, $urlToRequest, $params)
{
	global $merchan_service_start_time;
	$merchan_service_start_time = microtime(true);
	
	$CI =& get_instance();
	
	$logData = array();
	
	$logData['user_id'] 		= $userId; 
	$logData['service'] 		= $callFor;
	$logData['url'] 			= $urlToRequest;
	$logData['params'] 			= json_encode($params);
	$logData['response'] 		= '';
	$logData['request_time'] 	= date('Y-m-d H:i:s');
	$logData['response_time'] 	= '';
	$logData['ip_address'] 		= $CI->input->ip_address();
	
	$CI->load->model('logs');
	$log_id = $CI->logs->add_merchant_log($logData);
	
	return $log_id;
}

function _updateMerchantLogResponse($log_id=0, $response='')
{
	global $merchan_service_start_time;
	
	$end_time 		= microtime(true);
	
	$CI =& get_instance();
	
	$logData = array();
	
	$logData['response'] 		= $response; 
	$logData['response_time'] 	= date('Y-m-d H:i:s');
	$logData['total_seconds'] 	= $end_time-$merchan_service_start_time; //in seconds
	
	$CI->load->model('logs');
	$CI->logs->edit_merchant_log($log_id, $logData);
}

function _getPaymentModeIdByText($modeText='') //-->Live (or) Sandbox 
{
	if($modeText)
	{
		$modeText = strtolower($modeText); //Live --> live
	}
	
	$modeId = CONST_MERCHANT_MODE_SANDBOX;
	if($modeText == CONST_TXT_MERCHANT_MODE_LIVE) 
	{
		$modeId = CONST_MERCHANT_MODE_LIVE;
	}
	
	return $modeId;
}

function _getBankStatusIdByText($statusText='') //-->Not Verified (or) Verified
{
	if($statusText)
	{
		$statusText = strtolower($statusText); //Verified --> verified
	}
	
	$statusId = CONST_BANK_STATUS_NOT_VERIFIED;
	if($statusText == CONST_TXT_BANK_STATUS_VERIFIED) 
	{
		$statusId = CONST_BANK_STATUS_VERIFIED;
	}
	
	return $statusId;
}
	
function splitName($name)
{
    $name = trim($name);
    $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
    $first_name = trim( preg_replace('#'.$last_name.'#', '', $name ) );
    return array('first_name'=>$first_name, 'last_name'=>$last_name);
}

function generateReceiptByOrderId($order_id=0, $user_id=0)
{	
	if($order_id && $user_id)
	{
		$CI =& get_instance();
		
		$CI->load->model('order');
		$CI->load->model('profile');
	
		$orderInfo			= $CI->order->get_order_detail($order_id);
		$paymentTransaction	= $CI->order->get_payment_transaction_by_order($order_id);
		$storeDetails		= $CI->profile->checkUserStoreDetails($user_id);
		$userDetails		= $CI->profile->get_user_detail($user_id);
		
		$_data = array();
		$_data['storeDetails'] 			= $storeDetails;		
		$_data['orderInfo'] 			= $orderInfo;
		$_data['paymentTransaction']	= $paymentTransaction;		
		$_data['userDetails'] 			= $userDetails;		
		
		$orderReceipt	= @$orderInfo['receipt'];	

		if($orderReceipt) //means Receipt already generated and already emailed to customer. So, no need to do it again!
		{
			return false;
		}
		
		$recieptUrl = _createRecieptPDF($_data);
		
		if($recieptUrl)
		{
			$order_data = array();
				
			$order_data['receipt'] = $recieptUrl;		//it has recipt path!
			$order_data['updated'] = date('Y-m-d H:i:s');
			
			$CI->order->edit_order($order_id, $order_data);			

			_sendRecieptEmail($_data, $recieptUrl);
		}
		
		return $recieptUrl;
	}
	
	return false;
}

function _createRecieptPDF($data=array())
{
	$storeDetails 		= @$data['storeDetails'];
	$orderInfo 			= @$data['orderInfo'];
	$paymentTransaction = @$data['paymentTransaction'];
	$userDetails		= @$data['userDetails'];
	
	//store details
	$storeName 			= @$storeDetails['name'];
	$storeLogo 			= @$storeDetails['logo'];
	$storeEmail			= @$storeDetails['email'];
	$storeAddress 		= @$storeDetails['address'];
	$receiptHeaderText	= @$storeDetails['receipt_header_text'];
	$receiptFooterText	= @$storeDetails['receipt_footer_text'];
	$receiptBGColor		= @$storeDetails['receipt_bg_color'];
	$receiptTextColor	= @$storeDetails['receipt_text_color'];
	
	//transaction details
	$paymentNoticeText				= CONST_CC_PAYMENT_SUCCESS_NOTICE;
	$paymentNoticeDescriptorText 	= @$paymentTransaction['cx_descriptor'];
	
	$paymentNotice = '';		
	if($paymentNoticeDescriptorText)
	{
		$paymentNotice = str_replace('descriptor', $paymentNoticeDescriptorText, $paymentNoticeText);
	}		
	
	$invoiceId			= @$paymentTransaction['transaction_id'];
	$cashAmount			= @$paymentTransaction['amount_cash'];
	$ccAmount			= @$paymentTransaction['amount_cc'];
	$ccNumber			= @$paymentTransaction['cc_number'];
	$ccName				= @$paymentTransaction['cc_name'];
	
	//order details	
	$order_id			= @$orderInfo['order_id'];
	$totalAmount		= @$orderInfo['total_amount'];
	$customerSignature	= @$orderInfo['customer_signature'];
	$customerEmail		= @$orderInfo['customer_email'];
	$orderDate			= date(CONST_DATE_FORMAT, strtotime(@$orderInfo['created']));
	$orderTime			= date(CONST_TIME_FORMAT, strtotime(@$orderInfo['created']));
	$orderReceipt		= @$orderInfo['receipt'];		
	
	//user details	
	$user_id			= @$userDetails['user_id'];
	$userFirstName		= @$userDetails['first_name'];
	$userEmail			= @$userDetails['email'];
	
	$paymentCurrency	= CONST_DEFAULT_CURRENCY;
	
	/*
	if($orderReceipt) //means Receipt already generated and already emailed to customer. So, no need to do it again!
	{
		return $orderReceipt;
	}*/

	if(!$storeName)
	{
		if($userFirstName)
		{
			$storeName = ucfirst($userFirstName)." Store";
		}
	}
	
	if(!$storeEmail)
	{
		if($userEmail)
		{
			$storeEmail = $userEmail;
		}
	}

	/************************************ PDF Creation **************************/
	
	$tplLogo = '';
	if($storeLogo)
	{
		if(isValidURL($storeLogo))
		{
			$tplLogo = <<<EOT
	
				<tr>
					<td align="center">
						<img src="$storeLogo" width="100" />
					</td>
				</tr>
EOT;
		}
	}
	
	$tplStoreName = '';
	if($storeName)
	{
		$tplStoreName = <<<EOT
	
				<tr>
					<td align="center" style="font-family: verdana; font-size: 14px;">
						$storeName
					</td>
				</tr>
EOT;
	}
	
	$tplStoreAddress = '';
	if($storeAddress)
	{
		$tplStoreAddress = <<<EOT
	
				<tr>
					<td align="center" style="font-family: verdana; font-size: 10px;">
						$storeAddress
					</td>
				</tr>
EOT;
	}
	$tplReceiptHeaderText = '';
	if($receiptHeaderText)
	{
		$tplReceiptHeaderText = <<<EOT
	
				<tr>
					<td align="center" style="font-family: verdana; font-size: 10px;">
						$receiptHeaderText
					</td>
				</tr>
EOT;
	}
	
	$tplCCNumber = '';
	if($ccNumber)
	{
		$tplCCNumber = <<<EOT
	
				<tr>
					<td align="left" colspan="2" style="font-family: 'Courier New'; font-size: 13px;color:$receiptTextColor;">
						<strong>$ccNumber</strong>
						
						<br />
						<span style="font-size: 8px;">$ccName</span>
					</td>
				</tr>
				
				<tr>
					<td colspan="2" height="10" style="height: 10px;"></td>
				</tr>
EOT;
	}
	
	$tplCustomerSignature = '';
	if($customerSignature)
	{
		if(isValidURL($customerSignature))
		{
			$tplCustomerSignature = <<<EOT
	
				<tr>
					<td align="center" style="font-family: 'Courier New'; font-size: 11px;color:$receiptTextColor;">
						<strong>VERIFIED BY SIGNATURE</strong>
					</td>
				</tr>
				<tr>
					<td align="center">
						<img src="$customerSignature" width="150" />
					</td>
				</tr>
EOT;
		}
	}
	
	$tplReceiptFooterText = '';
	if($receiptFooterText)
	{	
		$tplReceiptFooterText = <<<EOT
	
				<tr>
					<td align="center" style="font-family: verdana; font-size: 10px;">
						$receiptFooterText
					</td>
				</tr>
EOT;
	}
	
	$tplPaymentNotice = '';
	if($paymentNotice)
	{	
		$tplPaymentNotice = <<<EOT
	
				<tr>
					<td align="left" style="font-family: verdana; font-size: 10px;">
						$paymentNotice
					</td>
				</tr>
EOT;
	}
		
	$html = <<<EOT

		<table width="200px" border="0" cellpadding="5" cellspacing="0" style="border: 1px solid #000;color:$receiptTextColor;"  bgcolor="$receiptBGColor">
			
			$tplLogo
			
			$tplStoreName
			
			$tplStoreAddress
			
			$tplReceiptHeaderText
			
			<tr style="color:$receiptTextColor;">
				<td align="center" style="font-family: 'Courier New'; font-size: 10px;">
					<table width="100%" border="0" cellpadding="2" cellspacing="0" >
						<tr>
							<td align="left" style="font-family: 'Courier New'; font-size: 10px;color:$receiptTextColor;">
								<strong>MERCHANT ID:</strong>
							</td>
							<td align="right" style="font-family: 'Courier New'; font-size: 10px;color:$receiptTextColor;">
								<strong>$user_id</strong>
							</td>
						</tr>
						<tr>
							<td align="left" style="font-family: 'Courier New'; font-size: 10px;color:$receiptTextColor;">
								<strong>ORDER ID:</strong>
							</td>
							<td align="right" style="font-family: 'Courier New'; font-size: 10px;color:$receiptTextColor;">
								<strong>$order_id</strong>
							</td>
						</tr>
						<tr>
							<td align="left" style="font-family: 'Courier New'; font-size: 10px;color:$receiptTextColor;">
								<strong>INVOICE ID:</strong>
							</td>
							<td align="right" style="font-family: 'Courier New'; font-size: 10px;color:$receiptTextColor;">
								<strong>$invoiceId</strong>
							</td>
						</tr>
						<tr>
							<td align="left" style="font-family: 'Courier New'; font-size: 8px;color:$receiptTextColor;">
								DATE: $orderDate
							</td>
							<td align="right" style="font-family: 'Courier New'; font-size: 8px;color:$receiptTextColor;">
								TIME: $orderTime
							</td>
						</tr>					
						<tr>
							<td colspan="2" height="10" style="height: 10px;color:$receiptTextColor;"></td>
						</tr>
						
						$tplCCNumber
						
						<tr>
							<td align="left" style="font-family: 'Courier New'; font-size: 10px;color:$receiptTextColor;">
								<strong>By Cash</strong>
							</td>
							<td align="right" style="font-family: 'Courier New'; font-size: 10px;color:$receiptTextColor;">
								<strong>$paymentCurrency $cashAmount</strong>
							</td>
						</tr>
						<tr>
							<td align="left" style="font-family: 'Courier New'; font-size: 10px;color:$receiptTextColor;">
								<strong>By Credit Card</strong>
							</td>
							<td align="right" style="font-family: 'Courier New'; font-size: 10px;color:$receiptTextColor;">
								<strong>$paymentCurrency $ccAmount</strong>
							</td>
						</tr>						
						<tr>
							<td align="left" style="font-family: 'Courier New'; font-size: 10px;color:$receiptTextColor;">
								<strong>TOTAL AMOUNT</strong>
							</td>
							<td align="right" style="font-family: 'Courier New'; font-size: 10px;color:$receiptTextColor;">
								<strong>$paymentCurrency $totalAmount</strong>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			
			$tplCustomerSignature
			
			$tplReceiptFooterText
			
			$tplPaymentNotice
			
		</table>

EOT;

	if($html)
	{	
		$CI =& get_instance();
		$CI->load->library('Pdf');
		
		$pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// remove default header/footer
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		/*
		$pdf->SetHeaderData(PDF_HEADER_LOGO, 50);
		$pdf->setFooterData();*/

		// set margins
		//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

		/*$pdf->SetMargins(PDF_MARGIN_LEFT, 35, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);*/

		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$pdf->AddPage();
		$pdf->writeHTML($html, true, false, false, false, '');
		
		$pdfFileName = 'order-'. $order_id .'-'.date('M-d-Y-h-i-a').'.pdf';

		$pdfFilePath = FCPATH.'assets/img/orders/receipt/'.$pdfFileName;
		
		$pdf->Output($pdfFilePath, 'F');
		
		/*
			PDF OutPut:
			
			I: send the file inline to the browser. The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
			
			D: send to the browser and force a file download with the name given by name.
			
			F: save to a local file with the name given by name (may include a path).
			
			S: return the document as a string. name is ignored.
		*/
		
		$receiptBaseUrl = asset_url('img/orders/receipt/');
		
		$receiptUrl =  $receiptBaseUrl.'/'.$pdfFileName;
		
		return $receiptUrl;
	}
	
	return false;
}

function _sendRecieptEmail($data=array(), $pdfFilePath)
{	
	$CI =& get_instance();
	$storeDetails 		= @$data['storeDetails'];
	$orderInfo 			= @$data['orderInfo'];
	
	$customerEmail		= @$orderInfo['customer_email'];
	$storeEmail			= @$storeDetails['email'];
	$order_id 			= @$orderInfo['order_id'];
	//sending receipt to customer!
	if($customerEmail && $storeEmail)
	{
		$emailTo = $customerEmail;
	
		$CI->load->library('email');
		$CI->email->from($storeEmail);
		$CI->email->to($emailTo);
		$CI->email->subject('Receipt for Order# '.$order_id);
		$CI->email->message('Please check attachment to view the receipt for Order# '.$order_id);	
		$CI->email->attach($pdfFilePath);
		@$CI->email->send();
	}
}


function isValidURL($url='')
{
	$isValid = false;
	
	$arrHeaders = get_headers($url);
	$headerCode = @$arrHeaders[0];
	
	if(strpos($headerCode, "200"))
	{
		$isValid = true;
	}
	
	return $isValid;
}

function getDeviceTypeNameById($deviceTypeId=0)
{
	$deviceTypeName = 'iPhone';
	
	if($deviceTypeId ==  CONST_DEVICE_ID_ANDROID)
	{
		$deviceTypeName = 'Android';
	}
	
	return $deviceTypeName;
}