<?php
Class User extends CI_Model
{

    function login($email, $password)
    {
        $this -> db -> select('*');
        $this -> db -> from('users');
        $this -> db -> where('email', $email);
        //$this -> db -> where('is_admin', $is_admin);
        $this -> db -> group_start();
        $this -> db -> where('password', md5($password));
        $this -> db -> or_where('new_password', md5($password));
        $this -> db -> group_end();
        $this -> db -> limit(1);

        $query = $this -> db -> get();

        if($query -> num_rows() == 1)
        {
            return $query->result();
        }
        else
        {
            return false;
        }
    }

    function validStore($user_id, $store_id=0)
    {
        $sql_user = "select * from users where user_id=$user_id limit 1";
        $query = $this->db->query($sql_user);
        $result = $query->result_array();
        $query->free_result();
        if(count($result) > 0)
        {
            if($result[0]['role_id'] == CONST_ROLE_ID_BUSINESS_STAFF)
            {
                $sql = "select store_id from user_stores where user_id=".$result[0]['parent_user_id'];
            }
            elseif($result[0]['role_id'] == CONST_ROLE_ID_BUSINESS_ADMIN)
            {
                $sql = "select store_id from user_stores where user_id=$user_id";
            }
            else
            {
                return false;
            }

            $query = $this->db->query($sql);
            $result = $query->result_array();
            $query->free_result();
            if(count($result) > 0 && $result[0]['store_id'] == $store_id)
            {
                return true;
            }
            else
            {
                return false;
            }    
        }
        else
        {
            return false;
        }    
    }

    function facebookLogin($facebook_id, $is_admin)
    {
        $this -> db -> select('user_id, email, password');
        $this -> db -> from('users');
        $this -> db -> where('facebook_id', $facebook_id);
        $this -> db -> where('is_admin', $is_admin);
        $this -> db -> limit(1);

        $query = $this -> db -> get();

        if($query -> num_rows() == 1)
        {
            return $query->result();
        }
        else
        {
            return false;
        }
    }

    function checkUser($email)
    {
        $this -> db -> select('user_id, first_name, last_name, email');
        $this -> db -> from('users');
        $this -> db -> where('email', $email);
        //$this -> db -> where('is_admin', $is_admin);
        //$this -> db -> where('password', $password);
        $this -> db -> limit(1);

        $query = $this -> db -> get();

        if($query -> num_rows() == 1)
        {
            return $query->result();
        }
        else
        {
            return false;
        }
    }

    function checkFacebookUser($facebook_id)
    {
        $this -> db -> select('user_id, username, email');
        $this -> db -> from('users');
        $this -> db -> where('facebook_id', $facebook_id);
        //$this -> db -> where('is_admin', $is_admin);
        //$this -> db -> where('password', $password);
        $this -> db -> limit(1);

        $query = $this -> db -> get();

        if($query -> num_rows() == 1)
        {
            return $query->result();
        }
        else
        {
            return false;
        }
    }

    function checkUserById($user_id)
    {
        $this -> db -> select('user_id, password, first_name, last_name, email, role_id, status');
        $this -> db -> from('users');
        $this -> db -> where('user_id', $user_id);
        //$this -> db -> where('is_admin', $is_admin);
        //$this -> db -> where('password', $password);
        $this -> db -> limit(1);

        $query = $this -> db -> get();

        if($query -> num_rows() == 1)
        {
            return $query->result();
        }
        else
        {
            return false;
        }
    }

    function get_total_users()
    {
        $this -> db -> where('role_id', CONST_ROLE_ID_BUSINESS_ADMIN);
        return $this->db->count_all_results('users');
    }

    function get_user_detail($user_id)
    {
        $sql = "select * from users where user_id=$user_id" ;
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

    function get_admin()
    {
        $sql = "select * from users where email='admin@woo.com'" ;
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

    function get_users($page)
    {
        $start =  $page;
        $limit = $this->config->item('pagination_limit');
        $sql = "select * from users where role_id = ".CONST_ROLE_ID_BUSINESS_ADMIN." order by user_id desc limit $start,$limit" ;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function get_all_users()
    {
        $sql = "select * from users u where u.role_id".CONST_ROLE_ID_BUSINESS_ADMIN." order by u.user_id desc " ;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function get_complete_users()
    {
        $sql = "select * from users u where u.role_id".CONST_ROLE_ID_BUSINESS_ADMIN." and user_id not in (select user_id from stores) order by u.user_id desc " ;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function get_latest_five_users()
    {
        $sql = "select * from users where role_id".CONST_ROLE_ID_BUSINESS_ADMIN." order by user_id desc limit 5";
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

    function delete_user($user_id)
        {
        $sql = "delete from users where user_id=$user_id";
        $query = $this->db->query($sql);

    }

    function activate_user($user_id)
    {
        $sql = "update users set is_active=1 where user_id=$user_id";
        $query = $this->db->query($sql);

    }

    function edit_user($user_id,$data)
    {
        $this->db->where('user_id', $user_id);
        $this->db->update('users',$data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    function add_user($data)
    {
        $this->db->insert('users',$data);
        return $this->db->insert_id();
    }
	
	function get_states($country_code=get_states)
    {
        $sql = "select * from states where country_code='". $country_code ."' " ;
        $query = $this->db->query($sql);
        $result = $query->result_array();        
		
		if($result)
		{
			$query->free_result();
			return $result;
		}
		else			
		{
			return false;
		}
    }

    function get_user_store_id($user_id)
    {
        $sql = "SELECT store_id FROM user_stores WHERE user_id ='$user_id'";
        $query = $this->db->query($sql);
        return $query->result_array();

    }
}