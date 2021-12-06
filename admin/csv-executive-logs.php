<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');
include('../inc/PHPExcel.php'); 
$userid = current_adminid(); 
if(empty($userid)){
 header("Location: index.php");
 }	 
$executive = $_GET['executive']; 
$campaign = !empty($_GET['campaign']) ? $_GET['campaign'] : ''; 
		if(!empty($campaign)){
	$pilotsarray = runloopQuery("SELECT * FROM campaign_logs where executive_id = '".$executive."' and campaign_id ='".$campaign."' order by ID desc");
									  }else{
	$pilotsarray = runloopQuery("SELECT * FROM campaign_logs where executive_id = '".$executive."' order by ID desc");
									  }
	   
		$pilotsarray = $pilotsarray ?: array();
		foreach($pilotsarray as $orders){  
			$ordersdatat  = array();
		if(empty($campaign)){
			$ordersdatat['Campaign'] = campaign_details($orders["campaign_id"],'title');
			}
			$ordersdatat['logtime'] = ucwords($orders['logtime']);
			$ordersdatat['action'] = ucwords($orders['action']);  
			$listitemsofhealth[] = $ordersdatat; 
		}
		$listitemsofhealth = $listitemsofhealth ?: array();
		 function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // file name for download
  $filename = "Executive_list_" . date('Y-m-d') . ".xls";

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel");

  $flag = false;
  foreach($listitemsofhealth as $row) {
    if(!$flag) {
      // display field/column names as first row
      echo implode("\t", array_keys($row)) . "\n";
      $flag = true;
    }
    array_walk($row, __NAMESPACE__ . '\cleanData');
    echo implode("\t", array_values($row)) . "\n";
  }

  exit;
?>