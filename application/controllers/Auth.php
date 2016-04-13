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
	 }

	public function index()
	{
		if($this->session->userdata('logged_in'))
		{
			redirect(base_url()."index.php/admin/dashboard", 'refresh');
		}
		else
		{
            $this->load->view('login');
		}

	}

	function check_database()
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
		   	$sess_array['store_id'] = $store_id[0]['store_id'];
        	$this->session->set_userdata('logged_in', $sess_array);
		    redirect(base_url()."index.php/admin/dashboard");
	   }
	   else
	   {
			$this->load->view('login',array("error" =>"Invalid username or password"));
			$this->form_validation->set_message('check_database', 'Invalid username or password');
			return false;
	   }
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


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */