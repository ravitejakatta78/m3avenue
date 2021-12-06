<?php

 include("../../functions.php");
 
	$checked = $_POST['checked'];
	$changestatus = $_POST['changestatus'];
	
		$roderarray = $roderwharray=  array();
		$roderwharray['ID'] = $checked;
	$resellaramount = runQuery("select status,employee_id from clients where ID = ".$checked." order by ID desc");
	 $roderarray['status'] = $changestatus;
	 $roderarray['mod_date'] = date('Y-m-d H:i:s A');
		 
		updateQuery($roderarray,'clients',$roderwharray);
		emp_monthly_income_check(['empid' => $resellaramount['employee_id']]);


?>