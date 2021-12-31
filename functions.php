<?php

include('dbconfig.php');

date_default_timezone_set('Asia/Kolkata');

ini_set('display_errors', 'On');

ini_set('log_errors', 'On');

define('SITE_URL','https://superpilot.in/dev/m3avenue.com/');

define('MAIN_URL','https://localhost/');

define('EMPLOYEE_IMAGE',SITE_URL.'executiveimage/');
define('PAGE_URL',SITE_URL.'admin/');
define('MAILID','info@m3avenue.com');
define('_ROLES',['1' => 'Admin','2' => 'Super Admin','3' => 'General Manager','4' => 'Executive','5' => 'Ops Manager','5' => 'Ops Executive']);



function insertQuery($query,$table){

	include('dbconfig.php');

	$query['reg_date'] = date('Y-m-d H:i:s');

	$keys = array_keys($query);



$sql = "INSERT INTO ".$table." SET ";



for($e=0;$e<sizeof($keys);$e++) {



$sql .=  $keys[$e].'="'.$query[$keys[$e]].'"';



if($e != sizeof($keys)-1) { $sql .= ','; }



}

//echo $sql;exit;

$result = mysqli_query($conn,$sql);

if(!$result) {

	return $conn->error;

}

}


function insertIDQuery($query,$table){

	include('dbconfig.php');

	$query['reg_date'] = date('Y-m-d H:i:s');

	$keys = array_keys($query);



$sql = "INSERT INTO ".$table." SET ";



for($e=0;$e<sizeof($keys);$e++) {



$sql .=  $keys[$e].'="'.$query[$keys[$e]].'"';



if($e != sizeof($keys)-1) { $sql .= ','; }



}



$result = mysqli_query($conn,$sql);


if($result) {

	return $conn->insert_id;

}

}



function updateQuery($query,$table,$wherec){

	include('dbconfig.php');

	

	$arr_keys = array_keys( $query );

	

	$second_arr_keys = array_keys( $wherec );

	

	$sql = "UPDATE ".$table." SET ";

	

	for($y=0;$y<sizeof($arr_keys);$y++) {

		

		$sql .=  $arr_keys[$y].' = "'.$query[$arr_keys[$y]].'"';



        if($y != sizeof($arr_keys)-1) { $sql .= ','; }

		

	}

	

	$sql .= " WHERE ";

	

	for($x=0;$x<sizeof($second_arr_keys);$x++) {

		

		$sql .=  $second_arr_keys[$x].'="'.$wherec[$second_arr_keys[$x]].'"';



        if($x != sizeof($second_arr_keys)-1) { $sql .= ' AND '; }

		

	}

	$result = mysqli_query($conn,$sql);



	if(!$result) {

return $conn->error;

	}
}

function deleteQuery($wherec,$table){

	include('dbconfig.php');

 $second_arr_keys = array_keys( $wherec );



        $sql = "DELETE FROM ".$table." ";



        $sql .= " WHERE ";



        for($x=0;$x<sizeof($second_arr_keys);$x++) {



            $sql .=  $second_arr_keys[$x].'="'.$wherec[$second_arr_keys[$x]].'"';



            if($x != sizeof($second_arr_keys)-1) { $sql .= ' AND '; }



        }

$result = mysqli_query($conn,$sql);

     

	if(!$result) {



		return $conn->error;

	}

    }

function runQuery($query){

	include('dbconfig.php');

$data = array();

$result = $conn->query($query);



    // output data of each row

    while($row = $result->fetch_assoc()) {

       $data = $row;

    }

      return $data;

}

function runloopQuery($query){

	include('dbconfig.php');

	$data = array();

	$result = $conn->query($query);

    // output data of each row

    while($row = $result->fetch_assoc()) {

       $data[] = $row;

    }

      return $data;

}

function filter_string($string){

	$string = preg_replace('/\s+/', '-', $string);

	return $string;

}

function unfilter_string($string){

	$string = preg_replace('/-+/', ' ', $string);

	return $string;

}

function current_adminid(){

	$value = "";

if(!empty($_SESSION['m3avenue_adminid'])){

	return $value = $_SESSION['m3avenue_adminid'] ?: "";

	

}

}

function user_adminrole(){

	$value = "";

if(!empty($_SESSION['m3avenue_adminrole'])){

	return $value = $_SESSION['m3avenue_adminrole'] ?: "";

}

}


function current_employeeid(){

	$value = "";
if(!empty($_SESSION['m3avenue_employeeid'])){

	return $value = $_SESSION['m3avenue_employeeid'] ?: "";

	

}

}
function current_teammemberid(){

	$value = "";

if(!empty($_SESSION['m3avenue_teammemberid'])){

	return $value = $_SESSION['m3avenue_teammemberid'] ?: "";

}

}

function current_managerid(){

	$value = "";

if(!empty($_SESSION['m3avenue_managerid'])){

	return $value = $_SESSION['m3avenue_managerid'] ?: "";

}

}

function current_userid(){

	$value = "";

if(!empty($_SESSION['m3avenue_id'])){

	return $value = $_SESSION['m3avenue_id'] ?: "";

	}

}

function user_role(){

	$value = "";

if(!empty($_SESSION['m3avenue_role'])){

	return $value = $_SESSION['m3avenue_role'] ?: "";
}

}
function reg_date($date){
	return date('d/m/Y H:i:s',strtotime($date));
}

$admindata = runQuery("select ID from superadmin where email='info@m3avenue.com'");

if(empty($admindata)){

$password = password_hash("123456", PASSWORD_DEFAULT);

mysqli_query($conn,"insert into superadmin (username,email,password,reg_date) values('m3avenue','info@m3avenue.com','".$password."','".date('Y-m-d H:i:s')."')");

}

function get_option($optionname){

include("dbconfig.php");

$optionvalue = runQuery("select option_value from options where option_name='".$optionname."'");
if(!empty($optionvalue['option_value'])){
return $optionvalue['option_value'];

}
}

