<?php

function _processDataTableRequest($_params=array())
{
	$draw 				= $_params['draw'];

    $columns 			= $_params['columns'];
    $offset 			= $_params['start'];
    $searchKeyword 		= $_params['search']['value'];
    $sortOrderIndex 	= $_params['order'][0]['column'];
    $sortOrderDirection = $_params['order'][0]['dir'];    
    $sortColumn 		= $columns[$sortOrderIndex]['name'];

    $returnArray = array();

    $returnArray['offset'] 			= $offset;
    $returnArray['search_keyword'] 	= $searchKeyword;
    $returnArray['sort_column'] 	= $sortColumn;
    $returnArray['sort_direction'] 	= $sortOrderDirection;    
    $returnArray['draw'] 			= $draw;

	return $returnArray;
}

function getFormValidationErrorMessage($aErrors)
{
  $htmlErrorMessages = '';
  if(is_array($aErrors) && count($aErrors) > 0)
  {
    foreach($aErrors as $errorKey => $errorMessage)
    {
     $htmlErrorMessages .= '<li>'. $errorMessage .'</li>';
    }

    if($htmlErrorMessages)
    {
     $htmlErrorMessages = '<ul class="ul-text-danger-custom">'. $htmlErrorMessages .'</ul>';
    }
  }
  else
  {
    $htmlErrorMessages = '<ul class="ul-text-danger-custom"><li>'. $aErrors .'</li></ul>';
  }

  return $htmlErrorMessages;
}

function getLoggedInRoleId()
{ 
  $ci = &get_instance();

  $sess_logged_in_merchant = $ci->session->userdata('logged_in_merchant');
  if($sess_logged_in_merchant)
  {       
    $_logged_in_merchant_user_id = @$sess_logged_in_merchant['role_id'];
    
    return $_logged_in_merchant_user_id;
  }
  
  return $ci->session->userdata['logged_in']['role_id'];
}

function categoryTree($data=array(), $parent_id=0, $current_level=0, &$return_arr=array())
{
	if(is_array($data) && count($data) > 0)
	{
		foreach ($data as $row)
		{	
			if ($row['parent_id'] == $parent_id)
			{				
				$row['name'] = str_repeat('— ', $current_level) . $row['name'];		
				
				$return_arr[] = $row;
				
				$next_level = $current_level+1;
				
				categoryTree($data, $row['category_id'], $next_level, $return_arr);
			}
		}
	}
	
	return $return_arr;
}

function categoryTree2($data, $index=false, $parent_id=0, $level=0, $maxLevel=0, &$arrReturn=array())
{
	if(!$index)
	{
		if(is_array($data) && count($data) > 0)
		{
			foreach($data as $row)
			{
				$id = $row["category_id"];
				$parent_id = $row["parent_id"] === NULL ? "NULL" : $row["parent_id"];
				$index[$parent_id][] = $id;
			}
		}
	}
	
    $parent_id = $parent_id === NULL ? "NULL" : $parent_id;
    if (isset($index[$parent_id])) {
        foreach ($index[$parent_id] as $id)
		{   
			$name = str_repeat('— ', $level) . @$data[$id]["name"];
			
			$arrReturn[] = array(
							'category_id' => $id,
							'name' => $name,			
			);
			
			$nextLevel = $level + 1;
			
            categoryTree2($data, $index, $id, $nextLevel, $maxLevel, $arrReturn);
			
        }
    }
	
	return $arrReturn;
}

function getHTMLForSuccessMessage($message)
{
	$message = '<div class="alert alert-success">
                <strong>Success: </strong> '.$message.'</div>';
				
	return $message;            
}

function getHTMLForErrorMessage($message)
{
	$message = '<div class="alert alert-danger">
                <strong>Alert: </strong> '.$message.'</div>';
				
	return $message;            
}

function getHTMLForNotificationMessage($message)
{
	$message = '<div class="alert alert-success">
                <strong>Notification: </strong> '.$message.'</div>';
	
	return $message;
}

function uploadImage($path)
{
	$ci = &get_instance();
	
	$imageUpload = false;
	if (isset($_FILES['image']) && !empty($_FILES['image']['name']))
	{
		$config['upload_path'] = './'.$path;
		$config['allowed_types'] = 'gif|jpg|png';

		$ci->load->library('upload');

		$load =$ci->upload->initialize($config);

		if ( ! $ci->upload->do_upload("image"))
		{   
			$imageUploadError = array('error' => $ci->upload->display_errors());
			$imageUpload['Error']  = $imageUploadError['error'];
		}
		else
		{
			$file_name 			 	  = $ci->upload->data();
            $imageUpload['file_type'] = $file_name['file_type'];
			$file_name                = $file_name['file_name'];
            $imageUpload['file_path'] = base_url().$path.$file_name;
		}
	}

	return $imageUpload;
}