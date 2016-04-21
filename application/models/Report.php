<?php
class Report extends CI_Model
{	

	function get_order_summary($FormData)
    {   
        $where_clause = '';
        
        if($FormData!='')
        {
            $where_clause = $this->filter_where_clause($FormData);
        }

        $store_id = getLoggedInStoreId();
        if($where_clause=='')
        {
            $sql =" SELECT
            DATE(t1.created) order_date,COUNT(t1.store_id) AS total_order,
            COUNT((SELECT total_refund FROM order_line_items WHERE DATE(created)=order_date AND total_refund>0 GROUP BY DATE(order_date))) AS total_refund
            FROM orders AS t1
            WHERE created >= CURRENT_DATE - INTERVAL '1' MONTH AND created <= CURRENT_DATE AND t1.store_id ='$store_id'
            GROUP BY DATE(created) ";
        }
        else
        {
            $sql =" SELECT
            DATE(t1.created) order_date,COUNT(t1.store_id) AS total_order,
            COUNT((SELECT total_refund FROM order_line_items WHERE DATE(created)=order_date AND total_refund>0 GROUP BY DATE(order_date))) AS total_refund
            FROM orders AS t1
            WHERE ".$where_clause." AND t1.store_id ='$store_id'
            GROUP BY DATE(created) ";
        }

        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function get_sales_summary($FormData)
    {   
        $where_clause = '';
        
        if($FormData!='')
        {
            $where_clause = $this->filter_where_clause($FormData);
        }

        $store_id = getLoggedInStoreId();
        
        if($where_clause=='')
        {
            $sql =" SELECT DATE(t1.created) sale_date,SUM(t1.total_amount)AS total_sale,SUM(t1.total_refund)AS total_refund FROM orders AS t1 
            WHERE t1.created >= CURRENT_DATE - INTERVAL '1' MONTH AND t1.created <= CURRENT_DATE AND t1.store_id ='$store_id'
            GROUP BY DATE(created) ";
        }
        else
        {
            $sql="  SELECT DATE(t1.created) sale_date,SUM(t1.total_amount)AS total_sale,SUM(t1.total_refund)AS total_refund FROM orders AS t1 
            WHERE ".$where_clause." AND t1.store_id ='$store_id'
            GROUP BY DATE(created) ";
        }

        

        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function get_Item_sales_summary($FormData)
    {   
        $where_clause = '';
        
        if($FormData!='')
        {
            $where_clause = $this->filter_where_clause($FormData);
        }

    	$store_id = getLoggedInStoreId();
        if($where_clause=='')
        {
            $sql ="	SELECT 
            t1.order_id,
            DATE(t1.created)AS order_date,
            SUM(t1.quantity) AS total_quantity,
            (SUM(t1.quantity) * t1.product_price) AS total_price, 
            t1.product_id, 
            t2.name AS product_name,
            (SELECT category_id FROM product_categories WHERE product_id = t1.product_id AND store_id = '$store_id' GROUP BY product_id) AS category_ids,
            (SELECT `name` FROM categories WHERE category_id = category_ids AND store_id = '$store_id') AS category_name
            FROM order_line_items AS t1 
            LEFT JOIN products AS t2 ON t2.product_id = t1.product_id
            WHERE 
            t1.created >= CURRENT_DATE - INTERVAL '1' MONTH AND t1.created <= CURRENT_DATE AND t2.store_id ='$store_id'
            GROUP BY t1.product_id, t1.created
            ORDER BY DATE(t1.created)";
        }
        else
        {
            $sql =" SELECT 
            t1.order_id,
            DATE(t1.created)AS order_date,
            SUM(t1.quantity) AS total_quantity,
            (SUM(t1.quantity) * t1.product_price) AS total_price, 
            t1.product_id, 
            t2.name AS product_name,
            (SELECT category_id FROM product_categories WHERE product_id = t1.product_id AND store_id = '$store_id' GROUP BY product_id) AS category_ids,
            (SELECT `name` FROM categories WHERE category_id = category_ids AND store_id = '$store_id') AS category_name
            FROM order_line_items AS t1 
            LEFT JOIN products AS t2 ON t2.product_id = t1.product_id
            WHERE 
            ".$where_clause." AND t2.store_id ='$store_id'
            GROUP BY t1.product_id, t1.created
            ORDER BY DATE(t1.created)";
        }
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function get_category_sales_summary($FormData)
    {   
        $where_clause = '';
        
        if($FormData!='')
        {
            $where_clause = $this->filter_where_clause($FormData);
        }

    	$store_id = getLoggedInStoreId();

        if($where_clause =='')
        {
            $sql ="	SELECT DATE(t1.created) AS order_date, t2.category_id, COUNT(t2.category_id) AS total_quantity, SUM(t1.product_price) AS total_sale,t3.name AS category_name
            FROM order_line_items AS t1
            LEFT JOIN product_categories AS t2 ON t1.product_id = t2.product_id
            LEFT JOIN categories AS t3 ON t2.category_id = t3.category_id
            WHERE t1.created >= CURRENT_DATE - INTERVAL '1' MONTH AND t1.created <= CURRENT_DATE AND t3.store_id='$store_id'
            GROUP BY DATE(t1.created)";
        }
        else
        {
            $sql =" SELECT DATE(t1.created) AS order_date, t2.category_id, COUNT(t2.category_id) AS total_quantity, SUM(t1.product_price) AS total_sale,t3.name AS category_name
            FROM order_line_items AS t1
            LEFT JOIN product_categories AS t2 ON t1.product_id = t2.product_id
            LEFT JOIN categories AS t3 ON t2.category_id = t3.category_id
            WHERE ".$where_clause." AND t3.store_id='$store_id'
            GROUP BY DATE(t1.created)"; 
        }
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function get_sales_summary_by_value($FormData)
    {   
        $store_id = getLoggedInStoreId();
        
        $sql =" SELECT DATE(t1.created) AS order_date, t2.category_id, COUNT(t2.category_id) AS total_quantity, SUM(t1.product_price) AS total_sale,t3.name AS category_name
        FROM order_line_items AS t1
        LEFT JOIN product_categories AS t2 ON t1.product_id = t2.product_id
        LEFT JOIN categories AS t3 ON t2.category_id = t3.category_id
        WHERE ".$where_clause."
        GROUP BY DATE(t1.created)";

        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;

    }

    function filter_where_clause($FormData)
    {   

        $where_clause ='';

        if($FormData['FromDate']!='' AND $FormData['ToDate']!='')
        {
            $FromDate   = date("Y-m-d",strtotime($FormData['FromDate']));

            $ToDate     = date("Y-m-d",strtotime($FormData['ToDate']));

            $where_clause = "t1.created >= '$FromDate' AND t1.created <= '$ToDate'";
        }

        if($FormData['FromDate']!='' AND $FormData['ToDate']=='')
        {
            $FromDate   = date("Y-m-d",strtotime($FormData['FromDate']));

            $where_clause = "t1.created = '$FromDate'";
        }

        if($FormData['FromDate']=='' AND $FormData['ToDate']!='')
        {
            $toDate   = date("Y-m-d",strtotime($FormData['FromDate']));

            $where_clause = "t1.created = '$toDate'";
        }

        if($where_clause=='')
        {
            if($FormData!='0')
            {
                if($FormData['select']=='Daily')
                {
                    $where_clause = "t1.created =CURRENT_DATE";
                }

                if($FormData['select']=='Weekly')
                {
                    $where_clause = "t1.created >= CURRENT_DATE - INTERVAL '7' DAY AND t1.created <= CURRENT_DATE";
                }

                if($FormData['select']=='Monthly')
                {
                    $where_clause = "t1.created >= CURRENT_DATE - INTERVAL '1' MONTH AND t1.created <= CURRENT_DATE";
                }
            }
        }

        return $where_clause;
    }

}


?>