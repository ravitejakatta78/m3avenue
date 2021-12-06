<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');

$userid = current_managerid(); 

if(empty($userid))
{
	header("Location: index.php");
}
$post = file_get_contents('php://input');

echo "<pre>";
    $data=array();

    $values=$_POST['values'];

    //print_r( $values); exit;


    foreach(explode('&', $values) as $value)
    {
        $value1 = explode('=', $value);
        $data[$value1[0]] = $value1[1];
    }
   


    $pagerarray['requested_by']=mysqli_real_escape_string($conn,$userid);

    $pagerarray['requested_for'] = mysqli_real_escape_string($conn,$_POST['requested_for']);
	$pagerarray['ticket_type'] = mysqli_real_escape_string($conn,$_POST['ticket_type']);
	$pagerarray['ticket_summary'] = mysqli_real_escape_string($conn,$_POST['ticket_summary']);
    $pagerarray['modified_values'] = mysqli_real_escape_string($conn,json_encode($data));
	$pagerarray['ticket_status'] = mysqli_real_escape_string($conn,'pending');

    // echo "<pre>";
    // print_r($pagerarray); exit;

	$result = insertQuery($pagerarray,'tbl_tickets');

    print_r($result);
	if(!$result){
		header("Location: executive-list.php?tsuccess=success");
	}

?>