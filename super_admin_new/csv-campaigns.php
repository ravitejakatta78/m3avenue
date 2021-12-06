<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');
include('../inc/PHPExcel.php'); 
$userid = current_adminid(); 
if(empty($userid)){
 header("Location: index.php");
 }	
		 
		  $pilotsarray = runloopQuery("select * from campaigns order by ID desc");	
	   
		$pilotsarray = $pilotsarray ?: array();
		foreach($pilotsarray as $orders){ 
			$execuitvenames = array();
			if(!empty($orders['executive'])){
			$execuitvenames =  runloopQuery("SELECT name FROM `executive` WHERE find_in_set_x('".$orders['executive']."',ID)");
			if(!empty($execuitvenames)){
			$execuitvenames = array_column($execuitvenames, 'name');
			} 
			}
			$managersnames = array();
			if(!empty($orders['manager_id'])){
			$managersnames =  runloopQuery("SELECT name FROM `manager` WHERE find_in_set_x('".$orders['manager_id']."',ID)");
			if(!empty($managersnames)){
			$managersnames = array_column($managersnames, 'name'); 
			}  
			}  
			$ordersdatat  = array();
			$ordersdatat['Created'] = $orders['user_role']=='superadmin' ? 'Admin' : ucwords(manager_details($orders['user_id'],'name'));
			$ordersdatat['Created By'] = ucwords($orders['user_role']);
			$ordersdatat['Campaign id'] = $orders['unique_id'];
			$ordersdatat['title'] = $orders['title']; 
			$ordersdatat['timeframe'] = $orders['timeframe'];
			$ordersdatat['managers'] = implode(',',$managersnames);
			$ordersdatat['executive'] = implode(',',$execuitvenames);
			$ordersdatat['status'] = status_details($orders['status']);
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