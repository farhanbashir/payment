<?php
Class Customer extends CI_Model
{	
	function checkCustomerByEmail($email=0)
    {
        $this->db->select('*');
        $this->db->from('customers');
        $this->db->where('email', $email);
        $this->db->limit(1);

        $query = $this->db->get();

        if($query->num_rows() == 1)
        {	
			$result = $query->result_array();
			$query->free_result();
			return $result[0];
        }
        else
        {
            return false;
        }
    }
	
    function edit_customer($customer_id, $data)
    {
        $this->db->where('customer_id', $customer_id);
        $this->db->update('customers',$data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    function add_customer($data)
    {
        $this->db->insert('customers',$data);
        return $this->db->insert_id();
    }
}
