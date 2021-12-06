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
$usersid = $_POST['usersid']; 
if(!empty($usersid)){
$userdetails = runQuery("select ID from employee where ID = '".$usersid."'");
	if(!empty($userdetails['ID'])){  
		if(!empty($_FILES['profilepic']['name'])){
			  $productarray =  $productwherearray = array(); 
				if (!file_exists('../../executiveimage')) {	
						mkdir('../../executiveimage', 0777, true);	
						}																			
					$target_dir = '../../executiveimage/';	 
						$file = $_FILES['profilepic']['name'];
						if(!empty($file)){
							$extn = pathinfo($file, PATHINFO_EXTENSION);
								$filename = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$extn;	
						$target_file = $target_dir.$filename;
							 
						if (move_uploaded_file($_FILES['profilepic']["tmp_name"], $target_file)){			 
							$productarray['profilepic'] = $filename;
							 
						}  
						 $productwherearray['ID'] =  $userdetails['ID'];
							$result = updateQuery($productarray,'employee',$productwherearray);
						$payload = array("status"=>'1',"text"=>"Profilepic updated","profilepic"=>executive_image($userdetails['ID']));
						}  else { 
							$payload = array("status"=>'0',"text"=>"Image not found.");
						}  
			  }  else { 
					$payload = array("status"=>'0',"text"=>"Image not found.");
			  } 
			  }  else {
					
					$payload = array("status"=>'0',"text"=>"Invalid executive");
			  } 
}else{
	 
	$payload = array('status'=>'0','message'=>'Invalid executive details');
}
echo json_encode($payload);
?>
