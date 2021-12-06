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
function exceluploads($uploadedfile,$uploadfolder,$lastid){
	if (!file_exists('../'.$uploadfolder)){
		mkdir('../'.$uploadfolder, 0777, true);
	}
	$target_dir = '../'.$uploadfolder.'/';
	$file = explode('.',$uploadedfile['name']);
	$filename = date('dmYHis').'-'.$file[0].'.'.strtolower(end($file));
	$target_file = $target_dir . $filename;
	$uploadOk = 1;	
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);	
	if($imageFileType != "xlsx" && $imageFileType != "xls" && $imageFileType != "ods" ) {		
	$message .= "Sorry, only xlsx,xls,ods files are allowed.";	
	$uploadOk = 0;
	}		
	if ($uploadOk == 0) {
	$message .= "Sorry, your file was not uploaded.";
	} else {
		//echo $target_file;exit;
	if (move_uploaded_file($uploadedfile["tmp_name"], $target_file)){
		$inputFileName = $target_file;
		$trid['ID'] = $lastid;
		if($uploadfolder == 'roi'){
			 
			$docstatus['roi_path'] = $filename;
		}
		else{
			$docstatus['company_category_path'] = $filename;
		}
		$result = updateQuery($docstatus,'pl_banks',$trid);
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
			for ($row = 1; $row <= $highestRow; ++ $row) {
				for ($col = 0; $col < $highestColumnIndex; ++ $col) {
					$cell = $worksheet->getCellByColumnAndRow($col, $row);
					if (PHPExcel_Shared_Date::isDateTime($cell)) {
						 $val =  $cell->getFormattedValue() ;
					} else {
						 $val = $cell->getValue() ;
					}
					
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
		$dataArr = array_values($dataArr);
		return $dataArr;
	}
}
}
$message = '';
$catarr = [];

if(!empty($_POST['plsubmit']) &&  $_POST['randcheck'] == $_SESSION['rand']){
	
	if(!empty($_FILES['bank_logo']['name'])){
		if (!file_exists('../banklogo')){
			mkdir('../banklogo', 0777, true);
		}
		$target_dir = '../banklogo/';
		$file = explode('.',$_FILES["bank_logo"]['name']);
		$filename = date('dmYHis').'-'.$file[0].'.'.strtolower(end($file));
		$target_file = $target_dir . $filename;
		$uploadOk = 1;	
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);	
		if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != "jpeg" ) {		
		$message .= "Sorry, only png,jpg,jpeg files are allowed.";	
		$uploadOk = 0;
		}		
		if ($uploadOk == 0) {
		$message .= "Sorry, your file was not uploaded.";
		} else {
		if (move_uploaded_file($_FILES["bank_logo"]["tmp_name"], $target_file)){
			$pagearray = []; 
			$pagearray['bank_name'] = $_POST['bankname'];
			$pagearray['bank_logo'] = $filename; 
			$lastid = insertIDQuery($pagearray,'pl_banks');
			$uploadexcelarr = ['roi','company_category'];
			
			for($i=0;$i < count($uploadexcelarr); $i++ )
			{
				$dataArr = exceluploads($_FILES[$uploadexcelarr[$i]],$uploadexcelarr[$i],$lastid);
				if($uploadexcelarr[$i] == 'roi'){
				$count = 0;
				foreach($dataArr as $singlearray){ 
					if($count > 0){
					   $questionarray['bank_id'] = $lastid;
					   $questionarray['salary_start'] = !empty($singlearray[1]) ? $singlearray[1] : 0; 
					   $questionarray['salary_end'] = $singlearray[2]; 
					   $questionarray['company_category'] = $singlearray[0]; 
					   $questionarray['state'] = $singlearray[3];
					   $questionarray['r1'] = $singlearray[4];
					   $questionarray['r2'] = $singlearray[5];
					   $questionarray['r3'] = $singlearray[6];
					   $questionarray['r4'] = $singlearray[7];
					   $questionarray['r5'] = $singlearray[8] ?? 0;
					   $questionarray['foir'] = @$singlearray[9] ?? 0;
					   $questionarray['tenure'] = @$singlearray[10] ?? 0;
					   $campaignid = insertIDQuery($questionarray,'pl_data');
					}	
				   
				   $count++;
				   }
				}
				if($uploadexcelarr[$i] == 'company_category'){
					$count = 0;
					
					foreach($dataArr as $singlearray){
						 
						if($count > 0){
						   $questionarray1['bank_id'] = $lastid;
						   $questionarray1['company_name'] =  $singlearray[0] ; 
						   $questionarray1['company_category'] = $singlearray[1]; 
						   $campaignid = insertIDQuery($questionarray1,'pl_company_category');
						   if(!in_array($questionarray1['company_category'],$catarr)){
							   
							$questionarray2['bank_id'] = $lastid;
							$questionarray2['company_category'] = $singlearray[1];
							$questionarray2['tenure'] = $singlearray[2];
							$questionarray2['multiplier'] = $singlearray[3];
							$ress = insertQuery($questionarray2,'pl_category_tenure');
						   
						}
						   
						   $catarr[] = $questionarray1['company_category'];
						}	
					   $count++;
					   }
				}
			}
			
			
			if(!empty($lastid)){ 
				header("Location: upload-pl-details.php?success=success");	
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

if(!empty($_REQUEST['plupdate'])){
	for($j=0;$j<count($_REQUEST['tenure']);$j++){
		$pagerwherearray['ID'] = mysqli_real_escape_string($conn,$_POST['idarr'][$j]);
		$pagerarray['tenure'] = mysqli_real_escape_string($conn,$_POST['tenure'][$j]);
		$pagerarray['multiplier'] = mysqli_real_escape_string($conn,$_POST['multiplier'][$j]); 
		
		$result = updateQuery($pagerarray,'pl_category_tenure',$pagerwherearray);
	}
		header("Location: upload-pl-details.php?success=success");


	
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

	<!-- Datatable -->
	<link href="assets/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

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
							<h4 class="page-title" style="color:#886CC0">PL Banks list</h4>
							<button type="button" class="btn btn-primary" style="float:right" data-toggle="modal" data-target="#newModal">Add Bank</button> 
						</div>
						<div class="card-body">

							<?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Bank Added Succussfully
								</div>
								<?php } ?>
			
						   <?php if(!empty($_GET['psuccess'])){?>
								<div class="alert alert-success">
								<?php echo $_GET['psuccess'];?>
								</div>
								<?php } ?>
								<?php if(!empty($message)){?>
								<div class="alert alert-danger">
								<?=$message?>
								</div>
								<?php } ?>

							<div class="table table-responsive">

								<!--begin: Datatable -->
								<table id="example3" style="min-width: 845px" class="table table-bordered table-hover table-checkable display">
								<thead>
					<tr>
								<th>S.No</th>
								<th>Bank Name</th>
								<th>Logo</th>
								<th>Company Category</th>
								<th>ROI</th>
								<th>Tenure/Multiplier</th> 
							</tr>
						
							</thead>
								<tbody>

								<?php
							$sql = runloopQuery("SELECT * from pl_banks");
							$x=1;  foreach($sql as $row){

							
							?>
							<tr>
							<td><?= $x; ?></td>
							<td><?php echo $row["bank_name"];?></td>
							<td><img src="<?php echo SITE_URL.'/banklogo/'.$row["bank_logo"];?>" height="50px" width="50px"></td>
							<td><a href="<?php echo SITE_URL.'/company_category/'.$row["company_category_path"];?>" target="_blank">Download</a></td>
							<td><a href="<?php echo SITE_URL.'/roi/'.$row["roi_path"];?>" target="_blank">Download</a></td>
							<td><a href="?updatepl=<?php echo $row["ID"];?>">Add</a></td>
							</tr>
<?php
								$x++; }
							?>
                          </tbody>
						</table>
						</div>
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

     <!-- Datatable -->
        <script src="assets/vendor/datatables/js/jquery.dataTables.min.js"></script>
        <script src="assets/js/plugins-init/datatables.init.js"></script>


         <div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Bank</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <form  method="post" action="" enctype="multipart/form-data" id="addvendorform" autocomplete="off" >
	  <?php
		$rand=rand();
   		$_SESSION['rand']=$rand;
  	?>
						<div class="form-group row">
						    
                        <label for="example-text-input" class="col-md-2 col-form-label">Bank Name</label>
                        <div class="col-md-4">
						<input class="form-control" name="bankname" type="text"  id="bankname" required >
							<input class="form-control" name="plsubmit" type="hidden"  id="plsubmit" value="1" >
							<input type="hidden" value="<?php echo $rand; ?>" name="randcheck" /> 	
						</div>


                        <label for="example-text-input"  class="col-md-2 col-form-label" >Logo</label>
                        <div class="col-md-4"  >
						<input class="custom-file-input" name="bank_logo" type="file"   id="bank_logo" required>
						<label class="custom-file-label" id="bank_logo_red" for="customFile">Choose file</label>
                        </div>

						</div>
						<div class="form-group row">
						    
                        <label for="example-text-input"  class="col-md-2 col-form-label" >Company Category</label>
                        <div class="col-md-4"  >
						<input class="custom-file-input" name="company_category" type="file"   id="company_category" required>
						<label class="custom-file-label" id="company_category_red" for="customFile">Choose file</label>
                        </div>
						<label for="example-text-input" class="col-2 col-form-label">ROI</label>
                        <div class="col-md-4"  >
						<input class="custom-file-input" name="roi" type="file"   id="roi" required>
						<label class="custom-file-label" id="roi_red" for="customFile">Choose file</label>
                        </div>
						</div>
					</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addvendorsubmitid">Add Application</button>
      </div>
    </div>
  </div>
</div> 



    <?php if(!empty($_REQUEST['updatepl'])) {
		$banks = runQuery("select * from pl_banks where ID = '".$_REQUEST['updatepl']."'");
	 ?>		
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Tenure</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <form  method="post" action=""  id="updatevendorform" autocomplete="off" >
	 
						<div class="form-group row">
						    
                        <label for="example-text-input" class="col-md-2 col-form-label">Bank Name</label>
                        <div class="col-md-4">
						<input class="form-control" name="displaybankname" type="text"  id="displaybankname"  value="<?= $banks['bank_name']; ?>" readonly>
							<input class="form-control" name="plupdate" type="hidden"  id="plupdate" value="<?= $_REQUEST['updatepl']; ?>" >
								
						</div>
	</div>

<div class="row">
                      <table class="table">
							<tr>
								<th>S.No</th>
								<th>Category</th>
								<th>Tenure</th>
								<th>Multiplier</th>
							</tr>
							<?php $cat_tenure = runloopQuery("select * from pl_category_tenure where bank_id = ".$_GET['updatepl']."");
							for($i=0;$i<count($cat_tenure);$i++) {
							?>
							<tr>
								<td><?= ($i+1) ?></td>
								<td><?= $cat_tenure[$i]['company_category']; ?></td>
								<td><input type="text" name="tenure[]" value="<?= $cat_tenure[$i]['tenure']; ?>">
								<input type="hidden" name="idarr[]" value="<?= $cat_tenure[$i]['ID']; ?>">
							</td>
								<td><input type="text" name="multiplier[]" value="<?= $cat_tenure[$i]['multiplier']; ?>"></td>
							</tr>
						<?php } ?>
						</table>
					</div>
	
					</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="updatevendorsubmitid">Update Tenure</button>
      </div>
    </div>
  </div>
</div> 
<script>
$("#updateModal").modal('show');
</script>
<?php } ?>

<script>
	$("#addvendorsubmitid").click(function(){
		var user_input_value;
		var err_value = 0
		$('#addvendorform').find('input,select,textarea').each(function(){
			if($(this).prop('required')){
				
				user_input_value  = $("#"+this.id).val();
				if(user_input_value == ''){
					if(err_value == 0){
						document.getElementById(this.id).focus();
					}
					err_value = err_value + 1;
					$("#"+this.id).css('border-color', 'red');
					if($(this).attr("type") == 'file'){
						$("#"+this.id+"_red").css('border-color', 'red');
					}
				}else{
					$("#"+this.id).css('border-color', '#e4e7ea');
					if($(this).attr("type") == 'file'){
						$("#"+this.id+"_red").css('border-color', '#e4e7ea');
					}
				}
			}	 
		});
		
		if(err_value == 0)
		{
			$("#addvendorsubmitid").hide();
			$("#addvendorform").submit();	
		}
		
	});

	$("#updatevendorsubmitid").click(function(){
		$("#updatevendorform").submit();
	});

	</script>

</body>
</html>