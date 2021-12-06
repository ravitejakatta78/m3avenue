<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_adminid(); 
if(empty($userid)){

	header("Location: index.php");

}

$message = '';
if(!empty($_POST['insertkit'])){
	
	   $pagerarray  = array();

		if (!file_exists('../boosterkitimages/')) {	
		mkdir('../boosterkitimages/', 0777, true);	
		}
	    $target_dir = '../boosterkitimages/';									

		$file = $_FILES["kitdoc"]['name'];				
		$target_file = $target_dir . strtolower($file);		

		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);								

		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "pdf" )

			{
		$message .= "Sorry, only JPG, JPEG, PNG & GIF & PDF files are allowed.";		
		$uploadOk = 0;						
		}
	    if ($uploadOk == 0) {			
		$message .= "Sorry, your file was not uploaded.";		
		} else {
			if (move_uploaded_file($_FILES["kitdoc"]["tmp_name"], $target_file)){				
				$pagerarray['kit_doc_path'] = strtolower($file);						
				} else {
					$message .= "Sorry, There Was an Error Uploading Your File.";			
					}
				}
				$superadminsoptions=$_POST['superadminsoptions'];
				$superadminsoptionsArray=implode(",",$superadminsoptions);
				$pagerarray['emp_id']='0';
                $pagerarray['kit_name'] = mysqli_real_escape_string($conn,$_POST['kitname']);
                $pagerarray['super_admin_options'] = $superadminsoptionsArray;
                $pagerarray['hierarchy'] = mysqli_real_escape_string($conn,$_POST['hierarchy']);
                
				$result = insertQuery($pagerarray,'booster_kit');
				if(!$result){
					header("Location: boosterkit-list.php?success=success");
                    }
	    
		 	}

	




if(!empty($_POST['updatekit'])){


			if(!empty($_POST['kitname'])){
                $pagewererarray['ID'] = $_GET['editkit'];
				$superadminsoptions=$_POST['superadminsoptions'];
				$superadminsoptionsArray=implode(",",$superadminsoptions);
				$pagerarray['emp_id']='0';
                $pagerarray['kit_name'] = mysqli_real_escape_string($conn,$_POST['kitname']);
                $pagerarray['super_admin_options'] = $superadminsoptionsArray;
                $pagerarray['hierarchy'] = mysqli_real_escape_string($conn,$_POST['hierarchy']);
                
				$result = updateQuery($pagerarray,'booster_kit',$pagewererarray);;
				if(!$result){
					header("Location: boosterkit-list.php?success=success");
                    }

			}else{

		$message .=" Kit Name Field is Empty";

	}

}

if(!empty($_REQUEST['deletekit'])){
    	$sql = "DELETE FROM booster_kit WHERE ID=".$_REQUEST['deletekit']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: boosterkit-list.php?success=success");
   
} else {
    echo "Error deleting record: " . $conn->error;
}
}


$roles = roles();

$superadmins = runloopQuery("SELECT unique_id,concat(fname,' ',lname) leadername FROM employee where ID =  '".$userid."'");
//$managers = runloopQuery("SELECT unique_id,concat(fname,' ',lname) leadername FROM employee where role_id = 3 and leader = '".$user_unique_id."' order by ID desc");

$superadminsnames = runloopQuery("SELECT ID,fname FROM employee where role_id = 2  order by ID desc");

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
<style>
.select{
	width: 150px;
}
@media screen and (max-width: 480px) {
 .select{
	width: 120px;
}
}
</style>	
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
					<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

					<!-- begin:: Subheader -->

