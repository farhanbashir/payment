<?php
class Categories extends CI_Controller
{
	function __construct()
	{
        parent::__construct();
        $this->load->model('Category');
        if (!$this->session->userdata('logged_in')) 
        {
            redirect(base_url());
        }
    }

    function index()
    {   

       
    	$data = array();
	    $data['categories'] = $this->Category->get_all_categories();
	    $content = $this->load->view('categories/categories', $data, true);
     	$this->load->view('main', array('content' => $content));
    }

    function create_category()
    {   
        $data = array();
        $data['form_title'] = "Add Category";
        $data['button_title'] = "Create a new Category";
        $data['categories'] = $this->Category->get_all_categories();
        $data['form_url'] = site_url('admin/categories/add_category');
        $content = $this->load->view('categories/category_form', $data, true);
        $this->load->view('main', array('content' => $content));
    }

    function add_category()
    {	
    	
    	$category_name = htmlentities($this->input->post('category_name'));
    	
    	$parent_category = htmlentities($this->input->post('parent_category'));
		
		$data = array(
			
			'store_id'		=>	getLoggedInStoreId(),
			'user_id'		=>	getLoggedInUserId(),
			'name'			=>	$category_name,
			'parent_id'		=>	$parent_category,
			'status'		=>	1,
			'created'		=>  date("Y-m-d H:i:s"),
			);
    	
    	$this->Category->add_category($data);
    	$this->session->set_flashdata('Message','Category add successfully');
    	
    	redirect('admin/categories','refresh');
    }

    function edit_category($category_id)
    {
    	if(!intval($category_id) || $category_id<0)
        {
            redirect('admin','refresh');
        }

        $data['form_title'] = "Edit Category";
        $data['button_title'] = "Edit Category";
		$data['id']= $category_id;
		$data['edit_data'] = $this->Category->edit_category($category_id);
        $data['form_url'] = site_url('admin/categories/update_category/'.$category_id);
        $data['categories'] = $this->Category->get_all_categories();
        $content = $this->load->view('categories/category_form', $data, true);
        $this->load->view('main', array('content' => $content));
    }

    function update_category($category_id)
    {
        if(!intval($category_id) || $category_id<0)
        {
            redirect('admin','refresh');
        }

        $category_name = htmlentities($this->input->post('category_name'));
        $parent_category = htmlentities($this->input->post('parent_category'));
        
        $data = array(
            
            'name'          =>  $category_name,
            'parent_id'     =>  $parent_category,
            'updated'       =>  date("Y-m-d H:i:s"),
            );

        $this->Category->update_category($data,$category_id);
        $this->session->set_flashdata('Message','Category Updated successfully');
        redirect('admin/categories','refresh');
    }

    function delete_category($id)
    {
        if(!intval($id) || $id<0)
        {
            redirect('admin','refresh');
        }

        $this->Category->delete_category($id);
        $this->session->set_flashdata('Message','Category Delete successfully');
        redirect('admin/categories','refresh');
    }
}


?>