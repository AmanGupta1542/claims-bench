<?php

class Subscriber_model extends CI_Model{	
	public function __construct(){
     $this->load->database();
	}

	public function getData($table='',$where='')
	{
      if(!empty($where)){
      	$this->db->where($where);
      }
      $res = $this->db->get($table);
      return $res->result();
	}
	public function insertData($table='',$data='')
	{
		if(!empty($data) && !empty($table)){
          $this->db->insert($table,$data);
          return $this->db->insert_id();
		}else{
			return false;
		}
	}
}
?>