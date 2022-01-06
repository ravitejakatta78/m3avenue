<?php
include("../../functions.php");
header("Access-Control-Allow-Origin: ".SITE_URL."api/executive/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST  , GET");
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

$usersid = $_REQUEST['usersid'];
if(!empty($usersid)){ 
	$userdetails = runQuery("select * from employee where ID = '".$usersid."'");
	$teamdetails = [];
	if(!empty($userdetails['role_id']) && $userdetails['role_id'] == '3'){
		$teamdetails = runloopQuery("select *,concat(fname,' ',lname) name from employee where leader = '".$userdetails['unique_id']."'");  
		$teamselect = array_column($teamdetails,"ID");
		$teamselect[] = $usersid;
		
	}else{
	$teamselect[] = $usersid;    
	}
	
	$teamidstring = implode("','",$teamselect);
	
	if(!empty($userdetails['ID'])){
		$action = trim($_REQUEST['action']);
		if(!empty($action)){
		switch($action){
			case 'apply-leave':
				if(!empty($_REQUEST['leave_from']) && !empty($_REQUEST['leave_to'])){
					$pagearray = [];
					$pagearray['emp_id'] = $userdetails['ID'];
					$pagearray['leave_from'] = $_REQUEST['leave_from'];
					$pagearray['leave_to'] = $_REQUEST['leave_to'];
					$pagearray['leave_type'] = $_REQUEST['leave_type'];
					$pagearray['leave_hrs'] = !empty($_REQUEST['leave_hrs']) ? $_REQUEST['leave_hrs'] : '';
					$pagearray['leave_days'] = !empty($_REQUEST['leave_days']) ? $_REQUEST['leave_days'] : '';
					$pagearray['reason'] = !empty($_REQUEST['reasons']) ? $_REQUEST['reasons'] : '';
					$pagearray['leave_status'] = 1;
					$pagearray['reg_date'] = date('Y-m-d H:i:s');
					$pagearray['created_by'] = $userdetails['ID'];
					insertQuery($pagearray,'employee_leaves');
					$payload = ['status' => '1', 'message' => 'Leave Applied Successfully'];

				}
				else{
					$payload = ['status' => '0', 'message' => 'Please Provide Leave Dates'];
				}
			break;
			case 'leave-decision':
				$pagearray = $pageerarray = [];
				$leaveid = $_REQUEST['leave_id'];
				$leavedetails = runQuery("select * from employee_leaves where id = '".$leaveid."'");
				if($leavedetails['leave_status'] == '1'){
					$pagearray['leave_status'] = $_REQUEST['leave_status'];
					$pagearray['updated_by'] = $usersid;
					$pagearray['updated_on'] = date('Y-m-d H:i:s');
					$pageerarray['id'] = $leaveid;
					updateQuery($pagearray,'employee_leaves',$pageerarray);
					$payload = ['status' => '1', 'message' => 'Leave Status Updated Successfully'];
				}
				else{
					$payload = ['status' => '1', 'message' => 'Leave Status Already Updated'];
				}
			break;
			case 'getLeaveDetails':
				if(!empty($_REQUEST['sdate']) && !empty($_REQUEST['edate'])){
					
					$sql = "select * from employee_leaves where leave_from between 
					'".$_REQUEST['sdate']."' and '".$_REQUEST['edate']."' ";
					if($userdetails['role_id'] == '3' && $_REQUEST['teamlist'] == '1'){
						$sql .=" and emp_id in ('".$teamidstring."')  and emp_id != '".$userdetails['ID']."' ";
					}else if($userdetails['role_id'] == '3' && $_REQUEST['teamlist'] == '2'){
						$sql .=" and emp_id in ('".$teamidstring."')  ";
					}
					else {
						$sql .=" and emp_id in ('".$userdetails['ID']."')  ";
					}
					if(!empty($_REQUEST['leave_status'])){
						$sql .=" and leave_status =  '".$_REQUEST['leave_status']."'";
					}
					
					$res = runloopQuery($sql);
					$payload = ['status' => '1', 'attendance_details' => $res];
				}
				else{
					$payload = ['status' => '0', 'message' => 'Please provide valid date range'];
				}	
			break;	
			default:
				$payload = array('status'=>'0','message'=>'Please specify a valid action details');
			break;

		}
	}else{
			$payload = array('status'=>'0','message'=>'Please specify a action');
	}
}else{
	$payload = array("status"=>'0',"text"=>"Invalid user");
}
}
else{
	$payload = array('status'=>'0','message'=>'Invalid users details');
}
echo json_encode($payload);
