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
//if(!empty($headerslist['Authorization'])){
//$usersid = validate_users($headerslist['Authorization']);
$usersid = $_REQUEST['usersid'];
if(!empty($usersid)){ 
$executivedetails = runQuery("select * from employee where ID = '".$usersid."'");
	if(!empty($executivedetails['ID'])){
		$action = mysqli_real_escape_string($conn,trim($_REQUEST['action']));
		if(!empty($action)){ 
			switch($action){ 
				case 'feedbackoptions':  
					if(!empty($_POST['campaignid'])){
					$campaignid = mysqli_real_escape_string($conn,trim($_POST['campaignid'])); 
					$campaignlistid = runQuery("select feedbackoptions from campaigns where ID = '".$campaignid."' order by ID asc");
					$campaignlistarary = runloopQuery("select * from feedbackoptions where find_in_set_x('".$campaignlistid['feedbackoptions']."',ID) order by ID asc");
					}else{
					$campaignlistarary = runloopQuery("select * from feedbackoptions order by ID asc");
					}
					if(!empty($campaignlistarary)){
						$campaigns = $campaignsarray = array();
						foreach($campaignlistarary as $campaignlist){
							$campaigns['id'] =	$campaignlist['ID']; 
							$campaigns['title'] =	$campaignlist['title']; 
							$campaignsarray[] = $campaigns;
						}
						$payload = array('status'=>'1','feedbacklist'=>$campaignsarray);
					}else{
						$payload = array('status'=>'1','message'=>'Feedback not found','feedbacklist'=>[]);
					}
				break; 
				case 'campaignslist':  
					$campaignlistarary = runloopQuery("select * from campaigns where FIND_IN_SET(".$executivedetails['ID'].",executive) and status = '1' order by ID asc");
					if(!empty($campaignlistarary)){
						$campaigns = $campaignsarray = array();
						foreach($campaignlistarary as $campaignlist){
							$campaigns['id'] =	$campaignlist['ID'];
							$campaigns['unique_id'] =	$campaignlist['unique_id'];
							$campaigns['title'] =	$campaignlist['title'];
							$campaigns['service'] =	$campaignlist['service'];
							$campaigns['timeframe'] =	(int)$campaignlist['timeframe'];
							$campaignsarray[] = $campaigns;
						}
						$payload = array('status'=>'1','campaigns'=>$campaignsarray);
					}else{
						$payload = array('status'=>'1','message'=>'Campaigns not found','campaigns'=>[]);
					}
				break; 
				case 'interestedcampaign': 
					if(!empty($_REQUEST['campaignid'])){
					$campaignid = mysqli_real_escape_string($conn,trim($_REQUEST['campaignid'])); 
					$campaignlistarary = runloopQuery("select * from campaigns_users where campaign_id = '".$campaignid."' and executive_id = '".$executivedetails['ID']."' and callstatus = '1' order by ID asc");
					if(!empty($campaignlistarary)){
						$campaigns = $campaignsarray = array();
						foreach($campaignlistarary as $campaignlist){
							$campaigns['id'] =	$campaignlist['ID'];
							$campaigns['name'] =	$campaignlist['name']; 
							$campaigns['mobile'] =	$campaignlist['mobile']; 
							$campaignsarray[] = $campaigns;
						}
						$payload = array('status'=>'1','campaigns'=>$campaignsarray);
					}else{
						$payload = array('status'=>'1','message'=>'Campaigns not found','campaigns' => []);
					}
					}else{
						$payload = array('status'=>'0','message'=>'Invalid Campaign.');
					}
				break;
				case 'campaignsusers': 
					if(!empty($_REQUEST['campaignid'])){
					$campaignid = mysqli_real_escape_string($conn,trim($_REQUEST['campaignid']));
					$campaignlistid = runQuery("select feedbackoptions from campaigns where ID = '".$campaignid."' order by ID asc");
					if(!empty($campaignlistid['feedbackoptions'])){ 
						$campaincount = runQuery("select count(*) as count from campaigns_users where campaign_id = '".$campaignid."'  and callstatus = '0'");
						$campaignlistarary = runloopQuery("select * from campaigns_users where campaign_id = '".$campaignid."' and callstatus = '0' and executive_id = '".$executivedetails['ID']."' order by rand() asc");
						if(!empty($campaignlistarary)){
							$campaigns = $campaignsarray = array();
							foreach($campaignlistarary as $campaignlist){
								$campaigns['id'] =	$campaignlist['ID'];
								$campaigns['name'] = trim($campaignlist['name']); 
								$campaigns['mobile'] =	trim($campaignlist['mobile']); 
								$campaigns['callstatus'] =	$campaignlist['callstatus']; 
								$campaigns['callstart'] =	$campaignlist['callstart']; 
								$campaignsarray[] = $campaigns;
							}
							$payload = array('status'=>'1','count'=>$campaincount['count'],'campaigns'=>$campaignsarray);
						}else{
							$payload = array('status'=>'1','message'=>'Campaign User not found','count'=>0,'campaigns'=>[]);
						}
					}else{
						$payload = array('status'=>'0','message'=>'Please update feedback options.');
					}
					}else{
						$payload = array('status'=>'0','message'=>'Invalid Campaign.');
					}
				break;
				case 'campaignmoreinfo': 
					if(!empty($_REQUEST['campaignuserid'])){
						$campaignlistarary = array();
						$campaignuserid = mysqli_real_escape_string($conn,trim($_REQUEST['campaignuserid'])); 
					$campaignlistarary = runloopQuery("select name,value from campaign_options where user_id = '".$campaignuserid."' order by ID asc");
/*					$trackworkdetails = runQuery("select * from track_work where executive_id = '".$executivedetails['ID']."' and campaignuser_id = '".$campaignuserid."'"); */
                    $trackworkdetails = runQuery("select * from track_work where executive_id = '".$executivedetails['ID']."' and campaignuser_id = '".$campaignuserid."'");
					if(!empty($trackworkdetails)){ 
					$campaignlistarary[] = array('name'=>'Feedback','value'=>feedbackstatus($trackworkdetails['selecttype']));
					$campaignlistarary[] = array('name'=>'Customer name','value'=>$trackworkdetails['clientname']);
					$campaignlistarary[] = array('name'=>'Mobile','value'=>feedbackstatus($trackworkdetails['selecttype']));
					$campaignlistarary[] = array('name'=>'Email','value'=>$trackworkdetails['email']);
					$campaignlistarary[] = array('name'=>'Amount','value'=>$trackworkdetails['amount']);
					$campaignlistarary[] = array('name'=>'Address','value'=>$trackworkdetails['address']);
					$campaignlistarary[] = array('name'=>'Remark','value'=>$trackworkdetails['remark']);
					$campaignlistarary[] = array('name'=>'Followup','value'=>$trackworkdetails['followup']);
					$campaignlistarary[] = array('name'=>'Source','value'=>$trackworkdetails['source']);
					}
					if(!empty($campaignlistarary)){ 
						$payload = array('status'=>'1','campaigns'=>$campaignlistarary);
					}else{
						$payload = array('status'=>'1','message'=>'Campaigns data not found','campaigns'=>[]);
					}
					}else{
						$payload = array('status'=>'0','message'=>'Invalid Campaign.');
					}
				break;
				case 'campaignfeedback': 
					if(!empty($_REQUEST['campaignuserid'])){
						$campaignuserid = mysqli_real_escape_string($conn,trim($_REQUEST['campaignuserid'])); 
						$callstatus = mysqli_real_escape_string($conn,trim($_REQUEST['callstatus'])); 
						$callmessage = mysqli_real_escape_string($conn,trim($_REQUEST['callmessage'])); 
						$callduration = mysqli_real_escape_string($conn,trim($_REQUEST['callduration'])); 
					$campaignlist = runQuery("select * from campaigns_users where ID = '".$campaignuserid."' order by ID asc");
					if(!empty($campaignlist)){
						$campaigns = $campaignsarray = $campaignsnewuser= array(); 
							$campaignsarray['ID'] =	$campaignlist['ID'];
							$campaigns['executive_id'] =	$executivedetails['ID']; 
							$campaigns['duration'] =	date('Y-m-d H:i:s'); 
							$campaigns['callduration'] =	$callduration; 
							$campaigns['callstatus'] =	$callstatus; 
							$campaigns['callmessage'] =	$callmessage;
							$campaigns['remark'] =	mysqli_real_escape_string($conn,trim($_REQUEST['remark']));
							$campaigns['followup'] =	mysqli_real_escape_string($conn,trim($_REQUEST['followup']));
							$result = updateQuery($campaigns,'campaigns_users',$campaignsarray);
							$campaignpendinguserlist = runQuery("select * from campaigns_users where campaign_id = '".$campaignlist['campaign_id']."' and callstatus = '0' and (executive_id  is NULL or executive_id = '') order by ID asc limit 1 ");
							
							if(!empty($campaignpendinguserlist)){
								$campaignsnewuser['id'] =	$campaignpendinguserlist['ID'];
								$campaignsnewuser['name'] = trim($campaignpendinguserlist['name']); 
								$campaignsnewuser['mobile'] =	trim($campaignpendinguserlist['mobile']); 
								$campaignsnewuser['callstatus'] =	$campaignpendinguserlist['callstatus']; 
								$campaignsnewuser['callstart'] =	$campaignpendinguserlist['callstart'];
								
								$campaignsexeIdarray['ID'] =  $campaignpendinguserlist['ID'];
								$campaignsexeassignarray['executive_id'] = $executivedetails['ID'];
								$resultnewassign = updateQuery($campaignsexeassignarray,'campaigns_users',$campaignsexeIdarray);
							}
							if(!$result){
								$payload = array('status'=>'1','message'=>'Feedback updated','campaignsnewuser' => $campaignsnewuser);
							}else{
								$payload = array('status'=>'0','message'=>"Technical error found.");
							}
					}else{
						$payload = array('status'=>'0','message'=>'Campaign User not found');
					}
					}else{
						$payload = array('status'=>'0','message'=>'Invalid Campaign.');
					}
				break;
				case 'campaignuserstart': 
					if(!empty($_POST['campaignuserid'])){
						$campaignuserid = mysqli_real_escape_string($conn,trim($_POST['campaignuserid']));  
					$campaignlist = runQuery("select * from campaigns_users where ID = '".$campaignuserid."' order by ID asc");
					if(!empty($campaignlist)){
						$campaigns = $campaignsarray = array(); 
							$campaignsarray['ID'] =	$campaignlist['ID']; 
							$campaigns['callstart'] = '1';
							$result = updateQuery($campaigns,'campaigns_users',$campaignsarray);
							if(!$result){
								$payload = array('status'=>'1','message'=>'Feedback updated');
							}else{
								$payload = array('status'=>'0','message'=>"Technical error found.");
							}
					}else{
						$payload = array('status'=>'0','message'=>'Campaign User not found');
					}
					}else{
						$payload = array('status'=>'0','message'=>'Invalid Campaign.');
					}
				break;
				case 'interestusers': 
					if(!empty($_REQUEST['name'])&&!empty($_REQUEST['selecttype'])&&!empty($_REQUEST['mobile'])&&!empty($_REQUEST['amount'])
					&&!empty($_REQUEST['address'])&&!empty($_REQUEST['remark'])&&!empty($_REQUEST['income'])&&!empty($_REQUEST['followup'])
					&&!empty($_REQUEST['campaignuserid'])){
						    $maxleadtrack = runQuery("select MAX(ID) as id from track_work");
		                    $maxid = @$maxleadtrack['id']+1;

							$pagerarray['campaignuser_id'] = mysqli_real_escape_string($conn,$_REQUEST['campaignuserid']);
							$pagerarray['employee_id'] = $executivedetails['ID'];
			                $pagerarray['lead_id'] = "M3A".$executivedetails['unique_id'].'L'.$maxid;
							$pagerarray['executive_id'] = $executivedetails['ID'];
							$pagerarray['clientname'] = mysqli_real_escape_string($conn,$_REQUEST['name']);
							$pagerarray['selecttype'] = mysqli_real_escape_string($conn,$_REQUEST['selecttype']);
							$pagerarray['mobile'] = mysqli_real_escape_string($conn,$_REQUEST['mobile']); 
							$pagerarray['email'] = mysqli_real_escape_string($conn,$_REQUEST['email']); 
							$pagerarray['amount'] = mysqli_real_escape_string($conn,$_REQUEST['amount']);
							$pagerarray['address'] = mysqli_real_escape_string($conn,$_REQUEST['address']);
							$pagerarray['remark'] = mysqli_real_escape_string($conn,$_REQUEST['remark']);
							$pagerarray['company'] = mysqli_real_escape_string($conn,$_REQUEST['company']);
							$pagerarray['income'] = mysqli_real_escape_string($conn,$_REQUEST['income']); 
							$pagerarray['source'] = 'Calling';
							$pagerarray['followup'] = date('Y-m-d',strtotime($_REQUEST['followup']));
							$pagerarray['status'] = 'Yes';
							$result = insertQuery($pagerarray,'track_work');
							if(!$result){
								$payload = array('status'=>'1','message'=>'Client updated');
							}else{
								$payload = array('status'=>'0','message'=>"Technical error found.");
							} 
					}else{
						$payload = array('status'=>'0','message'=>'Invalid Parameters.');
					}
				break;
				case 'feedbackcampaignsusers': 
					if(!empty($_REQUEST['campaignid'])&&!empty($_REQUEST['feedbackid'])){
					$campaignid = mysqli_real_escape_string($conn,trim($_REQUEST['campaignid'])); 
					$feedbackid = mysqli_real_escape_string($conn,trim($_REQUEST['feedbackid'])); 
					$campaignlistarary = runloopQuery("select * from campaigns_users where campaign_id = '".$campaignid."' and callstatus = '".$feedbackid."' and executive_id = '".$executivedetails['ID']."' order by duration asc");
					if(!empty($campaignlistarary)){
						$campaigns = $campaignsarray = array();
						foreach($campaignlistarary as $campaignlist){
							$campaigns['id'] =	$campaignlist['ID'];
							$campaigns['name'] =	$campaignlist['name']; 
							$campaigns['mobile'] =	$campaignlist['mobile']; 
							$campaigns['remark'] =	$campaignlist['remark']; 
							$campaigns['followup'] =	$campaignlist['followup']; 
							$campaignsarray[] = $campaigns;
						}
						$payload = array('status'=>'1','count'=>count($campaignsarray),'campaigns'=>$campaignsarray);
					}else{
						$payload = array('status'=>'1','message'=>'Campaigns not found','count'=>0,'campaigns'=>[]);
					}
					}else{
						$payload = array('status'=>'0','message'=>'Invalid Campaign.');
					}
				break;
				case 'campaignlogs': 
					if(!empty($_POST['campaignid'])&&!empty($_POST['logaction'])){
					$campaignid = mysqli_real_escape_string($conn,trim($_POST['campaignid'])); 
					$executiveid = $executivedetails['ID']; 
					$logaction = mysqli_real_escape_string($conn,trim($_POST['logaction'])); 
					if(!empty($logaction)){
						switch($logaction){
							case 'in':
							$openlogscamarray = runloopQuery("select * from campaign_logs where campaign_id = '".$campaignid."' and executive_id = '".$executiveid."' and logstatus = '1'");
							if(!empty($openlogscamarray)){
								$logsnewarray = $logsnewarraywhere  =array();
								foreach($openlogscamarray as $openlogs){
									$logsnewarraywhere['ID'] = $openlogs['ID']; 
									$logsnewarray['logstatus'] = '0'; 
									$result = updateQuery($logsnewarray,'campaign_logs',$logsnewarraywhere);
								}
							$logsarray = array();
							$logsarray['campaign_id'] = $campaignid;
							$logsarray['executive_id'] = $executiveid;
							$logsarray['logtime'] = date('Y-m-d H:i:s');
							$logsarray['logstatus'] = '0';
							$logsarray['action'] = 'out';
							$result = insertQuery($logsarray,'campaign_logs');
							}
							
							$logsarray = array();
							$logsarray['campaign_id'] = $campaignid;
							$logsarray['executive_id'] = $executiveid;
							$logsarray['logtime'] = date('Y-m-d H:i:s');
							$logsarray['logstatus'] = '1';
							$logsarray['action'] = 'in';
							$result = insertQuery($logsarray,'campaign_logs');
							if(!$result){
							$payload = array('status'=>'1','message'=>'Record updated');
							}
							break;
							case 'out':
							$openlogscamarray = runloopQuery("select * from campaign_logs where campaign_id = '".$campaignid."' and executive_id = '".$executiveid."' and logstatus = '1'");
							if(!empty($openlogscamarray)){
								$logsnewarray = $logsnewarraywhere  =array();
								foreach($openlogscamarray as $openlogs){
									$logsnewarraywhere['ID'] = $openlogs['ID']; 
									$logsnewarray['logstatus'] = '0'; 
									$result = updateQuery($logsnewarray,'campaign_logs',$logsnewarraywhere);
								}
							}
							$logsarray = array();
							$logsarray['campaign_id'] = $campaignid;
							$logsarray['executive_id'] = $executiveid;
							$logsarray['logtime'] = date('Y-m-d H:i:s');
							$logsarray['logstatus'] = '0';
							$logsarray['action'] = 'out';
							$result = insertQuery($logsarray,'campaign_logs');
							if(!$result){
							$payload = array('status'=>'1','message'=>'Record updated');
							}
							break;
						}
					}else{
					$payload = array('status'=>'0','message'=>'Action not found');
					} 
					}else{
						$payload = array('status'=>'0','message'=>'Invalid Parameters.');
					}
				break;
				case 'bargraphcampaign': 
					if(!empty($_POST['campaignid'])){
					$campaignid = mysqli_real_escape_string($conn,trim($_POST['campaignid']));  
					$campaignlistid = runQuery("select feedbackoptions from campaigns where ID = '".$campaignid."' order by ID asc");
					$executiveid = $executivedetails['ID']; 
					$feedbackid = !empty($_POST['feedbackid']) ? mysqli_real_escape_string($conn,trim($_POST['feedbackid'])) : '0'; 
					$month = !empty($_POST['month']) ? trim($_POST['month']) : date('m'); 
					$year = !empty($_POST['year']) ? trim($_POST['year']) : date('Y');
					if($feedbackid>0){  
						$feedbackarray = runQuery("select ID,title from feedbackoptions where ID = '".$feedbackid."' order by ID asc");
								$bargraphoptions = array();
							$days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
							for($i=1;$i<=$days;$i++){
								$datecur = date("Y-m-d",mktime(0,0,0,$month,$i,$year)); 
								$bardata = runQuery("select count(*) as count from campaigns_users where campaign_id = '".$campaignid."' and executive_id = '".$executiveid."' and callstatus = '".$feedbackid."' and duration >='".$datecur." 00:00:00'  and duration <='".$datecur." 23:59:59'")['count']; 
								$bargraphoptions[$i] = !empty($bardata) ? $bardata : 0;
							}
							$bargraphoptionstitle[$feedbackarray['title']] = $bargraphoptions; 
						$payload = array('status'=>'1','message'=>$bargraphoptionstitle);
					}else{
						$feedbackarray = runloopQuery("select * from feedbackoptions  order by ID asc");
						$bargraphoptionstitle = 	$bargraphoptionsid = array();
						foreach($feedbackarray as $feedback){
								$bargraphoptions = array();
							$days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
							for($i=1;$i<=$days;$i++){
								$datecur = date("Y-m-d",mktime(0,0,0,$month,$i,$year));
								$bardata = runQuery("select count(*) as count from campaigns_users where campaign_id = '".$campaignid."' and executive_id = '".$executiveid."' and callstatus = '".$feedback['ID']."' and duration >='".$datecur." 00:00:00'  and duration <='".$datecur." 23:59:59'")['count']; 
								$bargraphoptions[$i] = !empty($bardata) ? $bardata : 0;
							}
							$bargraphoptionstitle[$feedback['title']] = $bargraphoptions;
							$bargraphoptionsid[] = $bargraphoptions;
						}
						$payload = array('status'=>'1','data'=>$bargraphoptionsid,'message'=>$bargraphoptionstitle);
					}
					}else{
						$payload = array('status'=>'0','message'=>'Invalid Campaign.');
					}
				break;
				case 'piechartcampaign': 
					if(!empty($_POST['campaignid'])){
					$campaignid = mysqli_real_escape_string($conn,trim($_POST['campaignid']));  
					$campaignlistid = runQuery("select feedbackoptions from campaigns where ID = '".$campaignid."' order by ID asc");
						/* $feedbackarray = runloopQuery("select * from feedbackoptions where find_in_set_x('".$campaignlistid['feedbackoptions']."',ID) order by ID asc"); */
						$feedbackarray = runloopQuery("select * from feedbackoptions order by ID asc");
					$executiveid = $executivedetails['ID'];  
					$startdate = !empty($_POST['startdate']) ? mysqli_real_escape_string($conn,trim($_POST['startdate'])) : date('Y-m-d');  
					$enddate = !empty($_POST['enddate']) ? mysqli_real_escape_string($conn,trim($_POST['enddate'])) : date('Y-m-d');   
						$bargraphoptionstitle = array();
						foreach($feedbackarray as $feedback){
								$bargraphoptions = array(); 
								$bardata = runQuery("select count(*) as count from campaigns_users where campaign_id = '".$campaignid."' and executive_id = '".$executiveid."' and callstatus = '".$feedback['ID']."' and duration >='".$startdate." 00:00:00'  and duration <='".$enddate." 23:59:59'")['count'];  
							$bargraphoptionstitle[$feedback['title']] = $bardata;
						}
						$campaincount = runQuery("select count(*) as count from campaigns_users where campaign_id = '".$campaignid."' and callstatus = '0' order by ID asc");
						$payload = array('status'=>'1','count'=>$campaincount['count'],'message'=>$bargraphoptionstitle); 
					}else{
						$payload = array('status'=>'0','message'=>'Invalid Campaign.');
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
//}else{
	 
	//$payload = array('status'=>'0','message'=>'Invalid Authorization details');
//}
echo json_encode($payload);
?>