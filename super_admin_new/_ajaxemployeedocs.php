<?php
include('../functions.php');

	$employee_id = $_POST['employee_id'];
	$documentarray = $docsarray=  array();
     $docarray = runloopQuery("select * from employee_documents where employee_id = ".$employee_id."");
    foreach($docarray as $docarray){
        $docsarray['ID'] = $docarray['ID'];
        $docsarray['file_name'] = $docarray['file_name'];
        $docsarray['doc_name'] = $docarray['doc_name'];        
        $documentarray[] = $docsarray;
    }
    echo json_encode($documentarray);
?>