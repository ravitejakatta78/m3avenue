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
				if(!empty($_REQUEST['clockin'])){
					$sqlempdetails = runQuery("select * from employee_attendance ea where ea.emp_id = {$usersid} and date(attendance_date) = '".date('Y-m-d')."'");
					if(empty($sqlempdetails)){
						$pagearray = [];
						$pagearray['emp_id'] = $usersid;
						$pagearray['attendance_date'] = date('Y-m-d',strtotime($_REQUEST['clockin']));
						$pagearray['clock_in'] = date('H:i',strtotime($_REQUEST['clockin']));
						$pagearray['clockin_lat'] = !empty($_REQUEST['lat']) ? $_REQUEST['lat'] : '';
						$pagearray['clockin_lng'] = !empty($_REQUEST['lng']) ? $_REQUEST['lng'] : '';
						$imagename = uploadimage($_FILES['selfie_img'],$usersid);
						$pagearray['clockin_img'] = !empty($imagename) ? $imagename : '';
						$pagearray['clockin_range'] = !empty($_REQUEST['clockin_range']) ? $_REQUEST['clockin_range'] : '0';
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
				if(!empty($_REQUEST['clockout'])){
					$sqlempdetails = runQuery("select * from employee_attendance ea where ea.emp_id = {$usersid} and date(attendance_date) = '".date('Y-m-d')."'");
					if(!empty($sqlempdetails) && empty($sqlempdetails['clock_out'])){
						$pagearray = $pagewherearray = [];
						$pagearray['clock_out'] = date('H:i',strtotime($_REQUEST['clockout']));
						$pagearray['clockout_lat'] = !empty($_REQUEST['lat']) ? $_REQUEST['lat'] : '';
						$pagearray['clockout_lng'] = !empty($_REQUEST['lng']) ? $_REQUEST['lng'] : '';
						$imagename = uploadimage($_FILES['selfie_img'],$usersid);
						$pagearray['clockout_img'] = !empty($imagename) ? $imagename : '';
						$pagearray['clockout_range'] = !empty($_REQUEST['clockout_range']) ? $_REQUEST['clockout_range'] : '0';
						$pagearray['total_break_hours'] = !empty($_REQUEST['total_break_hours']) ? $_REQUEST['total_break_hours'] : 0;
						$pagearray['effective_hours'] = !empty($_REQUEST['effective_hours']) ? $_REQUEST['effective_hours'] : 0;
						$pagearray['total_hours'] = !empty($_REQUEST['total_hours']) ? $_REQUEST['total_hours'] : 0;

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
							$pagearray['breakin_lat'] = !empty($_REQUEST['lat']) ? $_REQUEST['lat'] : '';
							$pagearray['breakin_lng'] = !empty($_REQUEST['lng']) ? $_REQUEST['lng'] : '';
							$pagearray['breakin_range'] = !empty($_REQUEST['breakin_range']) ? $_REQUEST['breakin_range'] : '0';
							$imagename = uploadimage($_FILES['selfie_img'],$usersid);
							$pagearray['breakin_img'] = !empty($imagename) ? $imagename : '';
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
							$pagearray['breakout_lat'] = !empty($_REQUEST['lat']) ? $_REQUEST['lat'] : '';
							$pagearray['breakout_lng'] = !empty($_REQUEST['lng']) ? $_REQUEST['lng'] : '';
							$imagename = uploadimage($_FILES['selfie_img'],$usersid);
							$pagearray['breakout_img'] = !empty($imagename) ? $imagename : '';
							$pagearray['breakout_range'] = !empty($_REQUEST['breakout_range']) ? $_REQUEST['breakout_range'] : '0';
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
						$singledetail['clock_in'] = !empty($details['clock_in']) ? $details['attendance_date'].' '.$details['clock_in'] : '';
						$singledetail['clockin_lat'] = !empty($details['clockin_lat']) ? $details['clockin_lat'] : '';
						$singledetail['clockin_lng'] = !empty($details['clockin_lng']) ? $details['clockin_lng'] : '';
						$singledetail['clockin_img'] = !empty($details['clockin_img']) ? MAIN_URL.'sp_ace_docs/attendance/'.$usersid.'/'.$details['clockin_img'] : '';
						$singledetail['clockin_range'] = !empty($details['clockin_range']) ? $details['clockin_range'] : '0';						
						$singledetail['clock_out'] = !empty($details['clock_out']) ? $details['attendance_date'].' '.$details['clock_out'] : '';
						$singledetail['clockout_lat'] = !empty($details['clockout_lat']) ? $details['clockout_lat'] : '';
						$singledetail['clockout_lng'] = !empty($details['clockout_lng']) ? $details['clockout_lng'] : '';
						$singledetail['clockout_img'] = !empty($details['clockout_img']) ? MAIN_URL.'sp_ace_docs/attendance/'.$usersid.'/'.$details['clockout_img'] : '';
						$singledetail['clockout_range'] = !empty($details['clockout_range']) ? $details['clockout_range'] : '0';

						$singledetail['total_hours'] = !empty($details['total_hours']) ? gmdate("H:i", $details['total_hours']) : 0;
						$singledetail['total_break_hours'] = !empty($details['total_break_hours']) ? gmdate("H:i", $details['total_break_hours']) : 0;
						$singledetail['effective_hours'] = !empty($details['effective_hours']) ? gmdate("H:i", $details['effective_hours']) : 0;
						$breakdetail = [];
						$sqlbreakdetails = runloopQuery("select ebh.*,ea.attendance_date from employee_break_history ebh inner join  employee_attendance ea 
						on ea.ID = ebh.attendance_id where ebh.attendance_id = '".$details['ID']."' order by ebh.ID desc");
						$totalbreakhrs = $total_break_min = 0;
						foreach($sqlbreakdetails as $breakdetails){
							$breakarray['break_in'] =  $breakdetails['attendance_date'].' '.$breakdetails['break_in'];
							$breakarray['breakin_lat'] = !empty($breakdetails['breakin_lat']) ? $breakdetails['breakin_lat'] : '';
							$breakarray['breakin_lng'] = !empty($breakdetails['breakin_lng']) ? $breakdetails['breakin_lng'] : '';
							$breakarray['breakin_img'] = !empty($breakdetails['breakin_img']) ? MAIN_URL.'sp_ace_docs/attendance/'.$usersid.'/'.$breakdetails['breakin_img'] : '';
							$breakarray['breakin_range'] = !empty($breakdetails['breakin_range']) ? $breakdetails['breakin_range'] : '0';
							$breakarray['break_out'] = !empty($breakdetails['break_out']) ? $breakdetails['attendance_date'].' '.$breakdetails['break_out'] : '';
							$breakarray['breakout_lat'] = !empty($breakdetails['breakout_lat']) ? $breakdetails['breakout_lat'] : '';
							$breakarray['breakout_lng'] = !empty($breakdetails['breakout_lng']) ? $breakdetails['breakout_lng'] : '';
							$breakarray['breakout_img'] = !empty($breakdetails['breakout_img']) ? MAIN_URL.'sp_ace_docs/attendance/'.$usersid.'/'.$breakdetails['breakout_img'] : '';
							$breakarray['breakout_range'] = !empty($breakdetails['breakout_range']) ? $breakdetails['breakout_range'] : '0';
							$breakdetail[] = $breakarray;
							if(!empty($breakdetails['break_out'])){
								$breakin = str_replace(":","",$breakdetails['break_in']);
								$breakout = str_replace(":","",$breakdetails['break_out']);
								$totalbreakhrs += (int)$breakout -  (int)$breakin;
								
								$break_in_arr  = explode(":",$breakdetails['break_in']);
								$break_out_arr  = explode(":",$breakdetails['break_out']);
									
								$break_in_mins = (int)($break_in_arr[0])*60 + (int)($break_in_arr[1]);
								$break_out_mins = (int)($break_out_arr[0])*60 + (int)($break_out_arr[1]);
								$total_break_min += (int)($break_out_mins) - (int)($break_in_mins);
							}
						}

						
						$singledetail['total_break_min'] = $total_break_min;
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
