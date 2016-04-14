<?php 
Class Setting extends CI_Model
{
	function get_user_basic_info()
	{
		$user_id = getLoggedInUserId();
		$sql = "SELECT t1.user_id,t1.first_name,t1.last_name,t1.email,t2.security_question_id,t2.security_answer
				FROM users AS t1
				LEFT JOIN user_details AS t2
				ON t2.user_id = t1.user_id
				WHERE t1.user_id= '$user_id'";
		$query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
	}

	function get_user_bank_info()
	{	
		$user_id = getLoggedInUserId();
		$sql = "SELECT * FROM user_banks WHERE user_id='$user_id'";
		$query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
	}

	function get_security_questions()
	{	
		
		$sql = "SELECT * FROM security_questions";
		$query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;	
	}

	function update_basic_info($data)
	{	
		$user_id = getLoggedInUserId();
		$this->db->where('user_id', $user_id);
        $this->db->update('users', $data);
	}

	function update_business_info($data)
	{	
		$user_id = getLoggedInUserId();
		$this->db->where('user_id', $user_id);
        $this->db->update('user_stores', $data);
	}

	function update_security_info($ArrSeurityInfo)
	{
		$user_id = getLoggedInUserId();
		$this->db->where('user_id', $user_id);
        $this->db->update('user_details', $ArrSeurityInfo);
	}

	function get_user_business_info()
	{
		$user_id = getLoggedInUserId();
		$sql = "SELECT * FROM user_stores WHERE user_id='$user_id'";
		$query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
	}

	function update_bank_info($data)
	{
		$user_id = getLoggedInUserId();
		$this->db->where('user_id', $user_id);
        $this->db->update('user_banks', $data);
	}
}

?>