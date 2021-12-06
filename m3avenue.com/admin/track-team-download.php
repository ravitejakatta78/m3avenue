<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');
include('../inc/PHPExcel.php'); 
			
		  if(!empty($_GET['employeeid'])){
							 $empid = employee_id($_GET['employeeid']);
							$health_details = runloopQuery("SELECT * FROM employee where ID = '".$empid."' order by ID desc");
						 }else if(!empty($_GET['employeemobile'])){ 
							 $empid = $_GET['employeemobile'];
							$health_details = runloopQuery("SELECT * FROM employee where mobile = '".$empid."' order by ID desc");
						 }else{
							$health_details = runloopQuery("SELECT * FROM employee order by ID desc");
						 }
					  
		$health_details = $health_details ?: array();
		foreach($health_details as $row){
				 
				 $totalclients = runQuery("select count(*) as count from clients where employee_id = '".$row['ID']."'");
			$totalinvestedamount = runQuery("select sum(loanamount) as count from clients where employee_id = '".$row['ID']."'");
			$totalconvertedclients = runQuery("select count(*) as count from clients where employee_id = '".$row['ID']."' and status = 'Active'");
			$totalconvertedamount = runQuery("select sum(loanamount) as count from clients where employee_id = '".$row['ID']."' and status = 'Active'");
			
					$listitemsofhealth[] = array("leader"=>$row["leader"] ?: 'NA',"fname"=>$row["fname"] ?: 'NA',"unique_id"=>$row["unique_id"] ?: 'NA',"mobile"=>$row["mobile"] ?: 'NA',"clients"=>$totalclients["count"] ?: 'NA',"investedamount"=>$totalinvestedamount["count"] ?: 'NA',"investedpoints"=>points($totalinvestedamount["count"]) ?: 'NA',"convertedclients"=>$totalconvertedclients["count"] ?: 'NA',"convertedamount"=>$totalconvertedamount["count"] ?: 'NA',"convertedpoints"=>points($totalconvertedamount["count"]) ?: 'NA');
							
		}
		$listitemsofhealth = $listitemsofhealth ?: array();
		 function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // file name for download
  $filename = "emp_report_" . date('Ymd') . ".xls";

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