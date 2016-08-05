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

   /* function get_category_detail($category_id)
    {   
       
        $sql = "SELECT * FROM categories WHERE category_id='$category_id' AND store_id = '$store_id' AND user_id = '$store_id' " ;
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
*/
    function update_category($data,$categoryId)
    {
        $this->db->where('category_id', $categoryId);
        $this->db->update('categories', $data); 
    }

    function delete_category($categoryId)
    {   
        $sql = "UPDATE categories SET status ='".CONST_STATUS_ID_DELETE."',updated = NOW() WHERE category_id='$categoryId' OR parent_id='$categoryId'";
        $this->db->query($sql);
        $sql = "DELETE FROM product_categories WHERE category_id='$categoryId'";
        $this->db->query($sql);
        
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

    function get_all_categories($userId, $storeId)
    {       
        $sql = "SELECT name,category_id,parent_id,(SELECT COUNT(category_id) FROM product_categories WHERE category_id=categories.category_id) AS total_products
                FROM categories WHERE status >0 AND user_id='$userId' AND store_id='$storeId' AND parent_id = 0 ";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
	
	function get_all_categories_for_app_listing($userId, $storeId)
    {       
        $sql = "SELECT name,category_id,parent_id,(SELECT COUNT(category_id) FROM product_categories WHERE category_id=categories.category_id) AS total_products
                FROM categories WHERE status >0 AND user_id='$userId' AND store_id='$storeId' ";
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

    function getById($categoryId, $userId, $storeId)
    {
        $sql = "SELECT name AS category_name, category_id, parent_id AS parent_category, is_default FROM categories WHERE category_id = '$categoryId' AND user_id = '$userId' AND store_id = '$storeId'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result[0];
    }     
	
	function getDefaultCategory($userId, $storeId)
    {
        $sql = "SELECT name AS category_name, category_id, parent_id AS parent_category, is_default FROM categories WHERE user_id = '$userId' AND store_id = '$storeId' AND is_default='1' LIMIT 1";
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

    function getCategory($params=array(), $userId, $storeId)
    {   
        $params['queryForCount'] = false;

        $sql = $this->getCategoryQuery($params, $userId, $storeId);

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getCategoryCount($params=array(), $userId, $storeId)
    {   
        $params['queryForCount'] = true;

        $sql = $this->getCategoryQuery($params, $userId, $storeId);

        $query = $this->db->query($sql);
        $result = $query->result_array();
        $totalRecordsCount = (int) @$result[0]['totalRecordsCount'];

        return  $totalRecordsCount;
    }

    function getCategoryCountWithoutFilter($params=array(), $userId, $storeId)
    {   
        $params['queryForCount'] = true;

        $sql = $this->getCategoryQuery($params, $userId, $storeId);

        $query = $this->db->query($sql);
        $result = $query->result_array();
        $totalRecordsCount = (int) @$result[0]['totalRecordsCount'];

        return  $totalRecordsCount;
    }

    function getCategoryQuery($params=array(), $userId, $storeId)
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

            //-->$limit = "LIMIT ".intval($offset).", ".intval(CONST_PAGINATION_LIMIT); //UJ: We are displaying all categories due to tree view!
        }

        $arrayWhereClause = array();

        $arrayWhereClause[] = " t1.status >0 AND t1.user_id='$userId' AND t1.store_id='$storeId' ";
        
        if($searchKeyword)
        {
            $arrayWhereClause[] = " ( 
                                        t1.name LIKE '%$searchKeyword%'
                                    ) ";
        }

        $whereCondition = '';
        
        if(is_array($arrayWhereClause) && count($arrayWhereClause) > 0)
        {
            $whereCondition = ' WHERE ' . implode(' AND ', $arrayWhereClause);
        }

        $select = 't1.category_id,t1.name,t1.category_id,t1.parent_id,t1.is_default,
                    (SELECT COUNT(category_id) FROM product_categories 
                    WHERE category_id=t1.category_id) AS total_products, 
                    t2.name AS parent_category
                    FROM categories AS t1
                    LEFT JOIN categories AS t2
                    ON t1.parent_id = t2.category_id ';
        if($isQueryForCount)
        {
            $select = ' COUNT(t1.user_id) AS totalRecordsCount FROM categories AS t1';
        }
        
		$sql = "SELECT 
                        $select 
                    
                    ". $whereCondition. " 
                    ". $order. " 
                    ". $limit. " 

                    "; 
        
        return $sql;
    }
}
?>
