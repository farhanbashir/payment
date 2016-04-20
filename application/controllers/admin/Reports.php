<?php 
class Reports extends CI_Controller
{
	function __construct()
	{
        parent::__construct();
       
        if (!$this->session->userdata('logged_in')) 
        {
            redirect(base_url());
        }
		
		$this->load->model('report','',TRUE);
    }


    function index()
    {
		
		$data = array();
        $data['sales_summary'] = $this->report->get_sales_summary();
        $data['load_page'] = "sales/reports/sales_summary";
        $content = $this->load->view('sales/reports/index', $data, true);
        $this->load->view('main', array('content' => $content));    	
    }

    function order_summary()
    {

    	$data = array();
        $data['order_summary'] = $this->report->get_order_summary();
        $data['load_page'] = "sales/reports/order_summary";
        $content = $this->load->view('sales/reports/index', $data, true);
        $this->load->view('main', array('content' => $content));
    }

    function category_sales()
    {

    	$data = array();
        $data['category_sales'] = $this->report->get_category_sales_summary();
        $data['load_page'] = "sales/reports/category_sales";
        $content = $this->load->view('sales/reports/index', $data, true);
        $this->load->view('main', array('content' => $content));
    }

    function item_sales()
    {

    	$data = array();
        $data['item_sales'] = $this->report->get_item_sales_summary();
        $data['load_page'] = "sales/reports/item_sales";
        $content = $this->load->view('sales/reports/index', $data, true);
        $this->load->view('main', array('content' => $content));
    }
}


?>