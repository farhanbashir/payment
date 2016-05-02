<?php
Class Device extends CI_Model
{

    function get_total_devices()
    {
        return $this->db->count_all('devices');
    }

    function get_all_devices()
    {
        $sql = "select * from devices" ;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function validToken($user_id, $token)
    {
        $this -> db -> select('*');
        $this -> db -> from('devices');
        $this -> db -> where('user_id', $user_id);
        $this -> db -> where('token', $token);
        //$this -> db -> where('password', $password);
        $this -> db -> limit(1);

        $query = $this -> db -> get();

        if($query -> num_rows() == 1)
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

    function get_iphone_devices()
    {
        $sql = "select * from devices where `type`=0" ;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function get_android_devices()
    {
        $sql = "select * from devices where `type`=1" ;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function get_user_device($user_id, $uid)
    {
        $sql = "select * from devices where user_id=$user_id and uid='$uid'" ;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    function edit_device($user_id,$data)
    {
        $this->db->where('user_id', $user_id);
        $this->db->update('devices',$data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    function delete_device($user_id,$token)
    {
        return $this->db->delete('devices', array('user_id' => $user_id,"token"=>$token));
    }

    function insert_device($data)
    {
        $this->db->insert('devices',$data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }
}
