<?php 
session_start(); 
error_reporting(E_ALL); 
include('../functions.php'); 

function createZipAndDownload($files, $filesPath, $zipFileName)
{
    // Create instance of ZipArchive. and open the zip folder.
    $zip = new \ZipArchive();
    if ($zip->open($zipFileName, \ZipArchive::CREATE) !== TRUE) {
        exit("cannot open <$zipFileName>\n");
    }

    // Adding every attachments files into the ZIP.
    foreach ($files as $file) {
        $zip->addFile($filesPath . $file, $file);
    }
    $zip->close();

    // Download the created zip file
    header("Content-type: application/zip");
    header("Content-Disposition: attachment; filename = $zipFileName");
    header("Pragma: no-cache");
    header("Expires: 0");
    readfile("$zipFileName");
    unlink($zipFileName);
    exit;
}

if(!empty($_GET['id'])){
$trackworkid=$_GET['id'];
      $documentarray = $docsarray=  array();
     $docarray = runloopQuery("select twd.*
     ,tw.clientname,tw.mobile,tw.amount,tw.selecttype,tw.company,tw.address,tw.employee_id,tw.doc_status from track_work_documents twd
     inner join track_work tw on tw.ID = twd.track_work_id 
     where twd.track_work_id ='".$trackworkid."'");
    foreach($docarray as $docarray){
        
        $docsarray['doc_name'] = $docarray['doc_name'];
         array_push($documentarray,$docarray['doc_name']);;
    }

$filesPath="../upload_documents/".$trackworkid."/";

$files=$documentarray;
$zipFileName=$docarray['clientname'].".zip";

echo createZipAndDownload($files, $filesPath, $zipFileName);
}
?>