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
//$usersid = validate_users($headerslist['Authorization']);
$usersid = $_REQUEST['usersid'];
if(!empty($usersid)){ 
$userdetails = runQuery("select * from employee where ID = '".$usersid."'");
	if(!empty($userdetails['ID'])){
		$action = mysqli_real_escape_string($conn,trim($_REQUEST['action']));
		if(!empty($action)){ 
			switch($action){ 
			case 'password':
						if(!empty($_REQUEST['password'])){ 
							$userwherearray = $userarray = array();
							$userarray['password'] = password_hash(trim($_REQUEST['password']),PASSWORD_DEFAULT);
							$userwherearray['ID'] = $userdetails['ID'];
								$result = updateQuery($userarray,'executive',$userwherearray);
								if(!$result){ 
									
									$payload = array("status"=>'1',"text"=>"Password has been updated.");
								}else{
									
									$payload = array("status"=>'0',"text"=>$result);
								} 
							}else{
									
								$payload = array("status"=>'0',"text"=>"Please enter the password");
							}
							
				break;
				case 'pushid':
					if(!empty($_POST['pushid'])){ 
						$userwherearray = $userarray = array();
						$userarray['push_id'] =  $_POST['pushid'];
						$userwherearray['ID'] = $userdetails['ID'];
							$result = updateQuery($userarray,'executive',$userwherearray);
							if(!$result){ 
								
								$payload = array("status"=>'1',"text"=>"Push id has been updated.");
							}else{
								
								$payload = array("status"=>'0',"text"=>$result);
							} 
						}else{
								
							$payload = array("status"=>'0',"text"=>"Please enter the password");
						} 	
				break;
				case 'updation':
					if(!empty($_POST['name'])&&!empty($_POST['email'])&&!empty($_POST['mobile'])){ 
						$userarray = $userwherearray = array();
						$userwherearray['ID'] = $userdetails['ID'];
						$userarray['name'] = mysqli_real_escape_string($conn,trim($_POST['name']));
						$userarray['email'] =  mysqli_real_escape_string($conn,trim($_POST['email']));
						$userarray['mobile'] =  mysqli_real_escape_string($conn,trim($_POST['mobile']));
						
						$row = runQuery("SELECT * FROM executive WHERE email = '".$userarray['email']."' and ID <> '".$userdetails['ID']."'");
					if(empty($row['ID'])){
						$row = runQuery("SELECT * FROM executive WHERE mobile = '".$userarray['mobile']."' and ID <> '".$userdetails['ID']."'");
							if(empty($row['ID'])){  
							
							$result = updateQuery($userarray,'executive',$userwherearray); 
								if(!$result){ 
											
									$payload = array("status"=>'1',"text"=>"Account has been updated");
								}else{
											
									$payload = array("status"=>'1',"text"=>$result);
								}  
						}else{
									
					$payload = array("status"=>'0',"text"=>"Mobile already exists please try another");
						}
					}else{
									
						$payload = array("status"=>'0',"text"=>"Email already exists please try another");
					}
					
					}else{
									
						$payload = array('status'=>'0','message'=>'Invalid Parameters');
					}
				break; 
				case 'executive': 
						  if(!empty($userdetails['ID'])){  
						  $customerdetails = array();
						  $customerdetails['id'] =  $userdetails['ID'];
						  $customerdetails['unique_id'] =  $userdetails['unique_id'];
						  $customerdetails['name'] =  $userdetails['name'];
						  $customerdetails['email'] =  $userdetails['email'];
						  $customerdetails['mobile'] =  $userdetails['mobile']; 
						  $customerdetails['location'] =  $userdetails['location'];
						  $customerdetails['profilepic'] =  executive_image($userdetails['ID']); 
						  if(!empty($userdetails['employee_id'])){
							  $employeedetails = runQuery("select * from employee where ID = '".$userdetails['employee_id']."'");
						  $customerdetails['unique_id'] =  $employeedetails['unique_id'];
						  $customerdetails['leader'] =  $employeedetails['leader'];
						  $customerdetails['fname'] =  $employeedetails['fname'];
						  $customerdetails['lname'] =  $employeedetails['lname'];
						  $customerdetails['employeeemail'] =  $employeedetails['email'];
						  $customerdetails['employeemobile'] =  $employeedetails['mobile'];
						  $customerdetails['bankdetails'] =  $employeedetails['bankdetails'];
						  $customerdetails['accntnum'] =  $employeedetails['accntnum'];
						  $customerdetails['pannum'] =  $employeedetails['pannum'];
						  $customerdetails['dob'] =  $employeedetails['dob']; 
						  $customerdetails['marital_status'] =  $employeedetails['marital_status']; 
						  $customerdetails['occuption'] =  $employeedetails['occuption']; 
						  $customerdetails['income'] =  $employeedetails['income']; 
						  $customerdetails['emergency_contact'] =  $employeedetails['emergency_contact']; 
						  $customerdetails['gender'] =  $employeedetails['gender'];  
						  }
						 $currentdate = date('Y-m');
						$campaignlistid = runQuery("select count(*) as count from campaigns where find_in_set('".$userdetails['ID']."',executive) order by ID asc");
						  $customerdetails['assignedcampaings'] =  !empty($campaignlistid['count']) ? $campaignlistid['count'] : 0;
						$campaigncalls = runQuery("select count(*) as count from campaigns_users where executive_id = '".$userdetails['ID']."' and reg_date >='".$currentdate."-01 00:00:01'and reg_date <='".$currentdate."-31 23:59:59' order by ID asc");
						  $customerdetails['totalcalls'] =  !empty($campaigncalls['count']) ? $campaigncalls['count'] : 0;
						$campaignlistid = runQuery("select count(*) as count from campaigns_users where executive_id = '".$userdetails['ID']."' and callstatus = '1' and reg_date >='".$currentdate."-01 00:00:01'and reg_date <='".$currentdate."-31 23:59:59' order by ID asc");
						  $customerdetails['totalinterested'] =  !empty($campaignlistid['count']) ? $campaignlistid['count'] : 0; 
							$payload = array("status"=>'1',"executive"=>$customerdetails);
						 
					  }else{
							$payload = array("status"=>'0',"text"=>"Invalid executive id");
					  }
				break;
				case 'updatepassword':
					  if(!empty($_REQUEST['usersid'])&&!empty($_REQUEST['password'])){
					  $customer_id = validate_users($_REQUEST['usersid']); 
					  $row = runQuery("SELECT * FROM executive WHERE ID = '".$customer_id."'");
					  if(!empty($row['ID'])){
					$userarray = $userwherearray = array();
							$userwherearray['ID'] = $row['ID'];
				  
							$userarray['password'] = password_hash($_REQUEST['password'], PASSWORD_DEFAULT);
							$result = updateQuery($userarray,'customers',$userwherearray);
							if(!$result){
								
								$payload = array("status"=>'1',"text"=>"Password updated");
								}else{
								
								$payload = array("status"=>'0',"text"=>"Technical issue araised");
							}
				  }else{
								
						$payload = array("status"=>'0',"text"=>"Invalid users");
				  }
				  }else{
								
						$payload = array("status"=>'0',"text"=>"Invalid parameters");
				  }
				break;
				
				case 'updateprofilepic':
					    if(!empty($userdetails['ID'])){  
						  $productarray =  $productwherearray = array(); 
						  	if (!file_exists('../../executiveimage')) {	
									mkdir('../../executiveimage', 0777, true);	
									}																			
								$target_dir = '../../executiveimage/';	 
									$file = $_FILES['profilepic']['name'];
									if($file){
										$extn = pathinfo($file, PATHINFO_EXTENSION);
											$filename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$extn;	
									$target_file = $target_dir.$filename;
										 
									if (move_uploaded_file($_FILES['profilepic']["tmp_name"], $target_file)){			 
										$productarray['profilepic'] =$filename;
										 
									}  
									}  
									 $productwherearray['ID'] =  $userdetails['ID'];
								$result = updateQuery($productarray,'employee',$productwherearray);
							$payload = array("status"=>'1',"text"=>"Profilepic updated","profilepic"=>executive_image($userdetails['ID']));
						 
					  }else{
							$payload = array("status"=>'0',"text"=>"Invalid users id");
					  }
				break;
								case 'addemployee':
					if(!empty($_REQUEST['role_id'])&&!empty($_REQUEST['fname'])&&!empty($_REQUEST['lname'])
								&&!empty($_REQUEST['email'])
								&&!empty($_REQUEST['mobile']) 
								&&!empty($_REQUEST['address'])
								&& !empty($_REQUEST['pannum'])
								){
									$pagerarray = $sql  = array();
									if(!empty($_REQUEST['employeeid'])){
										$employeeid = $_REQUEST['employeeid'];
										$sql = runQuery("select * from employee where ID = '".$employeeid."'");	
									}
									else{
										$employeeid = '';
									}					
						
						$uniqueusers = (int)runQuery("select max(ID) as id from employee order by ID desc")['id'];
						$newuniquid = $uniqueusers+1;
						$pagerarray['role_id'] = mysqli_real_escape_string($conn,$_REQUEST['role_id']) ?? 2;
						$pagerarray['leader'] = $userdetails['unique_id'];
						$pagerarray['fname'] = mysqli_real_escape_string($conn,$_REQUEST['fname']);
						$pagerarray['lname'] = mysqli_real_escape_string($conn,$_REQUEST['lname']);
						$pagerarray['unique_id'] = 'M3'.sprintf('%05d',$newuniquid);
						$pagerarray['email'] = mysqli_real_escape_string($conn,$_REQUEST['email']);
						$pagerarray['mobile'] = mysqli_real_escape_string($conn,$_REQUEST['mobile']);
						$pagerarray['password'] = password_hash(trim($_REQUEST['password']),PASSWORD_DEFAULT);
						$pagerarray['bankdetails'] = mysqli_real_escape_string($conn,$_REQUEST['bankdetails']);
						$pagerarray['accntnum'] = mysqli_real_escape_string($conn,$_REQUEST['accntnum']);
						$pagerarray['ifsccode'] = mysqli_real_escape_string($conn,$_REQUEST['ifsccode']);
						$pagerarray['address'] = mysqli_real_escape_string($conn,$_REQUEST['address']);
						$pagerarray['income'] = mysqli_real_escape_string($conn,$_REQUEST['income']);
						$pagerarray['pannum'] = mysqli_real_escape_string($conn,$_REQUEST['pannum']);
						$pagerarray['adharnum'] = mysqli_real_escape_string($conn,$_REQUEST['adharnum']);
						$pagerarray['joining_date'] = mysqli_real_escape_string($conn,$_REQUEST['join_date']);
						$pagerarray['payment_type'] = mysqli_real_escape_string($conn,$_REQUEST['payment']);
						$pagerarray['designation'] = mysqli_real_escape_string($conn,$_REQUEST['designation']);
						$pagerarray['location'] = mysqli_real_escape_string($conn,$_REQUEST['location']);
						$pagerarray['emergency_contact'] = mysqli_real_escape_string($conn,$_REQUEST['emergency_contact']);
						$pagerarray['gender'] = mysqli_real_escape_string($conn,$_REQUEST['gender']);

						$pagerarray['whatsapp_number'] = mysqli_real_escape_string($conn,$_REQUEST['whatsapp_number']);
						$pagerarray['alternate_mobile_number'] = mysqli_real_escape_string($conn,$_REQUEST['alternate_mobile_number']);
						$pagerarray['personal_email'] = mysqli_real_escape_string($conn,$_REQUEST['personal_email']);
						$pagerarray['landmark'] = mysqli_real_escape_string($conn,$_REQUEST['landmark']);
						$pagerarray['state'] = mysqli_real_escape_string($conn,$_REQUEST['state']);
						$pagerarray['city'] = mysqli_real_escape_string($conn,$_REQUEST['city']);
						$pagerarray['pincode'] = mysqli_real_escape_string($conn,$_REQUEST['pincode']);


						$pagerarray['status'] = '0';
						if(!empty($_REQUEST['employeeid']))
						{
						    unset($pagerarray['unique_id']);
							$empwherearray['ID'] =	$_REQUEST['employeeid'];
							$result = updateQuery($pagerarray,'employee',$empwherearray);
							$payload = array("status"=>'1',"text"=>"Employee Details Updated Successfully");
						}
						else{
							$insertedId = insertIDQuery($pagerarray,'employee');
							if(!empty($insertedId)){
								$payload = array("status"=>'1','emp_id'=> $insertedId,"text"=>"Employee Created Successfully");
							}
						}

			}else{
				$payload = array('status'=>'0','message'=>'Invalid Parameters.');
			}
				break;
								case 'addhire':
					if(!empty($_REQUEST['mobile_number'])){
						// include('../offerletter/offerletter.php');
						$pagerarray  = array();
				
						if (!file_exists('../../hiredocs')) {	
					  mkdir('../../hiredocs', 0777, true);	
					  }
					  $target_dir = '../../hiredocs/';									
				
					  $file = @$_FILES["resume_path"]['name'];	
					  if(@$file)	{
						$extn = pathinfo($file, PATHINFO_EXTENSION);
						$file = strtolower(base_convert(time(), 10, 36) . '_pan_' . md5(microtime())).'.'.$extn;				
						$target_file = $target_dir . strtolower($file);		
						
				
					  $uploadOk = 1;
					  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);								
				
					  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
					   && $imageFileType != "gif" && $imageFileType != "doc" && $imageFileType != "docx" )
					  {
		
					  $payload = array('status'=>'0','message'=>'Sorry, only JPG, JPEG, PNG & GIF files are allowed.');
	
					  $uploadOk = 0;						
					  }
					  if ($uploadOk == 0) {			
					  $payload = array('status'=>'0','message'=>'Sorry, your file was not uploaded.');
						} else {
						   if (move_uploaded_file($_FILES["resume_path"]["tmp_name"], $target_file)){				
							   $_REQUEST['resume_path'] = strtolower($file);						
							   } else {
								$payload = array('status'=>'0','message'=>'Sorry, There Was an Error Uploading Your File.');


								   }
							  }
					}		  
				
							   
							  $_REQUEST['emp_id'] = $usersid; 
							  unset($_REQUEST['action'],$_REQUEST['usersid']);
							  $result = insertQuery($_REQUEST,'hire');
							  if(!$result){
								$payload = array('status'=>'1','message'=>'Added Hire Details Successfully.');
								}
								else{
									$payload = array('status'=>'0','message'=>'Error While Adding Hire Details.');

								}
						
					}
					else{
						$payload = array('status'=>'0','message'=>'Please Provide Mobile Number.');

					}
				break;
				case 'inductionlist':
				    $leadid = getsuperadmin($usersid);
					$sql = runloopQuery("select * from hire_induction where emp_id in ('".$usersid."','".$leadid."')  order by ID desc");
					$pagerarray = [];
					for($i=0;$i<count($sql);$i++){
					    $sqlinvitee = runloopQuery("select * from hire where induction_id = '".$sql[$i]['ID']."'");
						$pagerarray[$i] = $sql[$i];
						$pagerarray[$i]['inviteecount'] = count($sqlinvitee);
					}
					$payload = array('status'=>'1','inductionlist'=> $pagerarray);
				break;
				case 'hirelist':
					$sql = "select * from hire where emp_id = '".$usersid."'  "; 
					if(!empty($_REQUEST['inductionid']) || ($_REQUEST['inductionid'] === 0) ){
						$sql .= " and induction_id = '".$_REQUEST['inductionid']."' ";
					}
					if(!empty($_REQUEST['status'])){
						$sql .= " and status = '".$_REQUEST['status']."' ";
					}
					$sql .= " order by ID desc";
					$sql = runloopQuery($sql);
					$pagerarray = [];
					for($i=0;$i<count($sql);$i++){
						$pagerarray[$i] = $sql[$i];
					}
					$payload = array('status'=>'1','hirelist'=> $pagerarray);
				break;
                case 'updateinvitee':
					if(!empty($_REQUEST['status'])&&!empty($_REQUEST['invitee_id'])){
						$invitee_id = mysqli_real_escape_string($conn,$_REQUEST['invitee_id']);
						$row = runQuery("select * from hire where ID = '".$invitee_id."'");
						$hirewherearray = $hirearray = [];	 
						$hirewherearray['ID'] = $row['ID'];
						if(!empty($_REQUEST['status'])){ 
							$hirearray['status'] = mysqli_real_escape_string($conn,$_REQUEST['status']);
						}
						if(!empty($_REQUEST['induction_id'])){ 
							$hirearray['induction_id'] = mysqli_real_escape_string($conn,$_REQUEST['induction_id']);
						}
						if(!empty($_REQUEST['candidate_name'])){ 
							$hirearray['candidate_name'] = mysqli_real_escape_string($conn,$_REQUEST['candidate_name']);
						}
						if(!empty($_REQUEST['mobile_number'])){ 
							$hirearray['mobile_number'] = mysqli_real_escape_string($conn,$_REQUEST['mobile_number']);
						}
						if(!empty($_REQUEST['location'])){ 
							$hirearray['location'] = mysqli_real_escape_string($conn,$_REQUEST['location']);
						}
						if(!empty($_REQUEST['experience'])){ 
							$hirearray['experience'] = mysqli_real_escape_string($conn,$_REQUEST['experience']);
						}
						if(!empty($_REQUEST['designation_opting'])){ 
							$hirearray['designation_opting'] = mysqli_real_escape_string($conn,$_REQUEST['designation_opting']);
						}
						if(!empty($_REQUEST['current_ctc'])){ 
							$hirearray['current_ctc'] = mysqli_real_escape_string($conn,$_REQUEST['current_ctc']);
						}
						if(!empty($_REQUEST['reference'])){ 
							$hirearray['reference'] = mysqli_real_escape_string($conn,$_REQUEST['reference']);
						}
						if(!empty($_REQUEST['reference_mobile'])){ 
							$hirearray['reference_mobile'] = mysqli_real_escape_string($conn,$_REQUEST['reference_mobile']);
						}
						
						


						if (!file_exists('../../hiredocs')) {	
							mkdir('../../hiredocs', 0777, true);	
						  }
						  $target_dir = '../../hiredocs/';									
					
						  $file = @$_FILES["resume_path"]['name'];	
						  if(@$file)	{
							$extn = pathinfo($file, PATHINFO_EXTENSION);
							$file = strtolower(base_convert(time(), 10, 36) . '_pan_' . md5(microtime())).'.'.$extn;				
							$target_file = $target_dir . strtolower($file);		
							
					
						  $uploadOk = 1;
						  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);								
					
						  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
						   && $imageFileType != "gif" && $imageFileType != "doc" && $imageFileType != "docx" )
						  {
			
						  $payload = array('status'=>'0','message'=>'Sorry, only JPG, JPEG, PNG & GIF files are allowed.');
		
						  $uploadOk = 0;						
						  }
						  if ($uploadOk == 0) {			
						  $payload = array('status'=>'0','message'=>'Sorry, your file was not uploaded.');
							} else {
							   if (move_uploaded_file($_FILES["resume_path"]["tmp_name"], $target_file)){				
								   $hirearray['resume_path'] = strtolower($file);						
								   } else {
									$payload = array('status'=>'0','message'=>'Sorry, There Was an Error Uploading Your File.');
	
	
									   }
								  }
						}
						
						if(!empty($hirearray)){
						    $result = updateQuery($hirearray,'hire',$hirewherearray);
						}
						if(!$result){
							$payload = array('status'=>'1','message'=>'Details updated');
						}else{
							$payload = array('status'=>'0','message'=>"Technical error found.");
						} 
				}else{
					$payload = array('status'=>'0','message'=>'Invalid Parameters.');
				}
			break;
				default:
				$payload = array('status'=>'0','message'=>'Please specify a valid action');
				break;
			}
		}else{
			$payload = array('status'=>'0','message'=>'Please specify a valid action');
		}
	}else{
	
	$payload = array("status"=>'0',"text"=>"Invalid user");
	}
}else{
	 
	$payload = array('status'=>'0','message'=>'Invalid users details');
}
echo json_encode($payload);
?>