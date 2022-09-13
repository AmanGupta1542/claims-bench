<?php

class AuthModel extends CI_Model
{

    public function check_login($email)
    {
        $this->db->select('*');
        $this->db->from('admin');
        $this->db->where('email', $email);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function signup($table,$data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function authUserEmail($table,$user_email)
    {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where('email', $user_email);
        $query = $this->db->get();

        return $query->result_array()[0];
    }

    public function getUsers($table,$id = "")
    {
        
        if (!empty($id)) {
            $this->db->select('*');
            $this->db->from($table);
            $this->db->where('id', $id);
            $query = $this->db->get();

            return $query->result_array();
        } else {

            $this->db->select('*');
            $this->db->from($table);
            $query = $this->db->get();

            return $query->result_array();
        }
    }

}
