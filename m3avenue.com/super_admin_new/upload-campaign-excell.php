<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');
include('../inc/PHPExcel.php');
include('../inc/PHPExcel/IOFactory.php'); 

$userid = current_adminid(); 

if(empty($userid)){

	header("Location: index.php");

}
$campainid = $_GET['campaign'];
if(empty($userid)){

	header("Location: campaigns-list.php");

}
$compaindetails = runQuery("select * from campaigns where ID = '".$campainid."'");
if(empty($compaindetails)){

	header("Location: campaigns-list.php");

}
$message = '';
if(!empty($_POST['excellsubmit'])){
	$questionarray = array(); 
$questionarray['user_id'] = $userid;
$questionarray['user_role'] = 'superadmin';
$questionarray['campaign_id'] = $_POST['excellsubmit'];  

if(!empty($_FILES['excellupload']['name'])){
		if (!file_exists('../inc/campaignexcells')){
			mkdir('../inc/campaignexcells', 0777, true);
		}
		$target_dir = '../inc/campaignexcells/';
		$file = explode('.',$_FILES["excellupload"]['name']);
		$filename = date('dmYHis').'-'.$file[0].'.'.strtolower(end($file));
		$target_file = $target_dir . $filename;
		$uploadOk = 1;	
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);	
		if($imageFileType != "xlsx" ) {		
		$message .= "Sorry, only xlsx files are allowed.";	
		$uploadOk = 0;
		}		
		if ($uploadOk == 0) {
		$message .= "Sorry, your file was not uploaded.";
		} else {
		if (move_uploaded_file($_FILES["excellupload"]["tmp_name"], $target_file)){
			$inputFileName = $target_file;
		 try {
			$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
		} catch(Exception $e) {
			die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
			}
			
		$dataArr = $optionsarray =  array();  
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow();
			$highestColumn      = $worksheet->getHighestColumn();
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			for ($row = 2; $row <= $highestRow; ++ $row) {
				for ($col = 1; $col < $highestColumnIndex; ++ $col) {
					$cell = $worksheet->getCellByColumnAndRow($col, $row);
					$val = $cell->getValue();
					if(!empty($val)){
					$dataArr[$row][$col] = $val;
					} 
					$optionname = $worksheet->getCellByColumnAndRow($col, 1);
					$optionname = $optionname->getValue();
					if(!empty($optionname)){ 
							$optionsarray[$col] = $optionname; 
					}
				}
			}
		}	     
			 
		$excellarray = array();
			$excellarray['user_id'] = $userid;
			$excellarray['user_role'] = 'superadmin';
			$excellarray['campaign_id'] = $_POST['excellsubmit'];
			$excellarray['excellname'] = $file[0];
			$excellarray['excellfile'] = $filename;
			$excellarray['uploaddate'] = date('Y-m-d H:i:s');  
			$lastid = insertIDQuery($excellarray,'campaigns_excell');
			
			
			$totalques = $prevuserexts = $errorques = array();
			$count = 1;
			$loopCount = 0;
			$executive_string = $compaindetails['executive'];
			$assigned_executive_array = explode(',',$executive_string);
			if(count($assigned_executive_array) > 0){
				$assignedLimitUsers = count($assigned_executive_array) * 25;
			}
			else{
				$assignedLimitUsers = 0;
			}
			 
			foreach($dataArr as $singlearray){ 
				 if(!empty($singlearray[2])){
				 $mobilecheck = runQuery("select * from campaigns_users where campaign_id = '".$questionarray['campaign_id']."'
					  and mobile = '".$singlearray[2]."'");
					  if(empty($mobilecheck)){
						if($assignedLimitUsers > $loopCount){
							$questionarray['executive_id'] =	$assigned_executive_array[(int)($loopCount/25)];
						}
						else{
							$questionarray['executive_id'] = null;
						}
						$questionarray['campaignexcell_id'] = $lastid;
						$questionarray['name'] = !empty($singlearray[1]) ? $singlearray[1] : ''; 
						$questionarray['mobile'] = $singlearray[2]; 
						$questionarray['callstatus'] = 0; 
						$questionarray['callstart'] = 0; 
						
						$campaignid = insertIDQuery($questionarray,'campaigns_users');
						/*for($ig = 3;$ig < $highestColumnIndex;$ig++){
							$coulmnname =  $optionsarray[$ig];
							$coulmnval =  $singlearray[$ig];
							update_campaignuser_options($campaignid,$coulmnname,$coulmnval);
						} */
					  }
				}
				$loopCount++; 
				} 
				if(!empty($campaignid)){ 
					header("Location: upload-campaign-excell.php?campaign=".$questionarray['campaign_id']."&success=success");	
				}else{
				$message .=  'Technical error found';
				
					}
		 } else {			
		$message .= "Sorry, there was an error uploading your question file.";
		}	
		} 												
	}else{
		$message .= "Please upload the file";
	}
	
}

