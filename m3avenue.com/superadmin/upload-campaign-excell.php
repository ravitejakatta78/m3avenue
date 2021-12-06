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
	<!-- begin::Head -->
	<head>
		<!--begin::Base Path (base relative path for assets of this page) -->
		<!--end::Base Path -->
		<meta charset="utf-8" />
		<title>M3  | Dashboard</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />

		<?php include('headerscripts.php');?>
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--fixed kt-page--loading">
		<!-- begin:: Header Mobile -->
		<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
			<div class="kt-header-mobile__logo">
				<a href="demo1/index.html">
					<img alt="Logo" src="./assets/media/logos/logo-6.png" />
				</a>
			</div>
			<div class="kt-header-mobile__toolbar">
				<button class="kt-header-mobile__toolbar-toggler kt-header-mobile__toolbar-toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
				<button class="kt-header-mobile__toolbar-toggler" id="kt_header_mobile_toggler"><span></span></button>
				<button class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
			</div>
		</div>

		<!-- end:: Header Mobile -->

		<!-- begin:: Root -->
		<div class="kt-grid kt-grid--hor kt-grid--root">

			
			<!-- begin:: Page -->
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
 
<?php include('header.php');?>
 

				<!-- end:: Aside -->

				<!-- begin:: Wrapper -->
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

					<!-- begin:: Header -->

<?php include('headernav.php');?>
					<!-- end:: Header -->
	<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

					 			<div class="kt-portlet kt-portlet--mobile">
					
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-label">
								<h3 class="kt-portlet__head-title">Excell for <?php echo $compaindetails['title'];?></h3>
							 <a href="campign-excell.xlsx" download class="btn btn-danger" >Download Format</a>
								<a href="campaigns-list.php" class="btn btn-primary pull-right" >Back to Campaigns</a> 
						</div>
					</div>
					<div class="kt-portlet__body">
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
							 <button type="submit" name="excellsubmit" value="<?php echo $compaindetails['ID'];?>" class="btn btn-brand btn-elevate btn-pill">Upload</button>
						</div>
					</div>  
					</form>
					</form>
					 
						</div>
						</div>
						</div>


						<!-- begin:: Content -->
	<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
                          
		    <div class="kt-portlet kt-portlet--mobile"> 
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">  
								<a href="csv-campaigns-sheet.php?campaign=<?php echo $compaindetails["ID"];?>" class="btn btn-primary" >Download</a>
								<a href="upload-campaign-reports.php?campaign=<?php echo $compaindetails["ID"];?>" class="btn btn-warning pull-right" >Complete report</a> 
							</div>
						</div>     
		<div class="kt-portlet__body">  
						<!--begin: Datatable -->
			<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
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
							<td><?php echo $row["excellname"];?>
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
					 
				<!--end: Datatable --> 
	    	</div>
							
			</div>
		</div>
				 
</div>
</div>
	    
	    
					<!-- begin:: Footer -->
					<div class="kt-footer kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop">
						<div class="kt-footer__copyright">
							2019&nbsp;&copy;&nbsp;<a href="https://sansdigitals.com" target="_blank" class="kt-link">sansdigitals.com</a>
						</div>
					 
					</div>
					<!-- end:: Footer -->
				</div>

				<!-- end:: Wrapper -->
			</div>

			<!-- end:: Page -->
		</div>

		<!-- end:: Root -->
 
		
	<?php include("footerscripts.php");?>
	</body>

	<!-- end::Body -->
</html>