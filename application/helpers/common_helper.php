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