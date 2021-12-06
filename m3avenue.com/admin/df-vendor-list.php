<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_adminid(); 

if(empty($userid)){

	header("Location: index.php");

}

$message = '';
if(!empty($_POST['vendorsubmit'])){
	$mobile_number = mysqli_real_escape_string($conn,$_POST['mobile']);
		$prev_mobile_det = runQuery("select * from df_vendor where mobile = '".$mobile_number."'");
		if(!empty($prev_mobile_det))
		{
			$message .= "Mobile Number Already Taken.";
		}
		else{

	   $pagerarray  = array();

	 
	   if (!file_exists('vendorimage')) {	
		mkdir('vendorimage', 0777, true);	
		}	
		$target_dir = 'vendorimage/';
		$uploaddocarray = ['pan'=>'pan_doc','adhar'=>'adhar_doc','cert'=>'certificate','selfie'=>'selfie'];
		$uploadOk = 1;	
		foreach($uploaddocarray as $key => $value)
			{
				$file = $_FILES[$value]['name'];
				$extn = pathinfo($file, PATHINFO_EXTENSION);
				$file = strtolower(base_convert(time(), 10, 36) . '_'.$key.'_' . md5(microtime())).'.'.$extn;				
				$target_file = $target_dir . strtolower($file);	
				
				$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);		
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" )
				{
					$message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
					$uploadOk = 0;						
				}
				if ($uploadOk == 0) {			
					$message .= "Sorry, your file was not uploaded.";		
					break;
					} else {
						if (move_uploaded_file($_FILES[$value]["tmp_name"], $target_file)){				
							$_POST[$value] = strtolower($file);						
							} else {
								$message .= "Sorry, There Was an Error Uploading Your File.";			
								break;
							}
					}
			}
						
				$uniqueusers = (int)runQuery("select max(ID) as id from df_vendor order by ID desc")['id'];
				$newuniquid = $uniqueusers+1;
				$_POST['vendor_unique_id'] = 'VEND'.sprintf('%05d',$newuniquid);
				unset($_POST['vendorsubmit']);
				$result = insertQuery($_POST,'df_vendor');
				if(!$result){
					header("Location: df-vendor-list.php?success=success");
                    }
	    
		 	}

	}

	if(!empty($_POST['vendorupdate'])){
		$mobile_number = mysqli_real_escape_string($conn,$_POST['mobile']);
			$prev_mobile_det = runQuery("select * from df_vendor where mobile = '".$mobile_number."' 
			and ID != '".$_POST['vendor_update_id']."'");

			$updateprevdet = runQuery("select * from df_vendor where ID = '".$_POST['vendor_update_id']."'");

			if(!empty($prev_mobile_det))
			{
				$message .= "Mobile Number Already Taken.";
			}
			else{
	
		   $pagerarray  = array();
	
		 
		   if (!file_exists('vendorimage')) {	
			mkdir('vendorimage', 0777, true);	
			}	
			$target_dir = 'vendorimage/';
			$uploaddocarray = ['pan'=>'pan_doc','adhar'=>'adhar_doc','cert'=>'certificate','selfie'=>'selfie'];
			$uploadOk = 1;	
			foreach($uploaddocarray as $key => $value)
				{
					$file = @$_FILES[$value]['name'];
					if(@$file){
					$extn = pathinfo($file, PATHINFO_EXTENSION);
					$file = strtolower(base_convert(time(), 10, 36) . '_'.$key.'_' . md5(microtime())).'.'.$extn;				
					$target_file = $target_dir . strtolower($file);	
					
					$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);		
					if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" )
					{
						$message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
						$uploadOk = 0;						
					}
					if ($uploadOk == 0) {			
						$message .= "Sorry, your file was not uploaded.";		
						break;
					}else{
						if (move_uploaded_file($_FILES[$value]["tmp_name"], $target_file)){				
								$_POST[$value] = strtolower($file);		
								if(!empty($updateprevdet[$value])){
									unlink($target_dir.$updateprevdet[$value]);
								}				
							}
						}
				}	 
						
				}
							
					$trid['ID'] =mysqli_real_escape_string($conn,$_POST['vendor_update_id']); 
	                unset($_POST['vendorupdate'],$_POST['vendor_update_id']);
                    $result = updateQuery($_POST,'df_vendor',$trid);
					if(!$result){
						header("Location: df-vendor-list.php?success=success");
					}
			
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
					<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

					<!-- begin:: Subheader -->

<br>
<!-- end:: Subheader -->

						<!-- begin:: Content -->
	<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
		    <div class="kt-portlet kt-portlet--mobile"> 
			<div class="row mx-3" style="display:flex;align-items: center;min-height:60px;">
				<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
					<h4 class="page-title">Vendor list</h4> 
				</div>
				<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
					<button type="button" class="btn btn-primary" style="float:right" data-toggle="modal" data-target="#newModal">Add Vendor</button>    
				</div>
			</div>    
			<?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Vendor Added Succussfully
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
		<div class="kt-portlet__body">
		<div class="table table-responsive">

						<!--begin: Datatable -->
			<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
					<tr>
								<th>S.No</th>
								<th>Vendor Unique Id</th>
								<th>Vendor Name</th> 
								<th>Mobile</th>
								<th>Alternative Mobile</th>
								<th>Reference Name</th>
								<th>Reference Mobile</th>
								<th>Documets</th>
								<th>Action</th>
							</tr>
						
							</thead>
								<tbody>

								<?php
							$sql = runloopQuery("SELECT * FROM df_vendor   order by ID desc");
							$x=1;  foreach($sql as $row){
							?>
							<tr>
							<td><?= $x; ?></td>
							<td><?php echo $row["vendor_unique_id"];?></td>
							<td><?php echo $row["vendor_name"];?> </td>
							<td><?php echo $row["mobile"];?></td>
							<td><?php echo $row["alt_mobile"];?></td>
							<td><?php echo $row["reference_name"];?></td>
							<td><?php echo $row["reference_mobile"];?></td>
							<td></td>
							<td><a  style="cursor: pointer;" onclick="editvendor('<?= $row['ID']; ?>')">Edit</a></td>
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
        <h5 class="modal-title" id="exampleModalLabel">Add Vendor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <form  method="post" action="" enctype="multipart/form-data" id="addvendorform" autocomplete="off" >
						<div class="form-group row">
						    
                        <label for="example-text-input" class="col-md-2 col-form-label">Vendor Name</label>
                        <div class="col-md-4">
							<input class="form-control" name="vendor_name" type="text" placeholder="Enter Vendor Name" id="vendor_name" required>
							<input class="form-control" name="vendorsubmit" type="hidden"  id="vendorsubmit" value="1" >

						</div>


                        <label for="example-text-input"  class="col-md-2 col-form-label" >Business Type</label>
                        <div class="col-md-4"  >
						<input class="form-control" name="business_type" type="text" placeholder="Enter Business Type" id="business_type" required>
                        </div>

						</div>
						<div class="form-group row">
						    
                        <label for="example-text-input" class="col-md-2 col-form-label">Enter Mobile</label>
                        <div class="col-md-4">
                            <input class="form-control" name="mobile" type="text" placeholder="Enter Mobile Number" id="mobile" required>
                        </div>
						<label for="example-text-input" class="col-2 col-form-label">Enter ALternatvie Mobile</label>
                        <div class="col-4">
                            <input class="form-control" name="alt_mobile" type="text" placeholder="Enter Alt Mobile" id="alt_mobile" required>
                        </div>
						</div>
						
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Enter Reference Name</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="reference_name" placeholder="Enter Reference Name" id="reference_name" required>
                        </div>
						<label for="example-text-input" class="col-2 col-form-label">Enter Reference Mobile Number</label>
                        <div class="col-4">
                              <input type="text" class="form-control form-control-line"  onkeypress="return isNumberKey(event)" placeholder="Mobile No"  name="reference_mobile" id="reference_mobile" value=""  required maxlength="10" pattern="\d{10}"  >
                        </div>
                    </div>	
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Enter Location</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="vendor_location" placeholder="Enter Location" id="vendor_location" required>
                        </div>

                    </div>	
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Enter Bank Details</label>
                        <div class="col-3">
                            <input class="form-control" type="text" name="bank_name" placeholder="Enter Bank Name" id="bank_name" required>
                        </div>
                        <div class="col-3">
                            <input class="form-control" type="text" name="bank_account" placeholder="Enter Account Number" id="bank_account" required>
                        </div>
						<div class="col-3">
                            <input class="form-control" type="text" name="IFSC" placeholder="Enter IFSC Code" id="IFSC" required>
                        </div>
                    </div>
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">ABB</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="abb" placeholder="Enter Abg Bank Balance" id="abb" required>
          	              </div>
                        <label  for="example-text-input" class="col-2 col-form-label">PAN Number</label> 
                        	<div class="col-4">
										<input type="text" name="pan_number" id="pan_number" placeholder="Enter PAN Number"  class="form-control" required>
							</div> 
	
                    </div>
					
					
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Adhar Number</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="adhar_number" id="adhar_number" placeholder="Enter Adhar Number"  required>
                        </div>

						<label for="example-text-input" class="col-2 col-form-label">UPI Id</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="upi_id" id="upi_id" placeholder="Enter UPI Id"  required>
                        </div>
                        </div>
					
					
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Upload PAN Card</label>
                        <div class="col-4">
							<div class="custom-file">
                                <input type="file" class="custom-file-input" name="pan_doc" id="pan_doc">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
						</div>
						
						<label for="example-text-input" class="col-2 col-form-label">Upload ADHAAR Card</label>
                        <div class="col-4">
							<div class="custom-file">
                                <input type="file" class="custom-file-input" name="adhar_doc" id="adhar_doc">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
						</div>
                    </div>

					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Upload Certificate</label>
                        <div class="col-4">
							<div class="custom-file">
                                <input type="file" class="custom-file-input" name="certificate" id="certificate">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
						</div>
						
						<label for="example-text-input" class="col-2 col-form-label">Upload Selfie</label>
                        <div class="col-4">
							<div class="custom-file">
                                <input type="file" class="custom-file-input" name="selfie" id="selfie">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
						</div>
                    </div>
					
 
					</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addvendorsubmitid">Add Vendor</button>
      </div>
    </div>
  </div>
</div> 


<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateModalLabel">Update Vendor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <form  method="post" action="" enctype="multipart/form-data" id="updatevendorform" autocomplete="off" >
						<div class="form-group row">
						    
                        <label for="example-text-input" class="col-md-2 col-form-label">Vendor Name</label>
                        <div class="col-md-4">
							<input class="form-control" name="vendor_name" type="text" placeholder="Enter Vendor Name" id="update_vendor_name" required>
							<input class="form-control" name="vendor_update_id" type="hidden" placeholder="Enter Vendor Name" id="vendor_update_id" >
							<input class="form-control" name="vendorupdate" type="hidden"  id="vendorupdate" value="1">

						</div>


                        <label for="example-text-input"  class="col-md-2 col-form-label" >Business Type</label>
                        <div class="col-md-4"  >
						<input class="form-control" name="business_type" type="text" placeholder="Enter Vendor Name" id="update_business_type" required>
                        </div>

						</div>
						<div class="form-group row">
						    
                        <label for="example-text-input" class="col-md-2 col-form-label">Enter Mobile</label>
                        <div class="col-md-4">
                            <input class="form-control" name="mobile" type="text" placeholder="Enter Mobile Number" id="update_mobile" required>
                        </div>
						<label for="example-text-input" class="col-2 col-form-label">Enter ALternatvie Mobile</label>
                        <div class="col-4">
                            <input class="form-control" name="alt_mobile" type="text" placeholder="Enter Alt Mobile" id="update_alt_mobile" required>
                        </div>
						</div>
						
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Enter Reference Name</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="reference_name" placeholder="Enter employee email" id="update_reference_name" required>
                        </div>
						<label for="example-text-input" class="col-2 col-form-label">Enter Reference Mobile Number</label>
                        <div class="col-4">
                              <input type="text" class="form-control form-control-line"  onkeypress="return isNumberKey(event)" placeholder="Mobile No"  name="reference_mobile" id="update_reference_mobile" value=""  required maxlength="10" pattern="\d{10}"  >
                        </div>
                    </div>
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Enter Location</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="vendor_location" placeholder="Enter Location" id="update_vendor_location" required>
                        </div>

                    </div>		
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Enter Bank Details</label>
                        <div class="col-3">
                            <input class="form-control" type="text" name="bank_name" placeholder="Enter Bank Name" id="update_bank_name" required>
                        </div>
                        <div class="col-3">
                            <input class="form-control" type="text" name="bank_account" placeholder="Enter Account Number" id="update_bank_account" required>
                        </div>
						<div class="col-3">
                            <input class="form-control" type="text" name="IFSC" placeholder="Enter IFSC Code" id="update_IFSC" required>
                        </div>
                    </div>
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">ABB</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="abb" placeholder="Enter Abg Bank Balance" id="update_abb" required>
          	              </div>
                        <label  for="example-text-input" class="col-2 col-form-label">PAN Number</label> 
                        	<div class="col-4">
										<input type="text" name="pan_number" id="update_pan_number" placeholder="Enter PAN Number"  class="form-control" required>
							</div> 
	
                    </div>
					
					
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Adhar Number</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="adhar_number" id="update_adhar_number" placeholder="Enter Adhar Number"  required>
                        </div>

						<label for="example-text-input" class="col-2 col-form-label">UPI Id</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="upi_id" id="update_upi_id" placeholder="Enter UPI Id"  required>
                        </div>
                        </div>
					
					
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Upload PAN Card</label>
                        <div class="col-4">
							<div class="custom-file">
                                <input type="file" class="custom-file-input" name="pan_doc" id="update_pan_doc">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
						</div>
						
						<label for="example-text-input" class="col-2 col-form-label">Upload ADHAAR Card</label>
                        <div class="col-4">
							<div class="custom-file">
                                <input type="file" class="custom-file-input" name="adhar_doc" id="update_adhar_doc">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
						</div>
                    </div>

					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Upload Certificate</label>
                        <div class="col-4">
							<div class="custom-file">
                                <input type="file" class="custom-file-input" name="certificate" id="update_certificate">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
						</div>
						
						<label for="example-text-input" class="col-2 col-form-label">Upload Selfie</label>
                        <div class="col-4">
							<div class="custom-file">
                                <input type="file" class="custom-file-input" name="selfie" id="update_selfie">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
						</div>
                    </div>
					
 
					</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary"  id="addvendorupdateid">Update Vendor</button>
      </div>
    </div>
  </div>
</div> 
		
	<?php include("footerscripts.php");?>
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
				}else{
					$("#"+this.id).css('border-color', '#e4e7ea');
				}
			}	 
		});
		$('#addvendorform').find('input[type=file]').each(function(){
			
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
						/(\.jpg|\.jpeg|\.png|\.gif)$/i;
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
			$("#addvendorsubmitid").hide();
			$("#addvendorform").submit();	
		}
		
	});
	$("#addvendorupdateid").click(function(){
		var user_input_value;
		var err_value = 0
		$('#updatevendorform').find('input,select,textarea').each(function(){
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
	
		if(err_value == 0)
		{
			$("#addvendorupdateid").hide();
			$("#updatevendorform").submit();	
		}
		
	});
	function changedialerstatus(name,id){
	    
	        $.ajax({
            type: 'post',
            url: 'ajax/ajax.php',
            data: {
                conditionname: name,
                tableid: id
            },
            success: function(response) { 
			}
        });
     
	}
	function editvendor(id)
	{
		$.ajax({
            type: 'post',
            url: 'ajax/commonajax.php',
            data: {
                action: 'updatevendorpopup',
                id: id
            },
            success: function(response) { 
				var result = JSON.parse(response);

				$("#updateModal").modal();
				$("#update_vendor_name").val(result['vendor_name']);
$("#update_business_type").val(result['business_type']);
$("#update_mobile").val(result['mobile']);
$("#update_alt_mobile").val(result['alt_mobile']);
$("#update_reference_name").val(result['reference_name']);
$("#update_reference_mobile").val(result['reference_mobile']);
$("#update_bank_name").val(result['bank_name']);
$("#update_bank_account").val(result['bank_account']);
$("#update_IFSC").val(result['IFSC']);
$("#update_abb").val(result['abb']);
$("#update_pan_number").val(result['pan_number']);
$("#update_adhar_number").val(result['adhar_number']);
$("#update_upi_id").val(result['upi_id']);
$("#vendor_update_id").val(result['ID']);
$("#update_vendor_location").val(result['vendor_location']);

			}
        });
	}
	</script>
	</body>

	<!-- end::Body -->
</html>