function update_option($optionname,$newdata){

include("dbconfig.php");

$optionvalue = runQuery("select ID from options where option_name='".$optionname."'");

if(!empty($optionvalue['ID'])){

mysqli_query($conn,"update options set option_value = '".$newdata."' where ID = ".$optionvalue['ID']."");

}else{

mysqli_query($conn,"insert into options (option_name,option_value)values('".$optionname."','".$newdata."')");

}

}

function get_campaignuser_options($campaignuserid,$optionname){

include("dbconfig.php");

$optionvalue = runQuery("select value from campaign_options where name='".$optionname."' and user_id='".$campaignuserid."'");
if(!empty($optionvalue['value'])){
return $optionvalue['value'];

}
}

function update_campaignuser_options($campaignuserid,$optionname,$newdata){

include("dbconfig.php");

$optionvalue = runQuery("select ID from campaign_options where name='".$optionname."' and user_id = '".$campaignuserid."'");

if(!empty($optionvalue['ID'])){

mysqli_query($conn,"update campaign_options set value = '".$newdata."' where ID = ".$optionvalue['ID']."");

}else{

mysqli_query($conn,"insert into campaign_options (user_id,name,value,reg_date)values('".$campaignuserid."','".$optionname."','".$newdata."','".date('Y-m-d H:i:s')."')");

}

}
 

function user_status(){

	include('dbconfig.php');

	$userid =   current_adminid();

	$user_role = user_adminrole();

		if($user_role){

			$table = $user_role=='superadmin' ? 'superadmin' : 'user';

		$student_name = runQuery("select status from $table where ID = $userid");

		$name = $student_name['status'];

		 

		if($name=='Active'){

	return $name;

		} 

		}

}
function encrypt($string) {
	$action= 'encrypt';
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'This is my secret key';
    $secret_iv = 'This is my secret iv';
    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}
function decrypt($string) {
    $output = false;
	$action= 'decrypt';
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'This is my secret key';
    $secret_iv = 'This is my secret iv';
    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}
function admin_id($id,$role=false){

	include('dbconfig.php');

	$userid =   $id;

	$user_role = $role ?: 'student';

		$adminid = runQuery("select user_id,user_role from user where role='".$user_role."' and ID = ".$userid."");

		$name = $adminid['user_role']=='admin' ? $adminid['user_id'] : '';

	return $name;

}

function lecturer_id($id=false){

	include('dbconfig.php');

	$userid =   $id ?: current_userid();

	$user_role = user_role();

		$adminid = runQuery("select user_id from user where role='student' and ID = $userid");

		$name = $adminid['user_id'];

		 $lecturerid = runloopQuery("select ID from user where role='lecturer' and user_id = $name and user_role = 'admin'");

		$lecturer = array_column($lecturerid, 'ID');

		

	return implode(',',$lecturer);

}

function user_image($id=false){

	include('dbconfig.php');

	$userid =   $id ?: current_userid();

	$user_role = user_role();

		if(!empty($userid)){

			 $lecturer_name = runQuery("select profilepic,role from user where ID = $userid");

		

		if($lecturer_name['profilepic']){

			if($lecturer_name['role']=='student'){

			$name = SITE_URL.$lecturer_name['profilepic'];

				

			}else{

			$name = PAGE_URL.$lecturer_name['profilepic'];

				

			}

		}else{

			$name = SITE_URL.'images/studentimage.jpeg';

		}

	}else{

		$name = SITE_URL.'images/studentimage.jpeg';

	}

	return $name;

}
function executive_image($id=false){

	include('dbconfig.php');

	$userid =   $id ?: current_userid();

	$user_role = user_role();

		if(!empty($userid)){

			 $lecturer_name = runQuery("select profilepic from employee where ID = $userid");
 
		if($lecturer_name['profilepic']){
 
			$name = SITE_URL.'executiveimage/'.$lecturer_name['profilepic'];
 
		}else{

			$name = SITE_URL.'images/studentimage.jpeg';

		}

	}else{

		$name = SITE_URL.'images/studentimage.jpeg';

	}

	return $name;

}

function employee_id($unique_id){

	include('dbconfig.php');
    $name = '';
		if(!empty($unique_id)){
 
		$student_name = runQuery("select ID from employee where unique_id = '".$unique_id."'");
		if(!empty($student_name['ID'])){
		$name = $student_name['ID'];
		} 
		} 
	return $name;

}
function lead_details($unique_id,$type=null){

	include('dbconfig.php');
 		if(!empty($unique_id)){
 
		$student_name = runQuery("select * from employee where unique_id = '".$unique_id."'");
		    if(!empty($type)){
		        return   @$student_name[$type];
		    }
	    	else{
		        return    $student_name;
    		}
		} 


}
function user_id($unique_id){

	include('dbconfig.php');
 
		if(!empty($unique_id)){
 
		$student_name = runQuery("select ID from user where unique_id = '".$unique_id."'");

		$name = $student_name['ID'];
 

		}

	return $name;

}

function employee_details($id,$type = null){
	include('dbconfig.php');
	$details = '';
	if(!empty($id)){
		$student_details = runQuery("select * from employee where ID = $id");
		if(!empty($type)){
			$details = $student_details[$type];
		}
		else{
			$details = $student_details;
		}
	}
	return $details;
}
function user_details($id,$type){

	include('dbconfig.php');
 $name='';
		if(!empty($id)){
 
		$student_name = runQuery("select * from user where ID = $id");
		if(!empty($student_name[$type])){
		$name = $student_name[$type];
		} 
		}

	return $name;

}
function campaign_details($id,$type){

	include('dbconfig.php');
 $name='';
		if(!empty($id)){ 
		$student_name = runQuery("select * from campaigns where ID = $id");
		if(!empty($student_name[$type])){
		$name = $student_name[$type];
		} 
		}

	return $name;

}
function pointset_details($id,$type){

	include('dbconfig.php');
 $name='';
		if(!empty($id)){ 
		$student_name = runQuery("select * from pointset where ID = $id");
		if(!empty($student_name[$type])){
		$name = $student_name[$type];
		} 
		} 
	return $name;

}

