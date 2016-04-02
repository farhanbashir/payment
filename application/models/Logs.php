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
}