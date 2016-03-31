<?php
Class Order extends CI_Model
{

    function get_order_detail($order_id)
    {
        $sql = "select * from orders where order_id=$order_id" ;
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

    function get_all_orders_by_user($user_id)
    {
        $sql = "select * from orders where user_id=$user_id" ;
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

    function add_order($data)
    {
        $this->db->insert('orders',$data);
        return $this->db->insert_id();
    }

    function add_order_line_item($data)
    {
        $this->db->insert('order_line_items',$data);
        return $this->db->insert_id();
    }

    function add_transaction($data)
    {
        $this->db->insert('transactions',$data);
        return $this->db->insert_id();
    }

    function edit_order($order_id, $data)
    {
        $this->db->where('order_id', $order_id);
        $this->db->update('orders',$data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    function add_order_item($data)
    {
        $this->db->insert('order_line_item',$data);
        return $this->db->insert_id();
    }
	
	
	function get_order_transactions($order_id)
    {
		 $sql = "select t.type, t.amount_cc, t.amount_cash, t.is_cc_swipe, t.cc_number, cx_transaction_id, t.created from transactions t 
inner join orders o on t.order_id=o.order_id
where t.order_id=$order_id";

        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
	
	function get_payment_transaction_by_order($order_id)
    {
		$sql = "SELECT 
						t.amount_cc, t.amount_cash, t.is_cc_swipe, t.cc_number, 
						cx_transaction_id, t.created 
				FROM transactions t 
				INNER JOIN orders o ON t.order_id=o.order_id
				WHERE t.order_id='". $order_id ."' 
				  AND t.type='". CONST_TRANSACTION_TYPE_PAYMENT ."'";

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
