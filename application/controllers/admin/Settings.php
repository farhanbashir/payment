<?php
Class Settings extends CI_Controller
{
	function __construct()
	{
        parent::__construct();
        $this->load->model('User');
        $this->load->model('Profile');
        if (!$this->session->userdata('logged_in'))
        {
            redirect(base_url());
        }
    }

   	function index()
   	{   		
   		$userId 			= getLoggedInUserId();
   		$storeId 			= getLoggedInStoreId(); 
   		$data 				= array();
   		$basicInfoData 		= array();
   		$businessInfoData 	= array();
   		$bankInfoData 		= array();
   		$receiptInfoData 	= array();
   		$aErrorMessage 		= array();
		$showErrorMessage 	= "";
		$UsersDetails 		= $this->Profile->get_user_detail($userId);
		$userStoreDetails 	= $this->Profile->get_store_detail($storeId);


   		#<!-------Basic Info:: Section Start-------->
   		
   			#<!---Submitter::Basic Info START--->

   		if($this->input->post('btn-basic-info'))
   		{
   			$basicInfoData = $this->input->post();
   			extract($basicInfoData);
   			
   			if(!$first_name)
   			{
   				$aErrorMessage[] = "First name is required";
   			}

   			if(!$last_name)
   			{
   				$aErrorMessage[] = "Last name is required";
   			}

   			if(!$email)
   			{
   				$aErrorMessage[] = "Email is required";
   			}

   			if($email)
   			{
   				if (!filter_var($email, FILTER_VALIDATE_EMAIL))
   				{
					$aErrorMessage[] = "Please provide valid email address"; 
				}
   			}

   			if($password)
   			{
   				if($password!=$confirm_password)
   				{
   					$aErrorMessage[] = "Passwords does not match";
   				}
   			}

   			if(!$security_answer)
   			{
   				$aErrorMessage[]="Security answer required";   				
   			}

   			if(is_array($aErrorMessage) && count($aErrorMessage))
			{	
				$showErrorMessage = getFormValidationErrorMessage($aErrorMessage);
				$this->session->set_flashdata('errMsgBasicInfo',$showErrorMessage);
			}
			else
			{
				$saveBasicInfoData = array(

						'first_name'	=> $first_name,
						'last_name'		=> $last_name,
						'email'			=> $email,
						'updated'		=> date("Y-m-d H:i:s"),
					);
				if($password)
				{
					$saveBasicInfoData['password'] = md5($password);
				}
				$securityInfoData = array(

					'security_question_id'	=> $security_question,
					'security_answer'		=> $security_answer,

					);
				$this->User->edit_user($userId,$saveBasicInfoData);
				
				
				$user_detail = $this->Profile->checkUserDetails($userId);

				if($user_detail)
				{
					$securityInfoData['updated'] = date("Y-m-d H:i:s");
					$this->Profile->edit_user_detail($userId,$securityInfoData);
				}

				else
				{
					$securityInfoData['created'] = date("Y-m-d H:i:s");
					$securityInfoData['user_id'] = $userId;
					$securityInfoData['status'] = CONST_STATUS_ID_ACTIVE;
					$this->Profile->add_user_detail($securityInfoData);
				}
				$this->session->set_flashdata('successMsgBasicInfo','Basic Information updated successfully');
			}
   		}
   			#<!---Submitter::Basic Info End--->
   		else
   		{
   			$basicInfoData['first_name'] = $UsersDetails['first_name'];
   			$basicInfoData['last_name'] = $UsersDetails['last_name'];
   			$basicInfoData['email'] = $UsersDetails['email'];
   			$basicInfoData['security_question'] = $UsersDetails['security_question_id'];
   			$basicInfoData['security_answer'] = $UsersDetails['security_answer'];
   		}
   		
   		#<!-------Basic Info:: Section End-------->
   		#<------------------------------------------->


   		#<!-------Business Info:: Section Start-------->

   		   #<!---Submitter::Business Info Start--->

   		if($this->input->post('btn-business-info'))
   		{
   			$businessInfoData = $this->input->post();
   			extract($businessInfoData);
   			
   			if(!$business)
   			{
   				$aErrorMessage[]="Business name required";
   			}
   			if($email)
   			{
   				if (!filter_var($email, FILTER_VALIDATE_EMAIL))
   				{
				  $aErrorMessage[] = "Please provide valid email address"; 
				}
   			}

   			if (isset($_FILES['image']) && !empty($_FILES['image']['name']))
	      	{
		   		$config['upload_path'] = './'.CONST_IMAGE_UPLOAD_DIR;
   				$config['allowed_types'] = 'gif|jpg|png';
   				$this->load->library('upload');
   				$load =$this->upload->initialize($config);

   				if ( ! $this->upload->do_upload("image"))
   				{	
   					$imageUploadError = array('error' => $this->upload->display_errors());
   					$aErrorMessage[]  = $imageUploadError['error'];
   				}
   				else
   				{
   					$file_name = $this->upload->data();
   					
   					$file_name = base_url().CONST_IMAGE_UPLOAD_DIR.$file_name['file_name'];
   					
   					$User_Store_Detail = $this->Profile->checkUserStoreDetails($userId);
   					
   					if($User_Store_Detail)
   					{	
   						$old_image = $User_Store_Detail['logo'];

   						$this->Profile->edit_user_store($storeId, array('logo'=>$file_name));
   						$businessInfoData['old_image'] = $file_name;
   					}
   					
   					if($old_image)
   					{	
   						$old_image = str_replace(base_url(),'', $old_image);
   						@unlink($old_image);
   					}
   				}
   			}
   			if(is_array($aErrorMessage) && count($aErrorMessage))
   			{	
   				$showErrorMessage = getFormValidationErrorMessage($aErrorMessage);
   				$this->session->set_flashdata('errMsgBusinessInfo',$showErrorMessage);
   			}
   			else
   			{	
   				$saveBusinessInfoData = array();
   				$saveBusinessInfoData['name'] 	 	 = $business;
   				$saveBusinessInfoData['description'] = $description;
   				$saveBusinessInfoData['email'] 		 = $email;
   				$saveBusinessInfoData['phone'] 		 = $phone;
   				$saveBusinessInfoData['address'] 	 = $address;
   				$saveBusinessInfoData['facebook'] 	 = $facebook;
   				$saveBusinessInfoData['twitter'] 	 = $twitter;
   				$saveBusinessInfoData['website'] 	 = $website;
   				$saveBusinessInfoData['updated']	 = date("Y-m-d H:i:s"); 

   				$User_Store_Detail = $this->Profile->checkUserStoreDetails($userId);
   			
   				if($User_Store_Detail)
   				{
   					$this->Profile->edit_user_store($storeId, $saveBusinessInfoData);
   					$this->session->set_flashdata('successMsgBusinessInfo','Business Information updated successfully');
   				}

   			}
   		} #<!---Submitter::Business Info End--->
   		else
   		{
   			$businessInfoData['business'] 		= $userStoreDetails['name'];
   			$businessInfoData['old_image'] 		= $userStoreDetails['logo'];
   			$businessInfoData['description'] 	= $userStoreDetails['description'];
   			$businessInfoData['email'] 			= $userStoreDetails['email'];
   			$businessInfoData['phone'] 			= $userStoreDetails['phone'];
   			$businessInfoData['address'] 		= $userStoreDetails['address'];
   			$businessInfoData['facebook'] 		= $userStoreDetails['facebook'];
   			$businessInfoData['twitter'] 		= $userStoreDetails['twitter'];
   			$businessInfoData['website'] 		= $userStoreDetails['website'];
   		}

   		#<!-------Business Info:: Section ENd-------->
   		#<------------------------------------------->


		#<!-------Bank Info:: Section Start-------->

   		   #<!---Submitter::Bank Info Start--->

   		if($this->input->post('btn-bank-info'))
   		{
   			$bankInfoData = $this->input->post();
   			extract($bankInfoData);
   			
   			if(!$bank_name)
   			{
   				$aErrorMessage[] = "Bank Name is Required";
   			}

   			if(!$bank_address)
   			{
   				$aErrorMessage[] = "Bank Address is required";
   			}

   			if(!$swift_code)
   			{
   				$aErrorMessage[] = "Swift Code is required";
   			}

   			if(!$account_title)
   			{
   				$aErrorMessage[] = "Account Title is required";
   			}

   			if(!$account_number)
   			{
   				$aErrorMessage[] = " Account number is required";
   			}

   			if(is_array($aErrorMessage) && count($aErrorMessage))
			{	
				$showErrorMessage = getFormValidationErrorMessage($aErrorMessage);
				$this->session->set_flashdata('errMsgBankInfo',$showErrorMessage);
			}
			else
			{
				$saveBankInfoData = array();

				$saveBankInfoData['bank_name'] = $bank_name;
				$saveBankInfoData['bank_address'] = $bank_address;
				$saveBankInfoData['swift_code'] = $swift_code;
				$saveBankInfoData['account_title'] = $account_title;
				$saveBankInfoData['account_number'] = $account_number;

				$bankInfo = $this->Profile->checkUserBankDetails($userId);

				if($bankInfo)
				{
					$bank_id = $bankInfo['bank_id'];
			
					$saveBankInfoData['updated'] = date("Y-m-d H:i:s");

					$this->Profile->edit_user_bank($bank_id,$saveBankInfoData);
				}
				else
				{
					$saveBankInfoData['created'] = date("Y-m-d H:i:s");
					$saveBankInfoData['user_id'] = $userId;
					$saveBankInfoData['status']  = CONST_BANK_STATUS_NOT_VERIFIED;
					$this->Profile->add_user_bank($saveBankInfoData);
				}
				
				$postParams = array();
				$postParams['bank_name'] 			= $bank_name;
				$postParams['bank_address'] 		= $bank_address;
				$postParams['bank_swift_code'] 		= $swift_code;
				$postParams['bank_account_title'] 	= $account_title;
				$postParams['bank_account_number'] 	= $account_number;
				
				$apiStatus = false;
				$apiData = array();
				$apiResponse = editMerchantDetails($userId, $postParams);
				
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
				//Note: We are just updading business information to CardXecure system.
				
				$this->session->set_flashdata('successMsgBankInfo','Bank Information updated successfully');
			}

   		}		 #<!---Submitter::Bank Info End--->
   		else
   		{
   			$bankInfoData['bank_name'] 		= $UsersDetails['bank_name'];
   			$bankInfoData['bank_address'] 	= $UsersDetails['bank_address'];
   			$bankInfoData['swift_code'] 	= $UsersDetails['swift_code'];
   			$bankInfoData['account_title'] 	= $UsersDetails['account_title'];
   			$bankInfoData['account_number'] = $UsersDetails['account_number'];
   		}
		
		$bankInfoData['bank_status'] 	= $UsersDetails['bank_status'];
		
   		#<!-------Bank Information:: Section ENd-------->
   		#<------------------------------------------->


		#<!-------Receipt Design:: Section Start-------->

   		   #<!---Submitter::Receipt Design Info Start--->

   		if($this->input->post('btn-receipt-info'))
   		{
   			$receiptInfoData = $this->input->post();

   			extract($receiptInfoData);
   			
   			$saveReceiptInfoData = array();
   			
   			$saveReceiptInfoData['receipt_header_text'] = $header_text;
   			$saveReceiptInfoData['receipt_footer_text'] = $footer_text;
   			$saveReceiptInfoData['receipt_bg_color']	= $bg_color;
   			$saveReceiptInfoData['receipt_text_color']  = $text_color;
   			$saveReceiptInfoData['updated'] 			= date("Y-m-d H:i:s");

   			$User_Store_Detail = $this->Profile->checkUserStoreDetails($userId);
   			if($User_Store_Detail)
   			{
   				$this->Profile->edit_user_store($storeId, $saveReceiptInfoData);
   				$this->session->set_flashdata('successMsgReceiptInfo','Receipt Design Information updated successfully');
   			}
   		}
   		else
   		{
   			$receiptInfoData['header_text'] = $userStoreDetails['receipt_header_text'];
   			$receiptInfoData['footer_text'] = $userStoreDetails['receipt_footer_text'];
   			$receiptInfoData['bg_color'] = $userStoreDetails['receipt_bg_color'];
   			$receiptInfoData['text_color'] = $userStoreDetails['receipt_text_color'];
   		}

   		$data['basicInfoData'] 		= $basicInfoData;
   		$data['businessInfoData'] 	= $businessInfoData;
   		$data['bankInfoData'] 		= $bankInfoData;
   		$data['receiptInfoData'] 	= $receiptInfoData;
   		$data['security_questions'] = $this->Profile->get_questions();

   		$content = $this->load->view('profile/index.php', $data, true);
        $this->load->view('main', array('content' => $content));
   	}
}

?>