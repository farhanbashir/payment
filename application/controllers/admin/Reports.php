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
        $data       = array();

        $FormData   = array();
        
        $FormData   = $this->FormData();
        
        if($FormData['FromDate']=='' && $FormData['ToDate']=='' && $FormData['select']=='0')
        {
            $data['sales_summary'] = $this->report->get_sales_summary($FormData=null);
        } 
        else
        {   
            $data['sales_summary'] = $this->report->get_sales_summary($FormData);
        }
        
        $data['SelctedValue'] = $this->Set_input_Values($FormData);
        
        $data['load_page'] = "sales/reports/sales_summary";
        
        $content = $this->load->view('sales/reports/index', $data, true);
        
        $this->load->view('main', array('content' => $content));    	
    }

    function order_summary()
    {
        $data       = array();

        $FormData   = $this->FormData();
       
        if($FormData['FromDate']=='' && $FormData['ToDate']=='' && $FormData['select']=='0')
        {
            $data['order_summary'] = $this->report->get_order_summary($FormData=null);
        } 
        else
        {   
            $data['order_summary'] = $this->report->get_order_summary($FormData);
        }
        
        $data['SelctedValue'] = $this->Set_input_Values($FormData);

        $data['load_page'] = "sales/reports/order_summary";

        $content = $this->load->view('sales/reports/index', $data, true);

        $this->load->view('main', array('content' => $content)); 

    }

    function category_sales()
    {

    	$data       = array();

        $FormData   = $this->FormData();
       
        if($FormData['FromDate']=='' && $FormData['ToDate']=='' && $FormData['select']=='0')
        {
            $data['category_sales'] = $this->report->get_category_sales_summary($FormData=null);
        } 
        else
        {   
            $data['category_sales'] = $this->report->get_category_sales_summary($FormData);
        }

        $data['SelctedValue'] = $this->Set_input_Values($FormData);
        $data['load_page'] = "sales/reports/category_sales";
        $content = $this->load->view('sales/reports/index', $data, true);
        $this->load->view('main', array('content' => $content));
    }

    function item_sales()
    {
    	$data       = array();

        $FormData   = $this->FormData();
       
        if($FormData['FromDate']=='' && $FormData['ToDate']=='' && $FormData['select']=='0')
        {
            $data['item_sales'] = $this->report->get_item_sales_summary($FormData=null);
        } 
        else
        {   
            $data['item_sales'] = $this->report->get_item_sales_summary($FormData);
        }

        $data['SelctedValue'] = $this->Set_input_Values($FormData);

        $data['load_page'] = "sales/reports/item_sales";

        $content = $this->load->view('sales/reports/index', $data, true);

        $this->load->view('main', array('content' => $content));
    }

    function FormData()
    {   
        $FormData = array();

        $FormData['FromDate']  = htmlentities($this->input->post('FromDate'));
        
        $FormData['ToDate']  = htmlentities($this->input->post('ToDate'));
        
        $FormData['select'] = htmlentities($this->input->post('select'));

        return $FormData;
    }

    function Set_input_Values($FormData)
    {
        $SelctedValue   = array(

            
            'Daily'            =>  '',
            'Weekly'           =>  '',
            'Monthly'          =>  'selected',
            'FromDate'         =>  '',
            'ToDate'           =>  '',
            );
        
        if($FormData['FromDate'] || $FormData['ToDate'])
        {
            $SelctedValue['FromDate'] = $FormData['FromDate'];
            
            $SelctedValue['ToDate'] = $FormData['ToDate'];

            $SelctedValue['Monthly'] = '';
        }

        if($FormData['FromDate']=='' && $FormData['ToDate']=='')
        {
            if($FormData['select']=='Daily')
            {
                $SelctedValue['Daily'] = 'selected';
                $SelctedValue['Monthly'] = '';

            }

            if($FormData['select']=='Weekly')
            {
                $SelctedValue['Weekly'] = 'selected';
                $SelctedValue['Monthly'] = '';
            }
        }
        
        return $SelctedValue;
    }

    
}


?>