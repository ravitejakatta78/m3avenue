<?php

 include("../../functions.php");
 
	$checked = $_POST['tableid'];
	$tablename = $_POST['tablename'];
	
		$roderarray = $roderwharray=  array();
		$roderwharray['ID'] = $checked;
	$resellaramount = runQuery("select status from $tablename where ID = ".$checked." order by ID desc");
		if($resellaramount['status']=='1'){
				$roderarray['status'] ='2';
		}else{
				$roderarray['status'] = '1';
		}
		updateQuery($roderarray,$tablename,$roderwharray)

?>