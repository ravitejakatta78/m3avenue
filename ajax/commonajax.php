<?php
include("../functions.php");
if(!empty($_POST['action'])){
    $action = $_POST['action'];
    switch($action){
        case 'empzipdownload':
            // Enter the name of directory
            $pathdir = "../../sp_ace_docs/empdocs/".$_POST['emp_id'].'/'; 
            $emp_details = runQuery("select * from employee where ID = '".$_POST['emp_id']."'");
            // Enter the name to creating zipped directory
            $zipcreated = $pathdir.$emp_details['fname'].' '.$emp_details['lname'].".zip";
  
            // Create new zip class
            $zip = new ZipArchive;
            
            if($zip -> open($zipcreated, ZipArchive::CREATE ) === TRUE) {
                // Store the path into the variable
                $dir = opendir($pathdir);
                
                while($file = readdir($dir)) {
                    if(is_file($pathdir.$file)) {
                        $zip -> addFile($pathdir.$file, $file);
                    }
                }
                $zip ->close();
            }
     echo $zipcreated;
        break;
        default:
        echo json_encode([]);
        break; 
    }

}
else{
    echo json_encode([]);
}

?>