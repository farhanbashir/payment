<?php
Class Category extends CI_Model
{

    function categorPresent($category, $parent_id, $store_id)
    {
        $this -> db -> select('*');
        $this -> db -> from('categories');
        $this -> db -> where('name', $category);
        $this -> db -> where('store_id', $store_id);
        $this -> db -> where('parent_id', $parent_id);
        $this -> db -> limit(1);

        $query = $this -> db -> get();

        if($query -> num_rows() == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function get_category_detail($category_id)
    {
        $sql = "select * from categories where category_id=$category_id" ;
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

    function delete_all_categories_for_product($product_id)
    {
        $sql = "delete from product_categories where product_id=$product_id";
        $query = $this->db->query($sql);
    }

    function get_all_categories_for_product($product_id)
    {
        $sql = "select * from product_categories where product_id=$product_id" ;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;   
    }

    function get_all_categories($store_id)
    {
        $sql = "select * from categories where status=1 and store_id=$store_id" ;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function add_category($data)
    {
        $this->db->insert('categories',$data);
        return $this->db->insert_id();
    }

    function edit_category($category_id, $data)
    {
        $this->db->where('category_id', $category_id);
        $this->db->update('categories',$data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    function add_product_category($data)
    {
        $this->db->insert('product_categories',$data);
        return $this->db->insert_id();
    }

    function edit_product_category($product_category_id, $data)
    {
        $this->db->where('product_category_id', $category_id);
        $this->db->update('product_categories',$data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    /*function get_admin()
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

    */
}
