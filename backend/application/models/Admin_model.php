<?php

class Admin_model extends CI_Model{

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