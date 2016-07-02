<?php
Class Settings extends CI_Controller
{
	function __construct()
	{
        parent::__construct();
        $this->load->model('user');
        $this->load->model('profile');
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
		$UsersDetails 		= $this->profile->get_user_detail($userId);
		$userStoreDetails 	= $this->profile->get_store_detail($storeId);


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
				$this->user->edit_user($userId,$saveBasicInfoData);
				
				
				$user_detail = $this->profile->checkUserDetails($userId);

				if($user_detail)
				{
					$securityInfoData['updated'] = date("Y-m-d H:i:s");
					$this->profile->edit_user_detail($userId,$securityInfoData);
				}

				else
				{
					$securityInfoData['created'] = date("Y-m-d H:i:s");
					$securityInfoData['user_id'] = $userId;
					$securityInfoData['status'] = CONST_STATUS_ID_ACTIVE;
					$this->profile->add_user_detail($securityInfoData);
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

   				$User_Store_Detail = $this->profile->checkUserStoreDetails($userId);
   			
   				if($User_Store_Detail)
   				{
   					$this->profile->edit_user_store($storeId, $saveBusinessInfoData);
   					$this->session->set_flashdata('successMsgBusinessInfo','Business Information updated successfully');
   				}

   			}
   		} #<!---Submitter::Business Info End--->
   		else
   		{
   			$businessInfoData['business'] 		= $userStoreDetails['name'];
   			
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

				$bankInfo = $this->profile->checkUserBankDetails($userId);

				if($bankInfo)
				{
					$bank_id = $bankInfo['bank_id'];
			
					$saveBankInfoData['updated'] = date("Y-m-d H:i:s");

					$this->profile->edit_user_bank($bank_id,$saveBankInfoData);
				}
				else
				{
					$saveBankInfoData['created'] = date("Y-m-d H:i:s");
					$saveBankInfoData['user_id'] = $userId;
					$saveBankInfoData['status']  = CONST_BANK_STATUS_NOT_VERIFIED;
					$this->profile->add_user_bank($saveBankInfoData);
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
   			
            if(!$test_email)
            {
               $aErrorMessage[] = "Test Email Address Is Required";
            }

            if($test_email)
            {
               if (!filter_var($test_email, FILTER_VALIDATE_EMAIL))
               {
                  $aErrorMessage[] = "Please provide valid email address"; 
               }
            }

            if(is_array($aErrorMessage) && count($aErrorMessage))
            {
               $showErrorMessage = getFormValidationErrorMessage($aErrorMessage);
               $this->session->set_flashdata('errMsgReceiptInfo',$showErrorMessage);
            }
            else
            {

               $isImageUpload = uploadImage(CONST_IMAGE_UPLOAD_DIR);

               $file_path = false;
              
               if($isImageUpload)
               {
                  if(isset($isImageUpload['Error']))
                  {
                     $aErrorMessage[] = $isImageUpload['Error'];
                  }
                  else
                  {
                     $file_path = $isImageUpload['file_path'];
                      
                     if($file_path)
                     {   
                        $User_Store_Detail = $this->profile->checkUserStoreDetails($userId);
                     
                        if($User_Store_Detail)
                        {  
                           $old_image = $User_Store_Detail['logo'];

                           $this->profile->edit_user_store($storeId, array('logo'=>$file_path));
                           $receiptInfoData['old_image'] = $file_path;
                        }
                     }
                     
                     $receiptInfoData['tempLogo'] = "";
                     
                     if($old_image)
                     {   
                        $old_image = str_replace(base_url(),'', $old_image);
                        @unlink($old_image);
                     }
                  }            
               }
               else
               {
                  if($tempLogo)
                  {  
                     $tempLogo = str_replace(base_url(),'', $tempLogo);

                     $fileName = str_replace(CONST_IMAGE_UPLOAD_TEMP_ORDER_DIR, '', $tempLogo);
                     
                     rename($tempLogo, CONST_IMAGE_UPLOAD_DIR.$fileName);
                     
                     $logo = base_url().CONST_IMAGE_UPLOAD_DIR.$fileName;
                     $receiptInfoData['old_image'] = $logo;
                     
                     $this->profile->edit_user_store($storeId, array('logo'=>$logo));
                     
                     if($old_image)
                     {  
                        $old_image = str_replace(base_url(),'', $old_image);
                        @unlink($old_image);
                     }

                   
                     $receiptInfoData['tempLogo'] = "";
                  }
               }
            }
            //if image upload error
			   if(is_array($aErrorMessage) && count($aErrorMessage))
            {
               $showErrorMessage = getFormValidationErrorMessage($aErrorMessage);
               $this->session->set_flashdata('errMsgReceiptInfo',$showErrorMessage);
            }
            else
            {  

               $saveReceiptInfoData['receipt_header_text'] = $header_text;
               $saveReceiptInfoData['receipt_footer_text'] = $footer_text;
               $saveReceiptInfoData['receipt_bg_color']  = $bg_color;
               $saveReceiptInfoData['receipt_text_color']  = $text_color;
               $saveReceiptInfoData['test_email']        = $test_email;
               $saveReceiptInfoData['updated']        = date("Y-m-d H:i:s");

               $User_Store_Detail = $this->profile->checkUserStoreDetails($userId);
               if($User_Store_Detail)
               {
                  $this->profile->edit_user_store($storeId, $saveReceiptInfoData);
                  $this->session->set_flashdata('successMsgReceiptInfo','Receipt Design Information updated successfully');
               }
            }
		   }
   		elseif($this->input->post('btn-send-test-reciept'))
      	{ 
      		$receiptInfoData = $this->input->post();

      		extract($receiptInfoData);
   			
            $_storeDetails		= $this->profile->checkUserStoreDetails($userId);
   			$_userDetails		= $this->profile->get_user_detail($userId);

            if($bg_color=='')
            {
               $bg_color = "#FFFFFF";
            }
            if($text_color=='')
            {
               $text_color = "#000000";
            }
            if(!$test_email)
            {
               $aErrorMessage[] = "Test Email Address Is Required";
            }

            if($test_email)
            {
               if (!filter_var($test_email, FILTER_VALIDATE_EMAIL))
               {
                  $aErrorMessage[] = "Please provide valid email address"; 
               }
            }

            $isImageUpload = uploadImage(CONST_IMAGE_UPLOAD_TEMP_ORDER_DIR);

            $file_path = $_storeDetails['logo']; //-->false;
           
            if($isImageUpload)
            {
               if(isset($isImageUpload['Error']))
               {
                  $aErrorMessage[] = $isImageUpload['Error'];
               }
               else
               {
                  $file_path = $isImageUpload['file_path'];
                   
                  if($file_path)
                  {   
                      $receiptInfoData['tempLogo'] = $file_path;
                  }
               }            
            }
            else
            {
               if($tempLogo)
               {
                  $file_path = $tempLogo;
               }
            }

            //if image upload error has occured
            if(is_array($aErrorMessage) && count($aErrorMessage))
            {
               $showErrorMessage = getFormValidationErrorMessage($aErrorMessage);
               $this->session->set_flashdata('errMsgReceiptInfo',$showErrorMessage);
            }
            else
            {
               //_storeDetails
               $_storeDetails['receipt_header_text']  = $header_text; 
               $_storeDetails['receipt_footer_text']  = $footer_text;
               $_storeDetails['receipt_bg_color']     = $bg_color;
               $_storeDetails['receipt_text_color']   = $text_color;
               $_storeDetails['logo']  				  = @$file_path;
               
               //paymentTransaction
               $paymentTransaction = array();
               $paymentTransaction['cx_descriptor']   = 'some text';
               $paymentTransaction['transaction_id']  = 2001;
               $paymentTransaction['amount_cash']     = 0;
               $paymentTransaction['amount_cc']    = 156;
               $paymentTransaction['cc_number']    = '4111111111111111';
               $paymentTransaction['cc_name']         = 'Umair Khan';
               
               //orderInfo
               $orderInfo = array();
               $orderInfo['order_id']              = rand();
               $orderInfo['total_amount']          = 156;
               $orderInfo['customer_signature']    = '';
               $orderInfo['customer_email']        = $test_email;
               $orderInfo['created']               = date('Y-m-d H:i:s');
               
               $_data = array();
               $_data['storeDetails']        = $_storeDetails;
               $_data['orderInfo']           = $orderInfo;
               $_data['paymentTransaction']  = $paymentTransaction;
               $_data['userDetails']         = $_userDetails;
               
               $recieptUrl = _createRecieptPDF($_data);
               
               if($recieptUrl)
               {  

                  _sendRecieptEmail($_data, $recieptUrl);
                  $this->session->set_flashdata('successMsgReceiptInfo','Email Receipt has send successfully to '.$test_email);
               }
               else
               {
                  $this->session->set_flashdata('errMsgReceiptInfo','Something wnt wrong please try again');
               }
            }
           
   			/*
   			$header_text
      			$footer_text
   			$bg_color
   			$text_color			
   			$test_email
   			*/
      	}
      	else
   		{
            $receiptInfoData['old_image']   = $userStoreDetails['logo'];
   			$receiptInfoData['header_text'] = $userStoreDetails['receipt_header_text'];
   			$receiptInfoData['footer_text'] = $userStoreDetails['receipt_footer_text'];
   			$receiptInfoData['bg_color']    = $userStoreDetails['receipt_bg_color'];
            $receiptInfoData['text_color']  = $userStoreDetails['receipt_text_color'];
   			$receiptInfoData['test_email']  = $userStoreDetails['test_email'];
            $receiptInfoData['tempLogo']    = '';
   		}
		#<!-------Receipt Design:: Section END-------->

		$data['basicInfoData'] 		= $basicInfoData;
		$data['businessInfoData'] 	= $businessInfoData;
		$data['bankInfoData'] 		= $bankInfoData;
		$data['receiptInfoData'] 	= $receiptInfoData;
		$data['security_questions'] = $this->profile->get_questions();

		$content = $this->load->view('profile/index.php', $data, true);
     $this->load->view('main', array('content' => $content));
	}
}

?>