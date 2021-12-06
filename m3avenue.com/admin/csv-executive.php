<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');
include('../inc/PHPExcel.php'); 
$userid = current_adminid(); 
if(empty($userid)){
 header("Location: index.php");
 }	 
		  $pilotsarray = runloopQuery("SELECT * FROM executive order by ID desc");
	   
		$pilotsarray = $pilotsarray ?: array();
		foreach($pilotsarray as $orders){  
			$ordersdatat  = array();
			$ordersdatat['Created By'] = $orders['user_role']=='superadmin' ? 'Admin' : ucwords(manager_details($orders['user_id'],'name'));
			$ordersdatat['unique_id'] = ucwords($orders['unique_id']);
			$ordersdatat['name'] = ucwords($orders['name']);
			$ordersdatat['mobile'] = ucwords($orders['mobile']);   
			$ordersdatat['location'] = $orders['location'];   
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