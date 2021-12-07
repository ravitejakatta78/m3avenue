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
    }
    else{
        $teamselect[] = $usersid;    
    }
    $teamidstring = implode("','",$teamselect);

    if(!empty($userdetails['ID'])){

    }else{
        $payload = array("status"=>'0',"text"=>"Invalid user");
    }
}
else{
$payload = array('status'=>'0','message'=>'Invalid users details');
}
echo json_encode($payload);  
?>