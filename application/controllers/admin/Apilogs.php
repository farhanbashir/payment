<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Apilogs extends CI_Controller {
	
    function __construct()
	{
      parent::__construct();
	  
        if (!$this->session->userdata('logged_in')) 
        {
          redirect(base_url());
        }
		
		$this->load->model('logs');
    }
	
    public function index()
	{
		$data = array();
        $content  = $this->load->view('logs/logs_listing.php', $data, true);
        
        $this->load->view('main', array('content' => $content));
	}
	
	function ajaxWebServicesLogsListing()
	{
        $_getParams = $_GET;
        
        $params     				= _processDataTableRequest($_getParams);       
        $draw       				= $params['draw'];

        $logsList = $this->logs->getWebServicesLogs($params);

        $recordsFiltered = $this->logs->getWebServicesLogsCount($params); 
        $recordsTotal = $this->logs->getWebServicesLogsCountWithoutFilter(array());
        
        $logsData = array();
        
        if(is_array($logsList) && count($logsList) > 0)
        {
			foreach ($logsList as $row) 
			{
				$_service 			= $row['service'];
				
				$tplPostParams		= '<pre>'. print_r(@json_decode(@$row['post_params']), true). '</pre>';
				if($_service == 'createOrder')
				{
					$_postParams = @json_decode(@$row['post_params']);
					
					if(@$_postParams->data)
					{
						$tplPostParams		= '<pre>'. print_r(@json_decode(@$_postParams->data), true). '</pre>';
					}
				}
				
				$tplResponse = '<pre>'. print_r(@json_decode($row['response']), true). '</pre>';
				
				$tempArray  		= array();
				
				$tempArray[]       = $row['id'];
				$tempArray[]       = $row['user_id'];
				$tempArray[]       = $_service;            
				$tempArray[]       = $tplPostParams;
				$tempArray[]       = $tplResponse;

				$logsData[] 	= $tempArray;
			}
		}

		$data = array(
			"draw"            => isset ( $draw ) ? intval( $draw ) : 0,
			"recordsTotal"    => $recordsTotal,
			"recordsFiltered" => $recordsFiltered,
			"data"            => $logsData
		);

		echo json_encode($data);
		exit;
	}
}