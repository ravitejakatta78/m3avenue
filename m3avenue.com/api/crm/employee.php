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
		$action = mysqli_real_escape_string($conn,trim($_REQUEST['action']));
		if(!empty($action)){ 
			switch($action){
			    case 'profile':
			        	  $customerdetails = $customerdetailsall = [];
			        	  if($userdetails['role_id'] == '3' && $_REQUEST['teamlist'] == 2){
			        	      $employeedetails = runloopQuery("select * from employee where ID in ('".$teamidstring."')  order by ID desc");
			        	  }
			        	  else if($userdetails['role_id'] == '3' && $_REQUEST['teamlist'] == 1){
			        	      $employeedetails = runloopQuery("select * from employee where ID in ('".$teamidstring."') and ID != '".$usersid."'  order by ID desc");
			        	  }
			        	  else{
			        	      $employeedetails = runloopQuery("select * from employee where ID in ('".$usersid."')  order by ID desc");    	      
			        	  }
				
						  foreach($employeedetails as $employeedetails){
						    $designation_details = runQuery("select * from tbl_designations where ID = '".$employeedetails['designation']."' ");

						    $customerdetails['ID'] =  $employeedetails['ID'];
							$customerdetails['unique_id'] =  $employeedetails['unique_id'];
							$customerdetails['leader'] =  $employeedetails['leader'];
							$customerdetails['fname'] =  $employeedetails['fname'];
							$customerdetails['lname'] =  $employeedetails['lname'];
							$customerdetails['employeeemail'] =  $employeedetails['email'];
							$customerdetails['employeemobile'] =  $employeedetails['mobile'];
							$customerdetails['bankdetails'] =  $employeedetails['bankdetails'];
							$customerdetails['accntnum'] =  $employeedetails['accntnum'];
							$customerdetails['ifsccode'] =  $employeedetails['ifsccode'];							
							$customerdetails['pannum'] =  $employeedetails['pannum'];
							$customerdetails['adharnum'] =  $employeedetails['adharnum'];
							$customerdetails['dob'] =  $employeedetails['dob']; 
							$customerdetails['marital_status'] =  $employeedetails['marital_status']; 
							$customerdetails['occuption'] =  $employeedetails['occuption']; 
							$customerdetails['income'] =  $employeedetails['income']; 
							$customerdetails['emergency_contact'] =  $employeedetails['emergency_contact']; 
							$customerdetails['gender'] =  $employeedetails['gender']; 
							$customerdetails['role_id'] =  $employeedetails['role_id']; 
							$customerdetails['role_name'] =  _ROLES[$employeedetails['role_id']]; 
							$customerdetails['address'] =  $employeedetails['address'];
							$customerdetails['location'] =  $employeedetails['location'];
							$customerdetails['payment_type'] =  $employeedetails['payment_type'];
							$customerdetails['joining_date'] =  $employeedetails['joining_date'];
							$customerdetails['designation'] =  $employeedetails['designation']; 
							$customerdetails['designation_name'] =  !empty(@$designation_details['designation_name']) ? $designation_details['designation_name'] : ''; 
							$customerdetails['profilepic'] = executive_image($employeedetails['ID']);
							$customerdetails['adhaarimg'] = SITE_URL.'admin/empimage/'.$employeedetails['adhaarimg'];
							$customerdetails['panimg'] = SITE_URL.'admin/empimage/'.$employeedetails['panimg'];
							if($employeedetails["status"] == 1){
								$customerdetails['offerletter'] = SITE_URL.'offerletter/Offer_letters/'.$employeedetails["unique_id"].'_offerletter.pdf';
							}
							else{
								$customerdetails['offerletter'] = 'Not Generated';
							}
							$customerdetailsall[] = $customerdetails; 
						  }
						  
						  if(!empty($customerdetailsall)){
						    $payload = array("status"=>'1',"employee"=>$customerdetailsall);    
						  }
						  else{
						      $payload = array("status"=>'1',"message" => 'No details found',"employee"=>[]);
						  }
				break;
				case 'password':
					if(!empty($_REQUEST['password'])){ 
						$userwherearray = $userarray = array();
						$userarray['password'] = password_hash(trim($_REQUEST['password']),PASSWORD_DEFAULT);
						$userwherearray['ID'] = $userdetails['ID'];
							$result = updateQuery($userarray,'employee',$userwherearray);
							if(!$result){ 
								
								$payload = array("status"=>'1',"text"=>"Password has been updated.");
							}else{
								
								$payload = array("status"=>'0',"text"=>$result);
							} 
						}else{
								
							$payload = array("status"=>'0',"text"=>"Please enter the password");
						}
						
			break;
                case 'track-work':
                    if(($userdetails['role_id'] == '3' ) && $_REQUEST['teamlist'] == 2){
                        $sqlstring = ("SELECT * FROM track_work where employee_id in ('".$teamidstring."')  and doc_status != '2'   ");
                    }
                    else if(($userdetails['role_id'] == '3') && $_REQUEST['teamlist'] == 1){
                        
                    $sqlstring = ("SELECT * FROM track_work where employee_id in ('".$teamidstring."')  and employee_id != '".$userdetails['ID']."' and doc_status != '2' ");    

                    }
                    else{
                            $sqlstring = ("SELECT * FROM track_work where employee_id in ('".$userdetails['ID']."')   and doc_status != '2'  ");    
                    }
                    
                    if(!empty($_REQUEST['track_work_id'])){
                       $sqlstring .= " and ID = '".$_REQUEST['track_work_id']."' "; 
                    }
                    $sqlstring .= " order by ID desc ";
                    
                    $sql = runloopQuery($sqlstring);
                    
                    $trackwork = $trackworkarray = array();
                    foreach($sql as $row)
                	{
                	    $worksourcedet = runQuery("select * from worksource where title_type = 2 and ID = '".$row["source"]."'");
                	    $selecttypedet = runQuery("select * from worksource where title_type = 1 and ID = '".$row["selecttype"]."'");

						$trackwork["track_id"] =     $row["ID"];
						$trackwork["lead_id"] =     $row["lead_id"];
						$trackwork["employee_id"] = $row["employee_id"];
                        $trackwork["employee_name"] = @employee_details($row['employee_id'],'fname') ?? 'Admin';
                	    $trackwork["clientname"] =     $row["clientname"];
                        $trackwork["selecttype"] = $row["selecttype"];
                        $trackwork["selecttypename"] = !empty(@$selecttypedet) ? $selecttypedet["title"] : '';
                        $trackwork["mobile"] = $row["mobile"];
                        $trackwork["email"] = $row["email"];
                        $trackwork["amount"] = (float)$row["amount"];
                        $trackwork["address"] = $row["address"];
                        $trackwork["remark"] = $row["remark"];
                        $trackwork["company"] = $row["company"];
                        $trackwork["income"] = (float)$row["income"];
                        $trackwork["followup"] = date('d F Y',strtotime($row["followup"]));
                        $trackwork["assingedto"] = $row["assingedto"];
                        $trackwork["source"] = $row["source"];;
                        $trackwork["sourcename"] = !empty(@$worksourcedet) ? $worksourcedet['title'] : '';
                        $trackwork["additional_number"] = ($row["additional_number"]);
                        $trackwork["reg_date"] = reg_date($row["reg_date"]);
                        $trackwork["pincode"] = $row["pincode"];
                        $trackwork["obligation"] = $row["obligation"];
                        
                        $trackwork["eligbility_accepted_time"] = $row["eligbility_accepted_time"];
                        $trackwork["eligibility_requested_by"] = employee_details($row["eligibility_requested_by"],'fname').' '.employee_details($row["eligibility_requested_by"],'lname');
                        $trackwork["eligibility_requested_time"] = $row["eligibility_requested_time"];
                        $trackwork["eligibility_accepted_by"] = employee_details($row["eligibility_accepted_by"],'fname').' '.employee_details($row["eligibility_accepted_by"],'lname');
                        
                        $trackworkarray[] = $trackwork;
                    }
                	if(!empty($trackworkarray)){
						    $payload = array("status"=>'1',"trackwork"=>$trackworkarray);    
						  }
						  else{
						      $payload = array("status"=>'1',"message" => 'No details found',"trackwork"=>[]);
						  }
                	break;
                	case 'eligible_leads':
                    
                    if($userdetails['role_id'] == '5'){
                        $user_unique_id = employee_details($usersid,'unique_id');
                        $get_roles_array = [3,4];
                          
                        $tree = employeehirerachy($user_unique_id,$get_roles_array);
                        $empString = !empty($tree) ? implode("','",array_column($tree,'ID')) : '';

                        $sqlstring = ("SELECT * FROM track_work where employee_id in ('".$empString."')  and employee_id != '".$userdetails['ID']."' and eligibility_request_status = '".$_REQUEST['status']."'");    

                    if(!empty($_REQUEST['track_work_id'])){
                       $sqlstring .= " and ID = '".$_REQUEST['track_work_id']."' "; 
                    }
                    $sqlstring .= " order by ID desc ";
                    $sql = runloopQuery($sqlstring);
                    
                    $trackwork = $trackworkarray = array();
                    foreach($sql as $row)
                	{
						$trackwork["track_id"] =     $row["ID"];
						$trackwork["lead_id"] =     $row["lead_id"];
						$trackwork["employee_id"] = $row["employee_id"];
                        $trackwork["employee_name"] = @employee_details($row['employee_id'],'fname') ?? 'Admin';
                	    $trackwork["clientname"] =     $row["clientname"];
                        $trackwork["selecttype"] = $row["selecttype"];
                        $trackwork["mobile"] = $row["mobile"];
                        $trackwork["email"] = $row["email"];
                        $trackwork["amount"] = (float)$row["amount"];
                        $trackwork["address"] = $row["address"];
                        $trackwork["remark"] = $row["remark"];
                        $trackwork["company"] = $row["company"];
                        $trackwork["income"] = (float)$row["income"];
                        $trackwork["followup"] = date('d F Y',strtotime($row["followup"]));
                        $trackwork["assingedto"] = $row["assingedto"];
                        $trackwork["source"] = $row["source"];
                        $trackwork["additional_number"] = ($row["additional_number"]);
                        $trackwork["reg_date"] = reg_date($row["reg_date"]);
                        $trackwork["pincode"] = $row["pincode"];
                        $trackwork["obligation"] = $row["obligation"];
                        
                        $trackwork["eligbility_accepted_time"] = $row["eligbility_accepted_time"];
                        $trackwork["eligibility_requested_by"] = employee_details($row["eligibility_requested_by"],'fname').' '.employee_details($row["eligibility_requested_by"],'lname');
                        $trackwork["eligibility_requested_time"] = $row["eligibility_requested_time"];
                        $trackwork["eligibility_accepted_by"] = employee_details($row["eligibility_accepted_by"],'fname').' '.employee_details($row["eligibility_accepted_by"],'lname');
                        
                        $trackworkarray[] = $trackwork;
                    }
                	if(!empty($trackworkarray)){
						    $payload = array("status"=>'1',"trackwork"=>$trackworkarray);    
						  }
						  else{
						      $payload = array("status"=>'1',"message" => 'No details found',"trackwork"=>[]);
						  }
                    }
                    else{
                        	      $payload = array("status"=>'1',"message" => 'Access Denied');
                    }
                	break;
                	case 'assignedwork':
                    $sql = runloopQuery("SELECT * FROM track_work where assingedto = '".$userdetails['unique_id']."' order by ID desc");
                    $trackwork = $trackworkarray = array();
                    foreach($sql as $row)
                	{
                	    $trackwork["clientname"] =     $row["clientname"];
                        $trackwork["selecttype"] = $row["selecttype"];
                        $trackwork["mobile"] = $row["mobile"];
                        $trackwork["email"] = $row["email"];
                        $trackwork["amount"] = number_format((float)$row["amount"],2);
                        $trackwork["address"] = $row["address"];
                        $trackwork["remark"] = $row["remark"];
                        $trackwork["company"] = $row["company"];
                        $trackwork["income"] = number_format((float)$row["income"],2);
                        $trackwork["followup"] = date('d F Y',strtotime($row["followup"]));
                        $trackwork["assingedto"] = $row["assingedto"];
                        $trackwork["source"] = $row["source"];
                        $trackwork["reg_date"] = reg_date($row["reg_date"]); 
                        $trackworkarray[] = $trackwork;
                    
                	}
                	if(!empty($trackworkarray)){
						    $payload = array("status"=>'1',"trackwork"=>$trackworkarray);    
						  }
						  else{
						      $payload = array("status"=>'1',"message" => 'No details found',"trackwork"=>[]);
						  }
                	break;
                	case 'trackclients':
                    if(!empty($_REQUEST['track_client_id'])){
                        $sql = runloopQuery("SELECT c.*,tw.income FROM clients c left join track_work tw on c.track_work_id = tw.ID where c.employee_id = '".$userdetails['ID']."' and c.ID= '".$_REQUEST['track_client_id']."' order by c.ID desc");
                    }
                    else{
                        $sql = runloopQuery("SELECT c.*,tw.income FROM clients c left join track_work tw on c.track_work_id = tw.ID where c.employee_id in ('".$teamidstring."')  order by c.ID desc");
                    }
                    $trackclients = $trackclientsarray = array();
                    foreach($sql as $row)
                	{
                	    $trackclients["ID"] =     $row["ID"];
                	    $trackclients["clientname"] =     $row["clientname"];
                        $trackclients["reg_date"] = reg_date($row["reg_date"]); 
                        $trackclients["mobile"] = $row["mobile"];
                        $trackclients["companyname"] = $row["companyname"];                        
                        $trackclients["bankname"] = $row["bankname"];
                        $trackclients["servicetype"] = $row["servicetype"];
                        $trackclients["pointstype"] = $row["pointstype"];
                        $trackclients["location"] = $row["location"];
                        $trackclients["type_of_client"] = $row["type_of_client"];
                        $trackclients["variant"] = $row["variant"];
                        $trackclients["variant_location"] = $row["variant_location"];
                        $trackclients["status"] = $row["status"];
                        $trackclients["loanamount"] = $row["loanamount"];
                        $trackclients["salary"] = $row["income"] ?? 0;
                        $trackclients["employee_id"] = $row["employee_id"];
						$trackclients["track_work_id"] = $row["track_work_id"];
                        $trackclients["employee_name"] = @employee_details($row['employee_id'],'fname') ?? 'Admin';
                       
                        $trackclientsarray[] = $trackclients;
                    
                	    
                	}
                	if(!empty($trackclientsarray)){
						    $payload = array("status"=>'1',"trackwork"=>$trackclientsarray);    
						  }
						  else{
						      $payload = array("status"=>'1',"message" => 'No details found',"trackwork"=>[]);
						  }
                	break;
                	case 'followup': 
    						
							
				$role_id =	employee_details($usersid,'role_id');
				$start_date =   mysqli_real_escape_string($conn,$_REQUEST['sdate']);
				$end_date =   mysqli_real_escape_string($conn,$_REQUEST['edate']);
				$start_date = !empty($start_date) ? $start_date : date('Y-m-d');
				$end_date = !empty($end_date) ? $end_date : date('Y-m-d');

				if($role_id == '4'){
					$sqlstring = ("SELECT * FROM track_work 
					where employee_id = '".$userdetails['ID']."' and followup >='".$start_date."' and followup <='".$end_date."'  order by ID desc");
				}
				else{
					$sqlstring = ("SELECT * FROM track_work 
					where employee_id in  ('".$teamidstring."')  and followup >='".$start_date."' and followup <='".$end_date."'  order by ID desc");
				}
				$sql = runloopQuery($sqlstring);
							
    //                        $sql = runloopQuery("SELECT * FROM track_work where employee_id = '".$userdetails['ID']."' and followup >='".$datee."' and followup <='".$datee."'  order by ID desc");
                            $trackwork = $trackworkarray = array();
                            foreach($sql as $row)
                        	{
                        $trackwork["track_id"] =     $row["ID"];
						$trackwork["lead_id"] =     $row["lead_id"];
						$trackwork["employee_id"] = $row["employee_id"];
                        $trackwork["employee_name"] = @employee_details($row['employee_id'],'fname') ?? 'Admin';
                	    $trackwork["clientname"] =     $row["clientname"];
                        $trackwork["selecttype"] = $row["selecttype"];
                        $trackwork["mobile"] = $row["mobile"];
                        $trackwork["email"] = $row["email"];
                        $trackwork["amount"] = (float)$row["amount"];
                        $trackwork["address"] = $row["address"];
                        $trackwork["remark"] = $row["remark"];
                        $trackwork["company"] = $row["company"];
                        $trackwork["income"] = (float)$row["income"];
                        $trackwork["followup"] = date('d F Y',strtotime($row["followup"]));
                        $trackwork["assingedto"] = $row["assingedto"];
                        $trackwork["source"] = $row["source"];
                        $trackwork["additional_number"] = ($row["additional_number"]);
                        $trackwork["reg_date"] = reg_date($row["reg_date"]);
                                
                                
                                
                                
                                
                                $trackworkarray[] = $trackwork;
                            
                        	}
                        	if(!empty($trackworkarray)){
					    	    $payload = array("status"=>'1',"trackwork"=>$trackworkarray);    
						    }
						    else{
						      $payload = array("status"=>'1',"message" => 'No details found',"trackwork"=>[]);
						    }

                	break;
                	case 'updateinterestusers': 
					if(!empty($_REQUEST['name'])&&!empty($_REQUEST['selecttype'])&&!empty($_REQUEST['mobile'])&&!empty($_REQUEST['amount'])
					&&!empty($_REQUEST['address'])&&!empty($_REQUEST['income']) &&!empty($_REQUEST['track_id'])){
					        $track_id = mysqli_real_escape_string($conn,$_REQUEST['track_id']);
					        $row = runQuery("select * from track_work where ID = '".$track_id."'");
							$trackwherearray = [];	 
							$trackwherearray['ID'] = $row['ID']; 
							$pagerarray['employee_id'] = $userdetails['ID'];
							$pagerarray['clientname'] = mysqli_real_escape_string($conn,$_REQUEST['name']);
							$pagerarray['selecttype'] = mysqli_real_escape_string($conn,$_REQUEST['selecttype']);
							$pagerarray['mobile'] = mysqli_real_escape_string($conn,$_REQUEST['mobile']); 
							$pagerarray['email'] = mysqli_real_escape_string($conn,$_REQUEST['email']); 
							$pagerarray['amount'] = mysqli_real_escape_string($conn,$_REQUEST['amount']);
							$pagerarray['address'] = mysqli_real_escape_string($conn,$_REQUEST['address']);
							$pagerarray['company'] = mysqli_real_escape_string($conn,$_REQUEST['company']);
							$pagerarray['income'] = mysqli_real_escape_string($conn,$_REQUEST['income']); 
							$pagerarray['source'] = mysqli_real_escape_string($conn,$_REQUEST['source']);
							$pagerarray['status'] = 'Yes';
							$pagerarray['followup'] = date('Y-m-d',strtotime($_REQUEST['followup']));
							$pagerarray['assingedto'] = mysqli_real_escape_string($conn,$_REQUEST['assingedto']);
							$pagerarray['additional_number'] = mysqli_real_escape_string($conn,$_REQUEST['additional_number']);
							$pagerarray['pincode'] = mysqli_real_escape_string($conn,$_REQUEST['pincode']);
                            $pagerarray['obligation'] = mysqli_real_escape_string($conn,$_REQUEST['obligation']);

							$result = updateQuery($pagerarray,'track_work',$trackwherearray);
							if(!$result){
         					     $trackstory = runQuery("select * from track_work where ID = '".$row['ID']."'");

							     if($userdetails['role_id'] == '3'){
							         if($trackstory['employee_id'] == $userdetails['ID']){
							            $token =    lead_details($userdetails['leader'],'token');    
							         }
	     					         else{
	     					             $token = employee_details($trackstory['employee_id'],'token');
	     					         }
	     					     }
	     					     else{
	     					         $token = lead_details($userdetails['leader'],'token');
	     					     }
							    if(!empty($token)){
							    $notificationparam['title'] = 'Lead Edited';
							    $notificationparam['msg'] = $userdetails['fname'].' '.$userdetails['lname'].' has updated '.$trackstory['clientname'].' details';
							    $notificationparam['token'] = $token;
							    $notificationparam['type'] = 'Lead';
							    sendnotification($notificationparam);
							    }
							    
								$payload = array('status'=>'1','message'=>'Lead updated');
							}else{
								$payload = array('status'=>'0','message'=>"Technical error found.");
							} 
					}else{
						$payload = array('status'=>'0','message'=>'Invalid Parameters.');
					}
				break;
                	case 'interestusers': 
					if(!empty($_REQUEST['name'])&&!empty($_REQUEST['selecttype'])&&!empty($_REQUEST['mobile'])
					&&!empty($_REQUEST['amount'])
					&&!empty($_REQUEST['address'])&&!empty($_REQUEST['income'])&&!empty($_REQUEST['followup'])  ){
					    	$maxleadtrack = runQuery("select MAX(ID) as id from track_work");
		                    $maxid = @$maxleadtrack['id']+1;

							$pagerarray['employee_id'] = $userdetails['ID'];
							$pagerarray['lead_id'] = "M3A".$userdetails['unique_id'].'L'.$maxid;
							$pagerarray['clientname'] = mysqli_real_escape_string($conn,$_REQUEST['name']);
							$pagerarray['selecttype'] = mysqli_real_escape_string($conn,$_REQUEST['selecttype']);
							$pagerarray['mobile'] = mysqli_real_escape_string($conn,$_REQUEST['mobile']); 
							$pagerarray['email'] = mysqli_real_escape_string($conn,$_REQUEST['email']); 
							$pagerarray['amount'] = mysqli_real_escape_string($conn,$_REQUEST['amount']);
							$pagerarray['address'] = mysqli_real_escape_string($conn,$_REQUEST['address']);
							$pagerarray['company'] = mysqli_real_escape_string($conn,$_REQUEST['company']);
							$pagerarray['income'] = mysqli_real_escape_string($conn,$_REQUEST['income']); 
							$pagerarray['source'] = mysqli_real_escape_string($conn,$_REQUEST['source']);
							$pagerarray['followup'] = date('Y-m-d',strtotime($_REQUEST['followup']));
							$pagerarray['assingedto'] = mysqli_real_escape_string($conn,$_REQUEST['assingedto']);
							$pagerarray['additional_number'] = mysqli_real_escape_string($conn,$_REQUEST['additional_number']);
							$pagerarray['pincode'] = mysqli_real_escape_string($conn,$_REQUEST['pincode']);
							$pagerarray['obligation'] = mysqli_real_escape_string($conn,$_REQUEST['obligation']);

							$pagerarray['status'] = 'Yes';
							$result = insertQuery($pagerarray,'track_work');
							if(!$result){
							    $token = lead_details($userdetails['leader'],'token');
							    if(!empty($token)){
							    $notificationparam['title'] = 'New Lead Added';
							    $notificationparam['msg'] = $userdetails['fname'].' '.$userdetails['lname'].' has added a new lead';
							    $notificationparam['token'] = $token;
							    $notificationparam['trackId'] = null;
							    $notificationparam['empId'] = null;
							    $notificationparam['type'] = 'Lead';
							    sendnotification($notificationparam);
							    }
								$payload = array('status'=>'1','message'=>'Lead Added');
							}else{
								$payload = array('status'=>'0','message'=>"Technical error found.");
							} 
					}else{
						$payload = array('status'=>'0','message'=>'Invalid Parameters');
					}
				break;
				case 'addremarks' : 
	            if(!empty($userdetails['ID']))
	            {
	                if(!empty($_REQUEST['remarks']) && !empty($_REQUEST['track_id']))
	                {   
	                    	$pagerarray = [];
	                    	$pagerarray['track_work_id'] = $_REQUEST['track_id'];
	                    	$pagerarray['employee_id'] = $userdetails['ID'];
	                    	$pagerarray['remark_desc'] = $_REQUEST['remarks'];
							$result = insertQuery($pagerarray,'remarks_history');
							if(!$result){
	     					     $trackstory = runQuery("select * from track_work where ID = '".$_REQUEST['track_id']."'");
	     					     if($userdetails['role_id'] == '3'){
	     					         $token =    employee_details($trackstory['employee_id'],'token');
	     					     }
	     					     else{
	     					         $token = lead_details($userdetails['leader'],'token');
	     					     }
    						    
							    if(!empty($token)){
							    $nmsg = ($trackstory['doc_status'] == '2') ? 'client ' : 'lead ';
								$notificationparam['title'] = 'New Remarks Added in '.$nmsg;
							    $notificationparam['msg'] = $userdetails['fname'].' '.$userdetails['lname'].' has added remarks for '.$trackstory['clientname'].' '.$nmsg.' story';
							    $notificationparam['token'] = $token;
							    $notificationparam['trackId'] = $_REQUEST['track_id'];
							    $notificationparam['empId'] = $trackstory['employee_id'];
							    $notificationparam['type'] = 'Remarks';
							    sendnotification($notificationparam);
							    }
								$payload = array('status'=>'1','message'=>'Remarks updated');
							}else{
								$payload = array('status'=>'0','message'=>"Technical error found.");
							} 
	                   
	                }     
	                else{
	                    $payload = array('status'=>'0','message'=>'Invalid Parameters');
	                }
			    }else{
						$payload = array('status'=>'0','message'=>'Invalid User');
				}
	            break;
	            case 'getremarks' : 
	            if(!empty($userdetails['ID']))
	            {
	                if(!empty($_REQUEST['track_id']))
	                {
	                    $remarkshistory = runloopQuery("select * from remarks_history where track_work_id = '".$_REQUEST['track_id']."'");

	                    	$pagererarray = $pagerarray = [];
	                    	//$pagerarray = $remarkshistory;
	                    	foreach($remarkshistory as $remarkshistory)
                	        {
                    	        $pagerarray['track_work_id'] = $remarkshistory['track_work_id'];
    	                    	$pagerarray['employee_id'] = $remarkshistory['employee_id'];
    	                    	$pagerarray['remark_desc'] = $remarkshistory['remark_desc'];
    	                    	$pagerarray['reg_date'] = $remarkshistory['reg_date'];
        	                    $pagerarray['employee_name'] = @employee_details($remarkshistory['employee_id'],'fname') ?? 'Admin';
                	            $pagererarray[] = $pagerarray;
                	        }     
	                    	

	                    	
							
								$payload = array('status'=>'1','remarks' => $pagererarray,'message'=>'Remarks updated');
							 
	                   
	                }     
	                else{
	                    $payload = array('status'=>'0','message'=>'Invalid Parameters');
	                }
			    }else{
						$payload = array('status'=>'0','message'=>'Invalid User');
				}
	            break;
	            case 'trackworkdocuments' : 
	                $uploadtrackwork = $_REQUEST['trackworkid'];
	                if(!empty($uploadtrackwork))
	                {
	                    $trackerdocarray = [];
	                    $rows = runloopQuery("SELECT * FROM track_work_documents where  track_work_id = '".$uploadtrackwork."' order by ID desc");
	                    for($i=0;$i < count($rows); $i++)
	                    {
	                        $trackerdocarray[$i]['ID'] = $rows[$i]['ID'];

	                        $trackerdocarray[$i]['file_name'] = $rows[$i]['file_name'];
            			    $trackerdocarray[$i]['doc_name'] = SITE_URL.'upload_documents/'.$uploadtrackwork.'/'.$rows[$i]['doc_name'];
            			    $trackerdocarray[$i]['is_verified'] = ($rows[$i]['is_verified'] == 1) ? true : false;
                            $trackerdocarray[$i]['verified_by'] = $rows[$i]['verified_by'];
	                    }
						$payload = array('status'=>'1','documentdetails'=>$trackerdocarray);

	                }
	                else
	                {
	                    $payload = array('status'=>'0','message'=>'Invalid Parameters');
	                }
	            break;
	            case 'pointworksource' :
	                    $pointsetarray = $worksourcearray = [];
	                    $rows = runloopQuery("SELECT  * FROM pointset where status = '1'   order by ID desc");
	                    $pointsetarray = $rows;
	                    $wsrows = runloopQuery("SELECT * FROM worksource where status = '1'   order by ID desc");
	                    $worksourcearray = $wsrows;
					$payload = array('status'=>'1','pointsetarray'=>$pointsetarray,'worksourcearray' => $worksourcearray);
                break;
                 case 'trackmyteam' :
                    $monthstartdate = date('Y-m-01');
                    $currentdate = date('Y-m-d');
                	$startdate = !empty($_REQUEST['sdate']) ? $_REQUEST['sdate'] : $monthstartdate ;
					$enddate = !empty($_REQUEST['sdate']) ? $_REQUEST['edate'] : $currentdate ;
$sql = runloopQuery("SELECT * FROM employee where ID in ('".$teamidstring."') order by ID desc");
$pagearray =  $pagerarray = [];
   $x=1;  foreach($sql as $row)
		{
			$totalleads = runQuery("select count(*) as count from track_work where date(reg_date) between '".$startdate."' and '".$enddate."' and employee_id = '".$row['ID']."'");
			$totalclients = runQuery("select count(*) as count from clients where date(mod_date) between '".$startdate."' and '".$enddate."' and  employee_id = '".$row['ID']."'");
	        $emp_monthly_points = emp_points($row['ID']);
			$employee_performance = employee_performance($row['ID'],$startdate,$enddate);
			$totalmanagers = runQuery("select count(*) as count from employee where role_id = '3' and leader = '".employee_details($row['ID'],'unique_id')."' and status = 1");
			$totalexecutives = runQuery("select count(*) as count from employee where role_id = '4' and leader = '".employee_details($row['ID'],'unique_id')."' and status = 1");

	$pagearray['ID'] =  $x;
	$pagearray['name'] =  $row["fname"]. '('. $row["unique_id"].')';
	$pagearray['designation'] = $row["designation"];
	$pagearray['role'] = roles($row["role_id"]);
	if($row["status"] == 1){
		$pagearray['offerletter'] = SITE_URL.'offerletter/Offer_letters/'.$row["unique_id"].'_offerletter.pdf';
	}
	else{
		$pagearray['offerletter'] = 'Not Generated';
	}
	$pagearray['totalmanagers'] = @$totalmanagers["count"] ?? 0;
	$pagearray['totalexecutives'] = @$totalexecutives["count"] ?? 0;
	$pagearray['leadscount'] = $totalleads["count"];
	$pagearray['clientscount'] = $totalclients["count"];
	$pagearray['pendingclientspoints'] = !empty($emp_monthly_points['pendingclientspoints']) ? $emp_monthly_points['pendingclientspoints'] : 0;   
	$pagearray['approvedclientspoints'] = !empty($emp_monthly_points['approvedclientspoints']) ? $emp_monthly_points['approvedclientspoints'] : 0; 
	$pagearray['rejectclientspoints'] = !empty($emp_monthly_points['rejectclientspoints']) ? $emp_monthly_points['rejectclientspoints'] : 0; 

	$pagearray['calls_taken'] = $employee_performance['calls_taken'];
	$pagearray['hours_of_work'] = $employee_performance['hours_of_work'];
	$pagearray['effieciency'] = $employee_performance['effieciency'];
	$pagerarray[] = $pagearray; 

     $x++; }
     					$payload = array('status'=>'1','trackmyteam'=>$pagerarray);

                break; 
                				case 'pleligibility' : 
					$pagerarray = $pagearray = [];
					if(!empty($_REQUEST['pincode'])){
						$sqlpincode = runloopQuery("SELECT * FROM bankexcel_details  
						where pincode= '".$_REQUEST['pincode']."' ") ;
						$sqlstring = '';
							$banckexcel_id_arr = array_column($sqlpincode,'banckexcel_id');
							$banckexcel_id_string = implode("','",$banckexcel_id_arr);
								$sqlstring .= "select * from bankexcel_details where banckexcel_id in  ('".$banckexcel_id_string."') ";
								if(!empty($_REQUEST['company_name'])) {
									$sqlstring .= " and company_name like '%".$_REQUEST['company_name']."%' ";
								}
								
									if(!empty($_REQUEST['salary'])) {
										$sqlstring .= " and min_salary >= '".$_REQUEST['salary']."' ";
									}
									$sqlstring .= "  order by ID desc";
							
									$sql = runloopQuery($sqlstring);
									$x=1;  
									foreach($sql as $row)
									{
									
									 
									$pagerarray['pincode'] = @$_POST['pincode'] ?? $row['pincode'] ;
									$pagerarray['company_name'] = $row['company_name']; 
									$pagerarray['category'] =$row['category'];
									$pagerarray['bank_name'] = $row['bank_name'];
									$pagerarray['min_salary'] = $row['min_salary'];
									$pagearray[] = $pagerarray; 
									 $x++; }
									 $payload = array('status'=>'1','pagerarray' => $pagearray);

									}
					else{
						$payload = array('status'=>'1',$pagerarray = [],'message'=>'No Details Found');
 					}
				break;
				case 'convertclient' :
					if(!empty($_REQUEST['status']) && !empty($_REQUEST['pointstype']) && !empty($_REQUEST['type_of_client'])
					&& !empty($_REQUEST['variant']) && !empty($_REQUEST['variant_location']) &&  !empty($_REQUEST['track_work_id'])
					) {
						$trackwork = runQuery("select * from track_work where ID = '".$_REQUEST['track_work_id']."' and doc_status = '1'");
						

						$pagerarray  = array();

	   
			if(!empty($trackwork)){ 
				$empid = $trackwork['employee_id'];
				$employeeid = runQuery("select * from employee where ID = '".$empid."'");
				$maxleadtrack = runQuery("select MAX(ID) as id from clients");
		        $maxid = @$maxleadtrack['id']+1;

		        $pagerarray['employee_id'] = $employeeid['ID']; 
                $pagerarray['client_id'] = "M3A".$userdetails['unique_id'].'C'.$maxid;
                $pagerarray['clientname'] = $trackwork['clientname'];
                $pagerarray['mobile'] = $trackwork['mobile'];
                $pagerarray['servicetype'] = $trackwork['selecttype'];
                $pagerarray['loanamount'] = $trackwork['amount'];
                $pagerarray['companyname'] = $trackwork['company'];
                $pagerarray['pointstype'] = mysqli_real_escape_string($conn,$_REQUEST['pointstype']);
                $pagerarray['location'] = $trackwork['address'];
                $pagerarray['type_of_client'] = mysqli_real_escape_string($conn,$_REQUEST['type_of_client']);
                $pagerarray['variant'] = mysqli_real_escape_string($conn,$_REQUEST['variant']);
                $pagerarray['variant_location'] = mysqli_real_escape_string($conn,$_REQUEST['variant_location']);
                $pagerarray['status'] = $_REQUEST['status'];
                $pagerarray['track_work_id'] = mysqli_real_escape_string($conn,$_REQUEST['track_work_id']);
				$pagerarray['mod_date'] = date('Y-m-d H:i:s A');
	            $result = insertQuery($pagerarray,'clients');
				emp_monthly_income_check(['empid' => $employeeid['ID']]);
				$trid['ID'] =mysqli_real_escape_string($conn,$_REQUEST['track_work_id']); 
	                $docstatus['doc_status'] = 2;
                    $result = updateQuery($docstatus,'track_work',$trid);
					if(!$result){
					      $token = employee_details($userdetails['ID'],'token');
							    if(!empty($token)){
							     $trackstory = runQuery("select * from track_work where ID = '".$_REQUEST['track_work_id']."'");
							     if($userdetails['role_id'] == '3'){
							         if($trackstory['employee_id'] == $userdetails['ID']){
							         $token =    lead_details($userdetails['leader'],'token');    
							         }
	     					         else{
	     					             $token = employee_details($trackstory['employee_id'],'token');
	     					         }
	     					     }
	     					     else{
	     					         $token = employee_details($trackstory['employee_id'],'token');
	     					     } 
							    $notificationparam['title'] = 'Client converted';
							    $notificationparam['msg'] = $userdetails['fname'].' '.$userdetails['lname'].'  has updated '.$trackstory['clientname'].' status to '.$_REQUEST['status'];
							    $notificationparam['token'] = $token;
							    $notificationparam['trackId'] = $_REQUEST['track_work_id'];
							    $notificationparam['empId'] = $trackstory['employee_id'];
							    $notificationparam['type'] = 'ClientDetails';
							    sendnotification($notificationparam);
							    }
					    
						$payload = array('status'=>'1','message'=>'Client Created Successfully');
					}
					else{
						$payload = array('status'=>'0','message'=>'Error While Creating Client');
					} 
				}else{ 
					$payload = array('status'=>'0','message'=>'Invalid Track Work Details');
				}

				}
				else{
						$payload = array('status'=>'0','message'=>'Invalid Parameters');
				}
				break;
				case 'updateclient' :
					if(!empty($_REQUEST['status']) && !empty($_REQUEST['pointstype']) && !empty($_REQUEST['type_of_client'])
					&& !empty($_REQUEST['variant']) && !empty($_REQUEST['variant_location']) &&  !empty($_REQUEST['track_work_id'])
					&& !empty($_REQUEST['clientid'])) {
						$trackwork = runQuery("select * from track_work where ID = '".$_REQUEST['track_work_id']."' ");
						

						$pagerarray  = array();

	   
			if(!empty($trackwork)){ 
				$empid = $trackwork['employee_id'];
				$employeeid = runQuery("select * from employee where ID = '".$empid."'");
                $pagerwherearray['ID'] = $_REQUEST['clientid'];
                $pagerarray['clientname'] = $_REQUEST['clientname'];
                $pagerarray['mobile'] = $_REQUEST['mobile'];
                $pagerarray['servicetype'] = $_REQUEST['selecttype'];
                $pagerarray['loanamount'] = $_REQUEST['amount'];
                $pagerarray['companyname'] = $_REQUEST['company'];
                $pagerarray['pointstype'] = mysqli_real_escape_string($conn,$_REQUEST['pointstype']);
                $pagerarray['location'] = $_REQUEST['address'];
                $pagerarray['type_of_client'] = mysqli_real_escape_string($conn,$_REQUEST['type_of_client']);
                $pagerarray['variant'] = mysqli_real_escape_string($conn,$_REQUEST['variant']);
                $pagerarray['variant_location'] = mysqli_real_escape_string($conn,$_REQUEST['variant_location']);
                $pagerarray['status'] = $_REQUEST['status'];
                $pagerarray['track_work_id'] = mysqli_real_escape_string($conn,$_REQUEST['track_work_id']);
				$pagerarray['mod_date'] = date('Y-m-d H:i:s A');
	            $result = updateQuery($pagerarray,'clients',$pagerwherearray);
				if(!$result){
				    			$token = employee_details($userdetails['ID'],'token');
							    if(!empty($token)){
							     $trackstory = runQuery("select * from track_work where ID = '".$_REQUEST['track_work_id']."'");
							     if($userdetails['role_id'] == '3'){
	     					         if($trackstory['employee_id'] == $userdetails['ID']){
							            $token =    lead_details($userdetails['leader'],'token');    
							         }
	     					         else{
	     					             $token = employee_details($trackstory['employee_id'],'token');
	     					         }
	     					     }
	     					     else{
	     					         $token = employee_details($trackstory['employee_id'],'token');
	     					     } 
							    $notificationparam['title'] = 'Client Details Updated';
							    $notificationparam['msg'] = $userdetails['fname'].' '.$userdetails['lname'].'  has updated '.$trackstory['clientname'].' details';
							    $notificationparam['token'] = $token;
							    $notificationparam['trackId'] = $_REQUEST['track_work_id'];
							    $notificationparam['empId'] = $trackstory['employee_id'];
							    $notificationparam['type'] = 'ClientDetails';
							    sendnotification($notificationparam);
							    }
						$payload = array('status'=>'1','message'=>'Client Updated Successfully');
					}
					else{
						$payload = array('status'=>'0','message'=>'Error While Updating Client');
					} 
				}else{ 
					$payload = array('status'=>'0','message'=>'Invalid Track Work Details');
				}

				}
				else{
						$payload = array('status'=>'0','message'=>'Invalid Parameters');
				}
				break;
								case 'insertdpr' : 
					if(!empty($_REQUEST['remarks'])){
						$pagerarray = [];
						$pagerarray['title'] = mysqli_real_escape_string($conn,$_REQUEST['title']);
						$pagerarray['remarks'] = mysqli_real_escape_string($conn,$_REQUEST['remarks']);
						$pagerarray['employee_id'] = mysqli_real_escape_string($conn,$_REQUEST['usersid']);
						$result = insertQuery($pagerarray,'employee_dpr');
						if(!$result){
							$payload = array('status'=>'1','message'=>'Data Added Successfully');
						}
						else{
							$payload = array('status'=>'0','message'=>'Error While Data Adding');
						}
					}
					else{
						$payload = array('status'=>'0','message'=>'Please Enter Remarks');

					}
				break;	
				case 'employeedpr' :
				$role_id =	employee_details($usersid,'role_id');
				$start_date =   mysqli_real_escape_string($conn,$_REQUEST['sdate']);
				$end_date =   mysqli_real_escape_string($conn,$_REQUEST['edate']);
				$start_date = !empty($start_date) ? $start_date : date('Y-m-d');
				$end_date = !empty($end_date) ? $end_date : date('Y-m-d');

				if($role_id == '4'){
					$sqlstring = ("SELECT * FROM employee_dpr 
					where employee_id = '".$userdetails['ID']."' and date(reg_date) between '".$start_date."' and '".$end_date."'  order by ID desc");
				}
				else{
                          if($userdetails['role_id'] == '3' && $_REQUEST['teamlist'] == 2){
			        	      	$sqlstring = ("SELECT * FROM employee_dpr 
					            where employee_id in  ('".$teamidstring."') and date(reg_date) between '".$start_date."' and '".$end_date."'  order by ID desc");
			        	  }
			        	  else if($userdetails['role_id'] == '3' && $_REQUEST['teamlist'] == 1){
			        	      	$sqlstring = ("SELECT * FROM employee_dpr 
					            where employee_id in  ('".$teamidstring."') and employee_id != '".$usersid."' and date(reg_date) between '".$start_date."' and '".$end_date."'  order by ID desc");
			        	  }
			        	  else{
  			        	      	$sqlstring = ("SELECT * FROM employee_dpr 
					            where  employee_id = '".$usersid."' and date(reg_date) between '".$start_date."' and '".$end_date."'  order by ID desc");
			        	  }
				    

				}
				$sql = runloopQuery($sqlstring);
				$pagerarray = [];
				$pagerarray = $sql;
				
				 $payload = array('status'=>'1','dprdetails' => $pagerarray);
				break;
				case 'addabbhistory':
					if(!empty($_REQUEST['name']) && !empty($_REQUEST['mobile']) ){
						$pagearray = [];
						$pagearray['name'] = $_REQUEST['name'];
						$pagearray['emp_id'] = $usersid;
						$pagearray['mobile_number'] = $_REQUEST['mobile'];
						$pagearray['location'] = $_REQUEST['location'];
						$result = insertQuery($pagearray,'abb_history');
						if(!$result){
							$payload = array('status'=>'1','message'=>'Add Details Successfully');	
						}
						else{
							$payload = array('status'=>'0','message'=>'Error !!');
						}
					}
					else{
						$payload = array('status'=>'0','message'=>'Invalid Parameters');

					}
				break;
				case 'updateclientstatus':
				if(!empty($_REQUEST['status']) && !empty($_REQUEST['clientid'])){
				    	$clientupdatearray['status'] = trim($_REQUEST['status']);
						$clientwherearray['ID'] = $_REQUEST['clientid'];
							$result = updateQuery($clientupdatearray,'clients',$clientwherearray);    
							if(!$result){
							    
							    $token = employee_details($userdetails['ID'],'token');
							    if(!empty($token)){
							     $trackstory = runQuery("select tw.* from track_work tw inner join clients c on tw.ID = c.track_work_id  where c.ID = '".$_REQUEST['clientid']."'");
							     if($userdetails['role_id'] == '3'){
	     					         if($trackstory['employee_id'] == $userdetails['ID']){
							            $token =    lead_details($userdetails['leader'],'token');    
							         }
	     					         else{
	     					             $token = employee_details($trackstory['employee_id'],'token');
	     					         }
	     					     }
	     					     else{
	     					         $token = employee_details($trackstory['employee_id'],'token');
	     					     } 
							    $notificationparam['title'] = 'Client Details Updated';
							    $notificationparam['msg'] = $userdetails['fname'].' '.$userdetails['lname'].'  has updated '.$trackstory['clientname'].' status as '.trim($_REQUEST['status']);
							    $notificationparam['token'] = $token;
							    $notificationparam['trackId'] = $trackstory['ID'];
							    $notificationparam['empId'] = $trackstory['employee_id'];
							    $notificationparam['type'] = 'ClientDetails';
							    sendnotification($notificationparam);
							    }
							    
							$payload = array('status'=>'1','message'=>'Details Updated Successfully');	
						}
						else{
							$payload = array('status'=>'0','message'=>'Error !!');
						}
				}
				else{
						$payload = array('status'=>'0','message'=>'Invalid Parameters');

				}    
			    break;
			    case 'getboosterkit' :
			       $sql = runloopQuery("select * from booster_kit");
			       if(!empty($sql)){

			       $tree = $pagerarray = $pagererarray = [];
			       
			       for($i=0;$i<count($sql);$i++){
			          $hierarchy =  $sql[$i]['hierarchy'];
			          $consume = 1;
			          if($hierarchy == 3){
    			          if($hierarchy == $userdetails['role_id']){
    			            $consume = 1;        
    			          }
    			          else{
    			              $consume = 0;
    			          }
			              
			          }
			          else if($hierarchy == 4){
			              if($hierarchy == $userdetails['role_id']){
    			            $consume = 1;        
    			          }
    			          else{
    			              $consume = 0;
    			          }
			          }
			          else {
			              $consume = 1;
			          }
			        if($consume == 1){
			          $superadmin =  explode(',',$sql[$i]['super_admin_options']);
			          for($j=0;$j<count($superadmin);$j++){
                        $user_unique_id = employee_details($superadmin[$j],'unique_id');
                        $tree = employeehirerachy($user_unique_id);
                        $treeids = array_column($tree,'ID');
    			          if(in_array($usersid,$treeids)){
    			              $pagerarray['kit_name'] = $sql[$i]['kit_name'];
    			              $pagerarray['kit_doc_path'] = SITE_URL.'/boosterkitimages/'.$sql[$i]['kit_doc_path'];
    			          
    			              $pagererarray[] = $pagerarray;
    			          }
			          }
			       }
			       
			          		

			          
                    unset($tree);
			       }
			            $payload = array('status'=>'1','boosterkit'=>$pagererarray);
			       }else{
			            $payload = array('status'=>'1','boosterkit'=>$pagererarray);   
			       }
			       
			       	
			     break;   
        case 'getpointvariant':
            $sql = runloopQuery("select variant from points_set_variant where points_id = '".$_REQUEST['pointsetid']."'");
            $payload = array('status'=>'1','variant'=>$sql);
            
        break;
        case 'getvariantlocation':
            $sql = runloopQuery("select variant_location from points_set_variant where points_id = '".$_REQUEST['pointsetid']."' and variant = '".$_REQUEST['variantpopupid']."'");
            $payload = array('status'=>'1','variantlocation'=>$sql);
        break;
        case 'documentverify':
            $res = runQuery("select * from track_work_documents where ID = '".$_REQUEST['doc_id']."'");
            if(!empty($res)){
                		$docwherearray = $userarray = array();
						$docarray['is_verified'] = 1;
						$docarray['verified_by'] = $_REQUEST['usersid'];
						$docarray['verified_datetime'] = date('Y-m-d H:i:s');
						$docwherearray['ID'] = $_REQUEST['doc_id'];
						
						
						$result = updateQuery($docarray,'track_work_documents',$docwherearray);
                        $payload = array('status'=>'1','message'=>'Document Verified Successfully');
            }
            else{
                $payload = array('status'=>'1','message'=>'Unable to locate document!!');
            }
        break;
        		case 'eligibility-request':
			if(!empty($_REQUEST['track_work_id'])){
				$track_work_id = $_REQUEST['track_work_id'];
				$leaddetails = runQuery("SELECT * FROM track_work where ID = '".$track_work_id."'");
				if(!empty($leaddetails)){
					$reqwherearray = $reqarray = array();
					$reqarray['eligibility_requested_by'] = $usersid;
					$reqarray['eligibility_requested_time'] = date('Y-m-d H:i:s');
					$reqarray['eligibility_request_status'] = 1;
					$reqwherearray['ID'] = $track_work_id;
					$result = updateQuery($reqarray,'track_work',$reqwherearray);
					if(!$result){
						$payload = ['status' => 1,'message' => ' Eligibility Requested Successfully!!'];
					}
					else{
						$payload = ['status' => 0,'message' => 'Error Occured While Requesting Eligibility!!'];	
					}
				}
				else{
					$payload = ['status' => 0,'message' => 'Invalid Lead Details'];	
				}
			}
			else{
				$payload = ['status' => 0,'message' => 'Please Provide Lead Details'];
			}
		break;
				case 'eligibility-accept':
			if(!empty($_REQUEST['track_work_id'])){
				$track_work_id = $_REQUEST['track_work_id'];
				$leaddetails = runQuery("SELECT * FROM track_work where ID = '".$track_work_id."' and eligibility_request_status = 1");
				if(!empty($leaddetails)){
					$reqwherearray = $reqarray = array();
					$reqarray['eligibility_accepted_by'] = $usersid;
					$reqarray['eligbility_accepted_time'] = date('Y-m-d H:i:s');
					$reqarray['eligibility_request_status'] = 2;
					$reqwherearray['ID'] = $track_work_id;
					$result = updateQuery($reqarray,'track_work',$reqwherearray);
					if(!$result){
						//company
						$sqlpincode = runloopQuery("select * from pl_pincode where pin_code = '".$leaddetails['pincode']."'");
						if(!empty($sqlpincode)){
							$bankid_arr = array_column($sqlpincode,'bank_id');
							$bankidstring = implode("','",$bankid_arr);
							$sqlcompany = runloopQuery("select * from pl_company_category 
							where company_name = '".$leaddetails['company']."' and bank_id in ('".$bankidstring."') ");
							$companiecat_arr = array_column($sqlcompany,'company_category');
							$catstring = implode("','",$companiecat_arr);
							
							$sqldata = runloopQuery("select pd.*,bank_name from pl_data pd inner join pl_banks pb on pb.ID = pd.bank_id where pd.company_category in ('".$catstring."')  and pd.salary_start <= '".$leaddetails['income']."' 
							and pd.salary_end >= '".$leaddetails['income']."'");
							for($i=0,$j=0;$i<count($sqldata);$i++) {
	
								$pl_category_tenure = runQuery("select * from pl_category_tenure where bank_id='".$sqldata[$i]['bank_id']."' and company_category='".$sqldata[$i]['company_category']."'");
								$roi = $sqldata[$i]['r1'];
								$roi_year = ( $roi / (12 * 100) ); 
								$foir = $sqldata[$i]['foir'];
								$tenure = $sqldata[$i]['tenure'];
								$obligation =  !empty($leaddetails['obligation']) ? $leaddetails['obligation'] : 0 ;
								$emi = ($leaddetails['income']  * ($foir/100)) -  $obligation;
								$actual_loan_amount = (( $emi * ( pow(1+$roi_year,$tenure) -1) ) / ($roi_year * pow(1+$roi_year,$tenure)));
								if(is_integer($sqldata[$i]['multiplier'])){
									$multiplier = $sqldata[$i]['multiplier'] * $leaddetails['income'];    
									$multiplier_loan_amount =  min([$actual_loan_amount,$multiplier]);
								}
								else{
									$multiplier_loan_amount = $actual_loan_amount;
								}
								
								$loan_amount =  min([$multiplier_loan_amount,$sqldata[$i]['max_loan_amount']]);
								if(!empty($loan_amount) && $loan_amount > 0){
									$leadeligibility = [];
									$leadeligibility['bank_id'] = $sqldata[$i]['bank_id'];
									$leadeligibility['bank_name'] = $sqldata[$i]['bank_name'];
									$leadeligibility['roi'] = $roi;
									$leadeligibility['foir'] = $sqldata[$i]['foir'];
									$leadeligibility['tenure'] = $tenure;
									$leadeligibility['processing_fee'] = $sqldata[$i]['processing'];
									$leadeligibility['proposed_emi'] = $emi;
									$leadeligibility['loan_amount'] = round($loan_amount,2);
									$leadeligibility['track_work_id'] = $track_work_id;
									$leadeligibility['created_by'] = $usersid;
									
									insertQuery($leadeligibility,'lead_eligbility_details');
									unset($leadeligibility);	
								}
							}
					
						}
					


						$payload = ['status' => 1,'message' => ' Eligibility Accepted Successfully!!'];
					}
					else{
						$payload = ['status' => 0,'message' => 'Error Occured While Accepting Eligibility!!'];	
					}
				}
				else{
					$payload = ['status' => 0,'message' => 'Invalid Lead Details'];	
				}
			}
			else{
				$payload = ['status' => 0,'message' => 'Please Provide Lead Details'];
			}		
		break;
		case 'lead_bank_eligibility_list':
			if(!empty($_REQUEST['track_work_id'])){
				$track_work_id = $_REQUEST['track_work_id'];
				$leaddetails = runQuery("SELECT * FROM track_work where ID = '".$track_work_id."'");
				if(!empty($leaddetails)){
					$sql = runloopQuery("select led.*,pb.bank_name from lead_eligbility_details led 
					inner join pl_banks pb on  led.bank_id = pb.ID where led.track_work_id = '".$track_work_id."'");
					$payload = ['status' => 0,'bank_list' => $sql];
				}
				else{
					$payload = ['status' => 0,'message' => 'Invalid Lead Details'];	
				}
			}
			else{
				$payload = ['status' => 0,'message' => 'Please Provide Lead Details'];
			}

		break;
		case 'eligible_bank_details':
			if(!empty($_REQUEST['bank_name'])){
			    $superadmin_id = getsuperadmin($usersid);
			    
			    $bank_name = $_REQUEST['bank_name'];
			    $entity_details = runQuery("SELECT * FROM entity where entity_name = '".$bank_name."' and created_by = '".$superadmin_id."'");
			    if(!empty($entity_details)){
			        $location_details = runloopQuery("select * from entity_location where entity_id = '".$entity_details['ID']."'");
                    $servicetypes = [];
                    if(!empty($entity_details['worksource_type'])){
                      $servicetypes =   runloopQuery("select ID,title from worksource where ID in (".$entity_details['worksource_type'].")");
                    }
                    $pincode_details = runloopQuery("select distinct pincode from entity_staff_pincode where entity_id = '".$entity_details['ID']."'");
                    $data = ['entity_details' => $entity_details,'location'=>$location_details, 'servicetypes' => $servicetypes, 'pincode_details' => $pincode_details];
                    $payload = ['status' => 1,'data' => $data];
			    }
			    else{
			        $payload = ['status' => 0,'message' => 'Invalid Entity Details'];
			   }
			}
			else{
				$payload = ['status' => 0,'message' => 'Please Provide Bank Details'];
			}
		break;
		case 'staff_details':
			if(!empty($_REQUEST['bank_name'])){
			    $bank_name = $_REQUEST['bank_name'];
			    $superadmin_id = getsuperadmin($usersid);
			    $entity_details = runQuery("SELECT * FROM entity where entity_name = '".$bank_name."' and created_by = '".$superadmin_id."'");
			    if(!empty($entity_details)){
			        $staff_details = runloopQuery("select ef.* from entity_staff ef inner join entity_staff_pincode esp on ef.ID = esp.staff_id 
			        where ef.entity_id = '".$entity_details['ID']."' and esp.pincode = '".$_REQUEST['pincode']."'");
			        $payload = ['status' => 1,'staff_details' => $staff_details];

			    }
			    else{
			        $payload = ['status' => 0,'message' => 'Invalid Entity Details'];
		         }
			}
			else{
				$payload = ['status' => 0,'message' => 'Please Provide Bank Details'];
			}
			break;
		case 'add_lead_application':

            if(!empty($_REQUEST['track_work_id'])){
				$track_work_id = $_REQUEST['track_work_id'];
				$leaddetails = runQuery("SELECT * FROM track_work where ID = '".$track_work_id."'");
				$leadeligbilitydetails = runQuery("SELECT * FROM lead_eligbility_details where track_work_id = '".$track_work_id."'");

				
				if(!empty($leaddetails)){
				    			    $superadmin_id = getsuperadmin($usersid);
			    $entity_details = runQuery("SELECT * FROM entity where entity_name = '".$_REQUEST['entity_id']."' and created_by = '".$superadmin_id."'");
			    
				  $leadappmaxid = runQuery("select MAX(ID) maxid from lead_application");
				  $leadappid = !empty(@$leadappmaxid) ? ($leadappmaxid['maxid'] + 1) : 1;
                                 $pagerarray['track_work_id'] = $_REQUEST['track_work_id'];
                                 $pagerarray['application_id'] = 'LOS-APP-'.$leadappid;
                                 $pagerarray['applicant_name'] = $leaddetails['clientname'];
                                 $pagerarray['applicant_mobile'] = $leaddetails['mobile'];
                                 $pagerarray['applicant_alt_mobile'] = $leaddetails['additional_number'];
                                 $pagerarray['entity_id'] = $entity_details['ID'];
                                 $pagerarray['service_type'] = $_REQUEST['service_type'];
                                 $pagerarray['deal_amount'] = $_REQUEST['loan_amount'];
                                 $pagerarray['roi'] = $_REQUEST['roi'];
                                 $pagerarray['emi'] = $_REQUEST['proposed_emi'];
                                 $pagerarray['tenure'] = $_REQUEST['tenure'];
                                 $pagerarray['staff_id'] = $_REQUEST['staff_id'];
                                 $pagerarray['pincode'] = $_REQUEST['pincode'];
                                 $pagerarray['location_id'] = $_REQUEST['location_id'];

                                 //$pagerarray['followup'] = $_REQUEST['followup'];
                                 //$pagerarray['remarks'] = $_REQUEST['remarks'];
                                 $pagerarray['status'] = 1;
                                 $pagerarray['reg_date'] = date('Y-m-d H:i:s');
                                 
                                 $pagerarray['created_by'] = $usersid;
    							$result = insertQuery($pagerarray,'lead_application');
    							if(!$result){
    							    $payload = ['status' => 1,'message' => 'Application Added Successfully'];
    							}
    							else{
    							    $payload = ['status' => 1,'message' => 'Error While Adding Application'];
    							}				    
				    
				}
				else{
					$payload = ['status' => 0,'message' => 'Invalid Lead Details'];	
				}
			}
			else{
				$payload = ['status' => 0,'message' => 'Please Provide Lead Details'];
			}    


		break;   
        case 'get_lead_applications':
            if(!empty($_REQUEST['track_work_id'])){
				$track_work_id = $_REQUEST['track_work_id'];
				$leaddetails = runQuery("SELECT * FROM track_work where ID = '".$track_work_id."'");
				if(!empty($leaddetails)){
				    $sql_lead_details = "select la.*,e.ID entity_id,e.entity_name,w.title as service_name,el.location location_name,concat(es.first_name,' ',es.last_name) staff_name
				    ,es.mobile_number,es.email from lead_application la 
				    left join entity e on e.ID = la.entity_id
				    inner join worksource w on w.ID = la.service_type
				    left join entity_staff es on es.ID = la.staff_id
				    left join entity_location el on el.ID = la.location_id
				    where la.track_work_id = '".$track_work_id."'";
				    if(!empty($_REQUEST['app_id'])){
				        $sql_lead_details .= " and la.ID = '".$_REQUEST['app_id']."'";
				    }
				    $lead_details = runloopQuery($sql_lead_details);
				        	$payload = ['status' => 1,'application_details' => $lead_details];

				}
			    else{
			        $payload = ['status' => 0,'message' => 'Invalid Entity Details'];
		         }
			}
			else{
				$payload = ['status' => 0,'message' => 'Please Provide Bank Details'];
			}
        break;
        case 'role_base_dedignations':
            $sql = runloopQuery("select * from tbl_designations where role_under = '".$_REQUEST['role_id']."' and status = 1");
        	$payload = ['status' => 1,'designations' => $sql];
            
        break;
        case 'acknowledge_application':
                            $leadapplicationdetails =runQuery("select * from lead_application where ID = '".$_REQUEST['app_id']."'");
                                 $pagerarray['application_id'] = $_REQUEST['application_id'];
                                 $pagerarray['applicant_name'] = $_REQUEST['applicant_name'];
                                 $pagerarray['applicant_mobile'] = $_REQUEST['applicant_mobile'];
                                 $pagerarray['applicant_alt_mobile'] = $_REQUEST['applicant_alt_mobile'];
                                 $pagerarray['entity_id'] = $_REQUEST['entity_id'];
                                 $pagerarray['service_type'] = $_REQUEST['service_type'];
                                 $pagerarray['deal_amount'] = $_REQUEST['deal_amount'];
                                 $pagerarray['roi'] = $_REQUEST['roi'];
                                 $pagerarray['emi'] = $_REQUEST['emi'];
                                 $pagerarray['tenure'] = $_REQUEST['tenure'];
                                 $pagerarray['staff_id'] = $_REQUEST['staff_id'];
                                 $pagerarray['pincode'] = $_REQUEST['pincode'];
                                 $pagerarray['location_id'] = $_REQUEST['location_id'];

                                 $pagerarray['followup'] = $_REQUEST['followup'];
                                 $pagerarray['remarks'] = $_REQUEST['remarks'];
                                 $pagerfollowuparray['followup_entity'] = $_REQUEST['followup_entity'];
                                 $pagerfollowuparray['followup_staff_id'] = $_REQUEST['followup_staff_id'];
                                 //echo $_REQUEST['followup'];exit;
                                 $pagerarray['status'] = 2;
                                 //$pagerarray['reg_date'] = date('Y-m-d H:i:s');
                                 $pagewherearray['ID'] = $_REQUEST['app_id'];
                                 $result = updateQuery($pagerarray,'lead_application',$pagewherearray);
                                 if(!$result){
                                 	$trid['ID'] = $leadapplicationdetails['track_work_id']; 
	                                $docstatus['eligibility_request_status'] = 3;
                                    updateQuery($docstatus,'track_work',$trid);    
                                 }
                                 
                                 if(!empty($_REQUEST['followup'])){
                                     $followuparray['app_id'] = $_REQUEST['app_id'];
                                     $followuparray['entity_id'] = $_REQUEST['entity_id'];
                                     $followuparray['staff_id'] = $_REQUEST['staff_id'];
                                     $followuparray['followup'] = $_REQUEST['followup'];
                                     $followuparray['remarks'] = $_REQUEST['remarks'];
    							     $followupresults = insertQuery($followuparray,'lead_application_followups');

                                 }
    
                                 $payload = array('status'=>'1','message'=>'Updated the lead application');

        break;
        case 'entity-list':
			    $superadmin_id = getsuperadmin($usersid);
			    $entity_type = $_REQUEST['entity_type'];
			    if(!empty($entity_type)){
                    $entity_list = runloopQuery("select * from entity where created_by = '".$superadmin_id."'");			        
			    
			        $payload = ['status' => '1','entity_list'=> $entity_list];
			    }
			    else{
			        $payload = ['status' => '0','message'=> 'Please provide entity type'];
			    }
            
        break; 
        case 'get_application_followups':
            $sql = "select laf.*,laf.entity_id,entity_name,concat(es.first_name,' ',es.last_name) staff_name
            ,es.mobile_number,es.whatsapp_number,es.email from (select laf.* from (
                SELECT max(reg_date) reg_date,app_id FROM `lead_application_followups` 
                group by app_id
            ) A inner join lead_application_followups laf on laf.app_id = A.app_id and A.reg_date = laf.reg_date) laf 
            inner join entity e on laf.entity_id = e.ID
            left join entity_staff es on es.ID = laf.staff_id";
            if(!empty($_REQUEST['app_id'])){
                $sql .= " where laf.app_id = '".$_REQUEST['app_id']."'";    
            }
            
            $res = runloopQuery($sql);
            
            $payload = ['status' => '1','application_followups'=> $res];
        break;
        case 'add_followups':
                if(!empty($_REQUEST['app_id'])){ 
                 $followuparray['app_id'] = $_REQUEST['app_id'];
                 $followuparray['entity_id'] = $_REQUEST['entity_id'];
                 $followuparray['staff_id'] = $_REQUEST['staff_id'];
                 $followuparray['followup'] = $_REQUEST['followup'];
                 $followuparray['remarks'] = $_REQUEST['remarks'];
			     $followupresults = insertQuery($followuparray,'lead_application_followups');
        $payload = ['status' => '1','message'=> 'Follow up added successfully'];
                }
                else{
                    $payload = ['status' => '0','message'=> 'Please Provide Application Id'];
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