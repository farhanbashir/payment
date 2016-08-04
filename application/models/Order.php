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

    function get_all_orders_by_user($user_id, $filters=array())
    {
		$from_date = '';
		$to_date = '';
		
		if(is_array($filters) && count($filters) > 0)
		{
			if(isset($filters['from_date']))
			{
				$from_date = $filters['from_date'];
				
				if($from_date)
				{
					$from_date = date('Y-m-d', strtotime($from_date));
				}
			}
			
			if(isset($filters['to_date']))
			{
				$to_date = $filters['to_date'];
				
				if($to_date)
				{
					$to_date = date('Y-m-d', strtotime($to_date));
				}
			}
		}
		
		$arrWhere = array();
		
		if($from_date)
		{
			//-->$arrWhere[] = " ( created >= '". $from_date ."' ) ";
			
			$arrWhere[] = " ( DATE_FORMAT(created, '%Y-%m-%d') >= DATE_FORMAT('". $from_date ."', '%Y-%m-%d') ) ";
		}
		
		if($to_date)
		{
			//-->$arrWhere[] = " ( created <= '". $to_date ."' ) ";
			
			$arrWhere[] = " ( DATE_FORMAT(created, '%Y-%m-%d') <= DATE_FORMAT('". $to_date ."', '%Y-%m-%d') ) ";
		}
		
		$where = '';
		if(is_array($arrWhere) && count($arrWhere) > 0)
		{
			$where = implode(' AND ', $arrWhere);
			
			if($where)
			{
				//-->$where = ' WHERE '.$where;
				$where = ' AND  '.$where;
			}
		}
		
		$limit = ' LIMIT 0, 30 ';
		if($where)			
		{
			$limit = '';
		}
		
		
        $sql = "SELECT * 
				FROM orders 
				WHERE user_id='". $user_id ."' ". $where. " 
				ORDER BY order_id DESC ".$limit;
		
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

	

	function get_all_order_transactions_by_user($user_id)
    {
        $sql = "SELECT o.*, t.*
				FROM orders o, transactions t 
				WHERE o.order_id=t.order_id 
				  AND t.type='". CONST_TRANSACTION_TYPE_PAYMENT ."' 
				  AND o.user_id='". $user_id ."'  
				ORDER BY o.order_id DESC " ;
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
		 $sql = "select t.type, t.amount_cc, t.amount_cash, t.is_cc_swipe, t.cc_number, cx_transaction_id, cx_descriptor, t.created from transactions t 
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
						t.transaction_id, t.amount_cc, t.amount_cash, t.is_cc_swipe, 
						t.cc_name, t.cc_number, t.cc_expiry_year, t.cc_expiry_month, t.cc_code, 
						cx_transaction_id, cx_descriptor, t.app_type, t.created 
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
	
	function get_refund_transactions_by_order($order_id)
    {
		$sql = "SELECT 
						t.amount_cc, t.amount_cash, cx_transaction_id, t.created 
				FROM transactions t 
				INNER JOIN orders o ON t.order_id=o.order_id
				WHERE t.order_id='". $order_id ."' 
				  AND t.type='". CONST_TRANSACTION_TYPE_REFUND ."'";

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
	
    function getUserTransaction($params=array(),$userId)
    {   
        $params['queryForCount'] = false;

        $sql = $this->getUserTransactionQuery($params,$userId);

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getUserTransactionCount($params=array(),$userId)
    {   
        $params['queryForCount'] = true;

        $sql = $this->getUserTransactionQuery($params,$userId);

        $query = $this->db->query($sql);
        $result = $query->result_array();
        $totalRecordsCount = (int) @$result[0]['totalRecordsCount'];

        return  $totalRecordsCount;
    }

    function getUserTransactionCountWithoutFilter($params=array(),$userId)
    {   
        $params['queryForCount'] = true;

        $sql = $this->getUserTransactionQuery($params,$userId);

        $query = $this->db->query($sql);
        $result = $query->result_array();
        $totalRecordsCount = (int) @$result[0]['totalRecordsCount'];

        return  $totalRecordsCount;
    }

    function getUserTransactionQuery($params=array(), $userId)
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

        $arrayWhereClause[] = " o.order_id=t.order_id 
                                AND t.type='". CONST_TRANSACTION_TYPE_PAYMENT ."' 
                                AND o.user_id='". $userId."' ";
        
        if($searchKeyword)
        {
            $arrayWhereClause[] = " ( 
                                        o.order_id LIKE '%$searchKeyword%'
                                            OR 
                                        o.total_amount LIKE '%$searchKeyword%'
                                    ) ";
        }
       
        $whereCondition = '';
        
        if(is_array($arrayWhereClause) && count($arrayWhereClause) > 0)
        {
            $whereCondition = ' WHERE ' . implode(' AND ', $arrayWhereClause);
        }

        $select = ' o.*, t.* ';
        if($isQueryForCount)
        {
            $select     = 'COUNT(o.order_id) AS totalRecordsCount';
            
        }
        
        $sql = "SELECT 
                        $select 
                    FROM
                       orders o, transactions t
                    ". $whereCondition. " 
                    ". $order. " 
                    ". $limit. " 
                    
                    "; 
        
        return $sql;
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
