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
        
        if (!$this->session->userdata('logged_in'))
		{
            redirect(base_url());
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
                $tempArray   = array();
				
				$user_id = $row['user_id'];
				
				$tplLoginButton = '<a href="'.site_url("admin/users/login_merchant/".$user_id).'" class="btn btn-warning">Log-In as this Merchant</a>';
				if(getLoggedInUserId() == $user_id)
				{
					$tplLoginButton = '<span class="btn btn-primary">Logged-In as this Merchant</span>';
				}	

                $tempArray[] = $user_id;
                $tempArray[] = $row['first_name'];
                $tempArray[] = $row['last_name'];
                $tempArray[] = $row['email'];
                $tempArray[] = date(CONST_DATE_TIME_DISPLAY, strtotime($row['created']));
                $tempArray[] = '<span class="label label-success">Active</span>';
                $tempArray[] = $tplLoginButton;

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

        $filterStatus = $_GET['filter_status'];
        
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
                    $status ='<span class="label label-warning">Not Verified</span>';
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
                $tempArray[] = $row['bank_name']."<br />".$row['account_title']."<br />".$row['account_number']."<br />".$row['swift_code'];
                $tempArray[] = $lastChecked;
                $tempArray[] = $status;

                $MerchantBankStatusData[] = $tempArray;
            }
        }

        $data = array(
            "draw"            =>isset ( $draw ) ? intval( $draw ) : 0,
            "recordsTotal"    => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data"            => $MerchantBankStatusData
        );
     
        echo json_encode($data);
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
