<?php
Class Logs extends CI_Model
{
    function add_api_log($data)
    {
        $this->db->insert('logs_apis',$data);
        return $this->db->insert_id();
    }

    function edit_api_log($log_id, $data)
    {
        $this->db->where('id', $log_id);
        $this->db->update('logs_apis',$data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }
	
	function add_merchant_log($data)
    {
        $this->db->insert('logs_merchant',$data);
        return $this->db->insert_id();
    }

    function edit_merchant_log($log_id, $data)
    {
        $this->db->where('id', $log_id);
        $this->db->update('logs_merchant',$data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }
	
	
	function getWebServicesLogs($params=array(), $userId=0, $storeId=0)
    {   
        $params['queryForCount'] = false;

        $sql = $this->getWebServicesLogsQuery($params, $userId, $storeId);

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function getWebServicesLogsCount($params=array(), $userId=0, $storeId=0)
    {   
        $params['queryForCount'] = true;

        $sql = $this->getWebServicesLogsQuery($params, $userId, $storeId);

        $query = $this->db->query($sql);
        $result = $query->result_array();
        $totalRecordsCount = (int) @$result[0]['totalRecordsCount'];

        return  $totalRecordsCount;
    }

    function getWebServicesLogsCountWithoutFilter($params=array(), $userId=0, $storeId=0)
    {   
        $params['queryForCount'] = true;

        $sql = $this->getWebServicesLogsQuery($params, $userId, $storeId);

        $query = $this->db->query($sql);
        $result = $query->result_array();
        $totalRecordsCount = (int) @$result[0]['totalRecordsCount'];

        return  $totalRecordsCount;
    }

    function getWebServicesLogsQuery($params=array(), $userId, $storeId)
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
        if($searchKeyword)
        {
            $arrayWhereClause[] = " ( 
                                        service LIKE '%$searchKeyword%' 
										OR 
										post_params LIKE '%$searchKeyword%' 
										OR
										response LIKE '%$searchKeyword%' 
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
            $select     = 'COUNT(id) AS totalRecordsCount';
            
        }
        
        $sql = "SELECT 
                        $select 
                    FROM
                        logs_apis 
                    ". $whereCondition. " 
                    ". $order. " 
                    ". $limit. " 
                    
                    "; 
        return $sql;
    }
}