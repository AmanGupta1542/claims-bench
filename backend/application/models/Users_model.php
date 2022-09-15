<?php

class Users_model extends CI_Model
{
    public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->helper('string');
    }
   
    public function check_login($email)
    {
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where('email', $email);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function insert($table,$data){

        return $this->db->insert($table, $data);
    }

    public function get_users($table){

        $this->db->select("name,email,mobile,username,role,created_at");
        $this->db->from($table);
        $query = $this->db->get();
        return $query->result();
    }

    public function getUserProfile($table,$user_email)
    {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where('email', $user_email);
        $query = $this->db->get();

        return $query->result_array()[0];
    }

    public function delete($table,$user_id){

        // delete method
        $this->db->where("id", $user_id);
        return $this->db->delete($table);
    }

    public function update($table,$user_id, $user_info){

        $this->db->where("id", $user_id);
        return $this->db->update($table, $user_info);
    }

  
    
}

?>