<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');
include('../inc/PHPExcel.php'); 
$userid = current_managerid(); 
if(empty($userid)){
 header("Location: index.php");
 }	
 $campaign = $_GET['campaign'];
 $sheet = !empty($_GET['sheet']) ? $_GET['sheet'] : 0;
		 
		  if(!empty($sheet)){
							$pilotsarray = runloopQuery("SELECT cu.*,e.name as ename FROM campaigns_users cu,executive e where cu.executive_id = e.ID and cu.campaign_id = '".$campaign."' and cu.campaignexcell_id = '".$sheet."' order by cu.ID desc");
								   }else{
							$pilotsarray = runloopQuery("SELECT cu.*,e.name as ename FROM campaigns_users cu,executive e where cu.executive_id = e.ID and cu.campaign_id = '".$campaign."'  order by cu.ID desc");
								   }
	   
		$pilotsarray = $pilotsarray ?: array();
		foreach($pilotsarray as $orders){  
			$ordersdatat  = array();
			$ordersdatat['Executive'] = $orders['ename'];
			$ordersdatat['name'] = ucwords($orders['name']);
			$ordersdatat['mobile'] = ucwords($orders['mobile']);
			$ordersdatat['callstatus'] = feedbackstatus($orders['callstatus']);  
			$ordersdatat['callmessage'] = $orders['callstatus'];  
			$ordersdatat['callduration'] = $orders['callduration'].' Sec';  
			$ordersdatat['Called on'] = reg_date($orders['duration']);
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
  $filename = "Campaigns_reports_" . date('Y-m-d') . ".xls";

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