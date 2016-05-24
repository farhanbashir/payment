<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Sales extends CI_Controller {

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
    function __construct() {
      parent::__construct();
        /*$this->load->model('user', '', TRUE);
        $this->load->model('store', '', TRUE);*/
        if (!$this->session->userdata('logged_in')) {
          redirect(base_url());
        }

        $this->load->model('user','',TRUE);
        $this->load->model('product','',TRUE);
        $this->load->model('order','',TRUE);
        $this->load->model('profile','',TRUE);
      }

      public function index() {
        $data = array();
        // $data['total_users'] = $this->user->get_total_users();
        // $data['total_stores'] = $this->store->get_total_stores();
        // $data['latest_five_users'] = $this->user->get_latest_five_users();
        // $data['latest_five_stores'] = $this->store->get_latest_five_stores();

        $content = $this->load->view('content.php', $data, true);
        $this->load->view('main', array('content' => $content));
      }

      public function reports() {
        $data = array();
        // $data['total_users'] = $this->user->get_total_users();
        // $data['total_stores'] = $this->store->get_total_stores();
        // $data['latest_five_users'] = $this->user->get_latest_five_users();
        // $data['latest_five_stores'] = $this->store->get_latest_five_stores();
        $data['sales_summary'] = $this->order->get_sales_summary();
        $data['order_summary'] = $this->order->get_order_summary();
        $content = $this->load->view('sales/reports/index', $data, true);
        $this->load->view('main', array('content' => $content));
      }

      public function transactions()
      {
        $data = array();
        // $data['total_users'] = $this->user->get_total_users();
        // $data['total_stores'] = $this->store->get_total_stores();
        // $data['latest_five_users'] = $this->user->get_latest_five_users();
        // $data['latest_five_stores'] = $this->store->get_latest_five_stores();

        /*$user_id = getLoggedInUserId();

        $orders = $this->order->get_all_order_transactions_by_user($user_id);

        $data['orders'] = $orders;

        $content = $this->load->view('sales/transactions.php', $data, true);
        $this->load->view('main', array('content' => $content));*/

        $data = array();
        $content = $this->load->view('sales/transaction_listing.php', $data, true);
        $this->load->view('main', array('content' => $content));
      }
      
      

      function ajaxTransactionListing()
      {
        $userId = getLoggedInUserId();
        $_getParams = $_GET;
        $params     = _processDataTableRequest($_getParams);
        $draw       = $params['draw'];

        $transactionList = $this->order->getUserTransaction($params, $userId);
        
        $recordsFiltered = $this->order->getUserTransactionCount($params, $userId); 
        $recordsTotal = $this->order->getUserTransactionCountWithoutFilter($params=null, $userId);

        $transactionData = array();

        if(is_array($transactionList) && count($transactionList) > 0)
        {   
          foreach ($transactionList as $row) 
          {   
            $order_id    = $row['order_id'];
            $receipt     = $row['receipt'];
			$app_type     = $row['app_type'];

            $paymentMethod = '';
            $paymentMethod .= "<p><strong>App:</strong> ". getDeviceTypeNameById($app_type) ."</p>";


            $amount_cash = $row['amount_cash'];

            if($amount_cash > 0)
            {
              $paymentMethod .= "<p><strong>Cash:</strong> ".$amount_cash."</p>";
            }

            $amount_cc = $row['amount_cc'];
            $is_cc_swipe = $row['is_cc_swipe'];

            if($amount_cc > 0)
            {

              $paymentMethod .='
                                <p>
                                <strong>Credit Card:</strong> '.CONST_CURRENCY_DISPLAY.$amount_cc.'
                                <br /> 
                                <span class="small" style="font-size: 10px;">'.$row['cc_number']. '</span>
                                </p>';

              $iconSwipe = '<i class="fs-14 fa fa-remove" style="color: red;"></i>';
              if($is_cc_swipe)
              {
                $iconSwipe = '<i class="fs-14 fa fa-check" style="color: green;"></i>';
              }

              $paymentMethod .='
                                <p>
                                <strong>Swipe:</strong> '.$iconSwipe.'
                                </p>';

            }

            $customInfo = "<p>
                            <strong>Email:</strong>".$row['customer_email']."<br />
                            <strong>Phone:</strong>".$row['customer_phone']."<br />
                            <strong>State:</strong>".$row['customer_state']."<br />
                            <strong>City:</strong>".$row['customer_city']."<br />
                            <strong>Address:</strong>".trim($row['customer_address1']." ".$row['customer_address2'])."<br />
                            <strong>Zipcode:</strong>".trim($row['customer_zipcode'])."<br />
                          </p>";

            $actionsUrls = '<p><a href="#'.$order_id.'" class="btn btn-primary" onclick="Javascript: return openPopupForOrderDetails('. $order_id.');">View Details</a>
                            <br /><br />';
            if($receipt)
            {
              $actionsUrls .= '<a target="_blank" href="'.$receipt.'" class="btn btn-info">View Receipt</a>';
            }
            else
            {
              $actionsUrls .= '<a target="_blank" href="'.site_url('admin/sales/generate_receipt/'.$order_id).'" class="btn btn-info">Generate Receipt</a>';
            }
            $tempArray   = array();
            $tempArray[] = $order_id;
            $tempArray[] = CONST_CURRENCY_DISPLAY.$row['total_amount'];
            $tempArray[] = $paymentMethod; 
            $tempArray[] = $customInfo; 
            $actionsUrls .= '</p>';
            $tempArray[] = date(CONST_DATE_TIME_DISPLAY, strtotime($row['created']));
            $tempArray[] = $actionsUrls;

            $transactionData[] = $tempArray;
          }
        }

        $data = array(
          "draw"            =>isset ( $draw ) ? intval( $draw ) : 0,
          "recordsTotal"    => $recordsTotal,
          "recordsFiltered" => $recordsFiltered,
          "data"            => $transactionData
          );

        echo json_encode($data);
      }

      public function popup_order($order_id=0)
      {
        $orderInfo				= $this->order->get_order_detail($order_id);
        $paymentTransaction 	= $this->order->get_payment_transaction_by_order($order_id);
       
        $products 				= $this->product->get_order_products($order_id);
        $refundTransaction		= $this->order->get_refund_transactions_by_order($order_id);

        $data = array();

        $data['order_id'] 			    = $order_id;
        $data['products'] 			    = $products;
        $data['orderInfo'] 			    = $orderInfo;
        $data['paymentTransaction'] = $paymentTransaction;		
        $data['refundTransaction'] 	= $refundTransaction;

        $this->load->view('sales/popup_order.php', $data);
      }

      public function receipt($order_id=0)
      {
        $data = array();

        $this->load->view('sales/receipt.php', $data);
      }

      public function generate_receipt($order_id=0)
      {
        $user_id = getLoggedInUserId();

        $receiptCreated = generateReceiptByOrderId($order_id, $user_id);

        if($receiptCreated)
        {
         redirect($receiptCreated);
         exit;
       }
       else
       {
         $orderInfo			= $this->order->get_order_detail($order_id);
         $orderReceipt		= @$orderInfo['receipt'];

         if($orderReceipt)
         {
          redirect($orderReceipt);
          exit;
        }
        else
        {
          echo 'Not able to generate receipt for Order # '.$order_id;
          exit;
        }
      }
    }

    public function change_password()
    {
      $data = array("error"=>"");
      $content = $this->load->view('change_password.php', $data, true);
      $this->load->view('welcome_message', array('content' => $content));
    }

    public function change_password_submit()
    {
      $old_password = $this->input->post('old_password');
      $new_password = $this->input->post('password');

      $admin = $this->user->get_admin();

      if(md5($old_password) === $admin["password"])
      {
        $data = array();
        $this->user->edit_user($this->session->userdata["logged_in"]["id"],array("password"=>md5($new_password)));
        $data["error"] = "Your password has been changed successfully";
        $data["result"] = "info";
        $content = $this->load->view('change_password.php', $data, true);
        $this->load->view('welcome_message', array('content' => $content));   
      } 
      else
      {
        $data = array();
        $data["error"] = "You type wrong old password";
        $data["result"] = "danger";
        $content = $this->load->view('change_password.php', $data, true);
        $this->load->view('welcome_message', array('content' => $content));   
      }     
      
    }

    public function events() {
      $type = 'events';
      $data = array();
      $this->load->library("pagination");
      $total_rows = $this->content->get_total_content_by_type($type);

      $pagination_config = get_pagination_config($type, $total_rows, $this->config->item('pagination_limit'), 3);

      $this->pagination->initialize($pagination_config);

      $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

      $data["links"] = $this->pagination->create_links();

      $pages = $this->content->get_content_by_type($type, $page);
      $data['pages'] = $pages;
      $content = $this->load->view('pages.php', $data, true);
      $this->load->view('welcome_message', array('content' => $content));
    }

    function sales_summary()
    {
      $string = file_get_contents(base_url()."/data.json");
      echo $string;
    }
    /* public function parents()
      {
      $data = array();
      $this->load->library("pagination");
      $total_rows = $this->user->get_total_users();

      $pagination_config = get_pagination_config('parents', $total_rows, $this->config->item('pagination_limit'), 3);

      $this->pagination->initialize($pagination_config);

      $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

      $data["links"] = $this->pagination->create_links();

      $users = $this->user->get_users($page);

      $data['users'] = $users;
      $content = $this->load->view('users.php', $data ,true);
      $this->load->view('welcome_message', array('content' => $content));
      }

      public function parent_detail($user_id)
      {
      $data = array();
      $data['detail'] = $this->user->get_user_detail($user_id);
      $content = $this->load->view('user_detail.php', $data ,true);
      $this->load->view('welcome_message', array('content' => $content));
      }

      public function feed_detail($feed_id)
      {
      $data = array();
      $data['detail'] = $this->feed->get_feed_detail($feed_id);
      $content = $this->load->view('feed_detail.php', $data ,true);
      $this->load->view('welcome_message', array('content' => $content));

      }

      public function babies()
      {
      $data = array();
      $this->load->library("pagination");
      $total_rows = $this->baby->get_total_babies();

      $pagination_config = get_pagination_config('babies', $total_rows, $this->config->item('pagination_limit'), 3);

      $this->pagination->initialize($pagination_config);

      $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

      $data["links"] = $this->pagination->create_links();

      $babies = $this->baby->get_babies($page);

      $data['babies'] = $babies;
      $content = $this->load->view('babies.php', $data ,true);
      $this->load->view('welcome_message', array('content' => $content));
      }

      public function baby_detail($baby_id)
      {
      $data = array();
      $data['detail'] = $this->baby->get_baby_detail($baby_id);
      $content = $this->load->view('baby_detail.php', $data ,true);
      $this->load->view('welcome_message', array('content' => $content));
      }

      public function login()
      {
      $this->load->view("login");
      }

      public function logout()
      {
      $this->session->sess_destroy();
      redirect(base_url());
      }

      public function messages()
      {
      $data = array();
      $this->load->library("pagination");
      $total_rows = $this->message->get_total_messages();

      $pagination_config = get_pagination_config('messages', $total_rows, $this->config->item('pagination_limit'), 3);

      $this->pagination->initialize($pagination_config);

      $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

      $data["links"] = $this->pagination->create_links();

      $messages = $this->message->get_messages($page);

      $data['messages'] = $messages;
      $content = $this->load->view('message.php', $data ,true);
      $this->load->view('welcome_message', array('content' => $content));
      }

      public function send_message()
      {
      set_time_limit(0);
      $data = array();

      $message = "";
      $this->load->library('form_validation');

      $this->form_validation->set_rules('message_en', 'message', 'trim|required');
      $this->form_validation->set_rules('message_ar', 'message', 'trim|required');

      $data['error'] = "";
      if ($this->form_validation->run())
      {
      // Form was submitted and there were no errors
      $message_en = $this->input->post('message_en');
      $message_ar = $this->input->post('message_ar');


      $uniqid = $this->input->post('uniqid');
      //$service_id = (int) $this->input->post('service_id');

      $params       = array('date'=>date("Y-m-d"),'message'=>$message_en, 'message_ar'=>$message_ar);

      $message_id = $this->message->create_message($params);

      $android_ids = array();
      //$iphone_ids = array();
      $devices = $this->device->get_devices();
      foreach($devices as $device)
      {
      if($device['type'] == 0)
      {
      //$iphone_ids[$device["lang"]][] = $device['uid'];
      $message = ($device["lang"] == 0) ? $message_en : $message_ar;
      $file_url = ($device["lang"] == 0) ? asset_url("files/".$this->config->item('pem_en')) : asset_url("files/".$this->config->item('pem_ar'));
      send_notification_iphone($device['uid'], $message, $file_url);

      }
      else
      {
      $android_ids[$device["lang"]][] = $device['uid'];
      //$message = ($device["lang"] == 0) ? $message_en : $message_ar;
      //send_notification_android($device['uid'], $message);
      }
      }

      if(count($android_ids[0]) > 0)
      {
      send_notification_android($android_ids[0], $message_en);
      }
      if(count($android_ids[1]) > 0)
      {
      send_notification_android($android_ids[1], $message_ar);
      }
      // if(count($android_ids[0]) > 0)
      // {
      //   $file_url = asset_url("files/".$this->config->item('pem_en'));
      //   send_notification_iphone($iphone_ids[0], $message_en, $file_url);
      // }
      // if(count($android_ids[1]) > 0)
      // {
      //   $file_url = asset_url("files/".$this->config->item('pem_ar'));
      //   send_notification_iphone($iphone_ids[0], $message_ar, $file_url);
      // }

      redirect(base_url().'index.php/welcome/messages');




      }
      else
      {
      $is_submit = ($this->input->post('is_submit')) ? $this->input->post('is_submit') : 0;
      $uniqid = ($this->input->post('uniqid')) ? $this->input->post('uniqid') : uniqid();
      }



      $data['uniqid'] = $uniqid;
      $content = $this->load->view('create_message.php', $data ,true);
      $this->load->view('welcome_message', array('content' => $content));

      // $message = "how are you";
      // $id = "APA91bFuM4vc4PfgYsffQRiHPfaBC5CqF7GPlm-1i8LYx8Fl-A3CDAyqkOtmiSMpESQDQ5qBqrxJiHxLehbS7IgMmtxVEZCaKaHUCOxMFCIHQJDuxChIbCJLCkJZOOA14cUgIaGE-q9j";
      // send_notification_android(array($id), array("message"=>$message));


      }

      function test()
      {
      $message = "how are you";
      $id = "APA91bFuM4vc4PfgYsffQRiHPfaBC5CqF7GPlm-1i8LYx8Fl-A3CDAyqkOtmiSMpESQDQ5qBqrxJiHxLehbS7IgMmtxVEZCaKaHUCOxMFCIHQJDuxChIbCJLCkJZOOA14cUgIaGE-q9j";
      send_notification_iphone(array($id), array("message"=>$message));
      }


      function edit_feed($feed_id)
      {
      $error = "";
      $message = "";
      //$admin = $this->user->get_admin();

      if($feed_id == "")
      redirect(base_url());
      else
      $feed_id = intval($feed_id);

      //debug($_REQUEST,1);

      $this->load->library('form_validation');

      $this->form_validation->set_rules('from', 'from', 'trim|required');
      $this->form_validation->set_rules('to', 'to', 'trim|required');
      $this->form_validation->set_rules('feed', 'Feed', 'trim|required');
      $this->form_validation->set_rules('intro', 'Intro', 'trim|required');
      $this->form_validation->set_rules('feed_ar', 'Feed Arabic', 'trim|required');
      $this->form_validation->set_rules('intro_ar', 'Intro Arabic', 'trim|required');
      $this->form_validation->set_rules('milestone_id', 'Milestone', 'trim|required');

      if ($this->form_validation->run())
      {
      // Form was submitted and there were no errors
      $from        = $this->input->post('from');
      $to     = $this->input->post('to');
      $feed  = $this->input->post('feed', true);
      $intro    = $this->input->post('intro', true);
      $feed_ar    = $this->input->post('feed_ar', true);
      $intro_ar = $this->input->post('intro_ar', true);
      $milestone_id = $this->input->post('milestone_id');


      $uniqid = $this->input->post('uniqid');
      //$service_id = (int) $this->input->post('service_id');

      $params       = array('from'=>$from,
      'to'     =>$to,
      'feed'  =>$feed,
      'intro' =>$intro,
      'feed_ar'    =>$feed_ar,
      'intro_ar'    =>$intro_ar,
      'milestone_id'     =>$milestone_id

      );



      if($error == "")
      {
      $result = $this->feed->edit_feed($feed_id,$params);
      redirect(base_url().'index.php/welcome/feed_detail/'.$feed_id);
      }

      }
      else
      {
      $is_submit = ($this->input->post('is_submit')) ? $this->input->post('is_submit') : 0;
      $uniqid = ($this->input->post('uniqid')) ? $this->input->post('uniqid') : uniqid();
      }


      $data = array();


      $data['error'] = $error;
      $data['uniqid'] = $uniqid;
      $data['detail'] = $this->feed->get_feed_detail($feed_id);
      $data['milestones'] = $this->milestone->get_milestones();
      $content = $this->load->view('edit_feed.php', $data ,true);
      $this->load->view('welcome_message', array('content' => $content));
      }

      function create_feed()
      {
      $data = array();

      $message = "";
      //$admin = $this->user->get_admin();
      $this->load->library('form_validation');

      $this->form_validation->set_rules('from', 'from', 'trim|required');
      $this->form_validation->set_rules('to', 'to', 'trim|required');
      $this->form_validation->set_rules('feed', 'Feed', 'trim|required');
      $this->form_validation->set_rules('intro', 'Intro', 'trim|required');
      $this->form_validation->set_rules('feed_ar', 'Feed Arabic', 'trim|required');
      $this->form_validation->set_rules('intro_ar', 'Intro Arabic', 'trim|required');
      $this->form_validation->set_rules('milestone_id', 'Milestone', 'trim|required');

      $data['error'] = "";
      if ($this->form_validation->run())
      {
      // Form was submitted and there were no errors
      $from        = $this->input->post('from');
      $to     = $this->input->post('to');
      $feed  = $this->input->post('feed', true);
      $intro    = $this->input->post('intro', true);
      $feed_ar    = $this->input->post('feed_ar', true);
      $intro_ar = $this->input->post('intro_ar', true);
      $milestone_id = $this->input->post('milestone_id');


      $uniqid = $this->input->post('uniqid');
      //$service_id = (int) $this->input->post('service_id');

      $params       = array('from'=>$from,
      'to'     =>$to,
      'feed'  =>$feed,
      'intro' =>$intro,
      'feed_ar'    =>$feed_ar,
      'intro_ar'    =>$intro_ar,
      'milestone_id'     =>$milestone_id

      );

      $feed_id = $this->feed->create_feed($params);

      redirect(base_url().'index.php/welcome/feed_detail/'.$feed_id);




      }
      else
      {
      $is_submit = ($this->input->post('is_submit')) ? $this->input->post('is_submit') : 0;
      $uniqid = ($this->input->post('uniqid')) ? $this->input->post('uniqid') : uniqid();
      }



      $data['uniqid'] = $uniqid;
      $data['milestones'] = $this->milestone->get_milestones();
      $content = $this->load->view('create_feed.php', $data ,true);
      $this->load->view('welcome_message', array('content' => $content));
      }

      function deactivate_feed($feed_id)
      {
      $this->feed->deactivate_feed($feed_id);
      redirect(base_url().'/index.php/welcome/feed_detail/'.$feed_id);
      }

      function activate_feed($feed_id)
      {
      $this->feed->activate_feed($feed_id);
      redirect(base_url().'/index.php/welcome/feed_detail/'.$feed_id);
    } */
  }

  /* End of file welcome.php */
  /* Location: ./application/controllers/welcome.php */


