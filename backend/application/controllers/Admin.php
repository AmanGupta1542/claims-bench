<?php 
class Admin extends CI_Controller{

  public function __construct(){

    parent::__construct();
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET,POST,DELETE,PUT,PATCH');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    //load database
    $this->load->database();
    $this->load->model(array("Admin_model"));
    $this->load->library(array("form_validation"));
    $this->load->helper("security");
  }
 
  /*
    INSERT: POST REQUEST TYPE
    UPDATE: PUT REQUEST TYPE 
    DELETE: DELETE REQUEST TYPE
    LIST: Get REQUEST TYPE
  */

  public function index(){

  	echo "this is testing";
  }  

public function login_post() {
 
    $_POST = json_decode(file_get_contents('php://input'), true);
    $data = $this->input->post();
    $email = $data['email'];
    $password = $data['password'];//"admin";

    $credential = $this->Admin_model->CheckCredential($email);
      if($credential)
      {
       $detail = $credential[0];
  
       if(password_verify($password, $detail->password)){
         $role = $detail->role;
        
         if($role == 1) 
         {                      
           $data = array(
            'id' => $detail->id,
            'username' =>  $detail->username,
            'email' =>  $detail->email
           );
           $res = array(
            'status'=>'success', 
            'token'=>'authlogintoken', 
            'appRoleId'=>1,
            'user'=>$data
          );
          echo json_encode($res);
         }        
         elseif($role == 2)
          {
           $data = array(
            'id' => $detail->id,
            'username' =>  $detail->username,
            'email' =>  $detail->email,
            'role' =>  'Manager_ROLE',
            'status' =>  $detail->username,
           ); 
           $res = array(
            'status'=>2,
            'message'=>'Manager',
            'userData'=>$data
          );
          echo json_encode($res);
          } 

          elseif($role == 3)
          {
           $data = array(
            'id' => $detail->id,
            'username' =>  $detail->username,
            'email' =>  $detail->email,
            'role' =>  'Agent_ROLE',
            'status' =>  $detail->username,
           ); 
           $res = array(
            'status'=>3,
            'message'=>'Agent',
            'userData'=>$data
          );
          echo json_encode($res);
          } 

          elseif($role == 4)
          {
           $data = array(
            'id' => $detail->id,
            'username' =>  $detail->username,
            'email' =>  $detail->email,
            'role' =>  'Client_ROLE',
            'status' =>  $detail->username,
           ); 
           $res = array(
            'status'=>4,
            'message'=>'Client',
            'userData'=>$data
          );
          echo json_encode($res);
          } 


       }else{
        $res = array(
          'status'=>0,
          'message'=>'wrong'
        );
        echo json_encode($res);     
       }  
     }    
     else 
     {    
      $res = array(
        'status'=>0,
        'message'=>'not_found'
      );
      echo json_encode($res);    
     }
}

  // POST: <project_url>/index.php/student
  public function index_post(){
    // insert data method
   $_POST = json_decode(file_get_contents('php://input'), true);
    //print_r($this->input->post());die;

    // collecting form data inputs
    $name = $this->security->xss_clean($this->input->post("name"));
    $email = $this->security->xss_clean($this->input->post("email"));
    $mobile = $this->security->xss_clean($this->input->post("mobile"));
    $username = $this->security->xss_clean($this->input->post("username"));
    $role = $this->security->xss_clean($this->input->post("role"));
    $status = $this->security->xss_clean($this->input->post("status"));
    $password = $this->security->xss_clean($this->input->post("password"));
    $cpassword = $this->security->xss_clean($this->input->post("cpassword"));
 	

    // form validation for inputs
    $this->form_validation->set_rules("name", "Name", "required");
    $this->form_validation->set_rules("email", "Email", "required|valid_email");
    $this->form_validation->set_rules("mobile", "Mobile", "required");
    $this->form_validation->set_rules("username", "Username", "required");
    $this->form_validation->set_rules("role", "Role", "required");
    $this->form_validation->set_rules("status", "Status", "required");
    $this->form_validation->set_rules("password", "password", "required");
    $this->form_validation->set_rules("cpassword", "cpassword", "required|matches[cpassword]");

    // checking form submittion have any error or not
    if($this->form_validation->run() === FALSE){

      // we have some errors
      $arr = array(
          'status'=>0,
          'message'=>'All fields are needed'
       );
      echo json_encode($arr);
    }else{

      if(!empty($name) && !empty($email) && !empty($mobile) && !empty($username) && !empty($role) && !empty($status) && !empty($password)){
        // all values are available
        $password = password_hash($password, PASSWORD_BCRYPT);
        date_default_timezone_set("America/New_York");
        $created_date = date('Y-m-d H:i:s', time());
        $user = array(
          "name" => $name,
          "email" => $email,
          "mobile" => $mobile,
          "username" => $username,
          "role" => $role,
          "status" => $status,
          "password" => $password,
          "created_at" => $created_date
        );

        echo json_encode($user);
        
        if($this->Admin_model->insert_user('admin',$user)){

          $arr = array(
          'status'=>1,
          'message'=>'User has been created'
          );
          echo json_encode($arr);
        }else{

          $arr = array(
          'status'=>0,
          'message'=>'Failed to create User'
          );
          echo json_encode($arr);
        }
      }else{
        // we have some empty field
        $arr = array(
          'status'=>0,
          'message'=>'All fields are needed'
        );
        echo json_encode($arr);
      }
    }


  }