function user_email($id=false){

	include('dbconfig.php');

	$userid =   $id ?: current_userid();

	$user_role = $role ?: user_role();

		if(!empty($userid)){

		$student_name = runQuery("select email from user where ID = $userid");

		$name = $student_name['email'];

	return $name;

	 }

}
function manager_details($userid,$type){

	include('dbconfig.php'); 

		if(!empty($userid)){

		$student_name = runQuery("select * from employee where ID = $userid");

		$name = $student_name[$type];

	return $name;

	 }
}
function manager_details_with_unique_id($userid,$type){

	include('dbconfig.php'); 

		if(!empty($userid)){

		$student_name = runQuery("select * from employee where unique_id = '$userid'");

		$name = $student_name[$type];

	return $name;

	 }
}

function user_mobile($id=false){

	include('dbconfig.php');

	$userid =   $id ?: current_userid();

	$user_role = $role ?: user_role();

		if(!empty($userid)){

		$student_name = runQuery("select mobilenumber from user where ID = $userid");

		$name = $student_name['mobilenumber'];

	return $name;

	 }

}
function status_details($status){
 $value= '';
	 switch($status){
		 case '0':
		 $value= 'Pending';
		 break;
		 case '1':
		 $value = 'Active';
		 break;
		 case '2':
		 $value = 'In active';
		 break;
	 } 
	 return  $value;
}
function feedbackstatus($status){
 $value= '';
	 switch($status){ 
		 case '1':
		 $value = 'Interested';
		 break;
		 case '2':
		 $value = 'Not Interested';
		 break;
		 case '3':
		 $value = 'Switched OFF';
		 break;
		 case '4':
		 $value = 'Not Lifting';
		 break;
		 case '5':
		 $value = 'Call Back';
		 break;
		 case '6':
		 $value = 'Not Reachable';
		 break;
		 case '8':
		 $value = 'Not Working';
		 break;
		 case '9':
		 $value = 'Invalid Number';
		 break;
	 } 
	 return  $value;
}

function home_page(){

	include('dbconfig.php');

	$userid = current_userid();

	if(!empty($userid)){

	$user_role = user_role();

		if($user_role=='superadmin'){

			$page = "superadmin-dashboard.php";

		}elseif($user_role=='admin'){

			$page = "admin-dashboard.php";

		}elseif($user_role=='lecturer'){

			$page = "add-course.php";

		}elseif($user_role=='student'){

			$page = "user-exam-instacks.php";

		}

		}else{

			$page = SITE_URL.'index.php';

		}

	return $page;

}

function unique_id(){

	include('dbconfig.php');

	$userid = current_userid();

	$user_role = user_role();

	if($userid){

		$lecturer_name = runQuery("select unique_id from user where ID = $userid");

		$name = $lecturer_name['unique_id'];

	return $name;

	}

}

function txn_id(){

	include('dbconfig.php');

		$lecturer_name = runQuery("select MAX(ID) as id from transactions");

		$id = $lecturer_name['id']+1;

		$name = "INSTXN".$id;

	return $name;

}


function wallet($uid=false,$urole=false){

	include('dbconfig.php');

	$userid = $uid ?: current_userid();

	$user_role = $urole ?: user_role();

	$walletamt = runQuery("select amount from wallet where user_id = ".$userid." and user_role='".$user_role."'");

	return (int)$walletamt['amount'] ?: 0;

}

function wallet_update($id=false,$role=false,$amt){

	include('dbconfig.php');

	$userid = $id ?: current_userid();

	$user_role = $role ?: user_role();

	$amt = (int)$amt;

	$wallet_amt = runQuery("select * from wallet where user_id = ".$userid." and user_role = '".$user_role."'");

	if(!empty($wallet_amt['ID'])){

		$total_amt = (int)$wallet_amt['amount'] + $amt;

		$result = mysqli_query($conn,"update wallet set amount = ".$total_amt." where ID = ".$wallet_amt['ID']."");

	}else{

		$result =  mysqli_query($conn,"insert into wallet (user_id,user_role,amount,reg_date)values(".$userid.",'".$user_role."',".$amt.",'".date('Y-m-d H:i:s')."')");

	}

	if(!$result){

	echo $conn->error;

	}

}

function wallet_deduct($id=false,$role=false,$amt){

	include('dbconfig.php');

	$userid = $id ?: current_userid();

	$user_role = $role ?: user_role();

	$wallet_amt = runQuery("select * from wallet where user_id = ".$userid." and user_role = '".$user_role."'");

	if(!empty($wallet_amt['ID'])){

		if((int)$wallet_amt['amount']>=$amt){

		$amount = $wallet_amt['amount'] - $amt;

		mysqli_query($conn,"update wallet set amount = ".$amount." where ID = ".$wallet_amt['ID']."");

		}else{

		$message = 'Insufficient funds';

		return $message;

		}

	}else{

		$message = 'Wallet amt does not exits';

		return $message;

	}

}

function referralamount($uid=false,$urole=false){

	include('dbconfig.php');

	$userid = $uid ?: current_userid();

	$user_role = $urole ?: user_role();

	$referralamount = runQuery("select amount from referralamount where user_id = ".$userid." and user_role='".$user_role."'");

	return (int)$referralamount['amount'] ?: 0;

}

