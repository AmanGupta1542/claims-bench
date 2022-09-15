<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_auth extends CI_Controller{

  public function __construct()
  {
     parent::__construct();
       header('Access-Control-Allow-Origin: *');
       header('Access-Control-Allow-Methods: GET,POST,DELETE,PUT,PATCH');
       header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
       $this->load->library("form_validation");
  } 

 
  // public function register()
  // {
  //   $_POST = json_decode(file_get_contents('php://input'), true);

  //   $insert = $this->input->post(); 

  //       $result = $this->Auth_model->Register_model($insert);  

  //        if($result == 2)
  //        {
  //          $arr = array(
  //            'status'=>2,
  //            'message'=>'exists'
  //          );
  //          echo json_encode($arr);
  //        }
  //        else if($result == 1)
  //        {
  //          $arr = array(
  //            'status'=>1,
  //            'message'=>'success'
  //          );
  //          echo json_encode($arr);
  //        }
  //        else
  //        {
  //          $arr = array(
  //            'status'=>0,
  //            'message'=>'failed'
  //          );
  //          echo json_encode($arr);
  //        }
  //      // }else{
  //      //     $arr = array(
  //      //       'status'=> 'registraion failed',
  //      //       'message'=>'Please try again.'
  //      //     );
  //      //     echo json_encode($arr);
  //      // }
  //   }

 

 public function index_post(){
    // insert data method

    //print_r($this->input->post());die;
    $_POST = json_decode(file_get_contents('php://input'), true);

     
    // collecting form data inputs
    $fname = $this->security->xss_clean($this->input->post("fname"));
    $lname = $this->security->xss_clean($this->input->post("lname"));
    $email = $this->security->xss_clean($this->input->post("email"));
    $mobile = $this->security->xss_clean($this->input->post("mobile_no"));
    $language = $this->security->xss_clean($this->input->post("language"));
    $password = $this->security->xss_clean($this->input->post("password"));

    // form validation for inputs
    $this->form_validation->set_rules("fname", "First Name", "required");
    $this->form_validation->set_rules("lname", "Last Name", "required");
    $this->form_validation->set_rules("email", "Email", "required|valid_email");
    $this->form_validation->set_rules("mobile_no", "Mobile", "required");
    $this->form_validation->set_rules("language", "language", "required");
    $this->form_validation->set_rules("password", "password", "required");

    // checking form submittion have any error or not
    if($this->form_validation->run() === FALSE){

      $arr = array(
             'status'=>0,
             'message'=>'All fields are needed'
           );
      echo json_encode($arr);
    }else{

      if(!empty($fname) && !empty($lname)&& !empty($email)&& !empty($mobile) && !empty($mobile) && !empty($language)&& !empty($password)){
        // all values are available
        $data = array(
         $fname,
           $lname,
          $email,
           $mobile,
           $language,
           $password
        );
         echo json_encode($data);
        if($this->Auth_model->Register_model($data)){
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


  public function check_loggin()
  { 
    $_POST = json_decode(file_get_contents('php://input'), true);
    $data = $this->input->post();
    $email = $data['email'];
    $password = $data['password'];//"admin";
    $credential = $this->Auth_model->CheckCredential($email);
      if($credential)
      {
       $detail = $credential[0];
       if(password_verify($password, $detail->password)){
         $role = $detail->role;
         if($role == ADMIN_ROLE) 
         {                      
           $data = array(
            'id' => $detail->id,
            'username' =>  $detail->username,
            'email' =>  $detail->email,
            'role' =>  ADMIN_ROLE,
            'status' =>  $detail->username,
           );
           $res = array(
            'status'=>1, 
            'message'=>'Admin',
            'userData'=>$data
          );
          echo json_encode($res);
         }        
         elseif($role == SUBSCRIBER_ROLE)
          {
           $data = array(
            'id' => $detail->id,
            'username' =>  $detail->username,
            'email' =>  $detail->email,
            'role' =>  SUBSCRIBER_ROLE,
            'status' =>  $detail->username,
           ); 
           $res = array(
            'status'=>2,
            'message'=>'Subscriber',
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
 
   public function send_forgot_link()
   {
      $_POST = json_decode(file_get_contents('php://input'), true);
      $data = $this->input->post();
      $email = $data['email'];
      $check_email = $this->Auth_model->CheckCredential($email);
      if($check_email)
      {
          $detail = $check_email[0];
          $id = $detail->id;
          $name = $detail->username;
          // $hash = $detail->hash_code;
          $hash = 'testhashcode';
        // send Email.......................................
          $this->load->library('email');
          $this->email->from('info@mistpl.com',"Reset Your Password");
          $this->email->to($email);
          $this->email->subject('Reset Your Password');
          $this->email->set_header('Content-Type', 'text/html');
          $message = '
           <body style="background: #84dce01a">
           <div id="div">
           <b>Hi, '.$name.'</b>
           <p>Forgot your password? click on below link to reset your new password.
           <br><br>
           <a href="http://localhost:4200/reset_password/profile_id='.$id.'&email='.$email.'&hash='.$hash.'">Reset Password</a>
           </p>
           </div>
           ';
          $this->email->message($message);
          // if($this->email->send())
          if(1)
          {
            $result = array(
             'status' => 1,
             'message' => 'Please check your email, we have sent a link at '.$email.' to reset your password.'
            );  
            echo json_encode($result);         
          }
          else
          {
            $result = array(
             'status' => 0,
             'message' => 'Something went wrong!....'
            );  
            echo json_encode($result); 
          }
      }
      else
      {
        $result = array(
             'status' => -1,
             'message' => 'No such user exists with this email.'
            );  
        echo json_encode($result);       
      }
   }

   public function reset_password()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $data = $this->input->post();

       // if(!$this->session->has_userdata('isloggedin')) {
        $new_password = $data['password'];
        $conf_password = $data['confirm_password'];
        $id = $data['id'];
        $email = $data['email'];
        $hash = $data['hash'];
        $where = array('id' => $id, 'email' => $email , 'hash_code'=>$hash);
        $acc = $this->Auth_model->getData("users", $where);
      if($acc)
      { 
        if($new_password != $conf_password) {
           $result = array(
            'status'=> 0,
            'message'=>"The passwords you entered doesn't match."
           );
           echo json_encode($result);          
        } else {
          $detail = $acc[0];
          $update = $this->Auth_model->updateData("users", array("password" => password_hash($new_password, PASSWORD_BCRYPT)), array("id" => $id, "email" => $email,"hash_code"=>$hash));
          if($update) {
             $result = array(
               'status'=> 1,
               'message'=>"Your password has been successfully changed."
             );
             echo json_encode($result);
           }else {
             $result = array(
               'status'=> -1,
               'message'=>"Password reset failed, please try again."
             );
             echo json_encode($result);
          }
        }
       }else{
          $result = array(
               'status'=> -2,
               'message'=>"Something went wrong...."
             );
          echo json_encode($result);
       }
        // } else {
        //     $this->index();
        // }
    }
  public function email_confirmation()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $data = $this->input->post();
        $rows = $this->Auth_model->getData('users',['email'=>$data['email'],'hash_code'=>$data['hash']]);
        if($rows){
          $id = $rows[0]->id;
          $status = $rows[0]->status; 
          if($status==1){
            echo json_encode(['status'=>2,'message'=>'Email already verified.']);
          }else{
            $num = $this->Auth_model->updateData("users",['status'=>1],['id'=>$id]);
            if($num){
              echo json_encode(['status'=>1,'message'=>'Email verification success.']);
            }else{
              echo json_encode(['status'=>0,'message'=>'Email verification failed,please try again.']);
            }
          }
        }else{
          echo json_encode(['status'=>-1,'message'=>'Something went wrong, please try again..']);
        }
    }
     
     public function getActivelist()
     {
       $data = $this->Auth_model->getAllData('subscribers');
       echo json_encode($data);
     }

 //-----------------------------------------------------------------------------
   public function Authedit()
   {
    $_POST = json_decode(file_get_contents('php://input'), true);
    $data = $this->input->post();
    $result = $this->Auth_model->Edit_model($data);
    if($result)
    {
      $res = array(
        'status'=>1,
        'message'=>'Success',
        'userData'=>$result
      );
      echo json_encode($res);
    }
    else
    {
      $res = array(
        'status'=>0, 
        'message'=>'failed'
      );
      echo json_encode($res);
    }
   }
   public function AuthUpdate()
   {
     $_POST = json_decode(file_get_contents('php://input'), true);
     $data = $this->input->post();
     $result = $this->Auth_model->Update_model($data);
     if($result)
     {
       $res = array(
         'status'=>1,
         'message'=>'Success'
       );
       echo json_encode($res);
     }
     else
     {
       $res = array(
         'status'=>0,
         'message'=>'failed'
       );
       echo json_encode($res);
     }
   }
   public function AuthUpdatePass()
   {
      $_POST = json_decode(file_get_contents('php://input'), true);
      $data = $this->input->post();
      $result = $this->Auth_model->Update_Pass_model($data);
      if($result==Auth_model::SUCCESS){      
        echo json_encode(['status'=>Auth_model::SUCCESS,'message'=>'Password has been updated.']);
      }elseif($result==Auth_model::FAILED){
        echo json_encode(['status'=>Auth_model::FAILED,'message'=>'Password could not be updated.']);
      }elseif($result==Auth_model::NOT_MATCH){
        echo json_encode(['status'=>Auth_model::NOT_MATCH,'message'=>'Confirm password does not match.']);
      }elseif($result==Auth_model::CURRPASS_NOT_MATCH){
        echo json_encode(['status'=>Auth_model::CURRPASS_NOT_MATCH,'message'=>'Wrong current password.']);
      }else{
        echo json_encode(['status'=>Auth_model::USER_NOT_FOUND,'message'=>'Something went wrong!']);
      }
    }
}
?>