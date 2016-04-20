<?php
class Report extends CI_Model
{	

	function get_order_summary()
    {   

        $store_id = getLoggedInStoreId();
        $sql ="SELECT DATE(created) Dates,count(store_id)AS Total_Orders FROM orders 
                WHERE created >= CURRENT_DATE - INTERVAL '1' MONTH AND created <= CURRENT_DATE AND store_id ='$store_id'
                GROUP BY DATE(created) ";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function get_sales_summary()
    {   

        $store_id = getLoggedInStoreId();
        
        $sql =" SELECT DATE(created) order_date,SUM(total_amount)AS total_sale,SUM(total_refund)AS total_refund FROM orders 
                WHERE created >= CURRENT_DATE - INTERVAL '1' MONTH AND created <= CURRENT_DATE AND store_id ='$store_id'
                GROUP BY DATE(created) ";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function get_Item_sales_summary()
    {
    	$store_id = getLoggedInStoreId();
        $sql ="	SELECT 
                t1.order_id,
                DATE(t1.created)AS order_date,
                SUM(t1.quantity) AS total_quantity,
                (SUM(t1.quantity) * t1.product_price) AS total_price, 
                t1.product_id, 
                t2.name AS product_name
                FROM order_line_items AS t1 
                LEFT JOIN products AS t2 ON t1.product_id = t2.product_id 
                WHERE 
                t1.created >= CURRENT_DATE - INTERVAL '1' MONTH AND t1.created <= CURRENT_DATE AND t2.store_id ='$store_id'
                GROUP BY product_id, t1.created
                ORDER BY DATE(t1.created)";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function get_category_sales_summary()
    {
    	$store_id = getLoggedInStoreId();
        $sql ="	SELECT DATE(t1.created) AS Dates, t2.category_id, COUNT(t2.category_id) AS Total_Sale,t3.name
				FROM order_line_items AS t1
				LEFT JOIN product_categories AS t2 ON t1.product_id = t2.product_id
				LEFT JOIN categories AS t3 ON t2.category_id = t3.category_id
				WHERE t1.created >= CURRENT_DATE - INTERVAL '1' MONTH AND t1.created <= CURRENT_DATE AND t3.store_id='$store_id'
				GROUP BY DATE(t1.created)";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

}


?>