<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');
include('../inc/PHPExcel.php');
include('../inc/PHPExcel/IOFactory.php'); 

$userid = current_managerid(); 

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
$questionarray['user_role'] = 'manager';
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
			$excellarray['user_role'] = 'manager';
			$excellarray['campaign_id'] = $_POST['excellsubmit'];
			$excellarray['excellname'] = $file[0];
			$excellarray['excellfile'] = $filename;
			$excellarray['uploaddate'] = date('Y-m-d H:i:s');  
			$lastid = insertIDQuery($excellarray,'campaigns_excell');
			
			
			$totalques = $prevuserexts = $errorques = array();
			$count = 1; 
			foreach($dataArr as $singlearray){ 
				 if(!empty($singlearray[2])){
					$questionarray['campaignexcell_id'] = $lastid;
					$questionarray['name'] = !empty($singlearray[1]) ? $singlearray[1] : ''; 
					$questionarray['mobile'] = $singlearray[2]; 
					$questionarray['callstatus'] = 0; 
					$questionarray['callstart'] = 0; 
					$campaignid = insertIDQuery($questionarray,'campaigns_users');
						
					for($ig = 3;$ig < $highestColumnIndex;$ig++){
						$coulmnname =  $optionsarray[$ig];
						$coulmnval =  $singlearray[$ig];
						update_campaignuser_options($campaignid,$coulmnname,$coulmnval);
					}
				} 
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

 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../plugins/images/favicon.png">
    <title>M3 Dashboard</title>
    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- toast CSS -->
    <link href="../plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- morris CSS -->
    <link href="../plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="css/colors/blue-dark.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <!-- Preloader -->
   
    <div id="wrapper">
        <!-- Navigation -->
       <?php include('header.php');?>
        <!-- Left navbar-header end -->
		
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Campaigns</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li class="active">Profile page</li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
                <!-- .row -->
                <div class="row"> 
                    <div class="col-md-12 col-xs-12">
                        <div class="white-box">
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
                <div class="row"> 
                    <div class="col-md-12 col-xs-12">
                        <div class="white-box">
								<a href="csv-campaigns-sheet.php?campaign=<?php echo $campainid;?>" class="btn btn-brand btn-success btn-pill">Download</a>
                        <div class="table table-responsive">

						<!--begin: Datatable -->
				<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>S.No</th>  
							<th>Excel name</th>  
							<th>Excel file</th>  
							<th>Upload date</th>  
							<th>Reports</th>  
                            <th>Pending users</th> 
                            <th>Reg date</th>   
						</tr> 
							</thead>
							<tbody>
								  <?php
								  if($compaindetails['user_role']=='manager'){
							$sql = runloopQuery("SELECT * FROM campaigns_excell where campaign_id = '".$compaindetails['ID']."' and user_id = '".$userid."' and user_role = 'manager' order by ID desc");
								 }else{
							$sql = runloopQuery("SELECT * FROM campaigns_excell where campaign_id = '".$compaindetails['ID']."' order by ID desc");
								  }
							   $x=1;  foreach($sql as $row)
									{
							?>
							<tr>
							<td><?php echo  $x;?></td> 
							<td><?php echo $row["excellname"];?></td>
							<td><a <?php if($row["excellfile"]){?> download href="../inc/campaignexcells/<?php echo $row["excellfile"];?>" <?php }else{?> href="javascript:void(0);" onClick="return alert('File Not Found');" <?php }?>>Download</a></td>
							<td><?php echo reg_date($row["uploaddate"]);?></td>
							<td><a href="upload-campaign-reports.php?campaign=<?php echo $row["campaign_id"];?>&campaignsheet=<?php echo $row["ID"];?>">Report</a></td>
							<td><a href="campaigns-users.php?campaign=<?php echo $row["campaign_id"];?>&campaignsheet=<?php echo $row["ID"];?>">Users</a></td>
							<td><?php echo reg_date($row["reg_date"]);?></td> 
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
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
<?php include('footer.php');?>			
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/custom.min.js"></script>
</body>

</html>