function referralamount_update($id=false,$role=false,$amt){

	include('dbconfig.php');

	$userid = $id ?: current_userid();

	$user_role = $role ?: user_role();

	$amt = (int)$amt;

	$referral_amt = runQuery("select * from referralamount where user_id = ".$userid." and user_role = '".$user_role."'");

	if(!empty($referral_amt['ID'])){

		$total_amt = (int)$referral_amt['amount'] + $amt;

		$result = mysqli_query($conn,"update referralamount set amount = ".$total_amt." where ID = ".$referral_amt['ID']."");

	}else{

		$result =  mysqli_query($conn,"insert into referralamount (user_id,user_role,amount,reg_date)values(".$userid.",'".$user_role."',".$amt.",'".date('Y-m-d H:i:s')."')");

	}

	if(!$result){

	echo $conn->error;

	}

}

function referralamount_deduct($id=false,$role=false,$amt){

	include('dbconfig.php');

	$userid = $id ?: current_userid();

	$user_role = $role ?: user_role();

	$referral_amt = runQuery("select * from referralamount where user_id = ".$userid." and user_role = '".$user_role."'");

	if(!empty($referral_amt['ID'])){

		if((int)$referral_amt['amount']>=$amt){

		$amount = $referral_amt['amount'] - $amt;

		mysqli_query($conn,"update referralamount set amount = ".$amount." where ID = ".$referral_amt['ID']."");

		}else{

		$message = 'Insufficient funds';

		return $message;

		}

	}else{

		$message = 'referralamount amt does not exits';

		return $message;

	}

}


function getUserIP()

{

    $client  = @$_SERVER['HTTP_CLIENT_IP'];

    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];

    $remote  = $_SERVER['REMOTE_ADDR'];



    if(filter_var($client, FILTER_VALIDATE_IP))

    {

        $ip = $client;

    }

    elseif(filter_var($forward, FILTER_VALIDATE_IP))

    {

        $ip = $forward;

    }

    else

    {

        $ip = $remote;

    }



    return $ip;

}
 
