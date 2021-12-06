<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');
include('../inc/PHPExcel.php'); 
			
		 if(!empty($_GET['employeeid'])){
							 $empid = employee_id($_GET['employeeid']);
							$health_details = runloopQuery("SELECT * FROM clients where employee_id = '".$empid."' order by ID desc");
						 }else if(!empty($_GET['clientmobile'])){ 
							 $empid = $_GET['clientmobile'];
							$health_details = runloopQuery("SELECT * FROM clients where mobile = '".$empid."' order by ID desc");
						 }else{
							$health_details = runloopQuery("SELECT * FROM clients order by ID desc");
						 }
					  
		$health_details = $health_details ?: array();
		foreach($health_details as $row){
				 $pendingamount = $row["loanamount"];
				$appamount = 0;
				if($row["status"] == 'Active'){
					$pendingamount = 0;
					$appamount = $row["loanamount"];
				}
					$listitemsofhealth[] = array("name"=>employee_details($row["employee_id"],'fname') ?: 'NA',"employee id"=>employee_details($row["employee_id"],'unique_id') ?: 'NA',"clientname"=>$row["clientname"] ?: 'NA',"regdate"=>$row["regdate"] ?: 'NA',"mobile"=>$row["mobile"] ?: 'NA',"investedtype"=>$row["investedtype"] ?: 'NA',"pendingpoints"=>points($pendingamount) ?: 'NA',"apppoints"=>points($appamount) ?: 'NA');
							
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