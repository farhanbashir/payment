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

    public function index() {
       
        $data = array();
        
		/*
		$this->load->library("pagination");
        $total_rows = $this->user->get_total_users();

        $pagination_config = get_pagination_config('users/index', $total_rows, $this->config->item('pagination_limit'), 4);

        $this->pagination->initialize($pagination_config);

        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;


        $data["links"] = $this->pagination->create_links();
		*/

        $users = $this->user->get_all_users(); //-->$this->user->get_users($page);

        $data['users'] = $users;
        //$this->load->view('header');
        /* echo "<pre>";
        print_r($data['users']);die;*/
        $content = $this->load->view('users/tabular.php', $data, true);

        $this->load->view('main', array('content' => $content));
    }

    function test()
    {   
        $data = array();
        $content = $this->load->view('users/AjaxDataTable.php', $data, true);
        $this->load->view('main', array('content' => $content));
    }

    function AjaxDataTable()
    {   
        $order ='';
        $limit = '';
        $where = '';
        $col = array('first_name','last_name','email');
        
        if ( isset($_GET['order']) && count($_GET['order']) )
        {   
            $orderByCol = $_GET['order'][0]['column'];
            $dir = $_GET['order'][0]['dir'];
            if($orderByCol <= count($col)-1)
            {
                $order = "ORDER BY ".$col[$orderByCol]." ".$dir;
            }
        }
       
        if ( isset($_GET['start']) && $_GET['length'] != -1 ) 
        {
            $limit = "LIMIT ".intval($_GET['start']).", ".intval($_GET['length']);
        }

        if ( isset($_GET['search']) && $_GET['search']['value'] != '' )
        {
            $str = $_GET['search']['value'];
            $where = "WHERE first_name LIKE '%".$str."%' OR last_name LIKE '%".$str."%' OR email LIKE '%".$str."%'";
        }


        $users_list = $this->user->test_ajax($where, $order, $limit);
        $total_rows = $this->user->test_ajax_count($where);

        $usersData = array();

        foreach ($users_list as $row) 
        {
            $tempArray   = array();

            $tempArray[] = $row['first_name'];
            $tempArray[] = $row['last_name'];
            $tempArray[] = $row['email'];
            $tempArray[] = '<p><span class="label label-success">Active</span></p>';
            $tempArray[] = '<p><a href="Javascript: void();" class="btn btn-primary ">Edit</a>
                            <a href="Javascript: void();" class="btn btn-danger">Delete</a>
                            <br><br>
                            <a href="'.site_url("admin/users/login_merchant/".$row["user_id"]).'" class="btn btn-warning">Log-In as this Merchant</a>
                        </p>';
            $usersData[] = $tempArray;
        }

        $data = array(
            "draw"            =>isset ( $_GET['draw'] ) ? intval( $_GET['draw'] ) : 0,
            "recordsTotal"    => $total_rows,
            "recordsFiltered" => $total_rows,
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