function limit_words($string) {

	$string = strip_tags($string);

	$words = explode(' ', strip_tags($string));

	$return = trim(implode(' ', array_slice($words, 0, 10)));

	if(strlen($return) < strlen($string)){

	$return .= '...';

	}

	return $return;

}
 function m3admin_nav($page,$total,$per_page,$getarray=false){
						
include('dbconfig.php');
	$adjacents = "2"; 
	 
	$prevlabel = "&lsaquo; Prev";
	$nextlabel = "Next &rsaquo;";
	$lastlabel = "Last &rsaquo;&rsaquo;";
	 
	$page = ($page == 0 ? 1 : $page);  
	$start = ($page - 1) * $per_page;                               
	 
	$prev = $page - 1;                          
	$next = $page + 1;
	 
	$lastpage = ceil($total/$per_page);
	 
	$lpm1 = $lastpage - 1; // //last page minus 1
	 
	$pagination = "";
	if($lastpage > 1){   
			   $pagination.='<ul class="pagination">';
			 
			if ($page > 1) $pagination.= "<li class='page-item'><a class='page-link' href='?page={$prev}' aria-label='Previous'><i class='fa fa-angle-left'></i> {$prevlabel}</a></li>";
			 
		if ($lastpage < 7 + ($adjacents * 2)){   
			for ($counter = 1; $counter <= $lastpage; $counter++){
				if ($counter == $page)
					$pagination.= "<li class='page-item active'><a class='page-link' ><span>{$counter}</span></a></li>";
				else
					$pagination.= "<li class='page-item'><a class='page-link' href='?page={$counter}'><span>{$counter}</span></a></li>";                    
			}
		 
		} elseif($lastpage > 5 + ($adjacents * 2)){
			 
			if($page < 1 + ($adjacents * 2)) {
				 
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
					if ($counter == $page)
						$pagination.= "<li class='page-item active'><a class='page-link' ><span>{$counter}</span></a></li>";
					else
						$pagination.= "<li class='page-item' ><a class='page-link'  href='?page={$counter}'><span>{$counter}</span></a></li>";                    
				}
				$pagination.= "<li class='dot'>...</li>";
				$pagination.= "<li class='page-item' ><a class='page-link' href='?page={$lpm1}'><span>{$lpm1}</span></a></li>";
				$pagination.= "<li class='page-item' ><a class='page-link' href='?page={$lastpage}'><span>{$lastpage}</span></a></li>";  
					 
			} elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
				 
				$pagination.= "<li class='page-item' ><a class='page-link' href='?page=1'><span>1</span></a></li>";
				$pagination.= "<li class='page-item' ><a class='page-link' href='?page=2'><span>2</span></a></li>";
				$pagination.= "<li class='page-item dot'>...</li>";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
					if ($counter == $page)
						$pagination.= "<li class='active'><a class='page-link'><span>{$counter}</span></a></li>";
					else
						$pagination.= "<li class='page-item' ><a class='page-link' href='?page={$counter}'><span>{$counter}</span></a></li>";                    
				}
				$pagination.= "<li class='page-item dot'>..</li>";
				$pagination.= "<li class='page-item' ><a class='page-link' href='?page={$lpm1}'><span>{$lpm1}</span></a></li>";
				$pagination.= "<li class='page-item' ><a class='page-link' href='?page={$lastpage}'><span>{$lastpage}</span></a></li>";      
				 
			} else {
				 
				$pagination.= "<li class='page-item' ><a class='page-link' href='?page=1'><span>1</span></a></li>";
				$pagination.= "<li class='page-item' ><a class='page-link' href='?page=2'><span>2</span></a></li>";
				$pagination.= "<li class='page-item dot'>..</li>";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
					if ($counter == $page)
						$pagination.= "<li class='page-item active' ><a class='page-link' ><span>{$counter}</span></a></li>";
					else
						$pagination.= "<li class='page-item' ><a class='page-link' href='?page={$counter}'><span>{$counter}</span></a></li>";                    
				}
			}
		}
		 
			if ($page < $counter - 1) {
				$pagination.= "<li class='page-item'><a class='page-link' href='?page={$next}' aria-label='Previous'><i class='fa fa-angle-right'></i> {$nextlabel}</a></li>";
			}
			   $pagination.='</ul>';
	}
	 echo $pagination;
	}      
	function emp_monthly_income_check($arr)
	{
		// points new table employee_income points caclualtion
		$empMonthCountSql = runQuery("SELECT * FROM employee_income  where
		 emp_id = '".$arr['empid']."' and month ='".date('m-Y')."'");
		
		if(empty($empMonthCountSql)) {
			$employee = runQuery("SELECT * FROM employee  where ID = '".$arr['empid']."'");
		 	$empPointsArray['emp_name'] = $employee['fname']; 
			$empPointsArray['emp_id'] = $arr['empid'];
			$empPointsArray['emp_income'] = $employee['income'];
			$empPointsArray['month'] = date('m-Y');
			$empPointsArray['reg_date'] = date('Y-m-d H:i:s A');
			$empPointsArray['mod_date'] = date('Y-m-d H:i:s A');
			$result = insertQuery($empPointsArray,'employee_income');
		}
	}
function points($amount,$pointstype=false,$type_of_client=null,$variant = null,$variant_location = null,$roleid = 4,$leveltype = 1 ){
	include('dbconfig.php');
	$value = 0;
	
	if(!empty($pointstype)){
	   
		$pointsarray = runQuery("select psv.*,executive_share,manager_share,r1_share,r2_share,manager_points,executive_points,ps.amount unit_amount from pointset ps left join points_set_variant psv on ps.ID = psv.points_id 
		where ps.title = '".$pointstype."' and ps.type_of_client = '".$type_of_client."' and psv.variant = '".$variant."' and psv.variant_location = '".$variant_location."' limit 1");
		if($pointsarray){
		    $unit = ($amount/$pointsarray['unit_amount']);
		    if($roleid == 4 && $leveltype == 1){
		      //  echo $unit."===".$pointsarray['amount']."===".$pointsarray['executive_share'];exit;
		      $value = ($unit * $pointsarray['amount'] *  ($pointsarray['executive_share']/100))/25;
		    }
            if($roleid == 4 && $leveltype == 2){
		      //  echo $unit."===".$pointsarray['manager_points'];exit;
		      $value = ($unit * $pointsarray['manager_points']);
		    
                
            }
		    if($roleid == 3 && $leveltype == 1){

		      $value = ($unit * $pointsarray['executive_points']);
		    }
		    if($roleid == 3 && $leveltype == 2){
		        
		      $value = ($unit * $pointsarray['r1_points']);
		    }
		    if($roleid == 3 && $leveltype == 3){
		      $value = ($unit * $pointsarray['r2_points']);
		    }
		    
		
		}
	}
	return $value;
}
	function emp_monthly_points($empid,$startDate = '',$endDate = '',$roleid,$leveltype=1)
	{
	    
		$year =  date('Y');
		$month =   date('m');
		 $fromdate = !empty($startDate) ? $startDate : $year.'-'.$month."-01"; 
	   $todate =!empty($endDate) ? $endDate :  $year.'-'.$month."-31";
	  /* $teamdetails = [];
if(!empty($roleid) && $roleid == '3'){
    $userdetails = runQuery("select * from employee where ID = '".$empid."'");
    $teamdetails = runloopQuery("select *,concat(fname,' ',lname) name from employee where leader = '".$userdetails['unique_id']."'");  
    $teamselect = array_column($teamdetails,"ID");
    $teamselect[] = $empid;
}else{
$teamselect[] = $empid;    
}
$teamidstring = implode("','",$teamselect);*/
		$totalclientsarray = runloopQuery("select * from clients where employee_id in ('".$empid."') 
		and mod_date >='".$fromdate." 00:00:00' and mod_date <='".$todate." 23:59:59' order by ID desc");
		$pendingclientspoints = $approvedclientspoints = $rejectclientspoints = 0;
				$appamount =$rejectamount = $pendingamount = 0;
		$x=1;  foreach($totalclientsarray as $totalclient){
			switch($totalclient["status"]){
				case 'Pending': 
				$pendingamount = $totalclient["loanamount"];
				$pendingclientspoints += round(points($pendingamount,$totalclient["servicetype"],$totalclient["type_of_client"],$totalclient["variant"],$totalclient["variant_location"],$roleid,$leveltype),2); 
				break;
				case 'Approved':  
				$appamount = $totalclient["loanamount"];
				$approvedclientspoints += round(points($appamount,$totalclient["servicetype"],$totalclient["type_of_client"],$totalclient["variant"],$totalclient["variant_location"],$roleid,$leveltype),2);
				break;
				case 'Rejected':  
				$rejectamount = $totalclient["loanamount"];
				$rejectclientspoints += round(points($rejectamount,$totalclient["servicetype"],$totalclient["type_of_client"],$totalclient["variant"],$totalclient["variant_location"],$roleid,$leveltype),2);
				break;
			}
			  
		}
		return ['pendingclientspoints' => $pendingclientspoints
		, 'approvedclientspoints' => $approvedclientspoints
		, 'rejectclientspoints' => $rejectclientspoints
		];
	}
	function roles($id = null)
	{

		$roles = ['0' => 'No Role Assigned', '1' => 'Admin' , '2' => 'Super Admin' ,'5' => 'Operations Manager','6' => 'Operations Executive' , '3' => 'Manager' , '4' => 'Executive'];
		if(!empty($id)) {
			return @$roles[$id] ?? '';
		}
		else{
			return $roles;
		}
	}
	
	function emp_points($empid)
	{
	    $empdet = runQuery("select * from employee where ID = '".$empid."'");
	    
	    if($empdet['role_id'] == '4')
	    {
	        $points = emp_monthly_points($empid,'','',$empdet['role_id'],1);
	    }
	    else if($empdet['role_id'] == '3'){
	        $points = emp_monthly_points($empid,'','',$empdet['role_id'],1);
	        
    		$executives = runloopQuery("SELECT ID,unique_id FROM employee where role_id = 4 and leader = '".$empdet['unique_id']."'");
    	    $teampointsarr = [];
    		for($i=0;$i<count($executives);$i++)
    		{
    			$teampointsarr[] = emp_monthly_points($executives[$i]['ID'],'','',4,2);
    		}
    		$points['pendingclientspoints'] = round(((array_sum(array_column($teampointsarr, 'pendingclientspoints')) ) + $points['pendingclientspoints']) ,2);
    		$points['approvedclientspoints'] = round(((array_sum(array_column($teampointsarr, 'approvedclientspoints')) ) + $points['approvedclientspoints']),2);
    		$points['rejectclientspoints'] = round(((array_sum(array_column($teampointsarr, 'rejectclientspoints'))  ) + $points['rejectclientspoints']),2);			        
    	    
	        
	     	$managersunderhim = runloopQuery("SELECT ID,unique_id FROM employee where role_id = 3 and leader = '".$empdet['unique_id']."'");
		    $undermanagerpointsarr = [];
		    for($j=0;$j<count($managersunderhim);$j++)
		    {
			    $undermanagerpointsarr[] = emp_monthly_points($managersunderhim[$j]['ID'],'','',3,2);
		    }

		$points['pendingclientspoints'] = round(((array_sum(array_column($undermanagerpointsarr, 'pendingclientspoints')) ) + $points['pendingclientspoints']),2);
		$points['approvedclientspoints'] = round(((array_sum(array_column($undermanagerpointsarr, 'approvedclientspoints')) ) + $points['approvedclientspoints']),2);
		$points['rejectclientspoints'] = round(((array_sum(array_column($undermanagerpointsarr, 'rejectclientspoints'))  ) + $points['rejectclientspoints']),2);   
	        if(!empty($managersunderhim)){
			$managerids = implode("','",array_column($managersunderhim,'unique_id'));
			$levelexecutivesunderhim = runloopQuery("SELECT ID,unique_id FROM employee where role_id = 4 and leader in ( '".$managerids."') ");
			$undermanagerexecpointsarr = [];
			for($k=0;$k<count($levelexecutivesunderhim);$k++)
			{
				$undermanagerexecpointsarr[] = emp_monthly_points($levelexecutivesunderhim[$k]['ID'],'','',3,2);
			}
			$points['pendingclientspoints'] = round(((array_sum(array_column($undermanagerexecpointsarr, 'pendingclientspoints')) ) + $points['pendingclientspoints']),2);
			$points['approvedclientspoints'] = round(((array_sum(array_column($undermanagerexecpointsarr, 'approvedclientspoints')) ) + $points['approvedclientspoints']),2);
			$points['rejectclientspoints'] = round(((array_sum(array_column($undermanagerexecpointsarr, 'rejectclientspoints'))  ) + $points['rejectclientspoints']),2);		
	        
	        
	        $levelmanagerunderhim = runloopQuery("SELECT ID,unique_id FROM employee where role_id = 3 and leader in ( '".$managerids."') ");
			if(!empty($levelmanagerunderhim)) {
				$levelmanagerids = implode("','",array_column($levelmanagerunderhim,'unique_id'));
				$undermanagermanagerpointsarr = [];
				for($k=0;$k<count($levelmanagerunderhim);$k++)
				{
					$undermanagermanagerpointsarr[] = emp_monthly_points($levelmanagerunderhim[$k]['ID'],'','',3,3);
				}
				$points['pendingclientspoints'] = round(((array_sum(array_column($undermanagermanagerpointsarr, 'pendingclientspoints')) ) + $points['pendingclientspoints']),2);
				$points['approvedclientspoints'] = round(((array_sum(array_column($undermanagermanagerpointsarr, 'approvedclientspoints')) ) + $points['approvedclientspoints']),2);
				$points['rejectclientspoints'] = round(((array_sum(array_column($undermanagermanagerpointsarr, 'rejectclientspoints'))  ) + $points['rejectclientspoints']),2);		
				if(isset($levelmanagerids) && !empty($levelmanagerids))
				{
					$thirdlevelexecutives = runloopQuery("SELECT ID,unique_id FROM employee where role_id = 4 and leader in ( '".$levelmanagerids."') ");	
					if(!empty($thirdlevelexecutives)) {
						$thirdlevelexcpointsarr = [];
						for($k=0;$k<count($thirdlevelexecutives);$k++)
						{
							$thirdlevelexcpointsarr[] = emp_monthly_points($thirdlevelexecutives[$k]['ID'],'','',3,3);
						}
						$points['pendingclientspoints'] = round(((array_sum(array_column($thirdlevelexcpointsarr, 'pendingclientspoints')) ) + $points['pendingclientspoints']),2);
						$points['approvedclientspoints'] = round(((array_sum(array_column($thirdlevelexcpointsarr, 'approvedclientspoints')) ) + $points['approvedclientspoints']),2);
						$points['rejectclientspoints'] = round(((array_sum(array_column($thirdlevelexcpointsarr, 'rejectclientspoints'))  ) + $points['rejectclientspoints']),2);
					}
				}
			
			}
	        }
	        
	    }
	    return $points;
	}
	
	function emp_monthly_manager_points($empid,$unique_id)
	{
		$ownPoints = emp_monthly_points($empid);
		$executives = runloopQuery("SELECT ID,unique_id FROM employee where role_id = 4 and leader = '".$unique_id."'");
		$teampointsarr = [];
		for($i=0;$i<count($executives);$i++)
		{
			$teampointsarr[] = emp_monthly_points($executives[$i]['ID']);
		}
		$points['pendingclientspoints'] = round(((array_sum(array_column($teampointsarr, 'pendingclientspoints')) * (25/100)) + $ownPoints['pendingclientspoints']) ,2);
		$points['approvedclientspoints'] = round(((array_sum(array_column($teampointsarr, 'approvedclientspoints')) * (25/100)) + $ownPoints['approvedclientspoints']),2);
		$points['rejectclientspoints'] = round(((array_sum(array_column($teampointsarr, 'rejectclientspoints')) * (25/100) ) + $ownPoints['rejectclientspoints']),2);		
		
		$managersunderhim = runloopQuery("SELECT ID,unique_id FROM employee where role_id = 3 and leader = '".$unique_id."'");
		$undermanagerpointsarr = [];
		for($j=0;$j<count($managersunderhim);$j++)
		{
			$undermanagerpointsarr[] = emp_monthly_points($managersunderhim[$j]['ID']);
		}

		$points['pendingclientspoints'] = round(((array_sum(array_column($undermanagerpointsarr, 'pendingclientspoints')) * (5/100)) + $points['pendingclientspoints']),2);
		$points['approvedclientspoints'] = round(((array_sum(array_column($undermanagerpointsarr, 'approvedclientspoints')) * (5/100)) + $points['approvedclientspoints']),2);
		$points['rejectclientspoints'] = round(((array_sum(array_column($undermanagerpointsarr, 'rejectclientspoints')) * (5/100) ) + $points['rejectclientspoints']),2);		
		if(!empty($managersunderhim)){
			$managerids = implode("','",array_column($managersunderhim,'unique_id'));
			$levelexecutivesunderhim = runloopQuery("SELECT ID,unique_id FROM employee where role_id = 4 and leader in ( '".$managerids."') ");
			$undermanagerexecpointsarr = [];
			for($k=0;$k<count($levelexecutivesunderhim);$k++)
			{
				$undermanagerexecpointsarr[] = emp_monthly_points($levelexecutivesunderhim[$k]['ID']);
			}
			$points['pendingclientspoints'] = round(((array_sum(array_column($undermanagerexecpointsarr, 'pendingclientspoints')) * (5/100)) + $points['pendingclientspoints']),2);
			$points['approvedclientspoints'] = round(((array_sum(array_column($undermanagerexecpointsarr, 'approvedclientspoints')) * (5/100)) + $points['approvedclientspoints']),2);
			$points['rejectclientspoints'] = round(((array_sum(array_column($undermanagerexecpointsarr, 'rejectclientspoints')) * (5/100) ) + $points['rejectclientspoints']),2);		
		
			$levelmanagerunderhim = runloopQuery("SELECT ID,unique_id FROM employee where role_id = 3 and leader in ( '".$managerids."') ");
			if(!empty($levelmanagerunderhim)) {
				$levelmanagerids = implode("','",array_column($levelmanagerunderhim,'unique_id'));
				$undermanagermanagerpointsarr = [];
				for($k=0;$k<count($levelmanagerunderhim);$k++)
				{
					$undermanagermanagerpointsarr[] = emp_monthly_points($levelmanagerunderhim[$k]['ID']);
				}
				$points['pendingclientspoints'] = round(((array_sum(array_column($undermanagermanagerpointsarr, 'pendingclientspoints')) * (2.5/100)) + $points['pendingclientspoints']),2);
				$points['approvedclientspoints'] = round(((array_sum(array_column($undermanagermanagerpointsarr, 'approvedclientspoints')) * (2.5/100)) + $points['approvedclientspoints']),2);
				$points['rejectclientspoints'] = round(((array_sum(array_column($undermanagermanagerpointsarr, 'rejectclientspoints')) * (2.5/100) ) + $points['rejectclientspoints']),2);		
				if(isset($levelmanagerids) && !empty($levelmanagerids))
				{
					$thirdlevelexecutives = runloopQuery("SELECT ID,unique_id FROM employee where role_id = 4 and leader in ( '".$levelmanagerids."') ");	
					if(!empty($thirdlevelexecutives)) {
						$thirdlevelexcpointsarr = [];
						for($k=0;$k<count($thirdlevelexecutives);$k++)
						{
							$thirdlevelexcpointsarr[] = emp_monthly_points($thirdlevelexecutives[$k]['ID']);
						}
						$points['pendingclientspoints'] = round(((array_sum(array_column($thirdlevelexcpointsarr, 'pendingclientspoints')) * (2.5/100)) + $points['pendingclientspoints']),2);
						$points['approvedclientspoints'] = round(((array_sum(array_column($thirdlevelexcpointsarr, 'approvedclientspoints')) * (2.5/100)) + $points['approvedclientspoints']),2);
						$points['rejectclientspoints'] = round(((array_sum(array_column($thirdlevelexcpointsarr, 'rejectclientspoints')) * (2.5/100) ) + $points['rejectclientspoints']),2);
					}
				}
			
			}

		}

		return $points;
	}
	function employee_performance($empid,$startDate = null,$endDate  = null )
	{
		$perf = [];
		$startDate = !empty($startDate) ? $startDate : date('Y-m-01');
		$endDate = !empty($endDate) ? $endDate : date('Y-m-d');
		$sql = runloopQuery("select *,date(duration) formatedduration from campaigns_users where date(duration) between '".$startDate."' and '".$endDate."' 
		and callstatus = '1' and executive_id = '".$empid."'");
		
		$sqlleads = runloopQuery("select * from track_work where date(reg_date) between '".$startDate."' and '".$endDate."' 
		and employee_id = '".$empid."'");
		if(count($sqlleads) > 0){
		    $generatedLeads = count($sqlleads);    
		}
		else{
		    $generatedLeads = 0;
		}
		if(!empty($sql)){
			$calldurationarray = array_column($sql,'callduration');
			$numberofdayswork = count(array_values(array_unique(array_column($sql,'formatedduration'))));

			$calldurationinsecs = array_sum($calldurationarray);
			$call_duration = gmdate("H:i:s", $calldurationinsecs);
			$hours_duration = (gmdate("H", $calldurationinsecs).'.'.gmdate("i", $calldurationinsecs));
			$effieciency = 0;
			if(!empty($hours_duration)){
			    $hours_duration = (float)($hours_duration);
			    if($hours_duration > 0){
			    $effieciency = round($generatedLeads/$hours_duration,2);    
			    }
			    
			}
			
			//$effieciency = round($hours_duration/$numberofdayswork,2);
			
		}
		else{
			$call_duration = 0;
			$effieciency = 0;
		}

		$perf['calls_taken'] = count($sql) ?? 0;
		$perf['hours_of_work'] = @$call_duration ?? 0;
		$perf['effieciency'] = @$effieciency ?? 0;

		return $perf;
	}		
function buildTree($parentId) {
		$branch = array();
		$sql = runloopQuery("select * from employee where leader = '".$parentId."' and status = 1");
		
		foreach ($sql as $element) {
			if ($element['leader'] == $parentId) {
				$children = buildTree($element['unique_id']);
				if ($children) {
					$element['children'] = $children;
				}
				$branch[] = $element;
			}
		}
	
		return $branch;
	}
		function employeehirerachy($parentId,$roleIdArr = []){
		$branch = array();
		$sqlquery = ("select * from employee where leader = '".$parentId."' ");
		if(!empty($roleIdArr)){
			$roleIdStr = implode("','",$roleIdArr);
			$sqlquery .= " and role_id in ('".$roleIdStr."')";
		}
		$sql = runloopQuery($sqlquery); 
		foreach ($sql as $element) {
			if ($element['leader'] == $parentId) {
				$children = employeehirerachy($element['unique_id']);
				if ($children) {
					$element['children'] = $children;
				}
				
				$branch[] = $element;
				//return $branch;exit;
			}
		}
		return get_elements($branch);
		// return $branch; 
	}	
	
	function get_elements($array) {
		$result = array();
		foreach($array as $key => $row) {
			$result[] = $row;
			if(!empty($row['children'])) {
				$result = array_merge($result,get_elements($row['children'] ?? []));
			}
		}
		$res = $res1 = [];
		foreach($result as $row){
			$res1['ID'] = $row['ID'];
			$res1['unique_id'] = $row['unique_id'];
			$res1['leader'] = $row['leader'];
			$res1['role_id'] = $row['role_id'];
			$res[] = $res1;
		}

		return $res;
	}
function dfupdatecals($arr){
		$pending_amount = $arr['edi'] - $arr['paid_amount'];
		$penalty_amount = round((($pending_amount * 5)/100),2);
		$penalty_amount = $penalty_amount < 0 ? 0 : $penalty_amount;
		$balance = $arr['last_balance'] - $arr['paid_amount']  +  $penalty_amount ;
		$pagearray['edi'] = $arr['edi'];
		$pagearray['paid_amount'] = $arr['paid_amount'];
		
		$pagearray['penalty'] = $penalty_amount < 0 ? 0 : $penalty_amount;
		$pagearray['balance_amount'] = $balance < 0 ? 0 : $balance;
		$pagearray['pending_amount'] = $balance <= 0 ? 0 : $pending_amount;
		$tranwherearray['application_id'] = $arr['application_id'];
		$tranwherearray['installment_date'] = $arr['installment_date'];
		$result = updateQuery($pagearray,'df_transaction_details',$tranwherearray);

		return ['penalty' => $pagearray['penalty'],'pending_amount' => $pagearray['pending_amount'],'balance_amount' => $pagearray['balance_amount']];
	}

	function sendnotification($param)
	{


				$url = 'https://fcm.googleapis.com/fcm/send';
				$extraNotificationData = ["click_action" => 'FLUTTER_NOTIFICATION_CLICK'
				,'type' => $param['type'],'trackId' => trim(@$param['trackId']) ?? null, 'empId' => trim(@$param['empId']) ?? null ];

				$fields = [
				"registration_ids" => [$param['token']],
				"notification" => [
					"title" => $param['title'],
					"body" => $param['msg'],
					"click_action" => 'FLUTTER_NOTIFICATION_CLICK',
					"icon" => "",
					"sound" => "default",
					"data" => $extraNotificationData

				],
				"data" => $extraNotificationData
				
			];
			
				$headers = array(
					'Content-Type:application/json',
					'Authorization: key=' . "AAAA-mhI59w:APA91bEoz7AQUYhwk4lGbg5Nidln4qJ6RB01twjf5uvWkxWBGZG5xTB0helCL4UJMTGFx7dtqf4r_YpqDR3BK34o6Ke8Me4DW5vIz03HlM-ekBr5Ub16PV51-UWXE08AYD4ULjEIgJ1D",

				);
			
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
				$result = curl_exec($ch);
				if ($result === FALSE) {
					die('FCM Send Error: ' . curl_error($ch));
				}
				curl_close($ch);
				return $result;
			
			
	}
	function getsuperadmin($empid) {
		$parent ;
		$sql = runQuery("select * from employee where ID = '".$empid."'");
			if ($sql['role_id'] != 2) {
				
				$parent = getsuperadmin(lead_details($sql['leader'],'ID'));
				
			}
			else{
				$parent = $empid;
			}
			
			return $parent; 
			
	}
	function uploadimage($filearray,$usersid){
		$file_tmpname = $filearray['tmp_name'];
		$file_name = $filearray['name'];
		$file_size = $filearray['size'];
		$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);		
		$newname = date('YmdHis',time()).mt_rand().'.'.$file_ext;
		$path = '../../../sp_ace_docs/attendance/'.$usersid.'/';
		if (!is_dir($path)) {
			mkdir($path, 0777, true);
		}

		move_uploaded_file($file_tmpname,$path.'/'.$newname);
		return $newname;
	}  

?>