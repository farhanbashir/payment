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
    
    function __construct() {
        parent::__construct();
        $this->load->model('user', '', TRUE);
        
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
    }

    public function index() 
    {  
        $data = array();
        $content = $this->load->view('users/user_listing.php', $data, true);
        $this->load->view('main', array('content' => $content));
    }

    function merchantsListing()
    {      
        $_getParams = $_GET;
        $params     = _processDataTableRequest($_getParams);
        $draw       = $params['draw'];

        $users_list = $this->user->getUsers($params);
        
        $recordsFiltered = $this->user->getUsersCount($params); 
        $recordsTotal = $this->user->getUsersCountWithFilter();

        $usersData = array();

        if(is_array($users_list) && count($users_list) > 0)
        {
            foreach ($users_list as $row) 
            {
                $tempArray   = array();

                $tempArray[] = $row['user_id'];
                $tempArray[] = $row['first_name'];
                $tempArray[] = $row['last_name'];
                $tempArray[] = $row['email'];
                $tempArray[] = date("F, d, Y",strtotime($row['created']));
                $tempArray[] = '<p><span class="label label-success">Active</span></p>';
                $tempArray[] = '<p><a href="Javascript: void();" class="btn btn-primary ">Edit</a>
                                <a href="Javascript: void();" class="btn btn-danger">Delete</a>
                                <br><br>
                                <a href="'.site_url("admin/users/login_merchant/".$row["user_id"]).'" class="btn btn-warning">Log-In as this Merchant</a>
                            </p>';

                $usersData[] = $tempArray;
            }
        }

        $data = array(
            "draw"            =>isset ( $draw ) ? intval( $draw ) : 0,
            "recordsTotal"    => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data"            => $usersData
        );
     
        echo json_encode($data);
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
