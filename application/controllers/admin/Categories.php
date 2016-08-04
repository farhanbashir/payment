<?php
class Categories extends CI_Controller
{
	function __construct()
	{
        parent::__construct();
        $this->load->model('category');
        if (!$this->session->userdata('logged_in')) 
        {
            redirect(base_url());
        }
        
    }

    function index()
    {   
        /*$userId  = getLoggedInUserId();
        $storeId = getLoggedInStoreId();
       
    	$data = array();
	    $data['categories'] = $this->Category->get_all_categories($userId, $storeId);
	    $content = $this->load->view('categories/categories', $data, true);
     	$this->load->view('main', array('content' => $content));*/
        
        $data = array();
        $content = $this->load->view('categories/category_listing.php', $data, true);
        $this->load->view('main', array('content' => $content));
    }
    

    function ajaxCategoryListing()
    {
        $userId  = getLoggedInUserId();
        $storeId = getLoggedInStoreId();

        $_getParams = $_GET;
        $params     = _processDataTableRequest($_getParams);
        $draw       = $params['draw'];
		
        $categoryList = $this->category->getCategory($params, $userId, $storeId);

        $recordsFiltered = $this->category->getCategoryCount($params,$userId, $storeId); 
        $recordsTotal = $this->category->getCategoryCountWithoutFilter($params=array(),$userId, $storeId);

        $categoryData = array();
		
		$categoryList = categoryTree($categoryList);

        if(is_array($categoryList) && count($categoryList) > 0)
        {            
            foreach ($categoryList as $category) 
            {                 
                $tempArray       = array();  
                
                $categoryId      = $category['category_id'];
                $category_name   = $category['name'];
				$is_default   	 = @$category['is_default'];

                $parentCategory = "";
                
                if($category['parent_category'])
                {
                    $parentCategory = $category['parent_category'];
                }
                $deleteUrl   = site_url('admin/categories/delete_category/'.$categoryId); 
                $editUrl     = site_url('admin/categories/save/'.$categoryId);
				
				if($is_default)
				{
					$actionData = '<i>its a default category!</i>';
				}
				else
				{
					$actionData  = <<<EOT
                        <p>
                            <a href="$editUrl">
                              <button class="btn btn-primary btn-cons">Edit</button>
                            </a>
                            <a onclick="return confirm('Are you sure want to delete','$deleteUrl')"href="$deleteUrl">
                              <button class="btn btn-danger btn-cons">Delete</button>
                            </a>
                        </p>
EOT;

				}
                
                //-->$tempArray[] = $categoryId;
                $tempArray[] = $category_name;
                //-->$tempArray[] = $parentCategory;
                $tempArray[] = $category['total_products'];
                $tempArray[] = $actionData;
                $categoryData[] = $tempArray;
            }
        }
       
        $data = array(
          "draw"            =>isset ( $draw ) ? intval( $draw ) : 0,
          "recordsTotal"    => $recordsTotal,
          "recordsFiltered" => $recordsFiltered,
          "data"            => $categoryData
		);

        echo json_encode($data);
    } 

    function save($categoryId=0)
    {
        $data = array();
        $postedData = array();
        $aErrorMessage = array();
        $showErrorMessage = "";

        $userId = getLoggedInUserId();
        $storeId = getLoggedInStoreId();

        $formHeading = "Add New Category";

        if($categoryId)
        {
            $categoryInfo = $this->category->getById($categoryId, $userId, $storeId);
            
            if($categoryInfo)
            {
				$is_default = @$categoryInfo['is_default'];
			
				if($is_default)
				{
					$this->session->set_flashdata('showErrorMessage', "Default category can't be editable!");
					redirect(site_url('admin/categories'));
				}
				
                $formHeading = "Edit Category";

                $postedData = $categoryInfo;
            }
            else
            {                
                redirect(site_url('admin/categories'));
            }
        }
        
        #Submitter - Start!        
        if($this->input->post('btn-submit'))
        {
            $postedData = $this->input->post();
        
            extract($postedData);

            $category_name = htmlentities($category_name);
			
			if($categoryId && $parent_category)
			{
				if($categoryId == $parent_category)
				{
					$aErrorMessage[] = "Category can't be its own parent";
				}
			}

            if(!$category_name)
            {
               $aErrorMessage[] = "Category name required";
            }
            
            if(is_array($aErrorMessage) && count($aErrorMessage))
            {
                $showErrorMessage = getFormValidationErrorMessage($aErrorMessage);
                $showErrorMessage = $this->session->set_flashdata('showErrorMessage',$showErrorMessage);
            }

            else
            {
                $saveData = array(
                    'name'      => $category_name,
                    'store_id'  => $storeId,
                    'user_id'   => $userId,
                    'parent_id' => $parent_category,
                    'status'    => CONST_STATUS_ID_ACTIVE,
                );
                
                if($categoryId)
                {
                    $saveData['updated'] = date("Y-m-d H:i:s");   
                    
                    $this->category->update_category($saveData, $categoryId);
                }

                else
                {   

                    $saveData['created'] = date("Y-m-d H:i:s");

                    $this->category->add_category($saveData);                
                }

                $this->session->set_flashdata('Message','Category has been successfully saved!');
                redirect('admin/categories','refresh');
            }
           
        }
       
       
        $data['formHeading'] = $formHeading;
        $data['postedData'] = $postedData;

        $data['button_title'] = "Save";
        $data['categories'] = $this->category->get_all_categories($userId, $storeId);

        $content = $this->load->view('categories/category_form', $data, true);
        $this->load->view('main', array('content' => $content));

    }

    function delete_category($categoryId)
    {
        if(!intval($categoryId) || $categoryId<0)
        {
            redirect('admin/categories','refresh');
        }

        $userId  = getLoggedInUserId();
        $storeId = getLoggedInStoreId();
        $categoryInfo = $this->category->getById($categoryId, $userId, $storeId);
		
        if($categoryInfo)
        {
			$is_default = @$categoryInfo['is_default'];
			
			if($is_default)
			{
				$this->session->set_flashdata('showErrorMessage', "Default category can't be deleted!");
			}
			else
			{
				$this->category->delete_category($categoryId);
				$this->session->set_flashdata('Message','Category Delete successfully');
			}
        }
		
        redirect('admin/categories','refresh');
    }
    /*function edit_category($category_id)
    {
    	if(!intval($category_id) || $category_id<0)
        {
            redirect('admin','refresh');
        }
        $user_id = getLoggedInUserId();
        $data['form_title'] = "Edit Category";
        $data['button_title'] = "Edit Category";
		$data['id']= $category_id;
		$data['edit_data'] = $this->Category->edit_category($category_id);
        if(empty($data['edit_data']))
        {
            redirect('admin/categories','refresh');
        }
        $data['form_url'] = site_url('admin/categories/update_category/'.$category_id);
        $data['categories'] = $this->Category->get_all_categories($user_id);
        $content = $this->load->view('categories/category_form', $data, true);
        $this->load->view('main', array('content' => $content));
    }
*/
   /* function update_category($category_id)
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
    }*/

   
}


?>