<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'/libraries/REST_Controller.php');

class Api extends REST_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 
	function __construct()
	{
		parent::__construct();
		$this->load->model('user','',TRUE);
        $this->load->model('device','',TRUE);
        $this->load->model('category','',TRUE);
        $this->load->model('profile','',TRUE);
        $this->load->model('product','',TRUE);
		$this->load->model('order','',TRUE);
		$this->load->model('customer','',TRUE);
		$this->load->model('logs','',TRUE);
    	
        $this->user_id  	= 0;
        $this->token    	= 0;
        $this->store_id 	= 0;
		$this->device_type	= 0;
		
		$isLocalTesting = false;

        $headers = getallheaders();

	   if(!in_array($this->router->method, $this->config->item('allowed_calls_without_token')))
       {
			$headerToken	= @$headers['Token'];
			$headerUserId	= @$headers['Userid'];
			$headerStoreId	= @$headers['Storeid'];
			
			if($isLocalTesting)
			{
				$headerToken	= @$headers['token'];
				$headerUserId	= @$headers['userid'];
				$headerStoreId	= @$headers['storeid'];
			}
			
            if($headerToken)
            {
                if($headerUserId)
                {
					$deviceInfo = $this->device->validToken($headerUserId,$headerToken);
					
                    if(!$deviceInfo)
                    {
                        $data["header"]["error"] = "1";
                        $data["header"]["message"] = "Please provide valid token";
                        $this->response($data, 200);                     
                    }
                    else
                    {
                        if(!$this->user->validStore($headerUserId,$headerStoreId))
                        {
                            $data["header"]["error"] = "1";
                            $data["header"]["message"] = "Please provide valid store id";
                            $this->response($data, 200);
                        }
                        else
                        {
                            $this->user_id  = $headerUserId;
                            $this->token    = $headerToken;
                            $this->store_id = $headerStoreId;
							$this->device_type = @$deviceInfo['type'];
                        }    
                    }  
                }   
                else
                {
                    $data["header"]["error"] = "1";
                    $data["header"]["message"] = "Please provide user id (header)";
                    $this->response($data, 200);              
                } 
            } 
            else
            {
                $data["header"]["error"] = "1";
                $data["header"]["message"] = "Please provide access token";
                $this->response($data, 200);       
            }    
        
       }
	  //  else
	  //  {
		 //    if(isset($headers['user_id']))
			// {
			// 	$this->user_id = $headers['user_id'];
			// }
			
			// if(!$this->user_id)
			// {
			// 	$this->user_id = $this->post('user_id');
			// }
	  //  }
        
       
	 }

	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	function startup_post()
    {
		$startUpData = array();
		
		$startUpData['cc_payment_notice']	= CONST_CC_PAYMENT_SUCCESS_NOTICE;
		
		//states
		$arrStates = $this->user->get_states(CONST_DEFAULT_COUNTRY);
		
		$usaStates = array();
		if(is_array($arrStates) && count($arrStates) > 0)
		{
			foreach($arrStates as $_stateInfo)
			{
				$_stateCode = $_stateInfo['code'];
				$_stateName = $_stateInfo['name'];
				
				$usaStates[$_stateCode] = $_stateName;
			}
		}
		
		$startUpData['states']['usa']		= $usaStates;		
		
		$data["header"]["error"] = "0";
        $data['body']            = $startUpData;
		
		$this->response($data, 200);
	}

    function updateDevice_post()
    {
        $device_id = $this->post('device_id');
        $user_id = $this->post('user_id');     
        $type = $this->post('device_type');     
        $token = $this->token;

        if(!$device_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Please provide device id";
            $this->response($data, 200);
        }

        if(!$user_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Please provide user id";
            $this->response($data, 200);
        }

        $user_present = $this->user->checkUserById($user_id);
        
        

        if($user_present == false)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "No user present with this id.";
            $this->response($data, 200);
        }   
        else
        {
            
            $device = $this->device->get_user_device($user_id, $device_id);
            if(count($device) > 0)
            {
                //update device table
                $device_data = array('uid'=>$device_id, 'type'=>$type);
                $this->device->edit_device($user_id, $device_data);
            }
            else
            {
                if(isset($type) && isset($device_id))
                {

                    //insert device table
                    $device_data = array('user_id'=>$user_id,'uid'=>$device_id, 'type'=>$type);
                    $this->device->insert_device($device_data);
                }
            }

            $data["header"]["error"] = "0";
            $data["header"]["message"] = "Device id updated.";
            $this->response($data, 200);
        }
    }

    function logout_post()
    {
        //$user_id = $headers['Userid'];
        //$token = $headers['Token'];
        $this->device->delete_device($this->user_id,$this->token);
        $data["header"]["error"] = "0";
        $data["header"]["message"] = "User logout successfully";
        $this->response($data, 200);
    }

    function signup_post()
    {
        $first_name		= $this->post('first_name');
		$last_name		= $this->post('last_name');
        $email          = $this->post('email');
        $password       = $this->post('password');
		$parent_user_id = 0; //-->$this->post('parent_user_id');
        $role_id        = CONST_ROLE_ID_BUSINESS_ADMIN; //-->$this->post('role_id');
        $device_id      = $this->post('device_id');
        $device_type    = $this->post('device_type');
        $created        = date('Y-m-d H:i:s');
        $updated        = date('Y-m-d H:i:s');
        $status         = 1;
		
		if(!$device_id)
		{
			$device_id = uniqid('d_');
		}
		
		if(!$device_type)
		{
			$device_type  = 1; //1=iphone, 2=android
		}

        if(!$first_name)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "First name is required";
            $this->response($data, 200);
        }		
		
		if(!$last_name)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Last name is required";
            $this->response($data, 200);
        }        
        if(!$email)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Email is required";
            $this->response($data, 200);
        }   
        if(!valid_email($email))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Email is not valid";
            $this->response($data, 200);
        }    
        if(!$password)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Password is required";
            $this->response($data, 200);
        }
		if(!isset($parent_user_id))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Parent ID is required";
            $this->response($data, 200);
        }
        if(!isset($role_id))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Role is required";
            $this->response($data, 200);
        }
        if($role_id == CONST_ROLE_ID_SUPER_ADMIN)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Role ID should be ".CONST_ROLE_ID_BUSINESS_ADMIN." or ".CONST_ROLE_ID_BUSINESS_STAFF;
            $this->response($data, 200);   
        }    
        if($role_id == CONST_ROLE_ID_BUSINESS_STAFF && $parent_user_id == 0)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Parent ID is not valid";
            $this->response($data, 200);   
        }    

        $already_present = $this->user->checkUser($email);
        if($already_present !== false)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "User already present with this email";
            $this->response($data, 200);   
        }
		
		if(true)
		{
			$apiStatus = true;
			$apiData = array();
			if($role_id == CONST_ROLE_ID_BUSINESS_ADMIN)
			{
				$postParams = array();
				$postParams['email'] 				= $email;
				$postParams['password'] 			= $password;
				$postParams['first_name'] 			= $first_name;
				$postParams['last_name'] 			= $last_name;
				
				$apiStatus = false;
				$apiData = array();
				$apiResponse = merchantSignup($postParams);
				
				if($apiResponse)
				{
					if(isset($apiResponse['error']))
					{
						$data["header"]["error"] = "1";
						$data["header"]["message"] = $apiResponse['error'];
						$this->response($data, 200);
					}
					else if(isset($apiResponse['success']))
					{
						$apiStatus = true;
						
						$apiData = $apiResponse['data'];
					}
				}
			}
			
			if($apiStatus)
			{
				$user = array("first_name"=>$first_name,"last_name"=>$last_name,"parent_user_id"=>$parent_user_id,"email"=>$email,"password"=>md5($password),"plain_password"=>$password,"status"=>$status,"role_id"=>$role_id,"updated"=>$updated,"created"=>$created);
        
				// $temp_image_url = $this->__uploadFile($this->config->item('user_image_base'), asset_url('img/users'));

				// if($temp_image_url !== "")
				// {
				//     $user['image'] = $temp_image_url;
				// }
				
				// if($facebook_id !== '' && $temp_image_url === ""){

				//     $user['image'] = getFacebookImage($facebook_id);
					
				// }
				
				$user_id = $this->user->add_user($user);				
				
				if($user_id)
				{
					/* //UJ: Not needed at signup!
					//insert device table
					if(isset($device_type) && isset($device_id))
					{
						$device_data = array('user_id'=>$user_id,'uid'=>$device_id, 'type'=>$device_type);
						$this->device->insert_device($device_data);
					}
					*/
					
					$category_id = 0;
					
					if($role_id == CONST_ROLE_ID_BUSINESS_ADMIN) //business admin
					{
						//if user role is business admin then create empty store
						$store_id = $this->profile->add_user_store(array("user_id"=>$user_id));

						//insert into merchant info
						$merchant_info = array();
						$merchant_info['user_id'] 					= $user_id;
						$merchant_info['email'] 					= $email;
						$merchant_info['password'] 					= $password;
						$merchant_info['cx_authenticate_id'] 		= @$apiData['authenticate_id'];
						$merchant_info['cx_authenticate_password'] 	= @$apiData['authenticate_password'];
						$merchant_info['cx_secret_key'] 			= @$apiData['secret_key'];
						$merchant_info['cx_hash'] 					= @$apiData['hash'];
						$merchant_info['cx_mode'] 					= @$apiData['mode'];
						$merchant_info['last_updated'] 				= $created;
						
						$this->profile->add_user_merchant_info($merchant_info);
						
						//Adding "Default" category for this new user!
						$category_id = $this->category->add_category(
																		array(
																				"user_id" 		=> $user_id,
																				"store_id" 		=> $store_id,
																				"parent_id" 	=> 0,
																				"name"			=> 'Default',
																				"created"		=> $created,
																				"updated"		=> $updated,
																				"is_default" 	=> 1, 
																				"status"		=> 1
																			)
																	);
					}
					
					$data["header"]["error"] = "0";
					$data["header"]["message"] = "Signup successfull";
					$data['body'] = array("user_id" => $user_id, "default_category_id" => $category_id);
					$this->response($data, 200);
				}
				else
				{
					$data["header"]["error"] = "1";
					$data["header"]["message"] = "Something went wrong while creating user. Please try again!";
					$this->response($data, 200);
				}
			}
			else
			{
				$data["header"]["error"] = "1";
				$data["header"]["message"] = "Something went wrong. Please try again!";
				$this->response($data, 200);
			}			
		}
		else
		{
			$data["header"]["error"] = "1";
			$data["header"]["message"] = "Something went wrong. Please try later!";
			$this->response($data, 200);
		}
    }

    function imageTest_post()
    {
        $temp_image_url = $this->__uploadFile($this->config->item('product_image_base'), asset_url('img/products'));
        debug($temp_image_url,1);
    }

    function __uploadFile($uploadDir, $baseUrl)
    {
        $_imageURL = array();
        if(isset($_FILES))
        {
            if(isset($_FILES['file']))
            {
                if(isset($_FILES['file']['name']))
                {
                    $fileName = basename($_FILES['file']['name']);
                    
                    if($fileName)
                    {                           
                        $fileExtension = end((explode(".", $fileName)));
                        
                        $imageName = time().'.'.$fileExtension;
                        $uploadFile = $uploadDir . $imageName;

                        if(move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile))
                        {
                            $_imageURL['ext']  = $fileExtension;
                            $_imageURL['path'] = $baseUrl.'/'.$imageName;
                        }
                    }
                }   
            }
        }
        return $_imageURL;
    }

    function getSecurityQuestions_post()
    {
        $questions               = $this->profile->get_questions();
        $data["header"]["error"] = "0";
        $data['body']            = array("questions"=>$questions);
        $this->response($data, 200);
    }

    function setSecurityQuestion_post()
    {
        $security_question_id = $this->post('security_question_id');
        $security_answer      = $this->post('security_answer');
        $created              = date('Y-m-d H:i:s');
        $updated              = date('Y-m-d H:i:s');
        $status               = 1;

        if(!$security_question_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Security Question is required";
            $this->response($data, 200);
        }
        if(!$security_answer)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Security answer is required";
            $this->response($data, 200);
        }
		
		$_userDetails = $this->profile->checkUserDetails($this->user_id);
		
		if($_userDetails) //already inserted, need to update!
		{
			$profile_id = $this->profile->edit_user_detail($this->user_id, array("security_question_id"=>$security_question_id,"security_answer"=>$security_answer,"updated"=>$updated));
		}
		else //need to add
		{
			$profile_id = $this->profile->add_user_detail(array("user_id"=>$this->user_id,"security_question_id"=>$security_question_id,"security_answer"=>$security_answer,"created"=>$created,"updated"=>$updated,"status"=>$status));
		}
        
        $data["header"]["error"] = "0";
        $data["header"]["message"] = "Success";
        $data['body'] = array();
        $this->response($data, 200);
    }

    function setBusinessInfo_post()
    {
        $name = $this->post('name');
		$description = $this->post('description');
		$address = $this->post('address');
		$phone = $this->post('phone');
		
		$email = $this->post('email'); //its business email only like support@store-name.com OR info@blahblah.com
		$facebook = $this->post('facebook');
		$twitter = $this->post('twitter');
		$website = $this->post('website');
		
        if(!$name)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Business name is required";
            $this->response($data, 200);
        }
		
		/*
		if(!$description)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Description is required";
            $this->response($data, 200);
        }
		*/
		
		/*
		if(!$address)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Address is required";
            $this->response($data, 200);
        }*/
		
		/*
		if(!$phone)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Phone number is required";
            $this->response($data, 200);
        }*/
		
        // if(!$logo)
        // {
        //     $data["header"]["error"] = "1";
        //     $data["header"]["message"] = "Logo is required";
        //     $this->response($data, 200);
        // }
		
		$logo = '';
        $temp_image_data = $this->__uploadFile($this->config->item('store_image_base'), asset_url('img/stores'));

        if(is_array($temp_image_data) && count($temp_image_data) > 0)
        {
            $logo    = $temp_image_data['path'];
        }

        $created = date('Y-m-d H:i:s');
        $updated = date('Y-m-d H:i:s');
        $status  = 1;

        $store_id = $this->store_id;

        if(!$store_id)
        {
			$data["header"]["error"] = "1";
            $data["header"]["message"] = "Store ID is required";
            $this->response($data, 200);
			
			/* UJ: Don't need this for now
            $store_data = array("user_id"=>$this->user_id,"name"=>$name,"description"=>$description,"address"=>$address,"phone"=>$phone,"created"=>$created,"updated"=>$updated,"status"=>$status);
            if($logo !== '')
            {
                $store_data['logo'] = $logo;
            }    
            $store_id = $this->profile->add_user_store($store_data);
			*/
        }
        else
        {
            $store_data = array(
									//"user_id"=>$this->user_id,
									"name"=>$name,
									"description"=>$description,
									"address"=>$address,
									"phone"=>$phone,
									
									"email"=>$email,
									"facebook"=>$facebook,
									"twitter"=>$twitter,
									"website"=>$website,
									
									"updated"=>$updated,
									//"status"=>$status
							);
            if($logo !== '')
            {
                $store_data['logo'] = $logo;
            }
			
            $this->profile->edit_user_store($store_id, $store_data);
			
			$postParams = array();
			$postParams['phone'] 				= $phone;
			$postParams['store_name'] 			= $name;
			$postParams['website'] 				= $website;
			$apiResponse = editMerchantDetails($this->user_id, $postParams);
        }    
        
        $data["header"]["error"] = "0";
        $data["header"]["message"] = "Success";
        $data['body'] = array("store_id"=>$store_id);
        $this->response($data, 200);
    }
	
	function setReceiptInfo_post()
    {
		$updated = date('Y-m-d H:i:s');
		
		$store_id 		= $this->store_id;
		$header_text	= $this->post('header_text');
		$footer_text 	= $this->post('footer_text');
		$bg_color	 	= $this->post('bg_color');
		$text_color 	= $this->post('text_color');
		
		if(!$store_id)
        {
			$data["header"]["error"] = "1";
            $data["header"]["message"] = "Store ID is required";
            $this->response($data, 200);
		}
		
		$logo = '';
        $temp_image_data = $this->__uploadFile($this->config->item('store_image_base'), asset_url('img/stores'));

        if(is_array($temp_image_data) && count($temp_image_data) > 0)
        {
            $logo    = $temp_image_data['path'];
        }
		
		$store_data = array(
						'receipt_header_text'	=> $header_text,
						'receipt_footer_text'	=> $footer_text,
						'receipt_bg_color'		=> $bg_color,
						'receipt_text_color' 	=> $text_color,
						'updated' 				=> $updated
		);
		
		if($logo)
		{
			$store_data['logo'] 	= $logo;
		}
		
		$this->profile->edit_user_store($store_id, $store_data);
		
		$data["header"]["error"] = "0";
        $data["header"]["message"] = "Success";
        $this->response($data, 200);
	}
	
	function getBankAccountStatus_post()
    {
		$updated = date('Y-m-d H:i:s');
		$bankInfo = $this->profile->checkUserBankDetails($this->user_id);
		
		if($bankInfo)
		{
			$bank_id = $bankInfo['bank_id'];
				
			$postParams = array();
			
			$apiStatus = false;
			$apiData = array();
			$apiResponse = getMerchantBankAccountStatus($this->user_id, $postParams);
			
			if($apiResponse)
			{
				if(isset($apiResponse['error']))
				{
					$data["header"]["error"] = "1";
					$data["header"]["message"] = $apiResponse['error'];
					$this->response($data, 200);
				}
				else if(isset($apiResponse['success']))
				{
					$apiStatus = true;
					
					$apiData = $apiResponse['data'];
				}
			}
			
			if($apiStatus)
			{
				$_apiData_Status  = $apiData['status'];
				$_apiData_Message = $apiData['message'];
				
				if($_apiData_Status == CONST_BANK_STATUS_VERIFIED) //verified!
				{
					$this->profile->edit_user_bank($bank_id, array("updated"=>$updated,"status"=>CONST_BANK_STATUS_VERIFIED));
					
					$data["header"]["error"] = "0";
					$data["header"]["message"] = $_apiData_Message;
					$data['body'] = array("bank_id"=>$bank_id);
					$this->response($data, 200);
				}
				else  //not-verified!
				{
					$this->profile->edit_user_bank($bank_id, array("updated"=>$updated,"status"=>CONST_BANK_STATUS_NOT_VERIFIED));
					
					$data["header"]["error"] = "1";
					$data["header"]["message"] = $_apiData_Message;
					$this->response($data, 200);
				}
			}
			else
			{
				$data["header"]["error"] = "1";
				$data["header"]["message"] = "Something went wrong. Please try later!";
				$this->response($data, 200);
			}			
		}
		else
		{
			$data["header"]["error"] = "1";
			$data["header"]["message"] = "No bank linked with this user!";
			$this->response($data, 200);
		}		
	}

    function setBankAccountInfo_post()
    {
		$bank_id  = 0;
		$bankInfo = $this->profile->checkUserBankDetails($this->user_id);
		
		$bank_status = -1;
		if($bankInfo)
		{
			$bank_id		= @$bankInfo['bank_id'];
			$bank_status	= @$bankInfo['status'];
		}
		
		if($bank_status == CONST_BANK_STATUS_VERIFIED)
		{
			$data["header"]["error"] = "1";
			$data["header"]["message"] = "No need to change your bank details as its already verified!";
			$this->response($data, 200);
		}
		
		$bank_name			= $this->post('bank_name');
		$bank_address    	= $this->post('bank_address');
		$swift_code      	= $this->post('swift_code');
		$account_title      = $this->post('account_title');
		$account_number 	= $this->post('account_number');
		
		$created        = date('Y-m-d H:i:s');
		$updated        = date('Y-m-d H:i:s');

		if(!$bank_name)
		{
			$data["header"]["error"] = "1";
			$data["header"]["message"] = "Bank name is required";
			$this->response($data, 200);
		}
		
		if(!$bank_address)
		{
			$data["header"]["error"] = "1";
			$data["header"]["message"] = "Bank address is required";
			$this->response($data, 200);
		}
		
		if(!$swift_code)
		{
			$data["header"]["error"] = "1";
			$data["header"]["message"] = "Routing number / Swift code is required";
			$this->response($data, 200);
		}
		
		if(!$account_title)
		{
			$data["header"]["error"] = "1";
			$data["header"]["message"] = "Account title is required";
			$this->response($data, 200);
		}
		
		if(!$account_number)
		{
			$data["header"]["error"] = "1";
			$data["header"]["message"] = "Account Number is required";
			$this->response($data, 200);
		}
			
		$postParams = array();
		$postParams['bank_name'] 			= $bank_name;
		$postParams['bank_address'] 		= $bank_address;
		$postParams['bank_swift_code'] 		= $swift_code;
		$postParams['bank_account_title'] 	= $account_title;
		$postParams['bank_account_number'] 	= $account_number;
		
		$apiStatus = false;
		$apiData = array();
		$apiResponse = editMerchantDetails($this->user_id, $postParams);
		
		if($apiResponse)
		{
			if(isset($apiResponse['error']))
			{
				$data["header"]["error"] = "1";
				$data["header"]["message"] = $apiResponse['error'];
				$this->response($data, 200);
			}
			else if(isset($apiResponse['success']))
			{
				$apiStatus = true;
				
				$apiData = $apiResponse['data'];
			}
		}
		
		if($apiStatus)
		{
			if(!$bank_id)
			{	
				$bank_id = $this->profile->add_user_bank(
														array(
																"user_id"			=> $this->user_id,
																"bank_name"			=> $bank_name,
																"bank_address"		=> $bank_address,
																"swift_code"		=> $swift_code,
																"account_title"		=> $account_title,
																"account_number"	=> $account_number,
																"created"			=> $created,
																"updated"			=> $updated,
																"status"			=> CONST_BANK_STATUS_NOT_VERIFIED
															)
													);
			}
			else
			{
				$this->profile->edit_user_bank( $bank_id, 
														array(
																"bank_name"			=> $bank_name,
																"bank_address"		=> $bank_address,
																"swift_code"		=> $swift_code,
																"account_title"		=> $account_title,
																"account_number"	=> $account_number,
																"updated"			=> $updated
															)
													);
			}
			
			$data["header"]["error"] = "0";
			$data["header"]["message"] = "Success";
			$data['body'] = array("bank_id"=>$bank_id);
			$this->response($data, 200);
		}
		else
		{
			$data["header"]["error"] = "1";
			$data["header"]["message"] = "Something went wrong. Please try later!";
			$this->response($data, 200);
		}
    }

    /*function editProfile_post()
    {
        $email = $this->post('email');
        $device_id = $this->post('device_id');
        $device_type = $this->post('device_type');
        
        $already_present = $this->user->checkUserById($this->user_id);
        if($already_present === false)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "No user present with this username";
            $this->response($data, 200);   
        } 

        $user = array("first_name"=>$first_name,"last_name"=>$last_name,"facebook_id"=>$facebook_id);
        $temp_image_url = $this->__uploadFile($this->config->item('user_image_base'), asset_url('img/users'));

        if($temp_image_url !== "")
        {
            $user['image'] = $temp_image_url;
        }

        $user_id = $this->user->edit_user($this->user_id, $user);

        //edit device table
        if(isset($device_type) && isset($device_id))
        {
            $device_data = array('uid'=>$device_id, 'type'=>$device_type);
            $this->device->edit_device($this->user_id, $device_data);
        }  

        $data["header"]["error"] = "0";
        $data["header"]["message"] = "Profile edit successfull";
        $this->response($data, 200);
    }*/

	function login_post()
    {
    	$data = array();

        $email    = $this->post('email');
        $password    = $this->post('password');
        $device_id   = $this->post('device_id');
        $device_type = $this->post('device_type');
        $os_version  = $this->post('os_version');
		
		if(!$device_id)
		{
			$device_id = uniqid('d_');
		}
		
		if(!$device_type)
		{
			$device_type  = 1; //1=iphone, 2=android
		}
		
		if(!$email)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Email address is required";
            $this->response($data, 200);
        }
		
		if(!$password)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Password is required";
            $this->response($data, 200);
        }
		
		if(!$device_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Device ID is required";
            $this->response($data, 200);
        }

        if(!$email || !$password)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Email or password is incorrect";
            $this->response($data, 200);
        }
        else
        {
            $result = $this->user->login($email, $password);
            
            if($result !== false)
            {
                $user = (array) $result[0];
                
                if($user['role_id'] == CONST_ROLE_ID_BUSINESS_STAFF) //if user is staff then get admin store id
                {
                    $user_detail = $this->profile->get_user_detail($user['parent_user_id']);
                    $user_detail['name'] = $user['name'];
                    $user_detail['email'] = $user['email'];
                }   
                else
                {
                    $user_detail = $this->profile->get_user_detail($user['user_id']);    
                } 
                
                $token = bin2hex(openssl_random_pseudo_bytes(16));    
                
                if(md5($password) === $user['new_password'])
                {
                    $this->user->edit_user($user['user_id'], array('password'=>md5($password)));
                }    

                //insert device table
                if(isset($device_type) && isset($device_id))
                {
                    //-->$device_data = array('user_id'=>$user['user_id'],'uid'=>$device_id, 'type'=>$device_type,'token'=>$token);
                    //$this->device->insert_device($device_data);

                    $device = $this->device->get_user_device($user['user_id'], $device_id);
					
					//insert device table
					$device_data = array('user_id'=>$user['user_id'],'uid'=>$device_id, 'type'=>$device_type,'token'=>$token,"os_version"=>$os_version);
					$this->device->insert_device($device_data);
                    
					/* //UJ: Not needed. It will update all previous tokens when we have some new token assigned!
					if(count($device) > 0)
                    {
                        //update device table
                        $device_data = array('uid'=>$device_id, 'type'=>$device_type,'token'=>$token,"os_version"=>$os_version);
                        $this->device->edit_device($user['user_id'], $device_data);
                    }
                    else
                    {
                        //insert device table
                        $device_data = array('user_id'=>$user['user_id'],'uid'=>$device_id, 'type'=>$device_type,'token'=>$token,"os_version"=>$os_version);
                        $this->device->insert_device($device_data);
                    }
					*/
                }    
                
                $array['user_id']          = $user['user_id'];
                $array['token']            = $token;
                $array['store_id']         = $user_detail['store_id'];
                $array['role_id']          = $user['role_id'];
                $array['user']             = $user_detail;
                $data["header"]["error"]   = "0";
                $data["header"]["message"] = "Login successfully";
                $data['body']              = $array;
            }
            else
            {
                $data["header"]["error"]   = "1";
                $data["header"]["message"] = "Email or password is incorrect";
            }

            $this->response($data);
        }
    }

    function changePassword_post()
    {
        $old_password = $this->post('old_password');
        $new_password = $this->post('new_password');
		
		
		if(!$old_password)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Current password is required";
            $this->response($data, 200);
        }
		
		if(!$new_password)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "New password is required";
            $this->response($data, 200);
        }

        $user = $this->user->checkUserById($this->user_id);

        if(md5($old_password) !== $user[0]->password)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Please provide correct current password";
            $this->response($data, 200);
        }    
        else
        {
            $this->user->edit_user($this->user_id, array("password"=>md5($new_password)));

            $data["header"]["error"]   = "0";
            $data["header"]["message"] = "Password change successfully";
            $this->response($data, 200);
        }    
    }
	
	function editUserInfo_post()
    {
        $first_name		= $this->post('first_name');
		$last_name		= $this->post('last_name');	
		
		if(!$first_name)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "First name is required";
            $this->response($data, 200);
        }		
		 if(!$last_name)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Last name is required";
            $this->response($data, 200);
        }

		$this->user->edit_user($this->user_id, array("first_name"=>$first_name,"last_name"=>$last_name));
		
		$postParams = array();
		$postParams['first_name'] 			= $first_name;
		$postParams['last_name'] 			= $last_name;
		
		$apiResponse = editMerchantDetails($this->user_id, $postParams);
		
		$data["header"]["error"] = "0";
		$data["header"]["message"] = "Success";
		$this->response($data, 200);
    }

    function forgetPassword_post()
    {
    	$data = array();

        $username = $this->post('email');

        if(!$username)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Please provide email";
            $this->response($data,200);
        }
        else
        {
         
            $user = $this->user->checkUser($username);
            if(!$user)
            {
                $data["header"]["error"]   = "1";
                $data["header"]["message"] = "No user found with this email";
                $this->response($data,200);
            }   
            else
            {
                $user          = (array) $user[0];
                $temp_password = rand_string(8);
                $md5           = md5($temp_password);

                $user['new_password'] = $md5;
                $this->user->edit_user($user['user_id'],$user);
                
                    //email work here
                $subject = 'Your password has been changed successfully';
                $message = 'Your temporary password is '.$temp_password;
                $email   = array('to'=>$user['email'], 'from'=>$this->config->item('default_email'),'subject'=>$subject, 'message'=>$message);
                
                sendEmail($email);
                $data["header"]["error"]   = "0";
                $data["header"]["message"] = 'Please check your email';
                $this->response($data,200);
            } 
               
            
        }
    }

    function saveCategory_post()
    {
        $store_id  = $this->store_id;
        $parent_id = $this->post('parent_id');
        $name      = $this->post('name');
        $created   = date('Y-m-d H:i:s');
        $updated   = date('Y-m-d H:i:s');
        $status    = 1;

        if(!$store_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Store id is required";
            $this->response($data, 200);
        }
        if(!isset($parent_id))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Parent category is required";
            $this->response($data, 200);
        }
        if(!$name)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Category name is required";
            $this->response($data, 200);
        }

        if($this->category->categorPresent($name, $parent_id, $store_id))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Category already present";
            $this->response($data, 200);   
        }    

        $category_id = $this->category->add_category(array("user_id"=>$this->user_id,"store_id"=>$store_id,"parent_id"=>$parent_id,"name"=>$name,"created"=>$created,"updated"=>$updated,"status"=>$status));
        
        $data["header"]["error"]   = "0";
        $data["header"]["message"] = "Success";
        $data['body']              = array("category_id"=>$category_id);
        $this->response($data, 200);
    }

    function editCategory_post()
    {
        $category_id = $this->post('category_id');
        $store_id    = $this->store_id;
        $parent_id   = $this->post('parent_id');
        $name        = $this->post('name');
        $updated     = date('Y-m-d H:i:s');
        $status      = 1;

        if(!$category_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Category id is required";
            $this->response($data, 200);
        }
        if(!$store_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Store id is required";
            $this->response($data, 200);
        }
        if(!isset($parent_id))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Parent category is required";
            $this->response($data, 200);
        }
        if(!$name)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Category name is required";
            $this->response($data, 200);
        }

        $category_detail = $this->category->get_category_detail($category_id);

        if($store_id !== $category_detail['store_id'])
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Wrong store id";
            $this->response($data, 200);   
        }    

        $result = $this->category->edit_category($category_id, array("parent_id"=>$parent_id,"name"=>$name,"updated"=>$updated,"status"=>$status));
        if($result)
        {
            $data["header"]["error"]   = "0";
            $data["header"]["message"] = "Success";
            $data['body']              = array();
            $this->response($data, 200);    
        }    
        else
        {
            $data["header"]["error"]   = "1";
            $data["header"]["message"] = "Some error";
            $this->response($data,200);
        }
    }

    function getCategory_post()
    {
        $category_id = $this->post('category_id');
        if(!$category_id)
        {
            $data["header"]["error"]   = "1";
            $data["header"]["message"] = "Provide category";
            $this->response($data, 200);
        }

        $category_detail         = $this->category->get_category_detail($category_id);
        $data["header"]["error"] = "0";
        $data['body']            = array("category_detail"=>$category_detail);
        $this->response($data, 200);
    }

    function saveProduct_post()
    {
        $name         = $this->post('name');
        $description  = $this->post('description');
        $price        = $this->post('price');
        $store_id     = $this->store_id;
        $category_ids = $this->post('category_ids');
        $created      = date('Y-m-d H:i:s');
        $updated      = date('Y-m-d H:i:s');
        $status       = 1;
		
		$image_count  = $this->post('image_count');

        if(!$name)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide product name";
            $this->response($data, 200);
        }
        
		if(!$description)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide product description";
            $this->response($data, 200);
        }
        
		if(!isset($price))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide product price";
            $this->response($data, 200);
        }
		
        if(!$store_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide store";
            $this->response($data, 200);
        }
        
		$categories = array();
		if(!$category_ids)
        {
			/* //validation removed!
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide product category";
            $this->response($data, 200);
			*/
        }
		else
		{
			$categories = json_decode($category_ids,true);
			if(!$categories)
			{
				/* //validation removed!
				$data["header"]["error"] = "1";
				$data["header"]["message"] = "Please provide category";
				$this->response($data, 200);
				*/
			}
		}
		
		if(!$categories) // No Category Selected - START!
		{
			$defaultCategoryInfo = $this->category->getDefaultCategory($this->user_id, $store_id);
			
			$defaultCategoryId = 0;
			if($defaultCategoryInfo)
			{
				$defaultCategoryId = @$defaultCategoryInfo['category_id'];
			}
			
			if(!$defaultCategoryId)
			{
				//Adding "Default" category for this new user!
				$defaultCategoryId = $this->category->add_category(
																array(
																		"user_id" 		=> $this->user_id,
																		"store_id" 		=> $store_id,
																		"parent_id" 	=> 0,
																		"name"			=> 'Default',
																		"created"		=> $created,
																		"updated"		=> $updated,
																		"is_default" 	=> 1, 
																		"status"		=> 1
																	)
															);
			}					
			
			if($defaultCategoryId)
			{
				$categories = array();
				$categories[] = $defaultCategoryId;
			}
			
		} // No Category Selected - END!

        $product_id = $this->product->add_product(array("user_id"=>$this->user_id,"store_id"=>$store_id,"name"=>$name,"description"=>$description,"price"=>$price,"created"=>$created,"updated"=>$updated,"status"=>$status));
        
		if(is_array($categories) && count($categories) > 0)
		{
			foreach($categories  as $category)
			{
				$this->category->add_product_category(array("product_id"=>$product_id,"category_id"=>$category));
			}
		}        
        
		/** //Not using it now!
        $temp_image_data = $this->__uploadFile($this->config->item('product_image_base'), asset_url('img/products'));

        if(count($temp_image_data) > 0)
        {
            $product_media = array("product_id"=>$product_id,"file_name"=>$temp_image_data['path'],"media_type"=>$temp_image_data['ext'],"created"=>$created,"updated"=>$updated,"status"=>$status);

            $this->product->add_product_media($product_media);
        }
		**/
		
		if($image_count)
		{
			if($image_count > 0) //if has product images!
			{
				for($im=1 ; $im <= $image_count ; $im++)
				{
					if(isset($_FILES))
					{
						if(isset($_FILES['file-'.$im]))
						{
							if(isset($_FILES['file-'.$im]['name']))
							{
								$fileName = basename($_FILES['file-'.$im]['name']);
								
								if($fileName)
								{
									$_imageIndex = $im;								
									$uploadDir = $this->config->item('product_image_base');
									
									$fileExtension = 'jpg'; //-->end((explode(".", $fileName)));
									
									$imageName = $_imageIndex.'_'.time().'.'.$fileExtension;
									$uploadFile = $uploadDir . $imageName;

									if(move_uploaded_file($_FILES['file-'.$im]['tmp_name'], $uploadFile))
									{
										$_imageURL = asset_url('img/products').'/'.$imageName;
										
										if($_imageURL)
										{
											$product_media = array(
																	"product_id"	=> $product_id, 
																	"file_name"		=> $_imageURL,
																	"media_type"	=> $fileExtension,
																	"created"		=> $created,
																	"updated"		=> $updated,
																	"status"		=> $status
																);

											$this->product->add_product_media($product_media);
										}
									}
								}
							}	
						}
					}
				}
			}
		}

        $data["header"]["error"]   = "0";
        $data["header"]["message"] = "Success";
        $data['body']              = array("product_id"=>$product_id);
        $this->response($data, 200);
    }

    function editProduct_post()
    {
        $product_id   = $this->post('product_id');
        $name         = $this->post('name');
        $description  = $this->post('description');
        $price        = $this->post('price');
        $store_id     = $this->store_id;
        $category_ids = $this->post('category_ids');
		$created      = date('Y-m-d H:i:s');
        $updated      = date('Y-m-d H:i:s');
        $status       = 1;
		
		$image_count  = $this->post('image_count');

        if(!$product_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide product id";
            $this->response($data, 200);
        }
        if(!$name)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide product name";
            $this->response($data, 200);
        }
        if(!$description)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide product description";
            $this->response($data, 200);
        }
        
		if(!($product_id))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide product price";
            $this->response($data, 200);
        }
		
        if(!($store_id))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide store";
            $this->response($data, 200);
        }
        
		$categories = array();
		if(!$category_ids)
        {
			/*
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide product category";
            $this->response($data, 200);
			*/
        }
		else
		{
			$categories = json_decode($category_ids,true);
			if(!$categories)
			{
				/*
				$data["header"]["error"] = "1";
				$data["header"]["message"] = "Please provide category";
				$this->response($data, 200);
				*/
			}
		}
		
		if(!$categories) // No Category Selected - START!
		{
			$defaultCategoryInfo = $this->category->getDefaultCategory($userId, $storeId);
			
			$defaultCategoryId = 0;
			if($defaultCategoryInfo)
			{
				$defaultCategoryId = @$defaultCategoryInfo['category_id'];
			}
			
			if(!$defaultCategoryId)
			{
				//Adding "Default" category for this new user!
				$defaultCategoryId = $this->category->add_category(
																array(
																		"user_id" 		=> $userId,
																		"store_id" 		=> $storeId,
																		"parent_id" 	=> 0,
																		"name"			=> 'Default',
																		"created"		=> $created,
																		"updated"		=> $updated,
																		"is_default" 	=> 1, 
																		"status"		=> 1
																	)
															);
			}					
			
			if($defaultCategoryId)
			{
				$categories = array();
				$categories[] = $defaultCategoryId;
			}
			
		} // No Category Selected - END!

        if($this->product->edit_product($product_id, array("user_id"=>$this->user_id,"store_id"=>$store_id,"name"=>$name,"description"=>$description,"price"=>$price,"updated"=>$updated,"status"=>$status)))
        {
            //category work here
            $this->category->delete_all_categories_for_product($product_id);
			
			if(is_array($categories) && count($categories) > 0)
			{
				foreach($categories  as $category)
				{
					$this->category->add_product_category(array("product_id"=>$product_id,"category_id"=>$category));   
				} 
			}            
            
			/** //Not using it now!
            $temp_image_data = $this->__uploadFile($this->config->item('product_image_base'), asset_url('img/products'));

            if(count($temp_image_data) > 0)
            {
                $product_media = array("product_id"=>$product_id,"file_name"=>$temp_image_data['path'],"media_type"=>$temp_image_data['ext'],"created"=>$created,"updated"=>$updated,"status"=>$status);
				
				//-->$this->product->edit_product_media($product_id, $product_media); 				
				
				$this->product->delete_product_media($product_id);
				$this->product->add_product_media($product_media);
            }**/
			
			$this->product->delete_product_media($product_id);
			
			if($image_count)
			{
				if($image_count > 0) //if has product images!
				{
					for($im=1 ; $im <= $image_count ; $im++)
					{
						if(isset($_FILES))
						{
							if(isset($_FILES['file-'.$im]))
							{
								if(isset($_FILES['file-'.$im]['name']))
								{
									$fileName = basename($_FILES['file-'.$im]['name']);
									
									if($fileName)
									{
										$_imageIndex = $im;								
										$uploadDir = $this->config->item('product_image_base');
										
										$fileExtension = 'jpg'; //-->end((explode(".", $fileName)));
										
										$imageName = $_imageIndex.'_'.time().'.'.$fileExtension;
										$uploadFile = $uploadDir . $imageName;

										if(move_uploaded_file($_FILES['file-'.$im]['tmp_name'], $uploadFile))
										{
											$_imageURL = asset_url('img/products').'/'.$imageName;
											
											if($_imageURL)
											{
												$product_media = array(
																		"product_id"	=> $product_id, 
																		"file_name"		=> $_imageURL,
																		"media_type"	=> $fileExtension,
																		"created"		=> $created,
																		"updated"		=> $updated,
																		"status"		=> $status
																	);

												$this->product->add_product_media($product_media);
											}
										}
									}
								}	
							}
						}
					}
				}
			}

            $data["header"]["error"]   = "0";
            $data["header"]["message"] = "Success";
            $data['body']              = array();
            $this->response($data, 200);    
        }
        else
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Some Error";
            $this->response($data, 200);
        }
    }

    function test_post()
    {
        $categories              = $this->category->get_all_categories($this->user_id);
        debug($categories,1);
        $data["header"]["error"] = "0";
        $data['body']            = array("categories"=>$categories);
        $this->response($data, 200);
    }



    function getCategories_post()
    {
        $categories              = $this->category->get_all_categories_for_app_listing($this->user_id, $this->store_id);		
		$categories 			= categoryTree($categories);
		
        $data["header"]["error"] = "0";
        $data['body']            = array("categories"=>$categories);
        $this->response($data, 200);
    }

    function getProducts_post()
    {
        $products                = $this->product->get_all_active_products($this->store_id, $this->user_id);
		
		if(is_array($products) && count($products) > 0)
		{
			foreach($products as $_key => $_productInfo)
			{
				$productId = @$_productInfo['product_id'];
				
				if($productId)
				{
					$products[$_key]['categories']  = $this->product->getProductCategories($productId);
					$products[$_key]['images']  = $this->product->getProductImages($productId);
				}
			}
		}
		
        $data["header"]["error"] = "0";
        $data['body']            = array("products"=>$products);
        $this->response($data, 200);
    }

    function getProductsByCategory_post()
    {
        $category_id = $this->post('category_id');
        if(!$category_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide category";
            $this->response($data, 200);
        }

        $products    = $this->product->get_products_by_category($category_id);
		
		if(is_array($products) && count($products) > 0)
		{
			foreach($products as $_key => $_productInfo)
			{
				$productId = @$_productInfo['product_id'];
				
				if($productId)
				{
					$products[$_key]['categories']  = $this->product->getProductCategories($productId);
					$products[$_key]['images']  = $this->product->getProductImages($productId);
				}
			}
		}
        
        $data["header"]["error"] = "0";
        $data['body']            = array("products"=>$products);
        $this->response($data, 200);
    }

    function getProduct_post()
    {
        $product_id = $this->post('product_id');
        if(!$product_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide product";
            $this->response($data, 200);
        }

        $product_detail         = $this->product->get_product_detail($product_id);
		
		if(is_array($product_detail) && count($product_detail) > 0)
		{
			$productId = @$product_detail['product_id'];
				
			if($productId)
			{
				$product_detail['categories']  = $this->product->getProductCategories($productId);
				$product_detail['images']  = $this->product->getProductImages($productId);
			}
		}
		
        $data["header"]["error"] = "0";
        $data['body']            = array("product_detail"=>$product_detail);
        $this->response($data, 200);   
    }

    function createOrder_post()
    {
		/*
			*Header Params:
				- Token
				- Userid
				- Storeid
			
			*POST Params:
			
				{"products":[{"product_id":1,"quantity":2},{"product_id":3,"quantity":5}],"customer_info":{"name":"Umair Jaffar","email":"umair.jaffar97@gmail.com","address1":"Address 1","address2":"Address 2","city":"New York City - NYC","state":"New York","zipcode":"12345","phone":"111-222-3334"},"numeric_pad_amount":"200","total_amount":"1600","pay_by_cash_amount":"300","pay_by_credit_card":{"is_swipe":"0","cc_name":"Umair","cc_number":"4111111111111111","cc_expiry_year":"2016","cc_expiry_month":"12","cc_code":"123"}}
		*/
		
        $created	= date('Y-m-d H:i:s');
        $store_id	= $this->store_id;
        $json		= $this->post('data');

        $input_data = json_decode($json,true);
		
		if(!$input_data)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Please provide data";
            $this->response($data, 200);
        }
		
		//customer info!
		$customer_info		= @$input_data['customer_info'];
		
		$customer_country 	= CONST_DEFAULT_COUNTRY;
		$customer_name 		= @$customer_info['name'];
		$customer_email 	= @$customer_info['email'];
		$customer_address1 	= @$customer_info['address1'];
		$customer_address2 	= @$customer_info['address2'];
		$customer_city 		= @$customer_info['city'];
		$customer_state 	= @$customer_info['state'];
		$customer_zipcode 	= @$customer_info['zipcode'];
		$customer_phone 	= @$customer_info['phone'];
		
		//products!
		$products 			= @$input_data['products'];
		
		$numeric_pad_amount = @$input_data['numeric_pad_amount'];
		
		$total_amount 		= @$input_data['total_amount'];
		
		$pay_by_cash_amount = @$input_data['pay_by_cash_amount'];
		
		//credit card info!
		$pay_by_credit_card = @$input_data['pay_by_credit_card'];
		
		$cc_swipe			= @$pay_by_credit_card['is_swipe'];
		$cc_name			= @$pay_by_credit_card['cc_name'];
		$cc_number			= @$pay_by_credit_card['cc_number'];
		$cc_expiry_year		= @$pay_by_credit_card['cc_expiry_year'];
		$cc_expiry_month	= @$pay_by_credit_card['cc_expiry_month'];
		$cc_code			= @$pay_by_credit_card['cc_code'];
		
		// cc swipe!
		$is_cc_swipe = 0;
		if($cc_swipe == 1)
		{
			$is_cc_swipe = 1;
		}
		
		//validations start!		
		if(!$customer_name)
		{
			$data["header"]["error"] = "1";
            $data["header"]["message"] = "Customer name is required";
            $this->response($data, 200);
		}
		
		if(!$customer_email)
		{
			$data["header"]["error"] = "1";
            $data["header"]["message"] = "Customer email address is required";
            $this->response($data, 200);
		}
		
		if($customer_email)
		{
			if(!valid_email($customer_email))
			{
				$data["header"]["error"] = "1";
				$data["header"]["message"] = "Please provide valid email address";
				$this->response($data, 200);
			}
		}
		
		if(!$customer_address1)
		{
			$data["header"]["error"] = "1";
            $data["header"]["message"] = "Customer address is required";
            $this->response($data, 200);
		}
		
		if(!$customer_city)
		{
			$data["header"]["error"] = "1";
            $data["header"]["message"] = "Customer city is required";
            $this->response($data, 200);
		}
		
		if(!$customer_state)
		{
			$data["header"]["error"] = "1";
            $data["header"]["message"] = "Customer state is required";
            $this->response($data, 200);
		}
		
		if(!$customer_zipcode)
		{
			$data["header"]["error"] = "1";
            $data["header"]["message"] = "Customer zipcode is required";
            $this->response($data, 200);
		}
		
		if(!$customer_phone)
		{
			$data["header"]["error"] = "1";
            $data["header"]["message"] = "Customer phone is required";
            $this->response($data, 200);
		}
		
		if(!$total_amount || $total_amount <= 0)
		{
			$data["header"]["error"] = "1";
            $data["header"]["message"] = "Total amount is required field";
            $this->response($data, 200);
		}
		
		if($pay_by_cash_amount > $total_amount)
		{
			$data["header"]["error"] = "1";
            $data["header"]["message"] = "Pay via cash amount can not be greater than total amount";
            $this->response($data, 200);
		}
		
		$pay_by_credit_card_amount = $total_amount - $pay_by_cash_amount;
		
		if($pay_by_credit_card_amount <= 0) // negative value to zero
		{
			$pay_by_credit_card_amount = 0;
		}
		
		$apiStatus = true;
		$cx_transaction_id = 0;
		$cx_descriptor = '';
		$random_order_id = 0;
		if($pay_by_credit_card_amount > 0) //pay by credit card!
		{
			if(!$cc_name)
			{
				$data["header"]["error"] = "1";
				$data["header"]["message"] = "Name on credit card is required";
				$this->response($data, 200);
			}
			
			if(!$cc_number)
			{
				$data["header"]["error"] = "1";
				$data["header"]["message"] = "Credit card number is required";
				$this->response($data, 200);
			}
			
			if(!$cc_expiry_year)
			{
				$data["header"]["error"] = "1";
				$data["header"]["message"] = "Expiry year for credit card is required";
				$this->response($data, 200);
			}
			
			if(!$cc_expiry_month)
			{
				$data["header"]["error"] = "1";
				$data["header"]["message"] = "Expiry month for credit card is required";
				$this->response($data, 200);
			}
			
			if(!$cc_code)
			{
				$data["header"]["error"] = "1";
				$data["header"]["message"] = "CVV2 for credit card is required";
				$this->response($data, 200);
			}
			
			$arrNames = splitName($cc_name);
			
			$apiStatus = false;
			
			$custom_order_id = uniqid(); //-->'ORD-'
			
			$postParams = array();
			$postParams['amount'] 			= $pay_by_credit_card_amount;
			$postParams['cc_number'] 		= $cc_number;
			$postParams['cc_expiry_month'] 	= $cc_expiry_month;
			$postParams['cc_expiry_year'] 	= $cc_expiry_year;
			$postParams['cc_code'] 			= $cc_code;
			
			$postParams['customer_fname'] 	= $arrNames['first_name'];
			$postParams['customer_lname'] 	= $arrNames['last_name'];
			$postParams['customer_email']	= $customer_email;
			$postParams['customer_phone'] 	= $customer_phone;
			$postParams['customer_country']	= $customer_country;
			$postParams['customer_state'] 	= $customer_state;
			$postParams['customer_city']	= $customer_city;
			$postParams['customer_address'] = trim($customer_address1.' '.$customer_address2);
			$postParams['customer_zip'] 	= $customer_zipcode;			
		
			$postParams['order_id']	 		= $custom_order_id; //UJ: Passing this, becuase actual order is inserting after this API call
			
			$apiResponse = chargePaymentFromCreditCard($this->user_id, $postParams);
				
			if($apiResponse)
			{
				if(isset($apiResponse['error']))
				{
					$data["header"]["error"] = "1";
					$data["header"]["message"] = $apiResponse['error'];
					$this->response($data, 200);
				}
				else if(isset($apiResponse['success']))
				{
					$apiStatus = true;
					
					$apiData = $apiResponse['data'];
					
					$cx_transaction_id 	= @$apiData['transaction_id'];
					$cx_descriptor 		= @$apiData['descriptor'];
				}
			}
		}
		
		if($apiStatus)
		{
			if(!$customer_name)
			{
				$customer_name = $cc_name;
			}
			
			$customer_id = 0;
			$isNewCustomer = false;
			if($customer_email)
			{
				$customerInfo = $this->customer->checkCustomerByEmail($customer_email);
				
				if($customerInfo)
				{
					$customer_id = $customerInfo['customer_id'];
				}
				else
				{
					$isNewCustomer = true;
					
					$customer_data = array(
											'name' 				=> $customer_name,
											'email' 			=> $customer_email,
											'created_order_id'	=> 0,
											'created_store_id'	=> $this->store_id,
											'created_user_id'	=> $this->user_id,
											'created'			=> $created
					
					);
					
					$customer_id = $this->customer->add_customer($customer_data);
				}
			}
			
			$signature = '';
			$signature_image_data = $this->__uploadFile($this->config->item('order_signature_image_base'), asset_url('img/orders/signature'));

			if(is_array($signature_image_data) && count($signature_image_data) > 0)
			{
				if(isset($signature_image_data['path']))
				{
					$signature    = $signature_image_data['path'];
				}
			}
			
			$order_data = array(
									"store_id"				=> $store_id,
									"user_id"				=> $this->user_id,
									"total_amount"			=> $total_amount,
									"created"				=> $created,									
									"customer_id"			=> $customer_id,
									"customer_name"			=> $customer_name,
									"customer_email"		=> $customer_email,
									"customer_phone"		=> $customer_phone,
									"customer_country"		=> $customer_country,
									"customer_state"		=> $customer_state,
									"customer_city"			=> $customer_city,
									"customer_address1"		=> $customer_address1,
									"customer_address2"		=> $customer_address2,
									"customer_zipcode"		=> $customer_zipcode,
									"description"			=> '',
									"custom_order_id"		=> $custom_order_id
								);
								
			if($signature)
			{
				$order_data['customer_signature'] = $signature;
			}
			
			try
			{
				//insert order
				$order_id = $this->order->add_order($order_data);
				
				if($order_id)
				{
					if($isNewCustomer)
					{
						if($customer_id)
						{
							$customer_id = $this->customer->edit_customer($customer_id, array('created_order_id' => $order_id));
						}
					}
					
					// cc number - save only last 4 numbers!
					if($cc_number)
					{
						$cc_number = 'XXXX-XXXX-XXXX-'.substr($cc_number, -4);
					}
					
					//insert into transaction
					$transaction_data = array(
												"store_id"			=> $store_id,
												"user_id"			=> $this->user_id,
												"order_id"			=> $order_id,
												"type"				=> CONST_TRANSACTION_TYPE_PAYMENT, //1=payment, 2=refund
												"created"			=> $created,
												"amount_cc"			=> $pay_by_credit_card_amount,
												"amount_cash"		=> $pay_by_cash_amount,  
												"is_cc_swipe"		=> $is_cc_swipe, 
												"cc_name"			=> $cc_name,
												"cc_number"			=> $cc_number,
												"cc_expiry_year"	=> $cc_expiry_year,
												"cc_expiry_month"	=> $cc_expiry_month,
												"cc_code"			=> $cc_code,
												'cx_transaction_id' => $cx_transaction_id,
												'cx_descriptor'		=> $cx_descriptor, 
												'app_type'			=> $this->device_type
											);

					$this->order->add_transaction($transaction_data);

					//insert each product
					if(is_array($products) && count($products) > 0)
					{
						foreach($products as $product)
						{
							if(isset($product['product_id']))
							{
								$product_id = $product['product_id'];
								
								if($product_id)
								{
									$product_detail = $this->product->get_product_detail($product_id);
							
									if($product_detail)
									{
										$product_price = $product_detail['price'];
										
										$product_qty = 1;
										if(isset($product['quantity']))
										{
											$_product_qty = $product['quantity'];
											
											if($_product_qty > 1)
											{
												$product_qty = $_product_qty;
											}
										}									
										
										//-->$items_amount = $product_qty * $product_price; //UJ: not needed!
										
										$order_line_item_data = array(
																		"order_id"		=> $order_id,
																		"product_id"	=> $product_id,
																		"quantity"		=> $product_qty,
																		"product_price"	=> $product_price,
																		"created"		=> $created);

										$this->order->add_order_line_item($order_line_item_data);
									}
								}
							}
						}
					}					
					
					// for numeric pad product!
					if($numeric_pad_amount > 0)
					{
						$order_line_item_data = array(
														"order_id"		=> $order_id,
														"product_id"	=> CONST_PRODUCT_ID_NUMPAD,
														"quantity"		=> 1,
														"product_price"	=> $numeric_pad_amount,
														"created"		=> $created
													);

						$this->order->add_order_line_item($order_line_item_data);
					}
					
					//TODO: this _customerInfo needs to remove, even from response!
					$_customerInfo = array();
					
					$_customerInfo['name'] 		= '';
					$_customerInfo['email'] 	= '';
					$_customerInfo['phone'] 	= '';
					$_customerInfo['address1'] 	= '';
					$_customerInfo['address2'] 	= '';
					$_customerInfo['city'] 		= '';
					$_customerInfo['state'] 	= '';
					$_customerInfo['zipcode']	= '';

					$data["header"]["error"] = "0";
					$data['body']            = array("order_id" => $order_id, "descriptor" => $cx_descriptor); //-->, 'customer_info' => $_customerInfo
					$this->response($data, 200);
				}
				else
				{
					$data["header"]["error"] = "1";
					$data["header"]["message"] = "Something went wrong for order";
					$this->response($data, 200);
				}
			}
			catch(Exception $ex)
			{
				$data["header"]["error"] = "1";
				$data["header"]["message"] = "Something went wrong processing order";
				$this->response($data, 200);
			}
		}
		else
		{
			$data["header"]["error"] = "1";
			$data["header"]["message"] = "Something went wrong. Please try later!";
			$this->response($data, 200);
		}
    }
	
	function checkCustomer_post() 
	{
		/*
			Header Params:
				Token: 5cb78b2421d34eca7492d4ce4c9c6809
				Userid: 21
				Storeid: 17
			
			POST Params: 
				{"cc_info":{"cc_number":"4111111111111111","cc_expiry_year":2020,"cc_expiry_month":7,"cc_code":123}}
		*/
		
		$created	= date('Y-m-d H:i:s');
        $store_id	= $this->store_id;
        $json		= $this->post('data');

        $input_data = json_decode($json, true);
		
		if(!$input_data)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Please provide data";
            $this->response($data, 200);
        }
		
		//credit card info!
		$cc_info = @$input_data['cc_info'];
		
		$cc_number			= @$cc_info['cc_number'];
		$cc_expiry_year		= @$cc_info['cc_expiry_year'];
		$cc_expiry_month	= @$cc_info['cc_expiry_month'];
		$cc_code			= @$cc_info['cc_code'];
		
		if(!$cc_number)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Please provide credit card number";
            $this->response($data, 200);
        }
		
		if(!$cc_expiry_year)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Please provide credit card expiry year";
            $this->response($data, 200);
        }
		
		if(!$cc_expiry_month)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Please provide credit card expiry month";
            $this->response($data, 200);
        }
		
		if(!$cc_code)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Please provide credit card code";
            $this->response($data, 200);
        }

		$postParams = array();
		$postParams['cc_number'] 		= $cc_number;
		$postParams['cc_expiry_year'] 	= $cc_expiry_year;
		$postParams['cc_expiry_month'] 	= $cc_expiry_month;
		$postParams['cc_code'] 			= $cc_code;		
		
		$apiStatus 	= false;
		$apiData 	= array();
		
		$apiData['first_name'] 	= '';
		$apiData['last_name'] 	= '';
		$apiData['email'] 		= '';
		$apiData['phone'] 		= '';
		$apiData['street'] 		= '';
		$apiData['city'] 		= '';
		$apiData['state'] 		= '';
		$apiData['zip'] 		= '';
		
		$apiResponse = getCustomerDetails($this->user_id, $postParams);
		
		if($apiResponse)
		{
			if(isset($apiResponse['error']))
			{
				$apiStatus = false;
			}
			else if(isset($apiResponse['success']))
			{
				$apiStatus = true;
				
				$apiData = $apiResponse['data'];
			}
		}
		
		$_customerInfo = array();
		
		$_customerInfo['name'] 		= trim(@$apiData['first_name'].' '.@$apiData['last_name']);
		$_customerInfo['email'] 	= @$apiData['email'];
		$_customerInfo['phone'] 	= @$apiData['phone'];
		$_customerInfo['address1'] 	= @$apiData['street'];
		$_customerInfo['address2'] 	= '';					//Note: There is NO address2 field in the API from CardXecure!
		$_customerInfo['city'] 		= @$apiData['city'];
		$_customerInfo['state'] 	= @$apiData['state'];
		$_customerInfo['zipcode']	= @$apiData['zip'];

		$data["header"]["error"] = "0";
		$data['body']            = array('customer_info' => $_customerInfo);
		$this->response($data, 200);
	}
	
	function setCustomerSignatureForOrder_post()
	{
		$created			= date('Y-m-d H:i:s');
		$updated			= date('Y-m-d H:i:s');
		 
		$order_id 			= $this->post('order_id');
		
		if(!$order_id)
		{
			$data["header"]["error"] = "1";
            $data["header"]["message"] = "Order ID is required";
            $this->response($data, 200);
		}
		
		$order_detail = $this->order->get_order_detail($order_id);
		if($order_detail)
		{
			if($order_detail['user_id'] !== $this->user_id)
			{
				$data["header"]["error"] = "1";
				$data["header"]["message"] = "Order do not belong to this user";
				$this->response($data, 200);   
			}
		}
		else
		{	
			$data["header"]["error"] = "1";
			$data["header"]["message"] = "Order ID is not valid";
			$this->response($data, 200);   
		}
		
		$signature = '';
		$signature_image_data = $this->__uploadFile($this->config->item('order_signature_image_base'), asset_url('img/orders/signature'));

		if(is_array($signature_image_data) && count($signature_image_data) > 0)
		{
			if(isset($signature_image_data['path']))
			{
				$signature    = $signature_image_data['path'];
			}
		}
						
		if($signature)
		{
			$order_data = array(
								"customer_signature"	=> $signature,
								"updated"				=> $updated,
						);
			
			// update order
			$this->order->edit_order($order_id, $order_data);
			
			$receiptCreated = generateReceiptByOrderId($order_id, $this->user_id);
			
			$data["header"]["error"]   = "0";
			$data["header"]["message"] = "Customer signature has been successfully saved for the order";
			$this->response($data, 200);	
		}
		
		$data["header"]["error"]   = "1";
		$data["header"]["message"] = "Customer signature is required";
		$this->response($data, 200);
	}
	
	function setCustomerInfoForOrder_post() //TODO: NOT IN USE NOW!
	{		
		$created			= date('Y-m-d H:i:s');
		$updated			= date('Y-m-d H:i:s');
		 
		$order_id 			= $this->post('order_id');
		
		$customer_country 	= CONST_DEFAULT_COUNTRY;
		$customer_name 		= $this->post('name');
		$customer_email 	= $this->post('email');
		$customer_address1 	= $this->post('address1');
		$customer_address2 	= $this->post('address2');
		$customer_city 		= $this->post('city');
		$customer_state 	= $this->post('state');
		$customer_zipcode 	= $this->post('zipcode');
		$customer_phone 	= $this->post('phone');
		
		if(!$order_id)
		{
			$data["header"]["error"] = "1";
            $data["header"]["message"] = "Order ID is required";
            $this->response($data, 200);
		}
		
		$order_detail = $this->order->get_order_detail($order_id);
		if($order_detail)
		{
			if($order_detail['user_id'] !== $this->user_id)
			{
				$data["header"]["error"] = "1";
				$data["header"]["message"] = "Order do not belong to this user";
				$this->response($data, 200);   
			}
		}
		else
		{	
			$data["header"]["error"] = "1";
			$data["header"]["message"] = "Order ID is not valid";
			$this->response($data, 200);   
		}
		
		/*
		if(!$customer_name)
		{
			$data["header"]["error"] = "1";
            $data["header"]["message"] = "Customer name is required";
            $this->response($data, 200);
		}
		*/
		
		/*
		if(!$customer_email)
		{
			$data["header"]["error"] = "1";
            $data["header"]["message"] = "Customer email address is required";
            $this->response($data, 200);
		}
		*/
		
		if($customer_email)
		{
			if(!valid_email($customer_email))
			{
				$data["header"]["error"] = "1";
				$data["header"]["message"] = "Please provide valid email address";
				$this->response($data, 200);
			}
		}
		
		$customer_id = 0;
		$isNewCustomer = false;
		if($customer_email)
		{
			$customerInfo = $this->customer->checkCustomerByEmail($customer_email);
			
			if($customerInfo)
			{
				$customer_id = $customerInfo['customer_id'];
			}
			else
			{
				$isNewCustomer = true;
				
				$customer_data = array(
										'name' 				=> $customer_name,
										'email' 			=> $customer_email,
										'created_order_id'	=> $order_id,
										'created_store_id'	=> $this->store_id,
										'created_user_id'	=> $this->user_id,
										'created'			=> $created
				
				);
				
				$customer_id = $this->customer->add_customer($customer_data);
			}
		}
		
		$signature = '';
		$signature_image_data = $this->__uploadFile($this->config->item('order_signature_image_base'), asset_url('img/orders/signature'));

		if(is_array($signature_image_data) && count($signature_image_data) > 0)
		{
			if(isset($signature_image_data['path']))
			{
				$signature    = $signature_image_data['path'];
			}
		}
		
		$order_data = array(
								"customer_id"			=> $customer_id,
								"customer_name"			=> $customer_name,
								"customer_email"		=> $customer_email,
								"customer_phone"		=> $customer_phone,
								"customer_country"		=> $customer_country,
								"customer_state"		=> $customer_state,
								"customer_city"			=> $customer_city,
								"customer_address1"		=> $customer_address1,
								"customer_address2"		=> $customer_address2,
								"customer_zipcode"		=> $customer_zipcode,
								"updated"				=> $updated,
						);
						
		if($signature)
		{
			$order_data['customer_signature'] = $signature;
		}
		
		// update order
		$this->order->edit_order($order_id, $order_data);
		
		if($signature)
		{
			$receiptCreated = generateReceiptByOrderId($order_id, $this->user_id);
		}
		
		$data["header"]["error"]   = "0";
		$data["header"]["message"] = "Customer details has been successfully saved for the order";
		$this->response($data, 200);				
	}

    function getOrdersByUser_post()
    {
		$from_date 	= $this->post('from_date');
		$to_date 	= $this->post('to_date');
		
		$filters = array();
		$filters['from_date'] 	= $from_date;
		$filters['to_date'] 	= $to_date;
		
        $orders = $this->order->get_all_orders_by_user($this->user_id, $filters);
        $data["header"]["error"] = "0";
        $data['body']            = array("orders"=>$orders);
        $this->response($data, 200);   
    }

    function getOrder_post()
    {
        $order_id = $this->post('order_id');
        if(!$order_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide order id";
            $this->response($data, 200);
        }

        $order_detail         = $this->order->get_order_detail($order_id);
        $order_detail["products"] = $this->product->get_order_products($order_id);
		$order_detail["payment_transaction"] = $this->order->get_payment_transaction_by_order($order_id);
		$order_detail["refund_transactions"] = $this->order->get_refund_transactions_by_order($order_id);
		
        $data["header"]["error"] = "0";
        $data['body']            = array("order_detail"=>$order_detail);
        $this->response($data, 200);   
    }

    function refundOrder_post()
    {
        $created      = date('Y-m-d H:i:s');
        $order_id = $this->post('order_id');
        $amount_cash = $this->post('amount_cash');
        $amount_cc = $this->post('amount_cc');
        $description = $this->post('description');

        if(!$order_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide order id";
            $this->response($data, 200);
        }
        if(!$amount_cash && !$amount_cc)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide amount";
            $this->response($data, 200);
        }
        
		/*
		if(!$description)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide description";
            $this->response($data, 200);
        }*/

        $order_detail = $this->order->get_order_detail($order_id);
		if(!$order_detail)
		{
			$data["header"]["error"] = "1";
            $data["header"]["message"] = "Order ID is not valid";
            $this->response($data, 200);  
		}
		
        if($order_detail['user_id'] !== $this->user_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Order do not belong to this user";
            $this->response($data, 200);   
        }
		
		$_refund_amount = $amount_cc + $amount_cash;
		$_total_amount  = $order_detail['total_amount'];
		
		if($_refund_amount > $_total_amount)
		{
			$data["header"]["error"] = "1";
            $data["header"]["message"] = "Refund amount should be less than order amount";
            $this->response($data, 200); 
		}
		
		$apiStatus = true;
		$refund_cx_transaction_id = 0;
		if($amount_cc > 0) //refund via credit card
		{
			$apiStatus = false;
			
			$transactionInfo = $this->order->get_payment_transaction_by_order($order_id);
			
			if($transactionInfo)
			{
				$cx_transaction_id = $transactionInfo['cx_transaction_id'];
				
				if($cx_transaction_id)
				{
					$postParams = array();
					$postParams['amount'] 			= $amount_cc;
					$postParams['transaction_id'] 	= $cx_transaction_id;
					
					$apiResponse = refundPayment($this->user_id, $postParams);
					
					if($apiResponse)
					{
						if(isset($apiResponse['error']))
						{
							$data["header"]["error"] = "1";
							$data["header"]["message"] = $apiResponse['error'];
							$this->response($data, 200);
						}
						else if(isset($apiResponse['success']))
						{
							$apiStatus = true;
							
							$apiData = $apiResponse['data'];
							
							$refund_cx_transaction_id = @$apiData['transaction_id'];
						}
					}
				}
				else
				{
					$data["header"]["error"] = "1";
					$data["header"]["message"] = "Transaction ID is missing for this order";
					$this->response($data, 200);
				}
			}
			else
			{
				$data["header"]["error"] = "1";
				$data["header"]["message"] = "We didn't find any transaction for this order";
				$this->response($data, 200);
			}
		}

		if($apiStatus)
		{
			$amount = $amount_cc + $amount_cash;
			$total_amount = $order_detail['total_amount'] - $amount;
			$this->order->edit_order(
										$order_id, array
													(
														'total_refund'	=>	$order_detail['total_refund']+$amount,
														'total_amount'	=>	$total_amount
													)
									);

			$transaction_data = array(
										"order_id" 			=> $order_id,
										"store_id" 			=> $this->store_id,
										"user_id" 			=> $this->user_id,
										"type" 				=> CONST_TRANSACTION_TYPE_REFUND,
										"amount_cc" 		=> $amount_cc,
										"amount_cash" 		=> $amount_cash,
										"cx_transaction_id" => $refund_cx_transaction_id, 
										"app_type"			=> $this->device_type,
										"created" 			=> $created
									);
			$this->order->add_transaction($transaction_data);

			$data["header"]["error"] = "0";
			$data["header"]["message"] = "Success";
			$this->response($data, 200);    
		}
		else
		{
			$data["header"]["error"] = "1";
			$data["header"]["message"] = "Something went wrong. Please try later!";
			$this->response($data, 200);
		}
    }

    function getUserDetails_post()
    {
        $user_detail = $this->profile->get_user_detail($this->user_id);
        $data["header"]["error"] = "0";
        $data['body']            = array("user_detail"=>$user_detail);
        $this->response($data, 200);   
    }
	
	function getPaymentMode_post()
	{
		$updated      = date('Y-m-d H:i:s');
		
		$merchantInfo = $this->profile->checkUserMerchantDetails($this->user_id);
		
		if(!$merchantInfo)
		{
			$data["header"]["error"] = "1";
			$data["header"]["message"] = "No merchant details linked with this user!";
			$this->response($data, 200);
		}
		else			
		{
			$merchant_id = $merchantInfo['id'];
			
			$apiStatus = false;
			$apiData = array();
			
			$apiResponse = getMerchantPaymentMode($this->user_id);
						
			if($apiResponse)
			{
				if(isset($apiResponse['error']))
				{
					$data["header"]["error"] = "1";
					$data["header"]["message"] = $apiResponse['error'];
					$this->response($data, 200);
				}
				else if(isset($apiResponse['success']))
				{
					$apiStatus = true;
					
					$apiData = $apiResponse['data'];
				}
			}
			
			if($apiStatus)
			{
				$_apiData_Mode  	= $apiData['mode'];
				$_apiData_Message	= $apiData['message'];
				
				$this->profile->edit_user_merchant_info($merchant_id, array("last_updated"=>$updated, "cx_mode"=>$_apiData_Mode));
					
				$data["header"]["error"] = "0";
				$data["header"]["message"] = $_apiData_Message;
				$this->response($data, 200);
			}
			else
			{
				$data["header"]["error"] = "1";
				$data["header"]["message"] = "Something went wrong. Please try later!";
				$this->response($data, 200);
			}
		}
	}
	
	function setPaymentMode_post()
	{
		$updated		= date('Y-m-d H:i:s');
		$payment_mode	= $this->post('payment_mode'); //live or sandbox
		
		if(!$payment_mode)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Payment mode is required";
            $this->response($data, 200);
        }
		
		if($payment_mode != CONST_TXT_MERCHANT_MODE_LIVE && $payment_mode != CONST_TXT_MERCHANT_MODE_SANDBOX)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Payment mode should be ".CONST_TXT_MERCHANT_MODE_SANDBOX." or ".CONST_TXT_MERCHANT_MODE_LIVE;
            $this->response($data, 200);
        }
		
		$merchantInfo = $this->profile->checkUserMerchantDetails($this->user_id);
		
		if(!$merchantInfo)
		{
			$data["header"]["error"] = "1";
			$data["header"]["message"] = "No merchant details linked with this user!";
			$this->response($data, 200);
		}
		else			
		{
			$merchant_id = $merchantInfo['id'];
			
			$apiStatus = false;
			$apiData = array();
			
			$postParams = array();			
			$postParams['payment_mode'] = $payment_mode;
			
			$apiResponse = changeMerchantPaymentMode($this->user_id, $postParams);
						
			if($apiResponse)
			{
				if(isset($apiResponse['error']))
				{
					$data["header"]["error"] = "1";
					$data["header"]["message"] = $apiResponse['error'];
					$this->response($data, 200);
				}
				else if(isset($apiResponse['success']))
				{
					$apiStatus = true;
					
					$apiData = $apiResponse['data'];
				}
			}
			
			if($apiStatus)
			{
				$_apiData_Mode  	= $apiData['mode'];
				$_apiData_Message	= $apiData['message'];
				
				$this->profile->edit_user_merchant_info($merchant_id, array("last_updated"=>$updated, "cx_mode"=>$_apiData_Mode));
					
				$data["header"]["error"] = "0";
				$data["header"]["message"] = 'Payment mode has been changed to '.$_apiData_Message;
				$this->response($data, 200);
			}
			else
			{
				$data["header"]["error"] = "1";
				$data["header"]["message"] = "Something went wrong. Please try later!";
				$this->response($data, 200);
			}
		}
	}
}