<?php
include("../../functions.php");
header("Access-Control-Allow-Origin: ".SITE_URL."api/executive/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include("../core.php");
include('../../inc/php-jwt-master/src/BeforeValidException.php');
include('../../inc/php-jwt-master/src/ExpiredException.php');
include('../../inc/php-jwt-master/src/SignatureInvalidException.php');
include('../../inc/php-jwt-master/src/JWT.php');
use \Firebase\JWT\JWT;
header('Cache-Control: no-cache, must-revalidate');
header('Content-type: application/json'); 
 
function validate_users($jwttoken){
	
$key = "m3avenue_key";
	if($jwttoken){
 
    // if decode succeed, show user details
    try { 
        $decoded = JWT::decode($jwttoken, $key, array('HS256'));
 
        // set response code
        
  
         return $decoded->data->id;
 
    }
 
	   catch (Exception $e){
	 
			// set response code
			http_response_code(401);
			//echo $e->getMessage();
		}
	}
}
$headerslist = apache_request_headers();
$usersid = $_REQUEST['usersid']; 
if(!empty($usersid)){
$userdetails = runQuery("select * from employee where ID = '".$usersid."'");
	if(!empty($userdetails['ID'])){ 
		$action = mysqli_real_escape_string($conn,trim($_REQUEST['action']));
		if(!empty($action)){ 
			switch($action){ 
			case 'uploadtrackdocument':
				if(!empty($_FILES['uploadedfile']['name'])){
					$uploadtrackwork = $_REQUEST['trackworkid'];
					if(!empty($uploadtrackwork))
							{
								$file_tmpname = $_FILES['uploadedfile']['tmp_name'];
								$file_name = $_FILES['uploadedfile']['name'];
								$file_size = $_FILES['uploadedfile']['size'];
								$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);		
								$newname = date('YmdHis',time()).mt_rand().'.'.$file_ext;
								$path = '../../upload_documents/'.$uploadtrackwork;
								if (!is_dir($path)) {
									mkdir($path, 0777, true);
								}
					
								if(move_uploaded_file($file_tmpname,$path.'/'.$newname)){
								$trackerdocarray['file_name'] = $_REQUEST['uploadedfilename'];
								$trackerdocarray['doc_name'] = $newname;
								$trackerdocarray['employee_id'] = $usersid;
								$trackerdocarray['track_work_id'] = $uploadtrackwork;
								$trackerdocarray['reg_date'] = date('Y-m-d');
								$trackerdocarray['created_on'] = date('Y-m-d H:i:s A');
								$result = insertQuery($trackerdocarray,'track_work_documents');
								
								$tracksql = runQuery("select doc_status from track_work where ID='".$uploadtrackwork."'");
								if($tracksql['doc_status'] != 2){
								$sql = "UPDATE track_work set doc_status = '1' WHERE ID=".$uploadtrackwork."";
								$conn->query($sql);
								}
								$payload = array('status'=>'1','message'=>'Documents Uploaded Successfully');
		
										 $trackstory = runQuery("select * from track_work where ID = '".$_REQUEST['trackworkid']."'");
										 if($userdetails['role_id'] == '3'){
											 if($trackstory['employee_id'] == $userdetails['ID']){
											 $token =    lead_details($userdetails['leader'],'token');    
											 }
											  else{
												  $token = employee_details($trackstory['employee_id'],'token');
											  }
										  }
										  else{
											  $token =    lead_details($userdetails['leader'],'token');
										  } 
										  if(!empty($token)){
											  if($tracksql['doc_status'] != 2){
										$notificationparam['type'] = 'LeadDetails';
											$nmsg = 'lead';
										}
										else{
											$notificationparam['type'] = 'ClientDetails';
											$nmsg = 'client';
										}
										$notificationparam['title'] = 'Executive Added Docs in '.$nmsg;
										$notificationparam['token'] = $token;
										$notificationparam['trackId'] = $_REQUEST['trackworkid'];
										$notificationparam['empId'] = $trackstory['employee_id'];
										
										$notificationparam['msg'] = $userdetails['fname'].' '.$userdetails['lname'].' has added documents in '.$trackstory['clientname'].' '.$nmsg .' story';
										sendnotification($notificationparam);
										}    
								}
								else{
								$payload = array('status'=>'1','message'=>'Error While Documents Uploaded');    
								}
								
							}
							else
							{
								$payload = array('status'=>'0','message'=>'Invalid Parameters');
							}
				}  
				else { 
							$payload = array("status"=>'0',"text"=>"Image not found.");
				}
			break;
			case 'uploadempdocument':
			    if(!empty($_FILES['uploadedfile']['name'])){
				    $emp_id = $_REQUEST['emp_id'];
				    if (!empty($emp_id)) {
						$file_tmpname = $_FILES['uploadedfile']['tmp_name'];
						$file_name = $_FILES['uploadedfile']['name'];
						$file_size = $_FILES['uploadedfile']['size'];
						$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);		
						$newname = date('YmdHis',time()).mt_rand().'.'.$file_ext;
						$path = '../../../sp_ace_docs/empdocs/'.$emp_id.'/';
						if (!is_dir($path)) {
							mkdir($path, 0777, true);
						}

						move_uploaded_file($file_tmpname,$path.'/'.$newname);
						$docsarray['file_name'] = $_REQUEST['uploadedfilename'];
						$docsarray['doc_name'] = $newname;
						$docsarray['employee_id'] = $emp_id;
						$docsarray['reg_date'] = date('Y-m-d');
						$docsarray['created_on'] = date('Y-m-d H:i:s A');
						$result = insertQuery($docsarray,'employee_documents');
						$payload = array('status'=>'1','message'=>'Document(s) Uploaded Successfully');
					}
					else
					{
						$payload = array('status'=>'0','message'=>'Invalid Parameters');
					}
				}
				else { 
					$payload = array("status"=>'0',"text"=>"Image not found.");
				}		
			break;	
			default:
			$payload = array('status'=>'0','message'=>'Please specify a valid action');
			break;
			}
		}
		else{
			$payload = array('status'=>'0','message'=>'Please specify a valid action');
		}		
	  }  else {
					
					$payload = array("status"=>'0',"text"=>"Invalid executive");
			  } 
}else{
	 
	$payload = array('status'=>'0','message'=>'Invalid executive details');
}
echo json_encode($payload);
?>
