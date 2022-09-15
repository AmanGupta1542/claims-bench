<?php 
class Admin extends CI_Controller{

  public function __construct(){

    parent::__construct();
    Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
    Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
    Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed
    Header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
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

  public function ExlfileUpload()
	{

		$_FILES = json_decode(file_get_contents('php://input'), true);
    // print_r($_FILES);
		$folderPath = "/assets";
		$img = $_FILES['exc_file'];
		$code = $_FILES['fileSource'];
		$image_parts1 = explode(";base64", $code);
		$image_base64 = base64_decode($image_parts1[1]);
		$file_name = explode('\\',$img);
		$file = $file_name[2];
    // $arr = array(
    //   "status"=> $file,
    //   "message"=> $image_base64
    // );
    //   echo json_encode($arr);
    // echo json_encode($arr);
		if(file_put_contents($file, $image_base64)){
      // echo 'Inside if block';
        $this->load->library('excel');
        $objReader= PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);     
        $objPHPExcel=$objReader->load($file);
        $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);
        $arr2 = array();
    

        for($i=2; $i<$totalrows; $i++)
        {   
        
            $uid = $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();
            $facility = $objWorksheet->getCellByColumnAndRow(1,$i)->getValue();
            $carrier_name = $objWorksheet->getCellByColumnAndRow(2,$i)->getValue();
            $voucher_number = $objWorksheet->getCellByColumnAndRow(3,$i)->getValue();    
            $account_number = $objWorksheet->getCellByColumnAndRow(4,$i)->getValue();
            $patient_name = $objWorksheet->getCellByColumnAndRow(5,$i)->getValue();
            $service_date = $objWorksheet->getCellByColumnAndRow(6,$i)->getValue();   
            $fees = $objWorksheet->getCellByColumnAndRow(7,$i)->getValue();   
            $balance = $objWorksheet->getCellByColumnAndRow(8,$i)->getValue();   

            $subscribers_data = array(
              'UID'=>$uid,
              'Facility'=>$facility,
              'CarrierName' => $carrier_name,
              'VoucherNumber'=>$voucher_number,
              'AccountNumber'=>$account_number,
              'PatientName' => $patient_name,
              'ServiceDate'=>$service_date,
              'Fees'=>$fees,
              'Balance'=>$balance
            );
            $id = $this->Admin_model->insertFileData('tbl_output',$subscribers_data);
            echo $id." ";
        }
      
    }
  
  }
 
  
}
?>