if(!empty($_GET['delete'])){
	 $target_dir = '../inc/campaignexcells/';
	 if(!empty($_GET['filename'])){
		 if(file_exists($target_dir.$_GET['filename'])){
			 unlink($target_dir.$_GET['filename']);
		 }
	 }
	$sql = "DELETE FROM campaigns_users WHERE campaignexcell_id=".$_GET['delete']."";
	$conn->query($sql);
	$sql = "DELETE FROM campaigns_excell WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: upload-campaign-excell.php?campaign=".$_GET['campaign']."&dsuccess=success");
   
} else {
    echo "Error deleting record: " . $conn->error;
}
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<meta name="robots" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template" />
	<meta property="og:title" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template" />
	<meta property="og:description" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template" />
	<meta property="og:image" content="https:/fillow.dexignlab.com/xhtml/social-image.png" />
	<meta name="format-detection" content="telephone=no">
	
	<!-- PAGE TITLE HERE -->
	<title>Fillow Saas Admin Dashboard</title>
	
	
	<?php include('header_scripts.php');?>
</head>
<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
		<div class="lds-ripple">
			<div></div>
			<div></div>
		</div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

       
		<?php include('header.php');?>
		
		
		<!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
			<div class="container-fluid">
			 	<div class="row">

				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h4 class="page-title" style="color:#886CC0">Excell for <?php echo $compaindetails['title'];?></h4>

							 <a href="campign-excell.xlsx" download class="btn btn-danger" >Download Format</a>
								<a href="campaigns-list.php" class="btn btn-primary pull-right" >Back to Campaigns</a>  
						</div>

						<div class="card-body">

							<?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Excell upload Succussfully
								</div>
								<?php } ?>
						   <?php if(!empty($_GET['dsuccess'])){?>
								<div class="alert alert-success">
								User deleted Succussfully
								</div>
								<?php } ?>
						 
		 <form  method="post" action="" enctype="multipart/form-data" autocomplete="off" > 
						 
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Upload file</label>
						<div class="col-3">
							<div class="custom-file">
								<input type="file" class="custom-file-input" name="excellupload" id="customFile">
								<label class="custom-file-label" for="customFile">Choose file</label>
							</div>
						</div>
					</div> 
					 <div class="form-group row"> 
					
					   <div class="col-3">
							 <button type="submit" name="excellsubmit" value="<?php echo $compaindetails['ID'];?>" class="btn btn-primary btn-elevate btn-pill">Upload</button>
						</div>
					</div>  
					</form>

        </div>
    </div>
</div>
</div>
    <br><br><br>
    <div class="row">

				<div class="col-12">
    <div class="card">
						<div class="card-header">
							
								<a href="csv-campaigns-sheet.php?campaign=<?php echo $compaindetails["ID"];?>" class="btn btn-primary" >Download</a>
								<a href="upload-campaign-reports.php?campaign=<?php echo $compaindetails["ID"];?>" class="btn btn-warning pull-right" >Complete report</a>  
						</div>

						<div class="card-body">

							<?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Excell upload Succussfully
								</div>
								<?php } ?>
						   <?php if(!empty($_GET['dsuccess'])){?>
								<div class="alert alert-success">
								User deleted Succussfully
								</div>
								<?php } ?>
						 
		 			<table id="example3" style="min-width: 845px" class="table table-bordered table-hover table-checkable display">
					<thead>
						<tr>
							<th>S.No</th>  
							<th>Excel name</th>  
							<th>Upload date</th>  
							<th>Reports</th>  
							<th>Sheet</th>  
                            <th>Pending users</th> 
                            <th>Reg date</th>  
                            <th>Action</th>  
						</tr> 
							</thead>
							<tbody>
								  <?php
							$sql = runloopQuery("SELECT * FROM campaigns_excell where campaign_id = '".$compaindetails['ID']."' order by ID desc");

							   $x=1;  foreach($sql as $row)
									{
							?>
							<tr>
							<td><?php echo  $x;?></td> 
							<td><?php echo $row["excellname"];?></td>
							<td><?php echo reg_date($row["uploaddate"]);?></td>
							<td><a href="upload-campaign-reports.php?campaign=<?php echo $row["campaign_id"];?>&campaignsheet=<?php echo $row["ID"];?>">Report</a></td>
							<td><a href="../inc/campaignexcells/<?php echo $row["excellfile"];?>">Download</a></td>
							<td><a href="campaigns-users.php?campaign=<?php echo $row["campaign_id"];?>&campaignsheet=<?php echo $row["ID"];?>">Users</a></td>
							<td><?php echo reg_date($row["reg_date"]);?></td>
							<td><a onclick="return confirm('Are you sure want to delete??');"  href="?campaign=<?php echo $compaindetails['ID']; ?>&delete=<?php echo $row["ID"];?>&filename=<?php echo $row["excellfile"];?>">Delete</a></td>
							</tr>
							<?php
								 $x++; }
							?>
													  </tbody>
													  
							<tbody>
							 <?php ?>
							</tbody>
					</table>

        </div>
    </div>
         </div>
        </div>
				
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->


	</div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <?php include('footer_scripts.php');?>


</body>
</html>