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
	if(!empty($userdetails['ID'])){
		$action = trim($_REQUEST['action']);
		if(!empty($action)){
		switch($action){
			case 'clockin':
				if(!empty($_REQUEST['clock_in'])){
					$sqlempdetails = runQuery("select * from employee_attendance ea where ea.emp_id = {$usersid} and date(attendance_date) = '".date('Y-m-d')."'");
					if(empty($sqlempdetails)){
						$pagearray = [];
						$pagearray['emp_id'] = $usersid;
						$pagearray['attendance_date'] = date('Y-m-d',strtotime($_REQUEST['clock_in']));
						$pagearray['clock_in'] = date('H:i',strtotime($_REQUEST['clock_in']));
						$pagearray['lat'] = !empty($_REQUEST['lat']) ? $_REQUEST['lat'] : '';
						$pagearray['lng'] = !empty($_REQUEST['lng']) ? $_REQUEST['lng'] : '';
						$pagearray['reg_date'] = date('Y-m-d H:i:s A');
						$result = insertQuery($pagearray,'employee_attendance');
						$payload = ['status' => '1', 'message' => 'Clock in details are updated successfully'];
					}
					else{
						$payload = ['status' => '1', 'message' => 'Clock in details are already submitted'];
					}
					
				}
				else{
					$payload = ['status' => '0', 'message' => 'Please provide clock in details'];
				}
			break;
			case 'clockout':
				if(!empty($_REQUEST['clock_out'])){
					$sqlempdetails = runQuery("select * from employee_attendance ea where ea.emp_id = {$usersid} and date(attendance_date) = '".date('Y-m-d')."'");
					if(!empty($sqlempdetails) && empty($sqlempdetails['clock_out'])){
						$pagearray = $pagewherearray = [];
						$pagearray['clock_out'] = date('H:i',strtotime($_REQUEST['clock_out']));

						$totalhrs = 0;
						$clock_in = $sqlempdetails['clock_in'];
						$clock_out = $pagearray['clock_out'];
						$clockin = str_replace(":","",$clock_in);
						$clockout = str_replace(":","",$clock_out);
						$totalhrs = (int)($clockout) -  (int)($clockin);
						if($totalhrs <= 0 && $clock_out != ''){	
							$payload = ['status' => '1', 'message' => 'Please Provide valid clock in, clock out timings!!'];
						}
						else if($totalhrs > 0){
							$clock_in_arr  = explode(":",$clock_in);
							$clock_out_arr  = explode(":",$clock_out);
							$clock_in_mins = ((int)($clock_in_arr[0])*60) + ((int)($clock_in_arr[1]));
							$clock_out_mins = ((int)($clock_out_arr[0])*60) + (int)($clock_out_arr[1]);
							$total_min = $clock_out_mins - $clock_in_mins;
							$total_hours = round(($total_min/60),2);
							$pagearray['total_hours'] = $total_hours;
							$pagewherearray['ID'] = $sqlempdetails['ID'];
							$result = updateQuery($pagearray,'employee_attendance',$pagewherearray);
							$payload = ['status' => '1', 'message' => 'Clock Out details are updated successfully'];
						}
					}
					else{
						$payload = ['status' => '0', 'message' => 'Clock Out details are already submitted'];
					}
					
				}
				else{
					$payload = ['status' => '0', 'message' => 'Please provide clock in details'];
				}
			break;
			case 'breakin':
				if(!empty($_REQUEST['breakin'])){
					$sqlempdetails = runQuery("select * from employee_attendance ea where ea.emp_id = {$usersid} and date(attendance_date) = '".date('Y-m-d')."'");
					if(!empty($sqlempdetails) && empty($sqlempdetails['clock_out'])){
						$sqlbreakdetails = runQuery("select * from employee_break_history where attendance_id = '".$sqlempdetails['ID']."' order by ID desc  limit 1");
						if(!empty($sqlbreakdetails['break_out']) || empty($sqlbreakdetails)){
							$pagearray = [];
							$pagearray['emp_id'] = $usersid;
							$pagearray['attendance_id'] = $sqlempdetails['ID'];
							$pagearray['break_in'] = date('H:i',strtotime($_REQUEST['breakin']));
							$pagearray['reg_date'] = date('Y-m-d H:i:s');
							$result = insertQuery($pagearray,'employee_break_history');
							$payload = ['status' => '1', 'message' => 'Break In details are submitted successfully'];
						}
						else{
							$payload = ['status' => '0', 'message' => 'Please provide break in details!!'];
						}					
					}
					else{
						$payload = ['status' => '0', 'message' => 'Please check clock in details!!'];
					}
				}
				else{
					$payload = ['status' => '0', 'message' => 'Please provide break in details'];
				}
			break;
			case 'breakout':
				if(!empty($_REQUEST['breakout'])){
					$sqlempdetails = runQuery("select * from employee_attendance ea where ea.emp_id = {$usersid} and date(attendance_date) = '".date('Y-m-d')."'");
					if(!empty($sqlempdetails) && empty($sqlempdetails['clock_out'])){
						$sqlbreakdetails = runQuery("select * from employee_break_history where attendance_id = '".$sqlempdetails['ID']."' order by ID desc  limit 1");
						if(!empty($sqlbreakdetails) && empty($sqlbreakdetails['break_out'])){
							$pagearray = $pagewherearray = [];
							$pagearray['break_out'] = date('H:i',strtotime($_REQUEST['breakout']));
							$pagewherearray['ID'] = $sqlbreakdetails['ID'];
							$result = updateQuery($pagearray,'employee_break_history',$pagewherearray);
							$payload = ['status' => '1', 'message' => 'Break Out details are updated successfully'];
						}
						else{
							$payload = ['status' => '0', 'message' => 'Please provide break in details!!'];	
						}
					}
					else{
						$payload = ['status' => '0', 'message' => 'Please check clock in details!!'];
					}	
				}
				else{
					$payload = ['status' => '0', 'message' => 'Please provide break out details'];
				}	
			break;
			case 'getAttendanceDetails':
				if(!empty($_REQUEST['sdate']) && !empty($_REQUEST['edate'])){
					$sqlempdetails = runloopQuery("select * from employee_attendance ea where ea.emp_id = {$usersid} 
					and date(attendance_date) between '".$_REQUEST['sdate']."' and '".$_REQUEST['edate']."'
					order by attendance_date desc");
					$detailedarray = $singledetail = [];
					foreach($sqlempdetails as $details){
						$singledetail['attendance_date'] = $details['attendance_date'];
						$singledetail['clock_in'] = !empty($details['clock_in']) ? $details['clock_in'] : '';
						$singledetail['clock_out'] = !empty($details['clock_out']) ? $details['clock_out'] : '';
						$singledetail['total_hours'] = !empty($details['total_hours']) ? $details['total_hours'] : 0;
						$singledetail['total_break_hours'] = !empty($details['total_break_hours']) ? $details['total_break_hours'] : 0;
						$singledetail['effective_hours'] = !empty($details['effective_hours']) ? $details['effective_hours'] : 0;
						$breakdetail = [];
						$sqlbreakdetails = runloopQuery("select * from employee_break_history where attendance_id = '".$details['ID']."' order by ID desc");
						foreach($sqlbreakdetails as $breakdetails){
							$breakarray['break_in'] =  $breakdetails['break_in'];
							$breakarray['break_out'] = $breakdetails['break_out'];
							$breakdetail[] = $breakarray;
						}
						$singledetail['break_details'] = $breakdetail;						
						unset($breakdetail);
						$detailedarray[] = $singledetail;
					}
					$payload = ['status' => '1', 'message' => 'Attendance Details', 'data' => $detailedarray]; 
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
?>
