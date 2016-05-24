<?php
Class Profile extends CI_Model
{

    function get_user_detail($user_id)
    {
        $sql = "select 
						u.user_id, u.first_name, u.last_name, u.email, u.role_id, 
						
						ud.security_question_id, ud.security_answer,
						
						ub.bank_id, ub.bank_name, ub.bank_address, ub.swift_code, 
						ub.account_title, ub.account_number, ub.status as bank_status, 
						
						us.store_id, us.name as store_name, us.description, us.logo, us.address as business_address, us.phone, 
						us.status as store_status,
						
						us.receipt_header_text, us.receipt_footer_text, us.receipt_bg_color, us.receipt_text_color 
						
				from users u 						
				left join user_details ud on u.user_id=ud.user_id 
				left join user_banks ub on u.user_id=ub.user_id 
				left join user_stores us on u.user_id=us.user_id 
				where u.user_id=$user_id" ;
        $query = $this->db->query($sql);
        $result = $query->result_array();
		
		if($result)
		{
			$query->free_result();
			return $result[0];
		}
		else			
		{
			return false;
		}
    }
    /*
    function get_admin()
    {
        $sql = "select * from users where email='admin@woo.com'" ;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result[0];
    }

    function get_users($page)
    {
        $start =  $page;
        $limit = $this->config->item('pagination_limit');
        $sql = "select * from users where is_admin=0 order by user_id desc limit $start,$limit" ;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function get_all_users()
    {
        $sql = "select * from users u where u.is_admin=0 order by u.user_id desc " ;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function get_complete_users()
    {
        $sql = "select * from users u where u.is_admin=0 and user_id not in (select user_id from stores) order by u.user_id desc " ;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function get_latest_five_users()
    {
        $sql = "select * from users where is_admin=0 order by user_id desc limit 5";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function deactivate_user($user_id)
        {
        $sql = "update users set is_active=0 where user_id=$user_id";
        $query = $this->db->query($sql);

    }

    function activate_user($user_id)
    {
        $sql = "update users set is_active=1 where user_id=$user_id";
        $query = $this->db->query($sql);

    }
    */
    function get_questions()
    {
        $sql = "select * from security_questions" ;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
	
	function checkUserDetails($user_id=0)
    {
        $this->db->select('detail_id, security_question_id, security_answer');
        $this->db->from('user_details');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);

        $query = $this->db->get();

        if($query->num_rows() == 1)
        {
            return $query->result();
        }
        else
        {
            return false;
        }
    }
	
    function edit_user_detail($user_id,$data)
    {
        $this->db->where('user_id', $user_id);
        $this->db->update('user_details',$data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    function add_user_detail($data)
    {
        $this->db->insert('user_details',$data);
        return $this->db->insert_id();
    }
	
	function checkUserBankDetails($user_id=0)
    {
        $this->db->select('*');
        $this->db->from('user_banks');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);

        $query = $this->db->get();

        if($query->num_rows() == 1)
        {	
			$result = $query->result_array();
			$query->free_result();
			return $result[0];
        }
        else
        {
            return false;
        }
    }
	
	function checkUserStoreDetails($user_id=0)
    {
        $this->db->select('*');
        $this->db->from('user_stores');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);

        $query = $this->db->get();

        if($query->num_rows() == 1)
        {	
			$result = $query->result_array();
			$query->free_result();
			return $result[0];
        }
        else
        {
            return false;
        }
    }

    function add_user_bank($data)
    {
        $this->db->insert('user_banks',$data);
        return $this->db->insert_id();
    }

    function edit_user_bank($bank_id, $data)
    {
        $this->db->where('bank_id', $bank_id);
        $this->db->update('user_banks',$data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }
	
	function get_store_detail($store_id)
    {
        $sql = "select * from user_stores where store_id=$store_id" ;
        $query = $this->db->query($sql);
        $result = $query->result_array();
		
		if($result)
		{
			$query->free_result();
			return $result[0];
		}
		else			
		{
			return false;
		}
    }

    function add_user_store($data)
    {
        $this->db->insert('user_stores',$data);
        return $this->db->insert_id();
    }

    function edit_user_store($store_id, $data)
    {
        $this->db->where('store_id', $store_id);
        $this->db->update('user_stores',$data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    function delete_profile($user_id)
    {
        $sql = "delete from user_details where user_id=$user_id";
        $query = $this->db->query($sql);
    }
	
	function add_user_merchant_info($data)
    {
        $this->db->insert('user_merchant_info', $data);
        return $this->db->insert_id();
    }
	
	function edit_user_merchant_info($merchant_id, $data)
    {
        $this->db->where('id', $merchant_id);
        $this->db->update('user_merchant_info',$data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }
	
	function checkUserMerchantDetails($user_id=0)
    {
        $this->db->select('*');
        $this->db->from('user_merchant_info');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);

        $query = $this->db->get();

        if($query->num_rows() == 1)
        {	
			$result = $query->result_array();
			$query->free_result();
			return $result[0];
        }
        else
        {
            return false;
        }
    }
}
