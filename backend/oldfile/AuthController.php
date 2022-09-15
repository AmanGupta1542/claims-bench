<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AuthController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        error_reporting(0);
        $this->load->model('AuthModel');
        // $this->load->helper('verifyAuthToken');

        Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
        Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
        Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed
        Header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');

    }

    public function verifyAuthToken($token){
        $jwt = new JWT();
        $jwtSecret = 'myloginSecret';
        $verification = $jwt->decode($token,$jwtSecret,'HS256');
         return $verification;
        // $verification_json = $jwt->jsonEncode($verification);
        // return $verification_json;

    }
    
    public function index()
    {
        echo "this is working fine";
    }

    public function login()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $result = $this->AuthModel->check_login($email);
   
        if($result)
        {
         $detail = $result[0];


         if(password_verify($password, $detail->password)){
            $role = $detail->role;
            $jwt = new JWT();
            $JwtSecretKey = "myloginSecret";
            date_default_timezone_set('Asia/Kolkata');
            $date= date('d-m-Y H:i');    

            $result_t=array();
            $result_t['sub'] = $result[0]->email;
            $result_t['exp'] = time() + 172800; //172800;
     
            $token = $jwt->encode($result_t, $JwtSecretKey, 'HS256');

            if($role == 1) 
            {                      
              $data = array(
               'id' => $detail->id,
               'username' =>  $detail->username,
               'email' =>  $detail->email,
               'appRoleId'=>1
              );
              $res = array(
               'status'=>'success', 
               'token'=>$token, 
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
                 'appRoleId'=>2
                );
                $res = array(
                 'status'=>'success', 
                 'token'=>$token, 
                 'user'=>$data
               );
              echo json_encode($res);
             } 
   
            elseif($role == 3)
             {
               $data = array(
                 'id' => $detail->id,
                 'username' =>  $detail->username,
                 'email' =>  $detail->email,
                 'appRoleId'=>3
                );
                $res = array(
                 'status'=>'success', 
                 'token'=>$token, 
                 'user'=>$data
               );
              echo json_encode($res);
             } 
   
            elseif($role == 4)
             {
               $data = array(
                 'id' => $detail->id,
                 'username' =>  $detail->username,
                 'email' =>  $detail->email,
                 'appRoleId'=>4
                );
                $res = array(
                 'status'=>'success', 
                 'token'=>$token, 
                 'user'=>$data
               );
              echo json_encode($res);
             } 
   
   
          }
          else{
            $res = array(
                'status'=>'error',
                'message'=>'invalid Credentials!'
              );
              echo json_encode($res);
          }
        }
        else 
        {    
         $res = array(
           'status'=>'error',
           'message'=>'invalid Credentials!'
         );
         echo json_encode($res);    
        }

    }

    public function signup()
    {
        $headerToken = $this->input->get_request_header('Authorization');
        $splitToken = explode(" ", $headerToken);
        $token = $headerToken;

        try {
            $token = $this->verifyAuthToken($token);
            if ($token) {
                $_POST = json_decode(file_get_contents('php://input'), true);

                if ($this->input->post()) {
                    $name = $this->input->post("name");
                    $email = $this->input->post("email");
                    $mobile = $this->input->post("mobile");
                    $username = $this->input->post("username");
                    $role = $this->input->post("role");
                    $status = $this->input->post("status");
                    $password = $this->input->post("password");
                    $cpassword = $this->input->post("cpassword");

                    $password = password_hash($password, PASSWORD_BCRYPT);
                    $created_date = date('Y-m-d H:i:s', time());
                    date_default_timezone_set("America/New_York");

                    $data = array(
                        "name" => $name,
                        "email" => $email,
                        "mobile" => $mobile,
                        "username" => $username,
                        "role" => $role,
                        "status" => $status,
                        "password" => $password,
                        "created_at" => $created_date
                    );
                    echo json_encode($data);

                    $userId = $this->AuthModel->signup('admin',$data);
                    if ($userId) {
                        echo 'User Registered successfully!';
                    } else {
                        echo 'User Registeration faild!';
                    }
                }
            }

        } catch (Exception $e) {
            // echo 'Message: ' .$e->getMessage();
            $error = array(
                "status" => 401,
                "message" => "Invalid Token provided",
                "sucess" => false,
            );

            echo json_encode($error);
        }

    }

    public function get_roles($id = 0)
    {
        echo $id;
        $roles = $this->AuthModel->getRoles($id);
        echo json_encode($roles);
    }

    public function getUsers($id = 0)
    {

        $headerToken = $this->input->get_request_header('Authorization');
        $splitToken = explode(" ", $headerToken);
        $token = $splitToken[1]; //write Bearer befor token with space then get data
        // exm -Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W3sidXNlcl9pZCI6IjIiLCJuYW1lIjoiYXNoaXNoIiwiZW1haWwiOiJhc2hpc2hAbWlzdHBsLmNvbSIsInBhc3N3b3JkIjoiMTIzNDU2Iiwic3RhdHVzIjoiMCIsInJvbGVfaWQiOiIyIn1d.YAYbfZTVDUOzoX6mB6bMvOIh_yoAZljzvYBhMisnf6s

        try {
            $token = $this->verifyAuthToken($token);
            if ($token) {
                $users = $this->AuthModel->getUsers('admin',$id);
                echo json_encode($users);
            }

        } catch (Exception $e) {
            // echo 'Message: ' .$e->getMessage();
            $error = array(
                "status" => 401,
                "message" => "Invalid Token provided",
                "sucess" => false,
            );

            echo json_encode($error);
        }

    }

    public function getProfile()
    {
      
        $data = $this->authUserToken([1,2,3,4]);
        if($data){
            // take role from comming data 
            // perform operation
            unset($data["password"]);
            $res = array(
                'status'=>'success', 
                'user'=>$data
              );
             echo json_encode($res);
        }
        else{
            // return status error and message invalid token
            $res = array(
                'status'=>'error', 
                'message'=> "Invalid Token"
              );
             echo json_encode($res);
        }

    }

    public function authUserToken($roleArr) {
        $req= $this->input->request_headers() ;
        if (array_key_exists('Authorization', $req)) {
            $token = ltrim(substr($req['Authorization'], 6));
    
            $token_data = $this->verifyAuthToken($token);
            // print_r($token_data);
            date_default_timezone_set('Asia/Kolkata');
            $current_date = date('d-m-Y H:i:s', time());
            $token_date = date("d-m-Y H:i:s", $token_data->exp);

            // echo strtotime($current_date);
            // echo strtotime($token_date);
            // echo strtotime($current_date) - strtotime($token_date);
            
            if ((strtotime($current_date) - strtotime($token_date)) < 0){
                // get role from email 
                $user_email = $token_data->sub;
                
                // return data getting by email 
                $res = $this->AuthModel->authUserEmail('admin',$user_email);
                // print_r($res);
                 $role=$res['role'];
                // if role of user is exist in $role arrya ten return false else data
                if(in_array($role, $roleArr)){
                    return $res;
                }
                else{
                    //role is not matched means not autheticated for this action                   
                    // echo "false";
                    return false;
                }
            }
            else{
                // if tooken invalid or expired then return 
                return false;
            }
          }
          else{
            //if auth key not in header then return
            return false;
          }
    }
}