  // PUT: <project_url>/index.php/student
  public function index_put(){
    // updating data method
    //echo "This is PUT Method";
    $data = json_decode(file_get_contents("php://input"));
    
    if(isset($data->id) && isset($data->name) && isset($data->email) && isset($data->mobile) && isset($data->username) && !empty($data->role) && !empty($data->status)){
      date_default_timezone_set("America/New_York");
      $created_date = date('Y-m-d H:i:s', time());

      $user_id = $data->id;
      $user_info = array(
        "name" => $data->name,
        "email" => $data->email,
        "mobile" => $data->mobile,
        "username" => $data->username,
        "role" => $data->role,
        "status" => $data->status,
        "created_at" => $created_date
      );
      echo json_encode($user_info);

    }else{

      $arr = array(
          'status'=>0,
          'message'=>'All fields are needed'
      );
      echo json_encode($arr);
    }
  }


  // PUT: <project_url>/index.php/student
  public function index_put_id($id){
    // updating data method
    //echo "This is PUT Method";
    $data = json_decode(file_get_contents("php://input"));
    
    if(isset($id) && isset($data->name) && isset($data->email) && isset($data->mobile) && isset($data->username) && !empty($data->role) && !empty($data->status)){
      date_default_timezone_set("America/New_York");
      $updated_date = date('Y-m-d H:i:s', time());

      $user_id = $id;
      $user_info = array(
        "name" => $data->name,
        "email" => $data->email,
        "mobile" => $data->mobile,
        "username" => $data->username,
        "role" => $data->role,
        "status" => $data->status,
        "updated_date" => $updated_date
      );
      echo json_encode($user_info);
      if($this->Admin_model->update_user('admin',$user_id, $user_info)){

        $arr = array(
          'status'=>1,
          'message'=>'User data updated successfully'
        );
        echo json_encode($arr);
      }else{

   
        $arr = array(
          'status'=>0,
          'message'=>'Failed to update User data'
        );
        echo json_encode($arr);
      }
    }else{

      $arr = array(
          'status'=>0,
          'message'=>'All fields are needed'
      );
      echo json_encode($arr);
    }
  }

  // DELETE: <project_url>/index.php/student
  public function index_delete(){
    // delete data method
    $data = json_decode(file_get_contents("php://input"));
   
    $user_id = $this->security->xss_clean($data->id);

    if($this->Admin_model->delete_user('admin',$user_id)){
      // retruns true
    
      $arr = array(
          'status'=>1,
          'message'=>'User has been deleted'
      );
      echo json_encode($arr);
    }else{
      // return false
      $arr = array(
          'status'=>0,
          'message'=>'Failed to delete User'
      );
      echo json_encode($arr);
    }
  }

 // DELETE: <project_url>/index.php/student
  public function index_delete_id($id){
    // delete data method
    $data = json_decode(file_get_contents("php://input"));
   
    $user_id = $this->security->xss_clean($id);

    if($this->Admin_model->delete_user('admin',$user_id)){
      // retruns true
    
      $arr = array(
          'status'=>1,
          'message'=>'User has been deleted'
      );
      echo json_encode($arr);
    }else{
      // return false
      $arr = array(
          'status'=>0,
          'message'=>'Failed to delete User'
      );
      echo json_encode($arr);
    }
  }


  // GET: <project_url>/index.php/User
  public function index_get(){
    // list data method
    //echo "This is GET Method";
    // SELECT * from tbl_Users;
    $users = $this->Admin_model->get_users('admin');

    //print_r($query->result());

    if(count($users) > 0){

      $arr = array(
        "status" => 1,
        "message" => "Users found",
        "data" => $users
      );
      echo json_encode($arr);
    }else{

      $arr = array(
        "status" => 0,
        "message" => "No Users found",
        "data" => $users
      );
      echo json_encode($arr);
    }



  }

  // GET: <project_url>/index.php/User
  public function index_get_id($id){
    // list data method
    //echo "This is GET Method";
    // SELECT * from tbl_Users;
    $users = $this->Admin_model->get_userById('admin',$id);

    //print_r($query->result());

    if(count($users) > 0){

      $arr = array(
        "status" => 1,
        "message" => "Users found",
        "data" => $users
      );
      echo json_encode($arr);
    }else{

      $arr = array(
        "status" => 0,
        "message" => "No Users found",
        "data" => $users
      );
      echo json_encode($arr);
    }



  }
}
?>