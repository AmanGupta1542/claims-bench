<?php

class Admin_model extends CI_Model{

const FAILED = 0;
const SUCCESS = 1; 
const CURRPASS_NOT_MATCH = 2;
const NOT_MATCH = 3;
const USER_NOT_FOUND = 4;

  public function __construct(){
    parent::__construct();
    $this->load->database();
    $this->load->helper('string');
  }

  public function get_users($table){

    $this->db->select("*");
    $this->db->from($table);
    $query = $this->db->get();

    return $query->result();
  }

  public function get_userById($table,$id){

    $this->db->select("*");
    $this->db->from($table);
    $this->db->where("id", $id);
    $query = $this->db->get();

    return $query->result();
  }

   public function insert_user($table,$data){

       return $this->db->insert($table, $data);
   }

   public function delete_user($table,$user_id){

     // delete method
     $this->db->where("id", $user_id);
     return $this->db->delete($table);
   }

   public function update_user($table,$user_id, $user_info){

      $this->db->where("id", $user_id);
      return $this->db->update("admin", $user_info);
   }

  public function CheckCredential($email) 
    {
     $this->db->select('*');
     $this->db->from('admin');
     $this->db->where('email', $email);
     $this->db->limit(1);
     $query = $this ->db-> get();
     if($query -> num_rows() == 1)
     {
       return $query->result();
     }
     else
     {
       return false;
     }     
    }

}

 ?>