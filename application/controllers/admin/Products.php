<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Products extends CI_Controller {

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
        /*$this->load->model('user', '', TRUE);
        $this->load->model('store', '', TRUE);*/
        $this->load->model('product');
        $this->load->model('category');
        if (!$this->session->userdata('logged_in')) 
        {
          redirect(base_url());
        }

    }
	function test()
	{	
	 	$userId = $this->input->post('productId');	
		if($userId!='')
		{
			echo $userId;die;
		}
		
		else
		{
			$image =  uploadFile($this->config->item('product_image_base'), asset_url('img/products'));
			echo json_encode($image);
		}

	}

    public function index()
	{ 
        $userId  = getLoggedInUserId();
        $storeId = getLoggedInStoreId();
		
        $data = array();
		
		$all_categories = $this->product->get_all_categories($userId, $storeId);		
		$all_categories = categoryTree($all_categories);
		
		$data['categories'] = $all_categories;

        $content  = $this->load->view('products/products_listing.php', $data, true);
        
        $this->load->view('main', array('content' => $content));
	}
	
	

    function save($productId=0)
    {   
		$data = array();
		$storeId = getLoggedInStoreId();
		$userId = getLoggedInUserId();
		$postedData = array();
		$aErrorMessage = array();
		$showErrorMessage = "";
		$formHeading = "Add New Product";

		if($productId)
		{
			$productInfo = $this->product->getById($productId, $userId, $storeId);

			if($productInfo)
			{
				$formHeading = "Edit Product";
				
				// Product Categoires!
				$categories = array();
				$productCategories = $this->product->getProductCategories($productId);
				
				$arrProductCategories = array();
				if(is_array($productCategories) && count($productCategories) > 0)
				{
					foreach($productCategories as $_productCategoryInfo)
					{
						$categories[] = $_productCategoryInfo['category_id'];
					}
				}
				
				$strProductCategories = '';
				if(is_array($arrProductCategories) && count($arrProductCategories) > 0)
				{
					$strProductCategories = implode(', ', $arrProductCategories);
				}
				
				// Product Images!
				$strProductImage = '';
				$productImages = $this->product->getProductImages($productId);
				
				if(is_array($productImages) && count($productImages) > 0)
				{	
					$productImagesPath = array();

					foreach($productImages as $row) 
					{
						$productImagesPath[]  = @$row['media_path'];
					}

					$productImages = $productImages[0];
					
					if($productImages)
					{
						$productImageLink = @$productImages['media_path'];
						
						if($productImageLink)
						{
							$strProductImage =  $productImageLink;
						}
					}
				}
				
				$postedData['product_name'] =   $productInfo['name'];
				$postedData['description']  =   $productInfo['description'];
				$postedData['price']        =   $productInfo['price'];
				$postedData['old_image']    =   $strProductImage;
				$postedData['categories']   =   $categories;				
				$postedData['productImages']=   $productImagesPath;				
			}
			else
			{
				redirect('admin/products');
			}
		}
		
		#Submitter - START
		if($this->input->post('btn-submit'))
		{	
			$postedData = $this->input->post();

			extract($postedData);

			$categoryIds = array();
			$product_name  = htmlentities($product_name); 
			$description   = htmlentities($description);
			$replaceWordInPrice = array('$',',');
			$price = htmlentities(str_replace($replaceWordInPrice,'',$price));

			if(!$product_name)
			{
				$aErrorMessage[]= "Product name is required";
			}

			if(!$price)
			{
				$aErrorMessage[]= "Price is required";
			}
			if(empty($categories))
			{
				$aErrorMessage[]= "Please select atlease one category";
			}
			else
			{
				for($i=0; $i < count($categories) ; $i++) 
				{ 
					$categoryIds[] = htmlentities($categories[$i]);
				}
			}

			$product_media = array();
			
			if(empty($aErrorMessage))
			{
				if (isset($_FILES['image']) && !empty($_FILES['image']['name']))
				{
					$config['upload_path'] = './assets/img/products/';

					$config['allowed_types'] = 'gif|jpg|png';

					$this->load->library('upload');

					$load =$this->upload->initialize($config);

					if ( ! $this->upload->do_upload("image"))
					{
						$imageUploadError = array('error' => $this->upload->display_errors());
						$aErrorMessage[]  = $imageUploadError['error'];
					}
					else
					{
						$file_data     = $this->upload->data();

						$file_name   = asset_url('img/products/'.$file_data['file_name']);

						$media_type   = $file_data['image_type'];

						$product_media['file_name']   = $file_name;
						$product_media['media_type']  = $media_type;
						$product_media['status']      = CONST_STATUS_ID_ACTIVE;
						$product_media['created']     = date("Y-m-d H:i:s");
						$product_media['updated']     = date("Y-m-d H:i:s");						

						if($old_image)
						{ 
							$old_image = str_replace(base_url(),'', $old_image);
							@unlink($old_image);
						}
					}
				}
			}

			if(is_array($aErrorMessage) && count($aErrorMessage))
			{
				$showErrorMessage = getFormValidationErrorMessage($aErrorMessage);
				$showErrorMessage = $this->session->set_flashdata('showErrorMessage', $showErrorMessage);
			}
			else
			{
				$saveData = array(
					'store_id'      =>  $storeId,
					'user_id'       =>  $userId,
					'name'          =>  $product_name,
					'description'   =>  $description,
					'price'         =>  $price,
					'status'        =>  CONST_STATUS_ID_ACTIVE,
				);
				
				if($productId)
				{
					$saveData['updated'] = date("Y-m-d H:i:s");

					$this->product->edit_product($productId, $saveData);
					$this->product->update_product_categories($categories,$productId);
				}
				else
				{
					$saveData['created'] = date("Y-m-d H:i:s");
					$productId           = $this->product->add_product($saveData);
					
					$this->product->add_product_categories($categories, $productId);
				}
				
				if($productId)
				{
					if(is_array($product_media) && count($product_media) > 0)
					{
						$product_media['product_id']= $productId;
						$this->product->delete_product_media($productId);
						$this->product->add_product_media($product_media);
					}
				}
				
				$this->session->set_flashdata('Message','Product has been successfully saved!');
				redirect('admin/products','refresh');
			}
		}
		#Submitter - END
		
		$all_categories = $this->product->get_all_categories($userId, $storeId);		
		$all_categories = categoryTree($all_categories);

		$data['postedData'] = $postedData;
		$data['productId']  = $productId;
		$data['categories'] = $all_categories;
		$data['formHeading'] = $formHeading;
		$content = $this->load->view('products/product_form', $data, true);
		$this->load->view('main', array('content' => $content));
    }
    function ajaxProductsListing()
	{
        $userId  = getLoggedInUserId();
        $storeId = getLoggedInStoreId();
        
        $_getParams = $_GET;
        
        $params     				= _processDataTableRequest($_getParams);

        $filterCategory = $_getParams['filter_category'];
        $params['filter_category']  = '';
        $draw       				= $params['draw'];

		$_filterCategories = array();
        if($filterCategory)
        {
        	$filterCategories = $this->product->getAllCategoriesByCategoryId($filterCategory);
        	if($filterCategories)
        	{
        		foreach ($filterCategories as $row)
        		{	
        			$_filterCategories[] = $row['category_id'];		
        		}
				
        		$params['filter_category'] = $_filterCategories;
        	}	
		}

        $productsList = $this->product->getProducts($params, $userId, $storeId);

        $recordsFiltered = $this->product->getProductsCount($params, $userId, $storeId); 
        $recordsTotal = $this->product->getProductsCountWithoutFilter(array(), $userId, $storeId);
        
        $productsData = array();
        
        if(is_array($productsList) && count($productsList) > 0)
        {
			foreach ($productsList as $row) 
			{
				$productId   = $row['product_id'];

				

				$tplActions  = <<<EOT

					<a href="#$productId"  onclick="Javascript: return openPopupForProductDetails('$productId');">
						<button class="btn btn-primary btn-cons">View Details</button>
					</a>
EOT;
				// Product Categoires!
				$productCategories = $this->product->getProductCategories($productId);
				
				$arrProductCategories = array();
				if(is_array($productCategories) && count($productCategories) > 0)
				{	
					$count=0;
						
					foreach($productCategories as $_productCategoryInfo)
					{
						$arrProductCategories[] = $_productCategoryInfo['name'];
					}
				}
				
				$strProductCategories = '';
				if(is_array($arrProductCategories) && count($arrProductCategories) > 0)
				{
					$strProductCategories = implode(', ', $arrProductCategories);
				}
				
				// Product Images!
				$strProductImage = '';
				$productImages = $this->product->getProductImages($productId);
				
				if(is_array($productImages) && count($productImages) > 0)
				{
					$productImages = $productImages[0];
					
					if($productImages)
					{
						$productImageLink = @$productImages['media_path'];
						
						if($productImageLink)
						{
							$strProductImage =  '<img src="'.$productImageLink.'" width="150" height="100" alt="." />';
						}
					}
				}
				
				$tempArray  		= array();
				
				$tempArray[]       = $productId;
				$tempArray[]       = $row['name'];            
				$tempArray[]       = $strProductCategories;
				$tempArray[]       = CONST_CURRENCY_DISPLAY.$row['price'];
				$tempArray[]       = $strProductImage;
				$tempArray[]       = $tplActions;

				$productsData[] 	= $tempArray;
			}
		}

		$data = array(
			"draw"            => isset ( $draw ) ? intval( $draw ) : 0,
			"recordsTotal"    => $recordsTotal,
			"recordsFiltered" => $recordsFiltered,
			"data"            => $productsData
		);

		echo json_encode($data);
		exit;
	}

    function delete_product($product_id=0)
    { 
		if(!intval($product_id) || $product_id<0)
		{
			redirect('admin','refresh');
		}
		
		$storeId = getLoggedInStoreId();
		$userId = getLoggedInUserId();
		
		$productInfo = $this->product->getById($product_id, $userId, $storeId);
		if($productInfo)
		{ 
			$this->product->delete_product($product_id);
			$this->session->set_flashdata('Message','Product has been delete successfully');
		}
		
		redirect('admin/products', 'refresh');
    }


    public function popup_product($productId=0)
    {	
    	$data = array();

    	
    	$userId = getLoggedInUserId();
    	$storeId = getLoggedInStoreId();
    	$productInfo = $this->product->getById($productId, $userId, $storeId);
		
		if($productInfo)
		{
			$productCategories = $this->product->getProductCategories($productId);
			
			$arrProductImages = array();

			$productImages = $this->product->getProductImages($productId);
			
			if(is_array($productImages) && count($productImages) > 0)
			{	
				foreach ($productImages as $row)
				{
					$arrProductImages[] = @$row['media_path'];
				}
			}

			$data['productId']   	   = $productId;
			$data['productInfo']   	   = $productInfo;
			$data['productImages'] 	   = $arrProductImages;
			$data['productCategories'] = $productCategories;

			 $this->load->view('products/popup_product.php', $data);
		}  	
    }

}