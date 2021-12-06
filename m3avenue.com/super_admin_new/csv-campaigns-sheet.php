<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');
include('../inc/PHPExcel.php'); 
$userid = current_adminid(); 
if(empty($userid)){
 header("Location: index.php");
 }	
 $campaign = $_GET['campaign'];
		 
		  $pilotsarray = runloopQuery("select * from campaigns_excell where campaign_id = '".$campaign."' order by ID desc");	
	   
		$pilotsarray = $pilotsarray ?: array();
		foreach($pilotsarray as $orders){  
			$ordersdatat  = array();
			$ordersdatat['Created'] = $orders['user_role']=='superadmin' ? 'Admin' : ucwords(manager_details($orders['user_id'],'name'));
			$ordersdatat['Created By'] = ucwords($orders['user_role']);
			$ordersdatat['Campaign'] = campaign_details($orders['campaign_id'],'title');
			$ordersdatat['excellname'] = $orders['excellname'];  
			$ordersdatat['Uploadon'] = reg_date($orders['uploaddate']);
			$ordersdatat['Created on'] = reg_date($orders['reg_date']);
			 
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
  $filename = "Campaigns_" . date('Y-m-d') . ".xls";

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