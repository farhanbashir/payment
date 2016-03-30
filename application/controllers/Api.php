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
    	
        $this->user_id  = '';
        $this->token    = '';
        $this->store_id = '';

        $headers = getallheaders();

	   if(!in_array($this->router->method, $this->config->item('allowed_calls_without_token')))
       {
            if(isset($headers['Token']))
            {
                if(isset($headers['Userid']))
                {
                    if(!$this->device->validToken($headers['Userid'],$headers['Token']))
                    {
                        $data["header"]["error"] = "1";
                        $data["header"]["message"] = "Please provide valid token";
                        $this->response($data, 200);                     
                    }
                    else
                    {
                        if(!$this->user->validStore($headers['Userid'],$headers['Storeid']))
                        {
                            $data["header"]["error"] = "1";
                            $data["header"]["message"] = "Please provide valid store id";
                            $this->response($data, 200);
                        }
                        else
                        {
                            $this->user_id  = $headers['Userid'];
                            $this->token    = $headers['Token'];
                            $this->store_id = $headers['Storeid'];
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
		$parent_user_id = $this->post('parent_user_id');
        $role_id        = $this->post('role_id');
        $device_id      = $this->post('device_id');
        $device_type    = $this->post('device_type');
        $created        = date('Y-m-d H:i:s');
        $updated        = date('Y-m-d H:i:s');
        $status         = 1;

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
        if($role_id == 1)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Role ID should be 2 or 3";
            $this->response($data, 200);   
        }    
        if($role_id == 3 && $parent_user_id == 0)
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
				//insert device table
				if(isset($device_type) && isset($device_id))
				{
					$device_data = array('user_id'=>$user_id,'uid'=>$device_id, 'type'=>$device_type);
					$this->device->insert_device($device_data);
				}
				
				if($role_id == 2) //business admin
				{
					//if user role is business admin then create empty store
					$store_id = $this->profile->add_user_store(array("user_id"=>$user_id));       
				}
				
				$data["header"]["error"]   = "0";
				$data["header"]["message"] = "Signup successfull";
				$this->response($data, 200);
			}
			else
			{
				$data["header"]["error"] = "1";
				$data["header"]["message"] = "Some went wrong. Please try again!";
				$this->response($data, 200);
			}
		}
		else
		{
			$data["header"]["error"] = "1";
			$data["header"]["message"] = "Some went wrong. Please try later!";
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
		
        $logo = "";
        $temp_image_data = $this->__uploadFile($this->config->item('store_image_base'), asset_url('img/stores'));

        if(count($temp_image_data) > 0)
        {
            $logo    = $temp_image_data['path'];
        }

        if(!$name)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Business name is required";
            $this->response($data, 200);
        }
		
		if(!$description)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Description is required";
            $this->response($data, 200);
        }
		
		if(!$address)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Address is required";
            $this->response($data, 200);
        }
		
		if(!$phone)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Phone number is required";
            $this->response($data, 200);
        }
		
        // if(!$logo)
        // {
        //     $data["header"]["error"] = "1";
        //     $data["header"]["message"] = "Logo is required";
        //     $this->response($data, 200);
        // }

        $created = date('Y-m-d H:i:s');
        $updated = date('Y-m-d H:i:s');
        $status  = 1;

        $store_id = $this->store_id;

        if(!$store_id)
        {
            $store_data = array("user_id"=>$this->user_id,"name"=>$name,"description"=>$description,"address"=>$address,"phone"=>$phone,"created"=>$created,"updated"=>$updated,"status"=>$status);
            if($logo !== '')
            {
                $store_data['logo'] = $logo;
            }    
            $store_id = $this->profile->add_user_store($store_data);
        }
        else
        {
            $store_data = array("user_id"=>$this->user_id,"name"=>$name,"description"=>$description,"address"=>$address,"phone"=>$phone,"updated"=>$updated,"status"=>$status);
            if($logo !== '')
            {
                $store_data['logo'] = $logo;
            }
            $this->profile->edit_user_store($store_id, $store_data);
        }    
        
        $data["header"]["error"] = "0";
        $data["header"]["message"] = "Success";
        $data['body'] = array("store_id"=>$store_id);
        $this->response($data, 200);
    }
	
	function getBankAccountStatus_post()
    {
		$updated = date('Y-m-d H:i:s');
		$bankInfo = $this->profile->checkUserBankDetails($this->user_id);
		
		if($bankInfo)
		{
			$bank_id = $bankInfo['bank_id'];
			$user = $this->user->get_user_detail($this->user_id);
			
			$postParams = array();
			$postParams['email'] 	= @$user['email'];
			$postParams['password']	= @$user['plain_password'];
			
			$apiStatus = false;
			$apiData = array();
			$apiResponse = getMerchantBankAccountStatus($postParams);
			
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
				$_apiData_Status = $apiData['status'];
				$_apiData_Message = $apiData['message'];
				
				if($_apiData_Status) //verified!
				{
					$this->profile->edit_user_bank($bank_id, array("updated"=>$updated,"status"=>2)); //2=verified, 1=Not Verified
					
					$data["header"]["error"] = "0";
					$data["header"]["message"] = $_apiData_Message;
					$data['body'] = array("bank_id"=>$bank_id);
					$this->response($data, 200);
				}
				else  //not-verified!
				{
					$this->profile->edit_user_bank($bank_id, array("updated"=>$updated,"status"=>1)); //2=verified, 1=Not Verified
					
					$data["header"]["error"] = "1";
					$data["header"]["message"] = $_apiData_Message;
					$this->response($data, 200);
				}
			}
			else
			{
				$data["header"]["error"] = "1";
				$data["header"]["message"] = "Some went wrong. Please try later!";
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
		$bankInfo = $this->profile->checkUserBankDetails($this->user_id);
		
		if($bankInfo)
		{
			$this->getBankAccountStatus_post();
		}
		else //user can save this, if bank is not previouly inserted by the user! - becauase user will have only 1 bank linked.
		{
			$bank_name			= $this->post('bank_name');
			$bank_address    	= $this->post('bank_address');
			$swift_code      	= $this->post('swift_code');
			$account_title      = $this->post('account_title');
			$account_number 	= $this->post('account_number');
			
			$created        = date('Y-m-d H:i:s');
			$updated        = date('Y-m-d H:i:s');
			$status         = 1;

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

			$bank_id = $this->post('bank_id');

			if(!$bank_id)
			{
				$user = $this->user->get_user_detail($this->user_id);
				
				$store_details = $this->profile->get_store_detail($this->store_id);
				
				$postParams = array();
				$postParams['email'] 				= @$user['email'];
				$postParams['password'] 			= @$user['plain_password'];
				$postParams['first_name'] 			= @$user['first_name'];
				$postParams['last_name'] 			= @$user['last_name'];
				$postParams['phone'] 				= @$store_details['phone'];
				$postParams['store_name'] 			= @$store_details['name'];
				$postParams['bank_name'] 			= $bank_name;
				$postParams['bank_address'] 		= $bank_address;
				$postParams['bank_swift_code'] 		= $swift_code;
				$postParams['bank_account_title'] 	= $account_title;
				$postParams['bank_account_number'] 	= $account_number;
				
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
				
				if($apiStatus)
				{
					$bank_id = $this->profile->add_user_bank(array("user_id"=>$this->user_id,"bank_name"=>$bank_name,"bank_address"=>$bank_address,"swift_code"=>$swift_code,"account_title"=>$account_title,"account_number"=>$account_number,"created"=>$created,"updated"=>$updated,"status"=>$status));
					
					//insert into merchant info
					$merchant_info = array();
					$merchant_info['user_id'] 					= $this->user_id;
					$merchant_info['email'] 					= @$user['email'];
					$merchant_info['password'] 					= @$user['plain_password'];
					$merchant_info['cx_authenticate_id'] 		= @$apiData['authenticate_id'];
					$merchant_info['cx_authenticate_password'] 	= @$apiData['authenticate_password'];
					$merchant_info['cx_secret_key'] 			= @$apiData['secret_key'];
					$merchant_info['cx_hash'] 					= @$apiData['mode'];
					$merchant_info['cx_mode'] 					= @$apiData['hash'];
					$merchant_info['last_updated'] 				= $created;
					
					$this->profile->add_user_merchant_info($merchant_info);
				}
				else
				{
					$data["header"]["error"] = "1";
					$data["header"]["message"] = "Some went wrong. Please try later!";
					$this->response($data, 200);
				}
			}   
			else
			{
				$this->profile->edit_user_bank($bank_id, array("user_id"=>$this->user_id,"bank_name"=>$bank_name,"bank_address"=>$bank_address,"swift_code"=>$swift_code,"account_title"=>$account_title,"account_number"=>$account_number,"updated"=>$updated,"status"=>$status));
			} 
			
			$data["header"]["error"] = "0";
			$data["header"]["message"] = "Success";
			$data['body'] = array("bank_id"=>$bank_id);
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
		
		$device_type = 0;
		if($device_type)
		{
			$device_type = 1;
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
                
                if($user['role_id'] == 3) //if user is staff then get admin store id
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
                }    
                
                $array['user_id']          = $user['user_id'];
                $array['token']            = $token;
                $array['store_id']         = $user_detail['store_id'];
                $array['role_id']         = $user['role_id'];
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
        if(!isset($store_id))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide store";
            $this->response($data, 200);
        }
        if(!isset($category_ids))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide product category";
            $this->response($data, 200);
        }

        $product_id = $this->product->add_product(array("user_id"=>$this->user_id,"store_id"=>$store_id,"name"=>$name,"description"=>$description,"price"=>$price,"created"=>$created,"updated"=>$updated,"status"=>$status));
        //category work here
        $categories = json_decode($category_ids,true);
        foreach($categories  as $category)
        {
            $this->category->add_product_category(array("product_id"=>$product_id,"category_id"=>$category));    
        }    
        
        
        $temp_image_data = $this->__uploadFile($this->config->item('user_image_base'), asset_url('img/products'));

        if(count($temp_image_data) > 0)
        {
            $product_media = array("product_id"=>$product_id,"file_name"=>$temp_image_data['path'],"media_type"=>$temp_image_data['ext'],"created"=>$created,"updated"=>$updated,"status"=>$status);

            $this->product->add_product_media($product_media);
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
        $updated      = date('Y-m-d H:i:s');
        $status       = 1;

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
        if(!isset($product_id))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide product price";
            $this->response($data, 200);
        }
        if(!isset($store_id))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide store";
            $this->response($data, 200);
        }
        if(!isset($category_ids))
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide product category";
            $this->response($data, 200);
        }

        if($this->product->edit_product($product_id, array("user_id"=>$this->user_id,"store_id"=>$store_id,"name"=>$name,"description"=>$description,"price"=>$price,"updated"=>$updated,"status"=>$status)))
        {
            //category work here
            $this->category->delete_all_categories_for_product($product_id);
            $categories = json_decode($category_ids,true);
            foreach($categories  as $category)
            {
                $this->category->add_product_category(array("product_id"=>$product_id,"category_id"=>$category));   
            }    
            
            
            $temp_image_data = $this->__uploadFile($this->config->item('user_image_base'), asset_url('img/products'));

            if(count($temp_image_data) > 0)
            {
                $product_media = array("product_id"=>$product_id,"file_name"=>$temp_image_data['path'],"media_type"=>$temp_image_data['ext'],"updated"=>$updated,"status"=>$status);

                $this->product->edit_product_media($product_id, $product_media);
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
        $categories              = $this->category->get_all_categories($this->store_id);
        $data["header"]["error"] = "0";
        $data['body']            = array("categories"=>$categories);
        $this->response($data, 200);
    }

    function getProducts_post()
    {
        $products                = $this->product->get_all_products($this->store_id);
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
        $data["header"]["error"] = "0";
        $data['body']            = array("product_detail"=>$product_detail);
        $this->response($data, 200);   
    }

    function createOrder_post()
    {
        $created      = date('Y-m-d H:i:s');
        $store_id = $this->store_id;
        $json = $this->post('data');

        $input_data = json_decode($json,true);
		
		if(!$input_data)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Please provide product data";
            $this->response($data, 200);
        }
		
		$total_amount = $input_data["total_amount"];
		$pay_by_cash_amount = $input_data['pay_by_cash']['amount'];
		
		$pay_by_credit_card_amount = $total_amount - $pay_by_cash_amount;
		
		if($pay_by_credit_card_amount <= 0) // negative value to zero
		{
			$pay_by_credit_card_amount = 0;
		}
		
		//pay by credit card!
		if($pay_by_credit_card_amount > 0)
		{
			/* TODO: In-Progress work!
			$postParams = array();
			$postParams['amount'] 			= $pay_by_credit_card_amount;
			$postParams['authenticate_id'] 	= 'f521db864807b9a09f4abb3deb828c29';
			$postParams['authenticate_pw'] 	= '2e9120d98798cb6b04eda313966604ad';
			$postParams['ccn'] 				= @$input_data['pay_by_credit_card']['cc_number'];
			$postParams['city'] 			= 'NYC';
			$postParams['country'] 			= 'USA';
			$postParams['currency'] 		= 'USD';
			$postParams['customerip'] 		= '127.0.0.1';
			$postParams['cvc_code'] 		= @$input_data['pay_by_credit_card']['cc_code'];
			$postParams['email'] 			= $input_data['customer_info']['email'];
			$postParams['exp_month'] 		= @$input_data['pay_by_credit_card']['cc_expiry_month'];
			$postParams['exp_year'] 		= @$input_data['pay_by_credit_card']['cc_expiry_year'];
			$postParams['firstname'] 		= 'abc';
			$postParams['lastname'] 		= 'xyz';
			$postParams['orderid']	 		= '101';
			$postParams['phone'] 			= '111-222-333-4';
			$postParams['state'] 			= 'NY';
			$postParams['street'] 			= 'ABC Street';
			$postParams['transaction_type'] = 'A';
			$postParams['zip'] 				= '12345';
			
			chargePaymentFromCreditCard($postParams);
			*/
		}

        $order_data = array("store_id"=>$store_id,"user_id"=>$this->user_id,"total_amount"=>$total_amount,"created"=>$created,"customer_email"=>$input_data['customer_info']['email'],"description"=>'');
        try
        {
            //insert order
            $order_id = $this->order->add_order($order_data);
			
			// cc number!
			$cc_number = '';
			if(@$input_data['pay_by_credit_card']['cc_number'])
			{
				$cc_number = $input_data['pay_by_credit_card']['cc_number'];
				
				if($cc_number)
				{
					$cc_number = 'XXXX-XXXX-XXXX-'.substr($cc_number, -4);
				}
			}
			
			// cc swipe!
			$is_cc_swipe = 0;
			if(@$input_data['pay_by_credit_card']['is_swipe'])
			{
				$is_swipe = $input_data['pay_by_credit_card']['is_swipe'];
				
				if($is_swipe == 1)
				{
					$is_cc_swipe = 1;
				}
			}

            //insert into transaction
            $transaction_data = array(
										"store_id"=>$store_id,
										"user_id"=>$this->user_id,
										"order_id"=>$order_id,
										"type"=>1, //1=payment, 2=refund
										"created"=>$created,
										"amount_cc"=> $pay_by_credit_card_amount,
										"amount_cash"=> $pay_by_cash_amount,  
										"is_cc_swipe"=> $is_cc_swipe, 
										"cc_name"=> @$input_data['pay_by_credit_card']['cc_name'],
										"cc_number"=> $cc_number,
										"cc_expiry_year"=> @$input_data['pay_by_credit_card']['cc_expiry_year'],
										"cc_expiry_month"=> @$input_data['pay_by_credit_card']['cc_expiry_month'],
										"cc_code"=> @$input_data['pay_by_credit_card']['cc_code'],
									);

            $this->order->add_transaction($transaction_data);

            //insert each product
            foreach($input_data['products'] as $product)
            {
                $product_detail = $this->product->get_product_detail($product['product_id']);
                $items_amount = $product['quantity'] * $product_detail['price'];
                $order_line_item_data = array("order_id"=>$order_id,"product_id"=>$product['product_id'],"quantity"=>$product['quantity'],"product_price"=>$product_detail['price'],"created"=>$created);

                $this->order->add_order_line_item($order_line_item_data);
            }
			
			// for numeric pad product!
			if(isset($input_data["numeric_pad"]))
			{
				if(isset($input_data["numeric_pad"]["amount"]))
				{
					$_numeric_pad_amount = $input_data["numeric_pad"]["amount"];
					
					if($_numeric_pad_amount > 0)
					{
						$order_line_item_data = array("order_id"=>$order_id,"product_id"=>-1,"quantity"=>1,"product_price"=>$_numeric_pad_amount,"created"=>$created);

						$this->order->add_order_line_item($order_line_item_data);
					}
				}
			}

            $data["header"]["error"] = "0";
            $data['body']            = array("order_id"=>$order_id);
            $this->response($data, 200);
        }
        catch(Exception $ex)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Some error while processing order";
            $this->response($data, 200);
        }
    }

    function getOrdersByUser_post()
    {
        $orders = $this->order->get_all_orders_by_user($this->user_id);
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
		$order_detail["transactions"] = $this->order->get_order_transactions($order_id);
		
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
        if(!$description)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Provide description";
            $this->response($data, 200);
        }

        $order_detail = $this->order->get_order_detail($order_id);
        if($order_detail['user_id'] !== $this->user_id)
        {
            $data["header"]["error"] = "1";
            $data["header"]["message"] = "Order do not belong to this user";
            $this->response($data, 200);   
        }    

        $amount = $amount_cc + $amount_cash;
        $total_amount = $order_detail['total_amount'] - $amount;
        $this->order->edit_order($order_id, array('total_refund'=>$order_detail['total_refund']+$amount,'total_amount'=>$total_amount));

        $transaction_data = array("order_id"=>$order_id,"store_id"=>$this->store_id,"user_id"=>$this->user_id,"type"=>2,"amount_cc"=>$amount_cc,"amount_cash"=>$amount_cash,"created"=>$created);
        $this->order->add_transaction($transaction_data);


        $data["header"]["error"] = "0";
        $data["header"]["message"] = "Success";
        $this->response($data, 200);    

    }

    function getUserDetails_post()
    {
        $user_detail = $this->profile->get_user_detail($this->user_id);
        $data["header"]["error"] = "0";
        $data['body']            = array("user_detail"=>$user_detail);
        $this->response($data, 200);   
    }

    
}