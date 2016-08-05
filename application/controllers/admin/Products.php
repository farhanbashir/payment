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

    function ajaxDeleteImage()
    {	
    	$deleteImage = $this->input->post('deleteImage');	

    	if($deleteImage)
		{	
			if(!intval($deleteImage))
			{	
				$deleteImage = str_replace(base_url(),"", $deleteImage);
				
				@unlink($deleteImage);
				
				return true;
			}
			else
			{
				$mediaId = $deleteImage;
				$productId = $this->input->post('productId');;
				$mediaInfo = $this->product->checkMediaByProductId($productId , $mediaId);
				if($mediaInfo)
				{
					$mediaId 	 = $mediaInfo['media_id'];
					$removeImage = str_replace(base_url(), '',$mediaInfo['file_name']);
					
					$this->product->delete_product_media(0,$mediaId);

					@unlink($removeImage);
				}
			}
		}
    }

	function ajaxaUplaodImage()
	{	
		
	 	if($this->input->post('productId'))
	 	{
	 		$productId = $this->input->post('productId');

	 		$image     = $this->input->post('image');

	 		$mediaType = 'image'; //-->mime_content_type(str_replace(base_url(), '',$image));

	 		$product_media = array();

			$product_media['product_id']  = $productId;
			$product_media['file_name']   = $image;
			$product_media['media_type']  = $mediaType;
			$product_media['status']      = CONST_STATUS_ID_ACTIVE;
			$product_media['created']     = date("Y-m-d H:i:s");
			$product_media['updated']     = date("Y-m-d H:i:s");

			$productMediaId = $this->product->add_product_media($product_media);
			echo $productMediaId;
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
		$arrProductMedia  =  array();
		
		$created        = date('Y-m-d H:i:s');
		$updated        = date('Y-m-d H:i:s');

		if($productId)
		{
			$productInfo = $this->product->getById($productId, $userId, $storeId);

			if($productInfo)
			{
				$formHeading = "Edit Product";
				
				// Product Categoires!
				$categories        = array();
				
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
				
				$productImages = $this->product->getProductImages($productId);
				
				if(is_array($productImages) && count($productImages) > 0)
				{	
					$_arrProductMedia = array();

					foreach($productImages as $row) 
					{
						$_arrProductMedia['mediaPath']  = @$row['media_path'];
						$_arrProductMedia['mediaId']    = @$row['media_id'];

						$arrProductMedia[] = $_arrProductMedia;
					}
				}
				
				$postedData['product_name']  =   $productInfo['name'];
				$postedData['description']   =   $productInfo['description'];
				$postedData['price']         =   $productInfo['price'];
				$postedData['categories']    =   $categories;				
				$postedData['productMedia']  =   $arrProductMedia;				
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

			$categoryIds   = array();
			$product_name  = htmlentities($product_name); 
			$description   = htmlentities($description);
			$replaceWordInPrice = array('$',',');
			$price = htmlentities(str_replace($replaceWordInPrice,'',$price));

			if($productId)
			{
				$arrProductMedia  =  array();
			}

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
				//-->$aErrorMessage[]= "Please select atlease one category"; //NOT NEEDED NOW, after 'Default' category!
			}
			else
			{
				for($i=0; $i < count($categories) ; $i++) 
				{ 
					$categoryIds[] = htmlentities($categories[$i]);
				}
			}
		
			if(is_array($aErrorMessage) && count($aErrorMessage))
			{	
				if($productId)
				{	
					
					$productImages = $this->product->getProductImages($productId);
			
					if(is_array($productImages) && count($productImages) > 0)
					{	
						$_arrProductMedia = array();

						foreach($productImages as $row) 
						{
							$_arrProductMedia['mediaPath']  = @$row['media_path'];
							$_arrProductMedia['mediaId']    = @$row['media_id'];

							$arrProductMedia[] = $_arrProductMedia;
						}
					}

					$postedData['productMedia']  =   $arrProductMedia;
				}
			
				$showErrorMessage = getFormValidationErrorMessage($aErrorMessage);
				$showErrorMessage = $this->session->set_flashdata('showErrorMessage', $showErrorMessage);
			}
			else
			{
				if(empty($categories)) // No Category Selected - START!
				{
					$defaultCategoryInfo = $this->category->getDefaultCategory($userId, $storeId);
					
					$defaultCategoryId = 0;
					if($defaultCategoryInfo)
					{
						$defaultCategoryId = @$defaultCategoryInfo['category_id'];
					}
					
					if(!$defaultCategoryId)
					{
						//Adding "Default" category for this new user!
						$defaultCategoryId = $this->category->add_category(
																		array(
																				"user_id" 		=> $userId,
																				"store_id" 		=> $storeId,
																				"parent_id" 	=> 0,
																				"name"			=> 'Default',
																				"created"		=> $created,
																				"updated"		=> $updated,
																				"is_default" 	=> 1, 
																				"status"		=> 1
																			)
																	);
					}					
					
					if($defaultCategoryId)
					{
						$categories = array();
						$categories[] = $defaultCategoryId;
					}
					
				} // No Category Selected - END!
				
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
					if(isset($productMedia))
					{
						if(is_array($productMedia) && count($productMedia) > 0)
						{
							for ($i=0; $i <count($productMedia); $i++) 
							{ 
								$mediaType = 'image'; //-->mime_content_type(str_replace(base_url(), '',$productMedia[$i]));

								$product_media['product_id']  = $productId;
								$product_media['file_name']   = $productMedia[$i];
								$product_media['media_type']  = $mediaType;
								$product_media['status']      = CONST_STATUS_ID_ACTIVE;
								$product_media['created']     = date("Y-m-d H:i:s");
								$product_media['updated']     = date("Y-m-d H:i:s");

								$this->product->add_product_media($product_media);
							}
						}
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
			$this->product->delete_product_media($product_id);

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