<br>
<!-- end:: Subheader -->

						<!-- begin:: Content -->
	<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
		    
                          
		    <div class="kt-portlet kt-portlet--mobile"> 
			<?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								New Kit Added Succussfully
								</div>
								<?php } ?>
								
								<?php if(!empty($message)){?>
								<div class="alert alert-danger">
								<?=$message?>
								</div>
								<?php } ?>

							<div class="row mx-3" style="display:flex;align-items: center;min-height:60px;">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Kit list</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    	<button type="button" class="btn btn-primary" style="float:right" data-toggle="modal" data-target="#newModal">Add Kit</button>    
					
                    </div>
                </div>

    
		<div class="kt-portlet__body">
		<div class="table table-responsive">

						<!--begin: Datatable -->
			<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>S.No</th>
                            <th>Kit Name</th> 
                            <th>Super Admins</th>
                            <th>Hirerachy</th>
                            <th>Reg Date</th>
                            <th>Action</th>
							
						</tr>
						
							</thead>
								<tbody>
								  <?php

$sql = runloopQuery("SELECT * FROM booster_kit   order by ID desc");

   $x=1;  foreach($sql as $row)
		{
		
 $sqlnamearray = runloopQuery("select concat(fname, ' ',lname ) names from employee where ID in ('".str_replace(",","','",$row['super_admin_options'])."')"); 
$superadmin_names = implode(',',array_column($sqlnamearray,'names'));
?>
<tr>
<td><?php echo  $x;?></td>
<td><?php echo $row["kit_name"];?></td>
<td><?= $superadmin_names; ?></td>
<td><?php if($row['hierarchy'] == '3') {
echo 'Manager';
}else if($row['hierarchy'] == '4'){
    echo 'Executive';
}
else{
    echo 'Both';
}
?></td> 

<td><?php echo reg_date($row["reg_date"]);?></td> 
<td>
	
<a href="?editkit=<?php echo $row["ID"];?>">Edit |</a>
<a href="?deletekit=<?php echo $row["ID"];?>">Delete</a>
</td>
</tr>
<?php
     $x++; }
?>
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
		<div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Booster Kit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <form  method="post" action="" enctype="multipart/form-data" id="addkitform" autocomplete="off" >
						<div class="form-group col-12 row">
							<div class="form-group  col-12 col-md-6 col-lg-6 row">
							    <div class="col-6">	
							<label for="example-text-input" class="col-md-6 col-6 col-form-label">Enter Kit Name</label>
						</div>
                       <div class="col-6">
                            <input class="form-control" type="text" name="kitname" placeholder="Enter kit name" id="kitname" required>
                            <input class="form-control" type="hidden" name="insertkit"  id="insertkit" value="1">
                            
                        </div>
                    </div>
                    		<div class="form-group  col-12 col-md-6 col-lg-6 row">
							    <div class="col-6">	
                        <label for="example-text-input" class="col-md-6 col-6 col-form-label">Upload Document</label>
                    </div>
                        <div class="col-6">
							<div class="custom-file">
                                <input type="file" class="custom-file-input" name="kitdoc" id="kitdoc">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
						</div>
						</div>
                    </div>
						<div class="form-group  col-12 row">
						 <div class="form-group col-lg-6 col-md-6 col-12 row">
                        <label for="example-text-input" class="col-md-6 col-6 col-form-label">Super Admin</label>
                        <div class="col-md-6 col-6">

                            <select class="form-control select2 select" name="superadminsoptions[]" id="superadminsoptions " multiple required>
								<option value="">Select</option>
								<?php foreach($superadminsnames as $key => $value){?> 
									<option value="<?= $value['ID']; ?>"><?= $value['fname']; ?></option> 
								<?php }  ?>
							</select>
							
                        </div>
                    </div>
                      	<div class="form-group col-12 col-lg-6 col-md-6  row">
						<label for="example-text-input" class="col-md-6 col-6 col-form-label">Hierarchy</label>
                        <div class="col-md-6 col-6">
                            <select class="form-control " name="hierarchy" id="hierarchy" required>
								<option value="">Select</option>
								<?php foreach($roles as $roleid => $rolename) { if ($roleid != '1' &&  $roleid != '2' &&  $roleid != '0') {?> 
									<option value="<?= $roleid; ?>"><?= $rolename; ?></option> 
								<?php } } ?>
								<option value="5">Both</option>
							</select>
                        </div>
						</div>
					</div>
					</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addkitsubmitid">Add Kit</button>
      </div>
    </div>
  </div>
