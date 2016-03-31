<?php
Class Product extends CI_Model
{

    function get_product_detail($product_id)
    {
        $sql = "select * from products where product_id=$product_id" ;
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

    function get_all_products($store_id=0)
    {
		if($store_id)
		{
			$sql = "select * from products where status=1 and store_id=$store_id" ;
		}
		else
		{
			$sql = "select * from products where status=1 AND product_id!='". CONST_PRODUCT_ID_NUMPAD ."'" ;
		}
        
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function get_order_products($order_id)
    {
        $sql = "select ol.product_id,p.name,ol.quantity, ol.product_price from order_line_items ol 
inner join products p on ol.product_id=p.product_id
where ol.order_id=$order_id";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function get_products_by_category($category_id)
    {
        $sql = "select * from products where product_id IN (select product_id from product_categories where category_id=$category_id) and status=1" ;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function add_product($data)
    {
        $this->db->insert('products',$data);
        return $this->db->insert_id();
    }

    function edit_product($product_id, $data)
    {
        $this->db->where('product_id', $product_id);
        $this->db->update('products',$data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    function add_product_media($data)
    {
        $this->db->insert('product_media',$data);
        return $this->db->insert_id();
    }

    function edit_product_media($product_id, $data)
    {
        $this->db->where('product_id', $product_id);
        $this->db->update('product_media',$data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }
    /*function get_user_detail($user_id)
    {
        $sql = "select * from users where user_id=$user_id" ;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result[0];
    }

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
    }*/

    
}
