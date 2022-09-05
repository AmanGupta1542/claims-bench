<?php
class Auth_model extends CI_Model{
  
   const FAILED = 0;
   const SUCCESS = 1;
   const CURRPASS_NOT_MATCH = 2;
   const NOT_MATCH = 3;
   const USER_NOT_FOUND = 4;

   public function __construct()
   {
      $this->load->database();
      $this->load->helper('string');
   }
   public function CheckCredential($email)
    {
     $this->db->select('*');
     $this->db->from('users');
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

    public function insert_user($data = array()){

       return $this->db->insert("users", $data);
   }

    public function Register_model($data)
    {
      
      if(!empty($data))
      {
        
        $already = $this->db->where('email',$data[2])->get('users')->result();
        if(@count($already) == 0)
        {
          $password = password_hash($data[5], PASSWORD_BCRYPT);
          date_default_timezone_set("America/New_York");
          $created_date = date('Y-m-d H:i:s', time());
          $data_insert = array(
            'first_name' => $data[0],
            'last_name' => $data[1],
            'username' => $data[0]." ".$data[1],
            'email' => $data[2],
            'mobile_number' => $data[3],
            'language' => $data[4],          
            'password' => $password
            
          );
          $res = $this->db->insert('users',$data_insert);
     
        }
        else
          return 0;
      }
      else
        echo "no data";
    }

    public function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }

    public function getData($table='',$where='')
    {
       if(!empty($where)){
        $this->db->where($where);
        $res = $this->db->get($table);  
        return ($res->num_rows())? $res->result() : false;
       }else{
        return false;
       }
    }
    public function updateData($table='',$data='',$where='')
    {
      if(!empty($where) && !empty($data)){
        $this->db->where($where);
        $this->db->update($table,$data);
        if($this->db->affected_rows()>0){
          return true;
        }else{
          return false;
        }
      }
    }
    public function getAllData($table='')
    {
      if(!empty($table)){
        $res = $this->db->get($table);
        return $res->result();
      }
      return false;
    }

    //----------------------------------
  public function Edit_model($data)
    {
      return $this->db->where('id',$data["id"])->limit(1)->select('id,username,email,mobile_number,role,country,city')->get('users')->result();
    }
  public function Update_model($data)
    {
        date_default_timezone_set("America/New_York");
          $created_date = date('Y-m-d H:i:s', time());
          $data_update = array(
            'username' => $data['username'],
            'mobile_number' => $data['mobile_number'],
            'country' => $data['country'],
            'city' => $data['city'],
            'ip_address' => $this->get_client_ip(),
            'updated_at' => $created_date
          );
       return $this->db->where('id',$data['id'])->update('users',$data_update);
    }
    public function Update_Pass_model($data)
    {

      $pass = ['id'=>$data["id"]];
      $check_oldpass = $this->db->get_where('users', $pass)->row();
      if($check_oldpass) 
      {
        if(password_verify($data["update_Pass"]["old_pass"], $check_oldpass->password)){
          if($data["update_Pass"]["password"] == $data["update_Pass"]["c_pass"]){
              
              $upd = array(
               'password' => password_hash($data["update_Pass"]["password"], PASSWORD_BCRYPT)
              );
              if($this->updateData('users',$upd,['id'=>$data["id"]])){
                return self::SUCCESS;
              }else{
                return self::FAILED;
              }
          }else{
             return self::NOT_MATCH;
          }
        }else{
          return self::CURRPASS_NOT_MATCH;
        }
      }else{
        return self::USER_NOT_FOUND;
      }
    }
}
?>