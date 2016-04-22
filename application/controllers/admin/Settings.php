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
   		
   		$data = array();
   		$user_id 	= 	getLoggedInUserId();
   		$store_id 	=	getLoggedInStoreId(); 
   		$data['basic_info'] = $this->Profile->get_user_detail($user_id);
   		$data['security_questions'] = $this->Profile->get_questions();
   		$data['basic_info_form_url'] = site_url('admin/settings/update_basic_info');
   		$data['business_info'] = $this->Profile->get_store_detail($store_id);
   		$data['business_info_form_url'] =  site_url('admin/settings/update_business_info');
   		$data['bank_info_form_url'] =  site_url('admin/settings/update_bank_info');
   		$data['receipt_designer_form_url'] =  site_url('admin/settings/update_receipt_designer');
   		$content = $this->load->view('profile/index.php', $data, true);
        $this->load->view('main', array('content' => $content));
   	}

   	function update_basic_info()
   	{
   		$user_id 	= 	getLoggedInUserId();
		$store_id 	=	getLoggedInStoreId(); 
		$ArrFormValues = array(
			
				'first_name'		=>	htmlentities($this->input->post('first_name')),  		
				'last_name' 		=>  htmlentities($this->input->post('last_name')),   		
				'email' 			=> 	htmlentities($this->input->post('email')),   		
				'password' 			=> 	htmlentities($this->input->post('password')),  		
				'confirm_password' 	=> 	htmlentities($this->input->post('confirm_password')),
				'security_question_id' => 	htmlentities($this->input->post('security_question')),   		
				'security_answer' 	=> 	htmlentities($this->input->post('security_answer')),
		);
		if(!$ArrFormValues['first_name'] || !$ArrFormValues['last_name'] || !$ArrFormValues['email'] || !$ArrFormValues['security_question_id'] || !$ArrFormValues['security_answer'])
		{
			redirect(base_url(),'refresh');
		}
		$data = array();
		$ArrSeurityInfo = array(
			'security_question_id' 	=> $ArrFormValues['security_question_id'],
			'security_answer'		=> $ArrFormValues['security_answer'],
		);
		if($ArrFormValues['password']!='')
		{
			if($ArrFormValues['password']!=$ArrFormValues['confirm_password'])
			{	
				$ErrorMessage = "Passwords does not match";
				/**/
			}
			//if($ArrFormValues[0]['password']==$ArrFormValues[0]['confirm_password'])
			if($ArrFormValues['password'] == $ArrFormValues['confirm_password'])
			{
				$data['first_name'] = $ArrFormValues['first_name'];
				$data['last_name'] = $ArrFormValues['last_name'];
				$data['email'] = $ArrFormValues['email'];
				$data['password'] = md5($ArrFormValues['password']);
				$data['new_password'] = md5($ArrFormValues['confirm_password']);
				$data['updated'] = date("Y-m-d H:i:s");
			}
		}
		else
		{
			$data['first_name'] = $ArrFormValues['first_name'];
			$data['last_name'] 	= $ArrFormValues['last_name'];
			$data['email']		= $ArrFormValues['email'];

			//$data['updated'] 	= date("Y-m-d H:i:s");
		}

		if(isset($ErrorMessage) && $ErrorMessage!='')
		{		
				$this->session->set_flashdata('ErrorMessageTab1','Passwords does not match');
		   		$data['basic_info'] = $ArrFormValues;
		   		$data['security_questions'] = $this->Profile->get_questions();
		   		$data['basic_info_form_url'] = site_url('admin/settings/update_basic_info');
		   		$data['business_info'] = $this->Profile->get_store_detail($store_id);
		   		$data['business_info_form_url'] =  site_url('admin/settings/update_business_info');
		   		$data['bank_info_form_url'] =  site_url('admin/settings/update_bank_info');
		   		$data['receipt_designer_form_url'] =  site_url('admin/settings/update_receipt_designer');
		   		$content = $this->load->view('profile/index.php', $data, true);
	        	$this->load->view('main', array('content' => $content));
		}

		else
		{
			$this->User->edit_user($user_id,$data);

			$user_detail = $this->Profile->checkUserDetails($user_id);

			if(!$user_detail)
			{	
				$data['created'] 	= date("Y-m-d H:i:s");

				$data['status']		= CONST_BANK_STATUS_NOT_VERIFIED;
				
				$this->Profile->add_user_detail($ArrSeurityInfo);
			}
			else
			{	
				$data['updated'] 	= date("Y-m-d H:i:s");
				$this->Profile->edit_user_detail($user_id,$ArrSeurityInfo);
			}
			
			$this->session->set_flashdata('MessageTab1','Basic Information updated successfully');
			redirect('admin/settings','refresh');
   		}
   	}

   	function update_business_info()
   	{	
   		$data =array();
   		$file_name ="";
   		$ArrFormValues = array(
			array(
				'name'		=>	htmlentities($this->input->post('business')),  		
				'description' 		=>  htmlentities($this->input->post('description')),   		
				'email' 			=> 	htmlentities($this->input->post('email')),   		
				'phone' 			=> 	htmlentities($this->input->post('phone')),  		
				'facebook' 	=> 	htmlentities($this->input->post('facebook')),
				'address' 	=> 	htmlentities($this->input->post('address')),
				'twitter' => 	htmlentities($this->input->post('twitter')),   		
				'website' 	=> 	htmlentities($this->input->post('website')),
			)
		);
   		if (isset($_FILES['image']) && !empty($_FILES['image']['name']))
      	{
	   		$config['upload_path'] = '.'.CONST_IMAGE_UPLOAD_DIR;
			$config['allowed_types'] = 'gif|jpg|png';
			$this->load->library('upload');
			$load =$this->upload->initialize($config);
			if ( ! $this->upload->do_upload("image"))
			{
				$this->session->set_flashdata('ErrorMessageTab2','Image Uploading Faild');
				$data['basic_info'] = $this->Setting->get_user_basic_info();
		   		$data['security_questions'] = $this->Setting->get_security_questions();
		   		$data['basic_info_form_url'] = site_url('admin/settings/update_basic_info');
		   		$data['business_info'] = $ArrFormValues;
		   		$data['business_info_form_url'] =  site_url('admin/settings/update_business_info');
		   		$data['bank_info'] = $this->Setting->get_user_bank_info();
   				$data['bank_info_form_url'] =  site_url('admin/settings/update_bank_info');
   				$data['receipt_designer_form_url'] =  site_url('admin/settings/update_receipt_designer');
		   		$content = $this->load->view('profile/index.php', $data, true);
		        $this->load->view('main', array('content' => $content));
				redirect('admin/settings','refresh');			
			}
			$file_name = $this->upload->data();
			$old_image = $this->input->post('old-image');
			$old_image = str_replace(base_url(),'', $old_image);
			unlink($old_image);
		}
		$data['name'] 			= $ArrFormValues[0]['name'];
		$data['description'] 	= $ArrFormValues[0]['description'];
		$data['address']		= $ArrFormValues[0]['address'];
		$data['email'] 			= $ArrFormValues[0]['email'];
		$data['phone'] 			= $ArrFormValues[0]['phone'];
		$data['facebook'] 		= $ArrFormValues[0]['facebook'];
		$data['twitter'] 		= $ArrFormValues[0]['twitter'];
		$data['website'] 		= $ArrFormValues[0]['website'];
		$data['updated'] 		= date("Y-m-d H:i:s");
		if($file_name!='')
		{
			$data['logo']=base_url().CONST_IMAGE_UPLOAD_DIR.$file_name['file_name'];
		}
		$this->Setting->update_business_info($data);
		$this->session->set_flashdata('MessageTab2','Business Information updated successfully');
		redirect('admin/settings','refresh');

   	}

   	function update_bank_info()
   	{	
   		$bankInfo = $this->profile->checkUserBankDetails($this->user_id);
   		$data =array(
				
			'bank_name'				=>	htmlentities($this->input->post('bank_name')),  		
			'bank_address' 			=>  htmlentities($this->input->post('bank_address')),   		
			'swift_code' 			=>  htmlentities($this->input->post('swift_code')),   		
			'account_number' 		=> 	htmlentities($this->input->post('account_number')),   		
			'account_title' 		=> 	htmlentities($this->input->post('account_title')),  		
			'updated'				=> date("Y-m-d H:i:s"),
		);
		$this->Setting->update_bank_info($data);
		$this->session->set_flashdata('MessageTab3','Bank Information updated successfully');
		redirect('admin/settings','refresh');

   	}

   	function update_receipt_designer()
   	{
   		$data =array(
				
			'receipt_header_text'	=>	htmlentities($this->input->post('header_text')),  		
			'receipt_footer_text'	=>  htmlentities($this->input->post('footer_text')),   		
			'receipt_bg_color' 		=>  htmlentities($this->input->post('bg_color')),   		
			'receipt_text_color' 	=> 	htmlentities($this->input->post('text_color')),   		
			'updated'				=> date("Y-m-d H:i:s"),
		);
		$this->Setting->update_business_info($data);
		$this->session->set_flashdata('MessageTab4','Receipt Designing updated successfully');
		redirect('admin/settings','refresh');
   	}
}

?>