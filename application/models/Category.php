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

    function update_category($data,$id)
    {
        $this->db->where('category_id', $id);
        $this->db->update('categories', $data); 
    }

    function delete_category($id)
    {
        $this->db->where('category_id', $id);
        $this->db->delete('categories');
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

    function get_all_categories()
    {
        
        $user_id = getLoggedInUserId();
        $sql = "SELECT name,category_id,(SELECT COUNT(category_id) FROM product_categories WHERE category_id=categories.category_id) AS total_products
                FROM categories WHERE user_id='$user_id'";
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

    

    function edit_category($category_id)
    {   
        $sql = "SELECT t1.name AS category, t2.name AS parent_category,t1.parent_id AS parent_category_id 
                FROM categories AS t1 LEFT JOIN categories AS t2 ON t2.category_id = t1.parent_id WHERE t1.category_id ='$category_id'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
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
}
?>
