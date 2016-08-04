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
		/*
		$sql = "SELECT pc.product_id,pc.category_id,p.price,p.name AS product_name, c.name AS category_name FROM 
                    product_categories AS pc
                    LEFT JOIN products AS p
                    ON pc.product_id = p.product_id
                    LEFT JOIN categories AS c
                    ON pc.category_id = c.category_id
                    WHERE p.user_id ='$userId' AND p.store_id='$storeId'" ;
		*/
		
		$sql = "SELECT p.* 
				FROM products p
				WHERE p.user_id ='$userId' AND p.store_id='$storeId'" ;
		
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
	
	function get_all_active_products($storeId=0,$userId=0)
    {
		/*
		$sql = "SELECT pc.product_id,pc.category_id,p.price,p.name AS product_name, c.name AS category_name FROM 
                    product_categories AS pc
                    LEFT JOIN products AS p
                    ON pc.product_id = p.product_id
                    LEFT JOIN categories AS c
                    ON pc.category_id = c.category_id
                    WHERE p.user_id ='$userId' AND p.store_id='$storeId'" ;
		*/
		
		$sql = "SELECT p.* 
				FROM products p 
				WHERE p.user_id ='$userId' AND p.store_id='$storeId' AND status NOT IN (". CONST_STATUS_ID_DELETE .") 
				ORDER BY product_id DESC"  ;
		
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
		$_arrFilterCategories = array();
		if($category_id)
		{
			$filterCategories = $this->getAllCategoriesByCategoryId($category_id);
			if($filterCategories)
			{
				foreach ($filterCategories as $row)
				{	
					$_arrFilterCategories[] = $row['category_id'];		
				}
			}
		}
		
		if(is_array($_arrFilterCategories) && count($_arrFilterCategories) > 0)
        {
			$sql = "select * from products where product_id IN (select product_id from product_categories where category_id IN (".implode(',', $_arrFilterCategories)." )) and status=1 ORDER BY product_id DESC" ;
        }
		else
		{
			$sql = "select * from products where product_id IN (select product_id from product_categories where category_id=$category_id) and status=1 ORDER BY product_id DESC" ;
		}		
        
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
        $sql = "UPDATE products SET status ='".CONST_STATUS_ID_DELETE."',updated = NOW() WHERE product_id='$productId'";
        $this->db->query($sql);
		$this->db->where('product_id', $productId);
        $this->db->delete('product_categories');

    }

    function getAllCategoriesByCategoryId($categoryId)
    {
        $sql    = "SELECT * FROM categories WHERE category_id = '$categoryId' OR parent_id ='$categoryId'";
        $query  = $this->db->query($sql);
        $result = $query->result_array();

        $query->free_result();
        return $result;
    }

    function getById($productId=0, $userId=0, $storeId=0)
    {
		/*
		$sql = "    SELECT pc.product_id,pc.category_id,p.price,p.name AS product_name,p.description, c.name AS category_name, pm.file_name FROM 
                    product_categories AS pc
                    LEFT JOIN products AS p
                    ON pc.product_id = p.product_id
                    LEFT JOIN categories AS c
                    ON pc.category_id = c.category_id
                    LEFT JOIN product_media AS pm
                    on pm.product_id = p.product_id
                    WHERE p.product_id ='$productId' AND p.user_id ='$userId' AND p.store_id='$storeId'";
		*/
		
		$sql = "SELECT * 
				FROM products p 
				WHERE p.product_id ='$productId'
  				  AND p.user_id ='$userId' 
				  AND p.store_id='$storeId' 
				LIMIT 1";
        
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

    function getProducts($params=array(), $userId=0, $storeId=0)
    {   
        $params['queryForCount'] = false;

        $sql = $this->getProductQuery($params, $userId, $storeId);

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getProductsCount($params=array(), $userId=0, $storeId=0)
    {   
        $params['queryForCount'] = true;

        $sql = $this->getProductQuery($params, $userId, $storeId);

        $query = $this->db->query($sql);
        $result = $query->result_array();
        $totalRecordsCount = (int) @$result[0]['totalRecordsCount'];

        return  $totalRecordsCount;
    }

    function getProductsCountWithoutFilter($params=array(), $userId=0, $storeId=0)
    {   
        $params['queryForCount'] = true;

        $sql = $this->getProductQuery($params, $userId, $storeId);

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
       $filterCategory      = @$params['filter_category'];
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

        $arrayWhereClause[] = " p.user_id ='$userId' AND p.store_id='$storeId' AND p.status >0 ";
        if($filterCategory)
        {
            //-->$arrayWhereClause[] = "(pc.category_id IN (".implode(',', $filterCategory)."))";
			$arrayWhereClause[] = " ( p.product_id IN ( select product_id from product_categories where category_id IN (".implode(',', $filterCategory)." )) ) ";
        }
        if($searchKeyword)
        {
            $arrayWhereClause[] = " ( 
                                        p.name LIKE '%$searchKeyword%'
                                            OR 
                                        p.price LIKE '%$searchKeyword%'
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
            $select     = 'COUNT(p.product_id) AS totalRecordsCount';
            
        }
        
        $sql = "SELECT 
                        $select 
                    FROM
                        products AS p 
                    ". $whereCondition. " 
                    ". $order. " 
                    ". $limit. " 
                    
                    "; 
        return $sql;
    }
	
	function get_all_categories($userId, $storeId)
    {
        $sql = "SELECT 
						name, category_id, parent_id,(SELECT COUNT(category_id) FROM product_categories WHERE category_id=categories.category_id) AS total_products
                FROM categories 
				WHERE status >0 AND user_id='$userId' AND store_id='$storeId' ";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function getProductCategory_NOT_IN_USE($productId, $userId, $storeId)
    {
        $sql  ="    SELECT p.product_id, GROUP_CONCAT(c.name SEPARATOR ', ') AS category_name, pm.file_name
                    FROM product_categories AS pc 
                    LEFT JOIN products AS p ON pc.product_id = p.product_id 
                    LEFT JOIN categories AS c ON pc.category_id = c.category_id 
                    LEFT JOIN product_media AS pm ON pm.product_id = p.product_id 
                    WHERE p.user_id ='$userId' AND p.store_id='$storeId' AND p.product_id = '$productId'";
        
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $category_name = @$result[0];
        return $category_name;
    }
	
	function getProductCategories($productId=0)
    {
		$sql = "SELECT c.category_id, c.name, c.parent_id  
				FROM product_categories pc, categories c 
				WHERE pc.category_id=c.category_id AND pc.product_id='". $productId ."' ";
        
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
	
	function getProductImages($productId=0)
    {
		$sql = "SELECT file_name AS media_path, media_type,media_id
				FROM product_media 
				WHERE product_id='". $productId ."' ";
        
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function delete_product_media($productId = 0, $mediaId = 0)
    {
        if($productId)
        {
            $this->db->where('product_id', $productId);
        }
        if($mediaId)
        {
            $this->db->where('media_id', $mediaId);
        }

        $this->db->delete('product_media');      
    }
	
    function checkMediaByProductId($productId = 0,$mediaId = 0)
    {
        $sql = "SELECT * from product_media where product_id = '$productId' and media_id ='$mediaId'";
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
