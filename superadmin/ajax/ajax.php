<?php

 include("../../functions.php");
 
	$checked = $_POST['tableid'];
	$conditionname = $_POST['conditionname'];
	
		$roderarray = $roderwharray=  array();
		$roderwharray['ID'] = $checked;
	if($conditionname == 'dailerstatus'){
        $column_name = 'dialer_status';
        $tablename = 'employee';
    }
    else if($conditionname == 'worksourcestatus'){
        $column_name = 'status';
        $tablename = 'worksource';        
    }
	else if($conditionname == 'designation_status'){
        $column_name = 'status';
        $tablename = 'tbl_designations';        
    }
    else if($conditionname == 'pointsetstatus'){
        $column_name = 'status';
        $tablename = 'pointset';
    }
	$resellaramount = runQuery("select ".$column_name." from ".$tablename." where ID = ".$checked." order by ID desc");
		if($resellaramount[$column_name]=='1'){
				$roderarray[$column_name] ='0';
		}else{
				$roderarray[$column_name] = '1';
		}
		updateQuery($roderarray,$tablename,$roderwharray);
?>