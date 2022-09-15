<?php

class Message_model extends CI_Model{
	public function __construct(){
    parent::__construct();		
		$this->load->database();
	}

	public function create_broadcast($data='')
	{
		if(!empty($data)){
       $SaveData = array(
         'list_id'=> $data['list'],
         'subject'=> $data['subject'],
         'message'=> $data['broadcast_msg'],
        );
		  $this->db->insert('broadcast',$SaveData);
		  return $this->db->insert_id();
	    }else{
	      return false;
	    }
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
      if(!empty($table) && !empty($data)){
        $this->db->insert($table,$data);
        return $this->db->insert_id();
      }else{
        return false;
      } 
  }
	// Start Draft Code NK Date 10-06-2021
    public function AllSaveDraft($data)
    {
      if(!empty($data))
      {
         date_default_timezone_set("America/New_York");
          $created_date = date('Y-m-d H:i:s', time());
          $data_insert = array(
            'title' => $data['title'],
            'message' => $data['message'],
            'created_at' => $created_date,
            'updated_at' => $created_date
          );
          $res = $this->db->insert('draft_messages',$data_insert);
          if(!empty($res))
            return 1;
          else
            return 0;
      }
      else
        return 2;
    }
    public function AllGetDraft($data)
    {
      if(!empty($data))
      {
       	 return $this->db->order_by('id','DESC')->where('status',1)->get('draft_messages')->result();
      }
      else
        return 2;
    }

    public function AllGetOneDraft($data)
    {
      if(!empty($data))
      {
      	$arr_id = ['status'=>1, 'id'=>$data['id']];      	
       	return $this->db->get_where('draft_messages', $arr_id)->result();
      }
      else
        return 2;
    }
    public function deleteDraft($data)
    {
      if(!empty($data))
      {      	
       	$this->db->where('id',$data['id'])->delete('draft_messages');
       	return 1;
      }
      else
        return 2;
    }
   // End Draft Code NK Date 10-06-2021
   // Start Follow Code NK Date 11-06-2021
    public function AllSaveFollow($data)
    {
      if(!empty($data))
      {
        date_default_timezone_set("America/New_York");
          $created_date = date('Y-m-d H:i:s', time());
          $data_insert = array(
            'user_id'=>$data["id"],
            'title' => $data['title'],
            'message' => $data['message'],
            'created_at' => $created_date,
            'updated_at' => $created_date
          );
          $res = $this->db->insert('follow_messages',$data_insert);
          if(!empty($res))
            return 1;
          else
            return 0;
      }
      else
        return 2;
    }
    public function AllGetFollow($data)
    {
      if(!empty($data))
      {
       	 return $this->db->order_by('id','DESC')->where('status',1)->where('user_id',$data["id"])->get('follow_messages')->result();
      }
      else
        return 2;
    }
    public function deleteFollow($data)
    {
      if(!empty($data))
      {      	
       	$this->db->where('id',$data['id'])->delete('follow_messages');
       	return 1;
      }
      else
        return 2;
    }
   // End Follow Code NK Date 11-06-2021

   public function createActiveList($data = ''){
     if(!empty($data)){
       $this->db->insert('activelist',['listname'=>$data['listname']]);
       $id = $this->db->insert_id();
       if($id){
          $sub = [];
          foreach($data['subscriber'] as $row) { 
            $arr = array(
             'activelist_id' => $id,
             'user_id' => $row,
             'username' => (getSubscriber(['id'=>$row])) ? getSubscriber(['id'=>$row])->name : ''
            );
            $sub[] = $arr;
          }
          if($this->db->insert_batch('activelist_users', $sub)){
            return true;
          }else{
            return false;
          }        
       }
     }else{
      return false;
     }
   }
  
  //-----------------------------------------
   public function SavetoFollowup($data)
   {
    if (!empty($data)) {
       $id = $data['id'];
       $draft_data = $this->getData('draft_messages',['id'=>$id]);
       $check = $this->db->where('title',$draft_data[0]->title)->get('follow_messages')->num_rows();
       if ($check==0) {
         $this->db->insert('follow_messages',[
         'user_id'=>$data['user_id'],
         'title'=> $draft_data[0]->title,
         'message'=>$draft_data[0]->message
       ]);
       return $this->db->insert_id()?true:false;
       }
       else{
          return false;
        }
     }else{
       return false;
     }
    
   }

   public function SavetoDraft($data='')
   {
     if(!empty($data)) {
       $id = $data['id'];
       $from = $data['from'];
       if($from=='broadcast'){
          $broadData = $this->getData('broadcast',['id'=>$id]);
          $this->db->insert('draft_messages',[
           'title'=> $broadData[0]->subject,
           'message'=>$broadData[0]->message
          ]);
       }elseif($from=='followup') {
          $followupData = $this->getData('follow_messages',['id'=>$id]);
          $this->db->insert('draft_messages',[
           'title'=> $followupData[0]->title,
           'message'=>$followupData[0]->message
          ]);
       }
       return $this->db->insert_id()?true:false;
     }else{
       return false;
     } 
   }

   public function Create_code($data)
   {
     if (!empty($data)){
        $check = $this->db->where('user_id',$data["id"])->get('follow_messages')->num_rows();
        if($check>0)
        {
          return $get_code = $this->db->where('user_id',$data["id"])->select('code')->get('sharingcode')->result();
        }
        else
        {
          return false;
        }
     }
   }
   public function Share_code($data)
   {
     if (!empty($data)){
        $share_code = $data["sharing_code"]["share_code"];
        $get_check = $this->db->where('code',$share_code)->get('sharingcode')->result();
        if ($get_check) {
          if($get_check[0]->user_id == $data["id"]) {
            return 2;
          }
          else
          {
            $get_followUp = $this->db->where('user_id',$get_check[0]->user_id)->where('status',1)->get('follow_messages')->result();

            if (!empty($get_followUp)) {
              foreach ($get_followUp as $row) {
                $cross_check = $this->db->where('user_id',$data["id"])->where('title',$row->title)->where('message',$row->message)->get('follow_messages')->num_rows();
                if ($cross_check>0) {
                 continue;
                }
                else{
                  $copied_data = array(
                    'user_id' => $data["id"],
                    'title' => $row->title,
                    'message' => $row->message,
                    'created_at' => $row->created_at,
                    'updated_at' => $row->updated_at,
                    'status' => $row->status
                  );
                  $res = $this->db->insert('follow_messages',$copied_data);
                }
              }
                if(!empty($res))
                  return 1;
                else
                  return 3;
            }
          }
        }  
     }
   }

}

?>