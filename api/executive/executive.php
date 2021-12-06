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
$action = trim($_REQUEST['action']);
if(!empty($action)){
switch($action){
	case 'login':
			  if(!empty($_REQUEST['username'])){
			if(!empty($_REQUEST['password'])){
			
		  $mymailid = mysqli_real_escape_string($conn,$_REQUEST['username']);
		  $mypassword = mysqli_real_escape_string($conn,$_REQUEST['password']); 
		  if(filter_var($mymailid, FILTER_VALIDATE_EMAIL)) {
		  $row = runQuery("SELECT * FROM employee WHERE email = '$mymailid'");
		  }else{
			   $row = runQuery("SELECT * FROM employee WHERE mobile = '$mymailid'");
		  }
		  if(!empty($row['ID'])){
			  if(password_verify($mypassword,$row['password'])){
				if($row['status']==1){
				    $userwherearray['ID'] = $row['ID'];
					$userarray['token'] =  mysqli_real_escape_string($conn,$_REQUEST['token']); 
					$resultupdate = updateQuery($userarray,"employee",$userwherearray);
					$payload = array("status"=>'1',"usersid" => $row['ID'],'token' => $userarray['token'],"role_id" => $row['role_id']);

				} else {
					$payload = array("status"=>'0',"text"=>"Your I'd was blocked please contact admin for more details");
				}
			  }  else {
						
					$payload = array("status"=>'0',"text"=>"Invalid Password");
			  }
			  }  else {
						
					$payload = array("status"=>'0',"text"=>"Invalid Email / Mobile number");
			  }
		  }else{
						
			$payload = array("status"=>'0',"text"=>"Please enter the password");
		  }
		  }else{
						
			 	$payload = array("status"=>'0',"text"=>"Please enter the email");
		  }
	break;
	case 'emplogin':
			  if(!empty($_REQUEST['username'])){
			if(!empty($_REQUEST['password'])){
			
		  $mymailid = mysqli_real_escape_string($conn,$_REQUEST['username']);
		  $mypassword = mysqli_real_escape_string($conn,$_REQUEST['password']); 
		  if(filter_var($mymailid, FILTER_VALIDATE_EMAIL)) {
		  $row = runQuery("SELECT * FROM employee WHERE email = '$mymailid'");
		  }else{
			   $row = runQuery("SELECT * FROM employee WHERE mobile = '$mymailid'");
		  }
		  if(!empty($row['ID'])){
			  if(password_verify($mypassword,$row['password'])){
			      	 $userwherearray['ID'] = $row['ID'];
					$userarray['token'] =  mysqli_real_escape_string($conn,$_REQUEST['token']); 
					$resultupdate = updateQuery($userarray,"employee",$userwherearray);
					$payload = array("status"=>'1',"usersid" => $row['ID'],'token' => $userarray['token'],"role_id" => $row['role_id']);

				
			  }  else {
						
					$payload = array("status"=>'0',"text"=>"Invalid Password");
			  }
			  }  else {
						
					$payload = array("status"=>'0',"text"=>"Invalid Email / Mobile number");
			  }
		  }else{
						
			$payload = array("status"=>'0',"text"=>"Please enter the password");
		  }
		  }else{
						
			 	$payload = array("status"=>'0',"text"=>"Please enter the email");
		  }
	break;
	case 'registration':
		if(!empty($_REQUEST['name'])&&!empty($_REQUEST['email'])){ 
			$userarray = array();	
			$prevmerchnat = runQuery("select max(ID) as id from executive")['id'];
			$newid = $prevmerchnat+1;
			$userarray['unique_id'] = 'FDGE'.sprintf('%05d',$newid);
			$userarray['otp'] = rand(0000,9999);
			$userarray['name'] = ucwords(trim(mysqli_real_escape_string($conn,$_POST['name'])));
			$userarray['email'] = trim(mysqli_real_escape_string($conn,$_POST['email']));
			$userarray['mobile'] = trim(mysqli_real_escape_string($conn,$_POST['mobile'])); 	
			$userarray['password'] = password_hash(mysqli_real_escape_string($conn,trim($_POST['password'])),PASSWORD_DEFAULT); 	
			$userarray['status'] = 0;
			$row = runQuery("SELECT * FROM executive WHERE email = '".$userarray['email']."'");
			if(empty($row['ID'])){
				$row = runQuery("SELECT * FROM executive WHERE mobile = '".$userarray['mobile']."'");
				if(empty($row['ID'])){     
					$result = insertQuery($userarray,'executive');
					if(!$result){
						$message = "Hi ".$userarray['name']." ".$userarray['otp']." is your otp for verification.";
						 send_sms($userarray['mobile'],$message);
							$userdetails = runQuery("select ID from executive where unique_id = '".$userarray['unique_id']."'"); 
					$payload = array("status"=>'1',"executiveid"=>$userdetails['ID'],"text"=>"Account created");	 
			
					}else{
						$payload = array("status"=>'0',"text"=>$result);
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
	case 'register-otp':
		if(!empty($_POST['executiveid'])){
		  $executiveid =  mysqli_real_escape_string($conn,trim($_REQUEST['executiveid']));
				$userdetails = runQuery("select ID,otp from executive where ID = '".$executiveid."'");
			if(!empty($userdetails['ID'])){ 
						$otp = mysqli_real_escape_string($conn,$_POST['otp']);
						if($userdetails['otp']==$otp){ 
							mysqli_query($conn,"update executive set status = '1' where ID = '".$userdetails['ID']."'");
							$payload = array("status"=>'1',"executiveid"=>$userdetails['ID'],"text"=>"OTP Verified");
						}else{
					
							$payload = array("status"=>'0',"text"=>"Invalid OTP");
						}  
			}else{
					
				$payload = array("status"=>'0',"text"=>"Invalid user");
			}
		}else{
					
			$payload = array('status'=>'0','message'=>'Invalid Parameters');
		}
	break;  
	case 'resend-otp':
		if(!empty($_POST['executiveid'])){
		  $executiveid =  mysqli_real_escape_string($conn,trim($_REQUEST['executiveid']));
				$userdetails = runQuery("select * from executive where ID = '".$executiveid."'");
			if(!empty($userdetails['ID'])){ 
							$message = "Hi ".$userdetails['name']." ".$userdetails['otp']." is your otp for verification.";
						 send_sms($userdetails['mobile'],$message);
							$payload = array("status"=>'1',"text"=>"OTP Sent Successfully"); 
			}else{
					
				$payload = array("status"=>'0',"text"=>"Invalid user");
			}
		}else{
					
			$payload = array('status'=>'0','message'=>'Invalid Parameters');
		}
	break;  
	case 'forgotpassword':
		$username = mysqli_real_escape_string($conn,$_POST['username']); 
		if(!empty($username)){ 
			if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
				$row = runQuery("SELECT * FROM employee WHERE email = '".$username."'"); 
					if(!empty($row)){
							$userarray = $userwherearray = array();
							$otp = rand(1111,9999);
							$userarray['otp'] = $otp;
							$userwherearray['ID'] = $row['ID'];
							$result = updateQuery($userarray,"employee",$userwherearray);
							if(!$result){ 
							$headers = 'MIME-Version: 1.0' . "\r\n";

										$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

										$headers .= 'From:  M3avenue <'.MAILID.'>' . " \r\n" .
													'Reply-To:  '.MAILID.' '."\r\n" .
													'X-Mailer: PHP/' . phpversion();
								$emailmessage = '';
								$emailmessage .= '<p>Dear '.ucwords($row['name']).',</p>';
								$emailmessage .= '<br />';
								$emailmessage .= '<p>You have recevied otp for reset password for your account '.$row['email'].'</p>';
								$emailmessage .= '<br />';
								$emailmessage .= '<p>Enter the below verification code to reset the password.</p>';
								$emailmessage .= '<br />';
								$emailmessage .= '<strong>Verification code : '.$otp.'</strong>';
								$emailmessage .= '<br />';
								$emailmessage .= '<p>Thank you.</p>';
								$subject = 'Forgot password for M3avenue';
								$email = $row['email'];
								$result = mail($email,$subject,$emailmessage,$headers);
								if($result){
									$message = "Hi ".$row['name']." ".$userarray['otp']." is your otp for Forgot password.";
									/* send_sms($row['mobile'],$message); */
								 
								
								$payload = array("status"=>'1',"executiveid"=>$row['ID'],"text"=>"OTP Sent successfully");
								}else{
								
									$payload = array("status"=>'0',"text"=>"Please try again");
								} 
							}else{
							
								$payload = array("status"=>'0',"text"=>$result);
							} 
					}else{
							
						$payload = array("status"=>'0',"text"=>"Invalid User details");
					} 
			}else{
					
				$payload = array("status"=>'0',"text"=>"Invalid Emaid id. Please try again.");
			} 
		}else{
				
			$payload = array("status"=>'0',"text"=>"Please enter the password");
		} 
	break;
	case 'forgotpassword-otp':
		if(!empty($_POST['executiveid'])){
		  $executiveid =  mysqli_real_escape_string($conn,trim($_REQUEST['executiveid']));
				$userdetails = runQuery("select ID,otp from executive where ID = '".$executiveid."'");
			if(!empty($userdetails['ID'])){ 
						$otp = mysqli_real_escape_string($conn,$_POST['otp']);
						if($userdetails['otp']==$otp){  
							$payload = array("status"=>'1',"executiveid"=>$userdetails['ID'],"text"=>"OTP Verified");
						}else{ 
							$payload = array("status"=>'0',"executiveid"=>"null","text"=>"Invalid OTP");
						}  
			}else{ 
				$payload = array("status"=>'0',"text"=>"Invalid user");
			}
		}else{ 	
			$payload = array('status'=>'0','message'=>'Invalid Parameters');
		}
	break;
	case 'executivepoints':
	if(!empty($_REQUEST['executiveid'])){
		  $executiveid =  mysqli_real_escape_string($conn,trim($_REQUEST['executiveid']));
		                        $monthstartdate = date('Y-m-01');
                    $currentdate = date('Y-m-d');
                	$startdate = !empty($_REQUEST['sdate']) ? $_REQUEST['sdate'] : $monthstartdate ;
					$enddate = !empty($_REQUEST['sdate']) ? $_REQUEST['edate'] : $currentdate ;
			if(!empty($executiveid)){ 
			            $empdetails = runQuery("select ID,income from employee where ID = '".$executiveid."'");
			            $target_points = (int)((int)$empdetails['income'] / (12 * 25)); 
						$emp_points = emp_points($_REQUEST['executiveid']);
						$emp_points['targetpoints'] = $target_points; 
						

						if(!empty($emp_points)){  
							$payload = array("status"=>'1',"points"=>$emp_points);
						}else{ 
							$payload = array("status"=>'0',"points"=>"null","message"=>"Executive Points Details Not Found");
						}  
			}else{ 
				$payload = array("status"=>'0',"text"=>"Please Link Executive With Employee");
			}
		}else{ 	
			$payload = array('status'=>'0','message'=>'Invalid Parameters');
		}
	break;  
	case 'changepassword':
		  if(!empty($_REQUEST['executiveid'])&&!empty($_REQUEST['password'])){
		  $customer_id =  mysqli_real_escape_string($conn,trim($_REQUEST['executiveid']));
		  $row = runQuery("SELECT * FROM employee WHERE ID = '".$customer_id."'");
		  if(!empty($row['ID'])){
		$userarray = $userwherearray = array();
				$userwherearray['ID'] = $row['ID']; 
				$userarray['password'] = password_hash(trim($_REQUEST['password']), PASSWORD_DEFAULT);
				$result = updateQuery($userarray,'executive',$userwherearray);
				if(!$result){
					
					$payload = array("status"=>'1',"text"=>"Password updated");
					}else{
					
					$payload = array("status"=>'0',"text"=>"Technical issue araised");
				}
	  }else{
					
			$payload = array("status"=>'0',"text"=>"Invalid executive");
	  }
	  }else{
					
			$payload = array("status"=>'0',"text"=>"Invalid parameters");
	  }
	break; 
		case 'pushid':
			if(!empty($_POST['pushid'])){ 
				$userwherearray = $userarray = array();
				$userarray['pushid'] =  $_POST['pushid']; 
					$result = insertQuery($userarray,'device_tokens');
					if(!$result){ 
						
						$payload = array("status"=>'1',"text"=>"Push id has been updated.");
					}else{
						
						$payload = array("status"=>'0',"text"=>$result);
					} 
				}else{
						
					$payload = array("status"=>'0',"text"=>"Please enter the password");
				} 
		break;
		case 'updateprofile':
		if(!empty($_POST['executiveid'])){
		  $executiveid =  mysqli_real_escape_string($conn,trim($_REQUEST['executiveid']));
				$userdetails = runQuery("select ID,otp from executive where ID = '".$executiveid."'");
			if(!empty($userdetails['ID'])){
					$productarray =  $productwherearray = array(); 
					if (!file_exists('../../userprofilepic')) {	
						mkdir('../../userprofilepic', 0777, true);	
						}																			
					$target_dir = '../../userprofilepic/';	 
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
						$result = updateQuery($productarray,'executive',$productwherearray);
				  
					$payload = array("status"=>'1',"text"=>"Profilepic updated");					
			}else{
					
				$payload = array("status"=>'0',"text"=>"Invalid user");
			}
		}else{
					
			$payload = array('status'=>'0','message'=>'Invalid Parameters');
		}
		break;
	default:
	$payload = array('status'=>'0','message'=>'Please specify a valid action details');
	break;
}
}else{
		$payload = array('status'=>'0','message'=>'Please specify a action');
}
echo json_encode($payload);
?>
