<?php

class MessageAPI extends CI_Controller{

	public function __construct(){
       parent::__construct();    
       header('Access-Control-Allow-Origin: *');
       header('Access-Control-Allow-Methods: GET,POST,DELETE,PUT,PATCH');
       header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
	}
     
	 public function create_broadcast()
     {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $data = $this->input->post();
        $result = $this->Message_model->create_broadcast($data);
        if($result){
        	echo json_encode(['id'=>$result,'status'=>1,'message'=>'Broadcast message created successfully.']);
        }else{
        	echo json_encode(['status'=>0,'message'=>'Something went wrong...']);
        }       
     }

   public function getBroadcastlist()
	 {
      $result = $this->Message_model->getData('broadcast');
      echo json_encode($result);
	 }

  // Start Draft Code NK Date 10-06-2021
    public function SaveDrafts()
    {
      $_POST = json_decode(file_get_contents('php://input'), true);
      $insert = $this->input->post();
      $result = $this->Message_model->AllSaveDraft($insert);
      if($result == 1)
      {
          $arr = array(
          'status'=>1,
          'message'=>'success'
          );
            echo json_encode($arr);
      }
      else
      {
          $arr = array(
          'status'=>0,
          'message'=>'failed'
          );
          echo json_encode($arr);
      }
    }
    public function getDrafts()
    {
      $_POST = json_decode(file_get_contents('php://input'), true);
      $data = $this->input->post();
      $result = $this->Message_model->AllGetDraft($data);
      if($result == 2)
      {
          $arr = array(
          'status'=>0,
          'message'=>'failed'
          );
          echo json_encode($arr);
      }
      else
      {
          $arr = array(
          'status'=>1,
          'message'=>'success',
          'allData'=>$result
          );
            echo json_encode($arr);
      }
    }

  public function getMessageone()
  {
    $_POST = json_decode(file_get_contents('php://input'), true);
      $data = $this->input->post();
      $result = $this->Message_model->AllGetOneDraft($data);
      if($result == 2)
      {
          $arr = array(
          'status'=>0,
          'message'=>'failed'
          );
          echo json_encode($arr);
      }
      else
      {
          $arr = array(
          'status'=>1,
          'message'=>'success',
          'alloneData'=>$result
          );
            echo json_encode($arr);
      }
  }
  public function deleteoneMessage()
  {
    $_POST = json_decode(file_get_contents('php://input'), true);
      $data = $this->input->post();
      $result = $this->Message_model->deleteDraft($data);
      if ($result == 1)
      {
          $arr = array(
          'status'=>1,
          'message'=>'success'
          );
            echo json_encode($arr);
      }
      if($result == 2)
      {
          $arr = array(
          'status'=>0,
          'message'=>'failed'
          );
          echo json_encode($arr);
      }
  }
  // End Draft Code NK Date 10-06-2021
    // Start Follow Code NK Date 11-06-2021
    public function SaveFollow()
    {
      $_POST = json_decode(file_get_contents('php://input'), true);
      $insert = $this->input->post();
      $result = $this->Message_model->AllSaveFollow($insert);
      if($result == 1)
      {
          $arr = array(
          'status'=>1,
          'message'=>'success'
          );
            echo json_encode($arr);
      }
      else
      {
          $arr = array(
          'status'=>0,
          'message'=>'failed'
          );
          echo json_encode($arr);
      }
    }
    public function getFollow()
    {
      $_POST = json_decode(file_get_contents('php://input'), true);
      $data = $this->input->post();
      $result = $this->Message_model->AllGetFollow($data);
      if($result == 2)
      {
          $arr = array(
          'status'=>0,
          'message'=>'failed'
          );
          echo json_encode($arr);
      }
      else
      {
          $arr = array(
          'status'=>1,
          'message'=>'success',
          'allData'=>$result
          );
            echo json_encode($arr);
      }
    }
   public function deleteFollowoneMessage()
   {
    $_POST = json_decode(file_get_contents('php://input'), true);
      $data = $this->input->post();
      $result = $this->Message_model->deleteFollow($data);
      if ($result == 1)
      {
          $arr = array(
          'status'=>1,
          'message'=>'success'
          );
            echo json_encode($arr);
      }
      if($result == 2)
      {
          $arr = array(
          'status'=>0,
          'message'=>'failed'
          );
          echo json_encode($arr);
      }
  }
    // End Follow Code NK Date 10-06-2021

  public function createActiveList()
  {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $data = $this->input->post();
        $res = $this->Message_model->createActiveList($data);
        if($res){
          echo json_encode(['status'=>1,'message'=>'List Created Successfully.']);
        }else{
          echo json_encode(['status'=>0,'message'=>'Something went wrong! please try again.']);
        }
  }

  public function getActivelist(){
    $data = $this->Message_model->getData('activelist');
    echo json_encode($data);
  }

  public function copytofollowup(){
     $_POST = json_decode(file_get_contents('php://input'), true);
     $data = $this->input->post();
     $draft_data = $this->Message_model->SavetoFollowup($data);
     if($draft_data){
      echo json_encode(['status'=>1,'message'=>'Copied! Successfully']);
     }else{
      echo json_encode(['status'=>0,'message'=>'Something went wrong...']);
     }   
  }
   
   public function copytoDraft()
   {
     $_POST = json_decode(file_get_contents('php://input'), true);
     $data = $this->input->post();
     $res = $this->Message_model->SavetoDraft($data);
     if($res){
      echo json_encode(['status'=>1,'message'=>'Copied! Successfully']);
     }else{
      echo json_encode(['status'=>0,'message'=>'Something went wrong...']);
     }  
   }

   public function SendTest()
   {
     $_POST = json_decode(file_get_contents('php://input'), true);
     $data = $this->input->post();
     $getData = getFollowup(['id'=>$data['msgid']]);
     if($getData){
      echo json_encode(['status'=>SUCCESS,'message'=>'Message has been sent to '.$data['number']]);
     }else{
      echo json_encode(['status'=>FAILED,'message'=>'Something went wrong.']);
     }
   }

   public function broadcastReview()
   {
     $_POST = json_decode(file_get_contents('php://input'), true);
     $data = $this->input->post();
     $msg = getBroadcast(['id'=>$data['id']]);
     if($msg){
      echo json_encode(['status'=>SUCCESS,'message'=>$msg->message]);
     }else{
      echo json_encode(['status'=>FAILED,'message'=>'Something went wrong.']);
     }
   }

   // Start Code By Nk Date 22/06/2021
   public function CreateCode(){
     $_POST = json_decode(file_get_contents('php://input'), true);
     $data = $this->input->post();
     $code_data = $this->Message_model->Create_code($data);
     if($code_data){
      echo json_encode(['status'=>1,'message'=>'success','sharecode'=>$code_data]);
     }else{
      echo json_encode(['status'=>0,'message'=>'No Data Found']);
     }   
    
  }

  public function ShareCode(){
     $_POST = json_decode(file_get_contents('php://input'), true);
     $data = $this->input->post();
     $code_data = $this->Message_model->Share_code($data);
      if ($code_data == 1)
      {
        echo json_encode(['status'=>1]);
      }
      if($code_data == 2)
      {
          echo json_encode(['status'=>2]);
      }
      if($code_data == 0)
      {
          echo json_encode(['status'=>0]);
      }
      if($code_data == 3)
      {
          echo json_encode(['status'=> 3]);
      }  
  }
   // End Code By Nk Date 22/06/2021
}

?>