<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

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
	   $this->load->model('profile','',TRUE);
	 }

	public function index()
	{
		if($this->session->userdata('logged_in'))
		{
			redirect(site_url('admin/dashboard'), 'refresh');
		}
		else
		{
			$data = array();
			$data['error'] = '';
			$data['username'] = '';
			
			$content = $this->load->view('login', $data, true);
			$this->load->view('main-front', array('content' => $content));
		}
	}

	function login()
	{
	   //Field validation succeeded.  Validate against database
	   $username = $this->input->post('username');
	   $password = $this->input->post('password');

	   //query the database
	   $result = $this->user->login($username, $password);

       //temporary work for admin
	   //if($username == "admin@club.com" && $password == "clubadmin")
	   if(is_array($result))
	   {
		    $sess_array = array();
		    $sess_array = (array) $result[0];
		   	$store_id = $this->user->get_user_store_id($sess_array['user_id']);
		   	$sess_array['store_id'] = @$store_id[0]['store_id'];
        	$this->session->set_userdata('logged_in', $sess_array);
        	$roleId = getLoggedInRoleId();
        	
        	if($roleId==CONST_ROLE_ID_SUPER_ADMIN)
        	{
        		redirect("admin/users",'refresh');
        	}

        	else
        	{
		    	redirect("admin/dashboard",'refresh');
		    }
	   }
	   else
	   {
			$data = array();
			$data['error'] = 'Invalid username or password';
			$data['username'] = $username;
			
			$content = $this->load->view('login', $data, true);
			$this->load->view('main-front', array('content' => $content));
			
			$this->form_validation->set_message('check_database', 'Invalid username or password');
			return false;
		}
	}

	function register()
	{
		$data = array();
        $postedData = array();
		
        $aErrorMessage = array();
		$error="";

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
            $status         = 1;

            if(!$first_name)
            {
                $aErrorMessage[] = "First name required";
            }

            if(!$last_name)
            {
                $aErrorMessage[] = "last name required";
            }

            if(!$email)
            {
                $aErrorMessage[] = "email required";
            }

            if(!$password)
            {
                $aErrorMessage[] = "Password required";
            }

            if($email)
            {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    $aErrorMessage[] = "Please provide valid email address"; 
                }
            }

            $already_present = $this->user->checkUser($email);
            if($already_present !== false)
            {
                $aErrorMessage[] = "User already present with this email";
            }

            if(is_array($aErrorMessage) && count($aErrorMessage))
            {
                $error = getFormValidationErrorMessage($aErrorMessage);

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
                            $error = getFormValidationErrorMessage($apiResponse['error']);
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
                        }
                        
                        $this->session->set_flashdata('Message','Signup Successfull. Please login to continue');
                        redirect(base_url(),'refresh');
                    }
                    else
                    {
                    	$error = "Something went wrong. Please try again!";
                    }
                }
            }
        }

        $data['error'] = $error;
        $data['postedData'] = $postedData;
		
		$content = $this->load->view('register', $data, true);
		$this->load->view('main-front', array('content' => $content));
	}
    public function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url());
    }

    public function forgot_password()
    {
    	$this->load->view('forgot_password');
    }

    function deactive_user($userId = 0)
    {   
        if(!intval($userId))
        {
            redirect(base_url(),'refresh');
        }

        $roll_id = $this->session->userdata('logged_in')['role_id'];

        if($roll_id==CONST_ROLE_ID_SUPER_ADMIN)
        {
            $this->user->deactiveUser($userId);
            $this->session->set_userdata('logged_in_merchant','');
            $this->session->set_flashdata('Message','Merchant successfully deactive');
            redirect('admin/users','refresh');
        }

        elseif($roll_id==CONST_ROLE_ID_BUSINESS_ADMIN)
        {
            if($userId==getLoggedInUserId())
            {
                $this->user->deactiveUser($userId);
                $this->session->set_flashdata('Message','Your account successfully deactive');
                $this->session->set_userdata('logged_in','');
                redirect(base_url(),'refresh');
            }
        }
        else
        {
            redirect(base_url());
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */