<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
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

        $this->load->model('user', '', TRUE);
        $this->load->model('profile', '', TRUE);
		$this->load->model('category','',TRUE);

        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
        
        global $allowOnlyForSuperAdmin;

        if($this->session->userdata('logged_in')['role_id']!=CONST_ROLE_ID_SUPER_ADMIN) 
        {
            if(in_array($this->uri->uri_string(), $allowOnlyForSuperAdmin))
            {   
                redirect(base_url());
            }
        }
    }

    public function index() 
    {
        $data = array();
        $content = $this->load->view('users/user_listing.php', $data, true);
        $this->load->view('main', array('content' => $content));
    }

    function ajaxMerchantsListing()
    {
        $_getParams = $_GET;
        $params     = _processDataTableRequest($_getParams);
        $draw       = $params['draw'];

        $users_list = $this->user->getUsers($params);
        
        $recordsFiltered = $this->user->getUsersCount($params); 
        $recordsTotal = $this->user->getUsersCountWithoutFilter();

        $usersData = array();

        if(is_array($users_list) && count($users_list) > 0)
        {
            foreach ($users_list as $row) 
            {  
                $userId = $row['user_id'];

                $deactiveLink = site_url('auth/deactive_user/'.$userId);
                $tplAction  = <<<EOT

                    <a onclick="return confirm('Are you sure, you want to delete Merchant account? It can not be reverted. So, please make sure before proceed','$deactiveLink')" href="$deactiveLink"
                        <button class="btn btn-danger btn-cons">De-Activate Now</button>
                    </a>
EOT;

				$loggedInMerchantId = getLoggedInUserId();
				
				$tplLoginAs  = '<a href="'.site_url("admin/users/login_merchant/".$userId).'" class="btn btn-warning">Log-In as this Merchant</a>';
				
				if($loggedInMerchantId == $userId)
				{
					$tplLoginAs  = '<span class="btn btn-primary">Already Logged-In</span>';
				}
				
                $tempArray   = array();

                $tempArray[] = $row['user_id'];
                $tempArray[] = $row['first_name'];
                $tempArray[] = $row['last_name'];
                $tempArray[] = $row['email'];
                $tempArray[] = date(CONST_DATE_TIME_DISPLAY,strtotime($row['created']));
                $tempArray[] = $tplAction;
                $tempArray[] = $tplLoginAs;

                $usersData[] = $tempArray;
            }
        }

        $data = array(
            "draw"            => isset ( $draw ) ? intval( $draw ) : 0,
            "recordsTotal"    => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data"            => $usersData
            );

        echo json_encode($data);
    }

    function bankstatus()
    {
        $data = array();
        $content = $this->load->view('users/bank_status_listing.php', $data, true);
        $this->load->view('main', array('content' => $content));
    }

    function ajaxMerchantBankStatus()
    {
        $_getParams = $_GET;

        $filterStatus = $_getParams['filter_status'];

        $params     = _processDataTableRequest($_getParams);
        $draw       = $params['draw'];
        
        $params['filter_status'] = $filterStatus;

        $MerchantBankStatus_list = $this->user->getMerchantBankStatus($params);
        
        $recordsFiltered = $this->user->getMerchantBankStatusCount($params); 
        $recordsTotal = $this->user->getMerchantBankStatusCountWithoutFilter();

        $MerchantBankStatusData = array();

        if(is_array($MerchantBankStatus_list) && count($MerchantBankStatus_list) > 0)
        {
            foreach ($MerchantBankStatus_list as $row) 
            {
                $tempArray   = array();

                $status ='<span class="label label-danger">No Bank Details</span>';
                
                if($row['status'] == CONST_BANK_STATUS_VERIFIED)
                {
                    $status ='<span class="label label-success">Verified</span>';
                }
                
                else if($row['status'] == CONST_BANK_STATUS_NOT_VERIFIED)
                {
                    $status ='<div id="'.$row['user_id'].'"><span class="label label-warning">Not Verified</span> <button onclick="return checkBankStatus(this)" value="'.$row['user_id'].'" class="btn btn-complete btn-cons">Check Status</button></div>';
                }       
                
                $lastChecked = '-';

                if($row['updated'])
                {
                    $lastChecked = date(CONST_DATE_TIME_DISPLAY, strtotime($row['updated']));
                }
                else if($row['created'])
                {
                    $lastChecked = date(CONST_DATE_TIME_DISPLAY, strtotime($row['created']));
                }          

                $tempArray[] = $row['user_id'];
                $tempArray[] = $row['name'];
                $tempArray[] = $row['email'];
                //-->$tempArray[] = $row['bank_name']."<br>".$row['account_title']."<br>".$row['account_number']."<br>".$row['swift_code'];
                $tempArray[] = $lastChecked;
                $tempArray[] = $status;

                $MerchantBankStatusData[] = $tempArray;
            }
        }

        $data = array(
            "draw"            => isset ( $draw ) ? intval( $draw ) : 0,
            "recordsTotal"    => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data"            => $MerchantBankStatusData
            );

        echo json_encode($data);
    }

    function check_bank_status()
    {
        $userId = $this->input->post('userId');

        $bankStatusInfo   = $this->getBankAccountStatus_post($userId);

        if($bankStatusInfo == CONST_BANK_STATUS_VERIFIED)
        {
            $bankStatusInfo ='<span class="label label-success">Verified</span>';
        }

        else if($bankStatusInfo == CONST_BANK_STATUS_NOT_VERIFIED)
        {
            $bankStatusInfo ='<span class="label label-warning">Not Verified</span> <button onclick="return checkBankStatus(this)" value="'.$userId.'"class="btn btn-complete btn-cons"style="width:60px;height:31px;">Check Status</button>';
        }

        else
        {
            $bankStatusInfo = getFormValidationErrorMessage($bankStatusInfo);
        } 
        

        echo $bankStatusInfo;
    }

    function getBankAccountStatus_post($userId=0)
    {   


        $updated = date('Y-m-d H:i:s');

        $bankInfo = $this->profile->checkUserBankDetails($userId);
        
        $currentBankStatus = "";

        if($bankInfo)
        {   
            $bank_id = $bankInfo['bank_id'];

            $postParams = array();
            
            $apiStatus = false;
            $apiData = array();
            $apiResponse = getMerchantBankAccountStatus($userId, $postParams);
            
           
            if($apiResponse)
            {   

                if(isset($apiResponse['error']))
                {
                    $currentBankStatus         = $apiResponse['error'];
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
                    
                    $currentBankStatus = CONST_BANK_STATUS_VERIFIED;
                }
                else  //not-verified!
                {
                    $this->profile->edit_user_bank($bank_id, array("updated"=>$updated,"status"=>CONST_BANK_STATUS_NOT_VERIFIED));
                    
                    $currentBankStatus = CONST_BANK_STATUS_NOT_VERIFIED;
                }
            }
            else
            {
                $currentBankStatus = "Something went wrong. Please try later!";
            }           
        }
        else
        {
            $currentBankStatus = "No bank linked with this user!";
        }

        return $currentBankStatus;       
    }

    public function accounts() {
        $data = array();
        // $data['total_users'] = $this->user->get_total_users();
        // $data['total_stores'] = $this->store->get_total_stores();
        // $data['latest_five_users'] = $this->user->get_latest_five_users();
        // $data['latest_five_stores'] = $this->store->get_latest_five_stores();

        $content = $this->load->view('profile/index.php', $data, true);
        $this->load->view('main', array('content' => $content));
    }

    function bank_status_cron()
    {   
        $params = array();

        $params['filter_status'] = CONST_BANK_STATUS_NOT_VERIFIED;

        $params['queryForCrone'] = true;
        
        $MerchantBankStatus_list = $this->user->getMerchantBankStatus($params);
        
        $verifiedBankStatus     = 0;
        $notVerifiedBankStatus  = 0;
        foreach ($MerchantBankStatus_list as $row) 
        {
            $bankStatusInfo = $this->getBankAccountStatus_post($row['user_id']);

            if($bankStatusInfo==CONST_BANK_STATUS_VERIFIED)
            {
                $verifiedBankStatus = $verifiedBankStatus+1;
            }
            else
            {
                $notVerifiedBankStatus = $notVerifiedBankStatus+1;
            }
        }
        
        $this->load->library('email');
        $this->email->set_mailtype("html");
        $this->email->from("icannpay@info.com");
        $this->email->to(EMAIL_ADDRESS_TO_SEND_CRON_UPDATES);
        $this->email->subject("Merchant Bank Status Information");
        $message = "Crone successfully run. Following are the list of verified and non verified Merchants";
        $message .= 'Verified Merchants : '.$verifiedBankStatus;
        $message .= 'Non-Verified Merchants : '.$notVerifiedBankStatus;
        $message .=   '<br><br>' .
        'Thanks,<br>'.
        'ICannPay';
        $this->email->message($message);
        $this->email->send();

        $this->user->editCronStatus();
    }
    
    function save()
    {

        $data = array();
        $postedData = array();
        $aErrorMessage = array();
        $showErrorMessage = "";

        if($this->input->post('btn-submit'))
        {
            $postedData = $this->input->post();

            extract($postedData);
            $first_name = htmlentities($first_name);
            $last_name  = htmlentities($last_name);
            $email      = htmlentities($email);
            $password   = htmlentities($password);
            $parent_user_id = 0; 
            $role_id        = CONST_ROLE_ID_BUSINESS_ADMIN;
            $created        = date('Y-m-d H:i:s');
			$updated        = date('Y-m-d H:i:s');
            $status         = 1;

            if(!$first_name)
            {
                $aErrorMessage[] = "First name required";
            }

            if(!$last_name)
            {
                $aErrorMessage[] = "Last name required";
            }

            if(!$email)
            {
                $aErrorMessage[] = "Email required";
            }
			
			if($email)
            {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    $aErrorMessage[] = "Please provide valid email address"; 
                }
            }

            if(!$password)
            {
                $aErrorMessage[] = "Password is required";
            }
            

            $already_present = $this->user->checkUser($email);
            if($already_present !== false)
            {
                $aErrorMessage[] = "User already present with this email";
            }

            if(is_array($aErrorMessage) && count($aErrorMessage))
            {
                $showErrorMessage = getFormValidationErrorMessage($aErrorMessage);
                $showErrorMessage = $this->session->set_flashdata('showErrorMessage',$showErrorMessage);
            }
            else
            {
                $apiStatus = true;

                $apiData = array();
                
                if($role_id == CONST_ROLE_ID_BUSINESS_ADMIN)
                {
                    $postParams = array();
                    $postParams['email']                = $email;
                    $postParams['password']             = $password;
                    $postParams['first_name']           = $first_name;
                    $postParams['last_name']            = $last_name;
                    
                    $apiStatus = false;
                    $apiData = array();
                    $apiResponse = merchantSignup($postParams);
                    
                    if($apiResponse)
                    {   
                        if(isset($apiResponse['error']))
                        {
                            $showErrorMessage = getFormValidationErrorMessage($apiResponse['error']);
                            $showErrorMessage = $this->session->set_flashdata('showErrorMessage',$showErrorMessage);
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
                    $user = array("first_name"=>$first_name,"last_name"=>$last_name,"parent_user_id"=>$parent_user_id,"email"=>$email,"password"=>md5($password),"plain_password"=>$password,"status"=>$status,"role_id"=>$role_id,"created"=>$created);
                    
                    $user_id = $this->user->add_user($user);
                    
                    if($user_id)
                    {
                        if($role_id == CONST_ROLE_ID_BUSINESS_ADMIN) //business admin
                        {
                            //if user role is business admin then create empty store
                            $store_id = $this->profile->add_user_store(array("user_id"=>$user_id));

                            //insert into merchant info
                            $merchant_info = array();
                            $merchant_info['user_id']                   = $user_id;
                            $merchant_info['email']                     = $email;
                            $merchant_info['password']                  = $password;
                            $merchant_info['cx_authenticate_id']        = @$apiData['authenticate_id'];
                            $merchant_info['cx_authenticate_password']  = @$apiData['authenticate_password'];
                            $merchant_info['cx_secret_key']             = @$apiData['secret_key'];
                            $merchant_info['cx_hash']                   = @$apiData['hash'];
                            $merchant_info['cx_mode']                   = @$apiData['mode'];
                            $merchant_info['last_updated']              = $created;
                            
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
                        
                        $this->session->set_flashdata('Message','Signup successfull');
                        redirect('admin/users','refresh');
                    }
                    else
                    {

                    }
                }
            }
        }

        $data['postedData'] = $postedData;
        $content = $this->load->view('users/merchant_form', $data, true);
        $this->load->view('main', array('content' => $content));
    }

    public function view($id) {
        $user = $this->user->get_user_detail($id);
        $data['user'] = $user;
        
        $content = $this->load->view('users/view.php', $data, true);
        $this->load->view('welcome_message', array('content' => $content));
    }

    public function edit($id) {
        $user = $this->user->get_user_detail($id);
        $data['user'] = $user;
        $content = $this->load->view('users/edit.php', $data, true);
        $this->load->view('welcome_message', array('content' => $content));
    }

    public function update() 
    {
        $username = $this->input->post('username');
        $new_password = $this->input->post('password');
        $email = $username;
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $user_id = $this->input->post('user_id');
        $data = array("username"=>$username,"first_name"=>$first_name,"last_name"=>$last_name,"email"=>$email);
        if($new_password !== '')
        {
            $data['new_password'] = md5($new_password);
        }    
        $temp_image_url = "";
        $temp_image_url = uploadFile($this->config->item('user_image_base'), asset_url('img/users'));

        if($temp_image_url !== "")
        {
            $data['image'] = $temp_image_url;
        }

        $this->user->edit_user($user_id, $data);
        
        redirect(site_url('admin/users/edit/' . $user_id));
    }

    public function addnew() {
        $content = $this->load->view('users/new.php', $data = NULL, true);
        $this->load->view('welcome_message', array('content' => $content));
    }

    public function submit() {

        $username = $this->input->post('username');
        $email = $username;
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $password = $this->input->post('password');
        $confirm_password = $this->input->post('confirm_password');
        $data = array("username"=>$username,"password"=>md5($password),"first_name"=>$first_name,"last_name"=>$last_name,"email"=>$email);
        
        $temp_image_url = "";
        $temp_image_url = uploadFile($this->config->item('user_image_base'), asset_url('img/users'));

        if($temp_image_url !== "")
        {
            $data['image'] = $temp_image_url;
        }

        $user_id = $this->user->add_user($data);
        
        redirect(site_url('admin/users/view/' . $user_id));
    }

    public function delete($id, $status, $view = NULL) {
        $flag = $this->user->edit_user($id, array("is_active"=>$status));
//        $this->image->delete_content_images($id);
//        if (empty($view)) {
        redirect(site_url('admin/users/index'));
//        } else {
//            redirect(site_url('admin/' . $this->type . '/view/' . $id));
//        }
    }

    public function confirm_delete($user_id)
    {
        $this->user->delete_user($user_id);
        redirect(site_url('admin/users/index'));
    }

    public function login_merchant($user_id=0)
    {
		  if($user_id)
		  {
			 $userInfo = $this->user->checkUserById($user_id);

			 if($userInfo)
			 {
				$userInfo = @$userInfo[0];

				if($userInfo)
				{
				   $role_id = $userInfo->role_id;

				   if($role_id == CONST_ROLE_ID_BUSINESS_ADMIN)
				   {
					  $userInfo = (array) $userInfo;

					  $this->session->set_userdata('logged_in_merchant', $userInfo);

					  redirect(site_url('admin/dashboard'));
				  }
			  }
		  }
	  }

	  redirect(site_url('admin/users/index'));	
	}
}
