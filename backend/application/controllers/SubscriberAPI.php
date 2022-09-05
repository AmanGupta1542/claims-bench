<?php
// defined('BASEPATH') OR exit('No direct script access allowed');
class SubscriberAPI extends CI_Controller{
	public function __construct(){
	   parent::__construct();    
       header('Access-Control-Allow-Origin: *');
       header('Access-Control-Allow-Methods: GET,POST,DELETE,OPTIONS,PUT,PATCH');
       header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
	}

	public function getSubscribers()
	{
	  $data = $this->Subscriber_model->getData('subscribers');
      echo json_encode($data);
	}

	public function ExlfileUpload()
	{

		$_FILES = json_decode(file_get_contents('php://input'), true);
		$folderPath = "./assets/";
		$img = $_FILES['exc_file'];
		$code = $_FILES['fileSource'];
		$image_parts1 = explode(";base64", $code);
		$image_base64 = base64_decode($image_parts1[1]);
		$file_name = explode('\\',$img);
		$file = $file_name[2];
		if(file_put_contents($folderPath.$file, $image_base64)){
        	echo json_encode(['status'=>"Success uploaded"]);
        }	    
        $this->load->library('excel');
        $objReader= PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);     
        $objPHPExcel=$objReader->load($folderPath.$file);
        $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);
        $arr2 = array();

        for($i=0; $i<$totalrows; $i++)
        { 
            $name = $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();
            $number = $objWorksheet->getCellByColumnAndRow(1,$i)->getValue();
            $tag = $objWorksheet->getCellByColumnAndRow(2,$i)->getValue();
            $email = $objWorksheet->getCellByColumnAndRow(3,$i)->getValue();    
            $country = $objWorksheet->getCellByColumnAndRow(4,$i)->getValue();
            $city = $objWorksheet->getCellByColumnAndRow(5,$i)->getValue();
            $sender_no = $objWorksheet->getCellByColumnAndRow(6,$i)->getValue();   

            $subscribers_data = array(
              'name'=>$name,
              'whatsapp_no'=>$number,
              'tag' => $tag,
              'email'=>$email,
              'country'=>$country,
              'city' => $city,
              'sender_no'=>$sender_no
            );
            $id = $this->Subscriber_model->insertData('subscribers',$subscribers_data);
            echo $id." ";
        }
        echo json_encode($subscribers_data);
        exit();
	}

	public function saveByText()
	{
		    $_POST = json_decode(file_get_contents('php://input'), true);
        $data = $this->input->post();

        $var = "";
        for ($i=0; $i < count($data); $i++) { 
        	$arrComma =  explode(",", $data[$i]);
            $subscribers_data = array(
              'name'=> !empty($arrComma[0]) ? $arrComma[0] : '',
              'whatsapp_no'=>!empty($arrComma[1]) ? $arrComma[1] : '',
              'tag' =>!empty($arrComma[2]) ? $arrComma[2] : '',
              'email'=>!empty($arrComma[3]) ? $arrComma[3] : '',
              'country' => !empty($arrComma[4]) ? $arrComma[4] : '',
              'city' => !empty($arrComma[5]) ? $arrComma[5] : '',
              'sender_no' => !empty($arrComma[6]) ? $arrComma[6] : '',
            );
            $id = $this->Subscriber_model->insertData('subscribers',$subscribers_data);
        }
        // $i = 1;
        echo json_encode($id);
	}
}
?>