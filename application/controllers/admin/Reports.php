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


    function index($data=array())
    {
        if(empty($data))
        {
            $data['sales_summary'] = $this->report->get_sales_summary();
        }
        
        $data['load_page'] = "sales/reports/sales_summary";
        $data['form_url'] = site_url('admin/reports/sales_summary_by_value');
        $content = $this->load->view('sales/reports/index', $data, true);
        $this->load->view('main', array('content' => $content));    	
    }

    function sales_summary_by_value()
    {   
        $data = array();
        
        $FormData = array();
        
        $FormData['date1']  = htmlentities($this->input->post('date1'));
        
        $FormData['date2']  = htmlentities($this->input->post('date2'));
        
        $FormData['select'] = htmlentities($this->input->post('select'));
        
        if($FormData['date1']=='' && $FormData['date2']=='' && $FormData['select']==0)
        {
            redirect(site_url('admin/reports'),'refresh');
        }
        
        $data['sales_summary'] = $this->report->get_sales_summary_by_value($FormData);
        
        //redirect($this->index($data),'refresh');

    }

    function order_summary()
    {

    	$data = array();
        $data['order_summary'] = $this->report->get_order_summary();
        $data['load_page'] = "sales/reports/order_summary";
        $data['form_url'] = '';//site_url('admin/reports/sales_summary_by_value');
        $content = $this->load->view('sales/reports/index', $data, true);
        $this->load->view('main', array('content' => $content));
    }

    function category_sales()
    {

    	$data = array();
        $data['category_sales'] = $this->report->get_category_sales_summary();
        $data['load_page'] = "sales/reports/category_sales";
         $data['form_url'] = '';//site_url('admin/reports/sales_summary_by_value');
        $content = $this->load->view('sales/reports/index', $data, true);
        $this->load->view('main', array('content' => $content));
    }

    function item_sales()
    {
    	
        $data = array();
        $data['item_sales'] = $this->report->get_item_sales_summary();
        $data['load_page'] = "sales/reports/item_sales";
        $data['form_url'] = '';//site_url('admin/reports/sales_summary_by_value');
        $content = $this->load->view('sales/reports/index', $data, true);
        $this->load->view('main', array('content' => $content));
    }
}


?>