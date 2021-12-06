<?php
include('../functions.php');

	$trackworkid = $_POST['trackworkid'];
	$documentarray = $docsarray=  array();
     $docarray = runloopQuery("select twd.*
     ,tw.clientname,tw.mobile,tw.amount,tw.selecttype,tw.company,tw.address,tw.employee_id,tw.doc_status from track_work_documents twd
     inner join track_work tw on tw.ID = twd.track_work_id 
     where twd.track_work_id = ".$trackworkid."");
    foreach($docarray as $docarray){
        $docsarray['ID'] = $docarray['ID'];
        $docsarray['file_name'] = $docarray['file_name'];
        $docsarray['doc_name'] = $docarray['doc_name'];
        $docsarray['clientname'] = $docarray['clientname'];
        $docsarray['mobile'] = $docarray['mobile'];
        $docsarray['amount'] = $docarray['amount'];
        $docsarray['selecttype'] = $docarray['selecttype'];
        $docsarray['company'] = $docarray['company'];
        $docsarray['address'] = $docarray['address'];
        $docsarray['employee_id'] = $docarray['employee_id'];
        $docsarray['doc_status'] = $docarray['doc_status'];
        
        $documentarray[] = $docsarray;
    }
    echo json_encode($documentarray);
?>