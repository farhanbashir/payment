<?php
Class Profile extends CI_Model
{

    function get_user_detail($user_id)
    {
        $sql = "select u.name, u.email,u.role_id, ud.security_question_id, ud.security_answer,ub.bank_id, ub.bank_name, ub.account_number, ub.status as bank_status,us.store_id, us.name as store_name,us.logo, us.status as store_status from users u 
            left join user_details ud on u.user_id=ud.user_id 
            left join user_banks ub on u.user_id=ub.user_id 
            left join user_stores us on u.user_id=us.user_id 
            where u.user_id=$user_id" ;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result[0];
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
}
