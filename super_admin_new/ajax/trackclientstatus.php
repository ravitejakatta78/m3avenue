<?php

 include("../../functions.php");
 
	$checked = $_POST['checked'];
	
		$roderarray = $roderwharray=  array();
		$roderwharray['ID'] = $checked;
	$resellaramount = runQuery("select status from clients where ID = ".$checked." order by ID desc");
		if($resellaramount['status']=='Approved'){
				$roderarray['status'] ='Pending';
		}else{
				$roderarray['status'] = 'Approved';
		}
		updateQuery($roderarray,'clients',$roderwharray)

?>