</div> 
		


		
	<?php include("footerscripts.php");?>
	
<!-- edit modal -->
<?php if(!empty($_GET['editkit'])){ 
$sql = runQuery("SELECT * FROM booster_kit where ID = '".$_GET['editkit']."' order by ID desc");

	?>
		<!-- Modal -->
<div id="myModaleditkit" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Kit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
<form  method="post" action="" id="editkitformid">	
							<div class="form-group row">
							<label for="example-text-input" class="col-2 col-form-label"> Kit Name</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="kitname" placeholder="Enter kit name" id="kitname" value="<?php echo $sql['kit_name'];?>" required>
                        </div>
                        
						
                    </div>
						<div class="form-group row">
						    
                        <label for="example-text-input" class="col-md-2 col-form-label">Super Admin</label>
                        <div class="col-md-4">

                            <select class="form-control select2" name="superadminsoptions[]" id="superadminsoptions" multiple required>
								<option value="">Select</option>
								<?php $selectedOptionsArray=explode(",",$sql['super_admin_options']);foreach($superadminsnames as $key => $value){?> 
									<option value="<?= $value['ID']; ?>" <?php if(in_array($value['ID'], $selectedOptionsArray)){ echo 'selected';}?> ><?= $value['fname']; ?></option> 
								<?php }  ?>
							</select>
							
                        </div>
						<label for="example-text-input" class="col-2 col-form-label">Hierarchy</label>
                        <div class="col-4">
                            <select class="form-control select2" name="hierarchy" id="hierarchy" required>
								<option value="">Select</option>
								<?php foreach($roles as $roleid => $rolename) { if ($roleid != '1' &&  $roleid != '2' &&  $roleid != '0') {?> 
									<option value="<?= $roleid; ?>" <?php if($roleid==$sql['hierarchy']){ echo 'selected';}?>><?= $rolename; ?></option> 
								<?php } } ?>
								<option value="5">Both</option>
							</select>
                        </div>
						</div>
					<input type="hidden" name="updatekit" value="1">
                                </form>      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success" id="updatekit"  value="submit">Update Kit</button>

      </div>
    </div>

  </div>
</div>




	<script>
			$("#myModaleditkit").modal('show');
		</script>
	<?php }?>

<!-- end of edit modal -->

 
	<script>


	$("#addkitsubmitid").click(function(){
		var user_input_value;
		var err_value = 0
		$('#addkitform').find('input,select,select2').each(function(){
			if($(this).prop('required')){
				user_input_value  = $("#"+this.id).val();
				if(user_input_value == ''){
					if(err_value == 0){
						document.getElementById(this.id).focus();
					}
					err_value = err_value + 1;
					$("#"+this.id).css('border-color', 'red');
				}else{
					$("#"+this.id).css('border-color', '#e4e7ea');
				}
			}	 
		});
		$('#addkitform').find('input[type=file]').each(function(){
			
			if($('#'+this.id)[0].files.length === 0){
				err_value = err_value + 1;
				alert("Please Upload File");
				//$("#"+this.id).css('border','1px solid red','padding','2px');
			}
			else{
				var fileInput = document.getElementById(this.id);
				var filePath = fileInput.value;
				// Allowing file type
				var allowedExtensions = 
						/(\.jpg|\.jpeg|\.png|\.gif|\.pdf)$/i;
				if (!allowedExtensions.exec(filePath)) {
					err_value = err_value + 1;
					alert('Invalid file type');
					$("#"+this.id).css('border','1px solid red','padding','2px');
					fileInput.value = '';
					
				}
				else{
					$("#"+this.id).css('border','1px solid black','padding','2px');
				}

			}
		});	
		if(err_value == 0)
		{
			$("#addkitsubmitid").hide();
			$("#addkitform").submit();	
		}
		
	});

	$("#updatekit").click(function(){
   $("#editkitformid").submit(); 
});
	</script>
	</body>

	<!-- end::Body -->
</html>