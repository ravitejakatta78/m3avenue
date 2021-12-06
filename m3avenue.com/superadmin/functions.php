<?php

include('dbconfig.php');

date_default_timezone_set('Asia/Kolkata');

ini_set('display_errors', 'On');

ini_set('log_errors', 'On');

define('SITE','devolopment');

if(SITE=='devolopment'){

define('SITE_URL','http://m3avenue.com/');

define('PAGE_URL','http://m3avenue.com/admin/admin/');


}elseif(SITE=='production'){

define('SITE_URL','http://instacks.in/');

define('PAGE_URL','http://instacks.in/ensino/');

	

}



function insertQuery($query,$table){

	include('dbconfig.php');

	$query['reg_date'] = date('Y-m-d H:i:s');

	$keys = array_keys($query);



$sql = "INSERT INTO ".$table." SET ";



for($e=0;$e<sizeof($keys);$e++) {



$sql .=  $keys[$e].'="'.$query[$keys[$e]].'"';



if($e != sizeof($keys)-1) { $sql .= ','; }



}



$result = mysqli_query($conn,$sql);

if(!$result) {

	return $conn->error;

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


function medget_option($optionname){

include("dbconfig.php");

$optionvalue = runQuery("select option_value from m3avenue option where option_name='".$optionname."'");
if(!empty($optionvalue['option_value'])){
return $optionvalue['option_value'];

}
}

function medupdate_option($optionname,$newdata){

include("dbconfig.php");

$optionvalue = runQuery("select ID from m3avenue option where option_name='".$optionname."'");

if(!empty($optionvalue['ID'])){

mysqli_query($conn,"update m3avenue option set option_value = '".$newdata."' where ID = ".$optionvalue['ID']."");

}else{

mysqli_query($conn,"insert into m3avenue option (option_name,option_value)values('".$optionname."','".$newdata."')");

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

function encrypt($string){

	$key = 'instacks admin';

	$dirty = array("+", "/", "=");

    $clean = array("_PLUS_", "_SLASH_", "_EQUALS_");

    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);

    $_SESSION['iv'] = mcrypt_create_iv($iv_size, MCRYPT_RAND);

    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $key, utf8_encode($string), MCRYPT_MODE_ECB, $_SESSION['iv']);

    $encrypted_string = base64_encode($encrypted_string);

    return str_replace($dirty, $clean, $encrypted_string);

}

function decrypt($string){

	$key = 'instacks admin';

		$dirty = array("+", "/", "=");

    $clean = array("_PLUS_", "_SLASH_", "_EQUALS_");



    $string = base64_decode(str_replace($clean, $dirty, $string));



    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $key,$string, MCRYPT_MODE_ECB, $_SESSION['iv']);

    return trim($decrypted_string);

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

function user_name($id=false,$role=false){

	include('dbconfig.php');

	$userid =   $id ?: current_userid();

	$user_role = $role ?: user_role();

		if(!empty($userid)){

		if($user_role!='superadmin'){

		$student_name = runQuery("select firstname,lastname from user where ID = $userid");

		$name = $student_name['firstname'].' '.$student_name['lastname'];

	 }else{

		$student_name = runQuery("select name from superadmin where ID = $userid");

		$name = $student_name['name']; 

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

function customer_type($id=false){

	include('dbconfig.php');

	$userid = $id ?: current_userid();

	$user_role = user_role();

	if($userid){

		$lecturer_name = runQuery("select customertype from user where ID = $userid");

		$name = $lecturer_name['customertype'];

	return $name;

		

	}

}
function questiontype($id){

	include('dbconfig.php');

	$questionquery = runQuery("select * from question where ID = $id");

	return  $questionquery['ques_type'] ?: '';

}



function module_name($id){

	include('dbconfig.php');

	if($id){

	$module_name = runQuery("select * from module where ID = $id");

	return  $module_name['module'] ?: '';

		

	}

}

function chapter_name($id){

	include('dbconfig.php');

	if($id){

	$chapter_name = runQuery("select * from chapter where ID = $id");

	return  $chapter_name['chapter'] ?: '';

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



$path = $_SERVER['PHP_SELF'];

if(basename($path)!='user-exam-page.php'){

    $timeout = 20 * 60; 

}else{

	  $timeout = (int)$_SESSION['examtime'] * 60; 

}

    if(isset($_SESSION['timeout'])) {

		$cookierole = $cookieid = '';

		if(isset($_COOKIE['m3avenue_id'])){

			$cookieid = (int)trim($_COOKIE['m3avenue_id']);

		}

		if(isset($_COOKIE['m3avenue_role'])){

			$cookierole = (int)trim($_COOKIE['m3avenue_role']);

		}

		$userid = current_userid() ?: $cookieid;

		$user_role = user_role() ?: $cookierole;

        $duration = time() - (int)$_SESSION['timeout'];

        if($duration > $timeout && !empty($userid)) {

			 mysqli_query($conn,"update user set loginstatus = 'false' where ID = ".$userid."");

			 $ip = getUserIP();

	   $admin_sql = runQuery("select * from user where ID = ".$userid."");

	   	if($admin_sql['role']=='admin'){

		$name = user_name($admin_sql['ID']);

		$collegename = $admin_sql['collegename'];

			}elseif($user_role=='lecturer'){

				$name = user_name($admin_sql['ID']);

				$cname = runQuery("select collegename from user where ID = ".$admin_sql['user_id']."");

				$collegename = $cname['collegename'];

			}elseif($admin_sql['role']=='student'){

				$cname = runQuery("select collegename from user where ID = ".$admin_sql['user_id']."");

			$collegename = $cname['collegename'];

			$name = user_name($admin_sql['ID']);;

			}elseif($admin_sql['role']=='superadmin'){

				$name = user_name($admin_sql['ID']);;

				$collegename = 'Instacks_admin';

			

			}

		mysqli_query($conn,"insert into logs (user_id,user_name,institute_name,user_role,ip,action,reg_date)values(".$userid.",'".$name."','".$collegename."','".$user_role."','".$ip."','out','".date('Y-m-d H:i:s')."')"); 

		session_destroy();

			session_unset();

			session_start();

        }

          

    }

     

    $_SESSION['timeout'] = time();

if(basename($path)=='user-exam-instacks.php'){

  $userid = current_userid();

		$user_role = user_role();

		if(!empty($userid)&&!empty($user_role)){

		$userstatusforexam = runQuery("select * from examacess where user_id = ".$userid." and user_role = '".$user_role."'");

		if(!empty($userstatusforexam['ID'])&&$userstatusforexam['type']=='paid'){

			$today = time();

				$enddate = strtotime($userstatusforexam['pa_end']);

				if ($enddate < $today){

					mysqli_query($conn,"update examacess set type='' where ID = ".$userstatusforexam['ID']."");

				}

			}

		}

}

function category_purchase($id,$type,$category){

	include('dbconfig.php');

	$userid = current_userid();

		$user_role = user_role();

	$activepurchase = runQuery("select * from categorypurchase where user_id = ".$userid." and user_role = '".$user_role."' and type = '".$type."' and category = '".$category."' and status = 'Active'");

	$today = time();

		$enddate = strtotime($activepurchase['pa_end']);

		if ($enddate < $today){

			mysqli_query($conn,"update categorypurchase set status='Expire' where ID = ".$activepurchase['ID']."");

		}

	$purchaseid = runQuery("select ID from categorypurchase where user_id = ".$userid." and user_role = '".$user_role."' and  status = 'Active' and type = '".$type."' and category = '".$category."' and FIND_IN_SET(".$id.", valid_ids)");

	return $purchaseid['ID'];

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

function queryanwerscount($qid){

	include('dbconfig.php');

	$anserscount = runQuery("select count(*) as count from queryanswers where ques_id = ".$qid." and status = 'Active'");

	return $anserscount['count'] ?: 0;

	

}

	function convert_number_to_words($number){



    $hyphen      = '-';

    $conjunction = ' and ';

    $separator   = ', ';

    $negative    = 'negative ';

    $decimal     = ' point ';

    $dictionary  = array(

        0                   => 'zero',

        1                   => 'one',

        2                   => 'two',

        3                   => 'three',

        4                   => 'four',

        5                   => 'five',

        6                   => 'six',

        7                   => 'seven',

        8                   => 'eight',

        9                   => 'nine',

        10                  => 'ten',

        11                  => 'eleven',

        12                  => 'twelve',

        13                  => 'thirteen',

        14                  => 'fourteen',

        15                  => 'fifteen',

        16                  => 'sixteen',

        17                  => 'seventeen',

        18                  => 'eighteen',

        19                  => 'nineteen',

        20                  => 'twenty',

        30                  => 'thirty',

        40                  => 'fourty',

        50                  => 'fifty',

        60                  => 'sixty',

        70                  => 'seventy',

        80                  => 'eighty',

        90                  => 'ninety',

        100                 => 'hundred',

        1000                => 'thousand',

        1000000             => 'million',

        1000000000          => 'billion',

        1000000000000       => 'trillion',

        1000000000000000    => 'quadrillion',

        1000000000000000000 => 'quintillion'

    );



    if (!is_numeric($number)) {

        return false;

    }



    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {

        // overflow

        trigger_error(

            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,

            E_USER_WARNING

        );

        return false;

    }



    if ($number < 0) {

        return $negative . convert_number_to_words(abs($number));

    }



    $string = $fraction = null;



    if (strpos($number, '.') !== false) {

        list($number, $fraction) = explode('.', $number);

    }



    switch (true) {

        case $number < 21:

            $string = $dictionary[$number];

            break;

        case $number < 100:

            $tens   = ((int) ($number / 10)) * 10;

            $units  = $number % 10;

            $string = $dictionary[$tens];

            if ($units) {

                $string .= $hyphen . $dictionary[$units];

            }

            break;

        case $number < 1000:

            $hundreds  = $number / 100;

            $remainder = $number % 100;

            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];

            if ($remainder) {

                $string .= $conjunction . convert_number_to_words($remainder);

            }

            break;

        default:

            $baseUnit = pow(1000, floor(log($number, 1000)));

            $numBaseUnits = (int) ($number / $baseUnit);

            $remainder = $number % $baseUnit;

            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];

            if ($remainder) {

                $string .= $remainder < 100 ? $conjunction : $separator;

                $string .= convert_number_to_words($remainder);

            }

            break;

    }



    if (null !== $fraction && is_numeric($fraction)) {

        $string .= $decimal;

        $words = array();

        foreach (str_split((string) $fraction) as $number) {

            $words[] = $dictionary[$number];

        }

        $string .= implode(' ', $words);

    }



    return $string;

}

function generateRandomString($length = 9) {

										$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

										$charactersLength = strlen($characters);

										$randomString = '';

										for ($i = 0; $i < $length; $i++) {

											$randomString .= $characters[rand(0, $charactersLength - 1)];

										}

										return $randomString;

									}

function time_elapsed_string($datetime, $full = false) {

    $now = new DateTime;

    $ago = new DateTime($datetime);

    $diff = $now->diff($ago);



    $diff->w = floor($diff->d / 7);

    $diff->d -= $diff->w * 7;



    $string = array(

        'y' => 'year',

        'm' => 'month',

        'w' => 'week',

        'd' => 'day',

        'h' => 'hour',

        'i' => 'minute',

        's' => 'second',

    );

    foreach ($string as $k => &$v) {

        if ($diff->$k) {

            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');

        } else {

            unset($string[$k]);

        }

    }



    if (!$full) $string = array_slice($string, 0, 1);

    return $string ? implode(', ', $string) . ' ago' : 'just now';

}



?>