<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stores extends CI_Controller {

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
        $this->load->model('store', '', TRUE);
        $this->load->model('user', '', TRUE);
        
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }
    }

    public function index() {
        $data = array();
        $this->load->library("pagination");
        $total_rows = $this->store->get_total_stores();

        $pagination_config = get_pagination_config('stores/index', $total_rows, $this->config->item('pagination_limit'), 4);

        $this->pagination->initialize($pagination_config);

        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        $data["links"] = $this->pagination->create_links();

        $stores = $this->store->get_stores($page);
        $data['stores'] = $stores;
        $content = $this->load->view('stores/tabular.php', $data, true);
        $this->load->view('welcome_message', array('content' => $content));
    }

    public function view($id) {
        $store = $this->store->get_store_detail($id);
        $data['store'] = $store;
        
        $content = $this->load->view('stores/view.php', $data, true);
        $this->load->view('welcome_message', array('content' => $content));
    }

    public function edit($id) {
        $store = $this->store->get_store_detail($id);
        $users = $this->user->get_all_users();
        $data['store'] = $store;
        $data['users'] = $users;
        $content = $this->load->view('stores/edit.php', $data, true);
        $this->load->view('welcome_message', array('content' => $content));
    }

    public function update() {
        $store_name = $this->input->post('store_name');
        $store_url = $this->input->post('store_url');
        $consumer_key = $this->input->post('consumer_key');
        $consumer_secret = $this->input->post('consumer_secret');
        $user_id = $this->input->post('user_id');
        $store_id = $this->input->post('store_id');
        $last_updated = date("Y-m-d h:i:s");
        $data = array("user_id"=>$user_id,"store_name"=>$store_name,"store_url"=>$store_url,"consumer_key"=>$consumer_key,"consumer_secret"=>$consumer_secret,"last_updated"=>$last_updated);
        
        $temp_image_url = "";
        $temp_image_url = uploadFile($this->config->item('store_image_base'), asset_url('img/stores'));

        if($temp_image_url !== "")
        {
            $data['store_image_url'] = $temp_image_url;
        }

        $this->store->edit_store($store_id, $data);
        
        redirect(site_url('admin/stores/edit/' . $store_id));
    }

    public function addnew() {
        $users = $this->user->get_complete_users();
        $data['users'] = $users;
        $content = $this->load->view('stores/new.php', $data, true);
        $this->load->view('welcome_message', array('content' => $content));
    }

    public function submit() {
        
        $store_name = $this->input->post('store_name');
        $store_url = $this->input->post('store_url');
        $consumer_key = $this->input->post('consumer_key');
        $consumer_secret = $this->input->post('consumer_secret');
        $user_id = $this->input->post('user_id');
        $last_updated = date("Y-m-d h:i:s");
        $data = array("user_id"=>$user_id,"store_name"=>$store_name,"store_url"=>$store_url,"consumer_key"=>$consumer_key,"consumer_secret"=>$consumer_secret,"last_updated"=>$last_updated);
        
        $temp_image_url = "";
        $temp_image_url = uploadFile($this->config->item('store_image_base'), asset_url('img/stores'));

        if($temp_image_url !== "")
        {
            $data['store_image_url'] = $temp_image_url;
        }

        $store_id = $this->store->add_store( $data);
        
        redirect(site_url('admin/stores/view/' . $store_id));
    }

    public function delete($id, $status, $view = NULL) {
        $flag = $this->store->edit_store($id, array("is_active"=>$status));
//        $this->image->delete_content_images($id);
//        if (empty($view)) {
            redirect(site_url('admin/stores/index'));
//        } else {
//            redirect(site_url('admin/' . $this->type . '/view/' . $id));
//        }
    }

    public function confirm_delete($store_id)
    {
        $this->store->delete_store_admin($store_id);
        redirect(site_url('admin/stores/index'));
    }

    

}
