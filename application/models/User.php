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
        /*$this -> db -> select('user_id, password, first_name, last_name, email, role_id, status');
        $this -> db -> from('users');
        $this -> db -> where('user_id', $user_id);
        $this -> db ->join('user_stores', 'user_stores.user_id = users.user_id',left);*/
        //$this -> db -> where('is_admin', $is_admin);
        //$this -> db -> where('password', $password);
        //$this -> db -> limit(1);

        //$query = $this -> db -> get();

        $SQL = "SELECT t1.user_id,t1.password,t1.first_name,t1.last_name,t1.email,t1.role_id,t1.status,t2.store_id
                FROM users AS t1
                LEFT JOIN user_stores AS t2 ON t2.user_id = t1.user_id
                WHERE t1.user_id ='$user_id'";

        $query = $this -> db -> query($SQL);

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
        $sql = "select * from users u where u.role_id=".CONST_ROLE_ID_BUSINESS_ADMIN." order by u.user_id desc " ;
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

    /*function test_ajax($_GET,$countRows=false)
    {   
        $sql = $this->

        $sql = "SELECT * from users ".$where." ".$order." ".$limit;

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function test_ajax_count($where)
    {
        $sql = "SELECT user_id as total_rows from users ".$where;
        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
        {
            return $query->num_rows();
        }
    }*/

    function getUsers($params=array())
    {   
        $params['queryForCount'] = false;

        $sql = $this->getUsersQuery($params);

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getUsersCount($params=array())
    {   
        $params['queryForCount'] = true;

        $sql = $this->getUsersQuery($params);

        $query = $this->db->query($sql);
        $result = $query->result_array();
        $totalRecordsCount = (int) @$result[0]['totalRecordsCount'];

        return  $totalRecordsCount;
    }

    function getUsersCountWithoutFilter($params=array())
    {   
        $params['queryForCount'] = true;

        $sql = $this->getUsersQuery($params);
    
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $totalRecordsCount = (int) @$result[0]['totalRecordsCount'];

        return  $totalRecordsCount;
    }

    function getUsersQuery($params=array())
    {
       $offset              = @$params['offset'];
       $searchKeyword       = @$params['search_keyword'];
       $sortColumn          = @$params['sort_column'];
       $sortOrderDirection  = @$params['sort_direction'];
       $isQueryForCount     = @$params['queryForCount'];
      

       $order = '';
       $limit = '';

        if (!$isQueryForCount)
        {
            if($sortColumn && $sortOrderDirection)
            {           
                $order = "ORDER BY ".$sortColumn." ".$sortOrderDirection;
            }

            $limit = "LIMIT ".intval($offset).", ".intval(CONST_PAGINATION_LIMIT);
        }

        $arrayWhereClause = array();

        $arrayWhereClause[] = " (role_id = '". CONST_ROLE_ID_BUSINESS_ADMIN ."') ";
        
        if($searchKeyword)
        {
            $arrayWhereClause[] = " ( 
                                        first_name LIKE '%$searchKeyword%'
                                            OR 
                                        last_name LIKE '%$searchKeyword%'
                                            OR
                                        email LIKE '%$searchKeyword%'
                                    ) ";
        }

        $whereCondition = '';
        
        if(is_array($arrayWhereClause) && count($arrayWhereClause) > 0)
        {
            $whereCondition = ' WHERE ' . implode(' AND ', $arrayWhereClause);
        }

        $select = ' * ';
        if($isQueryForCount)
        {
            $select = ' COUNT(user_id) AS totalRecordsCount ';
        }
        
        $sql = "SELECT 
                        $select 
                    FROM 
                            users
                    ". $whereCondition. " 
                    ". $order. " 
                    ". $limit. " 

                    "; 
        
        return $sql;
    }


}