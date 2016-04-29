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

    function get_all_products($storeId=0,$userId=0)
    {       
		$sql = "SELECT pc.product_id,pc.category_id,p.price,p.name AS product_name, c.name AS category_name FROM 
                    product_categories AS pc
                    LEFT JOIN products AS p
                    ON pc.product_id = p.product_id
                    LEFT JOIN categories AS c
                    ON pc.category_id = c.category_id
                    WHERE p.user_id ='$userId' AND p.store_id='$storeId'" ;
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

    function add_product_categories($arrCategoryIds,$productId=0)
    {
        for ($i=0; $i <count($arrCategoryIds) ; $i++) 
        {   
            $this->db->insert('product_categories',array("product_id" => $productId,"category_id" => $arrCategoryIds[$i]));
        }
    }

    function delete_product($productId)
    {
        $this->db->where('product_id', $productId);
        $this->db->delete('products');
        $this->db->where('product_id', $productId);
        $this->db->delete('product_categories');

    }

    function getById($productId=0, $userId=0, $storeId=0)
    {
		$sql = "    SELECT pc.product_id,pc.category_id,p.price,p.name AS product_name,p.description, c.name AS category_name FROM 
                    product_categories AS pc
                    LEFT JOIN products AS p
                    ON pc.product_id = p.product_id
                    LEFT JOIN categories AS c
                    ON pc.category_id = c.category_id
                    WHERE p.product_id ='$productId' AND p.user_id ='$userId' AND p.store_id='$storeId'";
        
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function update_product_categories($arrCategoryIds,$product_id)
    {   
        $this->db->where('product_id', $product_id);
        $this->db->delete('product_categories');
       
        for ($i=0; $i <count($arrCategoryIds) ; $i++) 
        {   
            $this->db->insert('product_categories',array("product_id" => $product_id,"category_id" => $arrCategoryIds[$i]));
        }
    }

    /*function update_product($data,$product_id)
    {
        $this->db->where('product_id', $product_id);
        $this->db->update('products', $data);
    }*/

    function edit_product($productId, $data)
    {
        $this->db->where('product_id', $productId);
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

    function getProducts($params=array(),$userId, $storeId)
    {   
        $params['queryForCount'] = false;

        $sql = $this->getProductQuery($params,$userId,$storeId);

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getProductsCount($params=array(),$userId, $storeId)
    {   
        $params['queryForCount'] = true;

        $sql = $this->getProductQuery($params,$userId,$storeId);

        $query = $this->db->query($sql);
        $result = $query->result_array();
        $totalRecordsCount = (int) @$result[0]['totalRecordsCount'];

        return  $totalRecordsCount;
    }

    function getProductsCountWithFilter($params=array(),$userId, $storeId)
    {   
        $params['queryForCount'] = true;

        $sql = $this->getProductQuery($params,$userId,$storeId);

        $query = $this->db->query($sql);
        $result = $query->result_array();
        $totalRecordsCount = (int) @$result[0]['totalRecordsCount'];

        return  $totalRecordsCount;
    }

    function getProductQuery($params=array(), $userId, $storeId)
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

        $arrayWhereClause[] = " user_id ='$userId' AND store_id='$storeId' ";
        
        if($searchKeyword)
        {
            $arrayWhereClause[] = " ( 
                                        name LIKE '%$searchKeyword%'
                                            OR 
                                        price LIKE '%$searchKeyword%'
                                    ) ";
        }
       
        $whereCondition = '';
        
        if(is_array($arrayWhereClause) && count($arrayWhereClause) > 0)
        {
            $whereCondition = ' WHERE ' . implode(' AND ', $arrayWhereClause);
        }

        $select = '*';
        if($isQueryForCount)
        {
            $select     = 'COUNT(product_id) AS totalRecordsCount';
            
        }
        
        $sql = "SELECT 
                        $select 
                    FROM
                        products
                    ". $whereCondition. " 
                    ". $order. " 
                    ". $limit. " 
                    
                    "; 
        
        return $sql;
    }

    function getProductCategory($productId, $userId, $storeId)
    {
        $sql  ="    SELECT p.product_id, GROUP_CONCAT(c.name SEPARATOR ', ') AS category_name 
                    FROM product_categories AS pc 
                    LEFT JOIN products AS p ON pc.product_id = p.product_id 
                    LEFT JOIN categories AS c ON pc.category_id = c.category_id 
                    WHERE p.user_id ='$userId' AND p.store_id='$storeId' AND p.product_id = '$productId'";
        
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $category_name = @$result[0]['category_name'];
        return $category_name;
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
