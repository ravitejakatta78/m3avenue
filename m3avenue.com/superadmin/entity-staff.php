<?php

session_start();

error_reporting(E_ALL);


include('../functions.php');


$userid = current_adminid(); 

if(empty($userid)){
	header("Location: index.php");
}

$message = '';
if(!empty($_POST['first_name']))
{
	$pagerarray  = array();

	$file = $_FILES["profile_pic"]['name'];
	if(!empty($file)){
	if (!file_exists('../uploads/staff_profile_pic')) {	
		mkdir('../uploads/staff_profile_pic', 0777, true);	
	}
	$target_dir = '../uploads/staff_profile_pic/';
	
	
	$imageFileType = pathinfo($file,PATHINFO_EXTENSION);	
	$convertedfile = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$imageFileType;				
	$target_file = $target_dir . strtolower($convertedfile);		

	$uploadOk = 1;
	
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" )
	{
		$message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
		$uploadOk = 0;						
	}
	if ($uploadOk == 0) {			
		$message .= "Sorry, your file was not uploaded.";		
	} else {
		if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)){				
			$pagerarray['profile_pic'] = strtolower($convertedfile);						
		} else {
			$message .= "Sorry, There Was an Error Uploading Your File.";			
		}
	}
}

	$pagerarray['first_name'] = mysqli_real_escape_string($conn,$_POST['first_name']);
	$pagerarray['last_name'] = mysqli_real_escape_string($conn,$_POST['last_name']);
	$pagerarray['entity_id'] = mysqli_real_escape_string($conn,$_POST['business_entity']);
	$pagerarray['designation'] = mysqli_real_escape_string($conn,$_POST['designation']);
	$pagerarray['reporting_manager'] = mysqli_real_escape_string($conn,$_POST['reporting_manager']);
	$pagerarray['reporting_mobile_number'] = mysqli_real_escape_string($conn,$_POST['reporting_mobile_number']);
	$pagerarray['reporting_designation'] = mysqli_real_escape_string($conn,$_POST['reporting_designation']);

	$pagerarray['whatsapp_number'] = mysqli_real_escape_string($conn,$_POST['whatsapp_number']);
	$pagerarray['mobile_number'] = mysqli_real_escape_string($conn,$_POST['mobile_number']);
	$pagerarray['alt_mobile_number'] = mysqli_real_escape_string($conn,$_POST['alt_mobile_number']);

	
	$pagerarray['email'] = mysqli_real_escape_string($conn,$_POST['email']);
	$pagerarray['emp_code'] = mysqli_real_escape_string($conn,$_POST['emp_code']);
	$pagerarray['created_by'] = $userid;
	$pagerarray['preferral_work_type'] = mysqli_real_escape_string($conn,$_POST['preferral_work_type']);
	$result = insertIDQuery($pagerarray,'entity_staff');
	
	if(!empty($result)){
		if(!empty($_POST['pincode'])){
			$pincodearray = [];
			$pincodes = $_POST['pincode'];
			$locations = $_POST['location'];
			for($p=0;$p<count($pincodes);$p++){
				$pincodearray['staff_id'] =  $result;
				$pincodearray['pincode'] =  $pincodes[$p];
				$pincodearray['entity_id'] =  mysqli_real_escape_string($conn,$_POST['business_entity']);
				$pincodearray['location_id'] =  $locations[$p];
				$pincodearray['created_by'] = $userid;
				$res = insertQuery($pincodearray,'entity_staff_pincode');
				
			}

		}

		header("Location: entity-staff.php?success=success");
	}
}
else{
	$message .=" First Name Field is Empty";
}

$entities = runloopQuery("select * from entity where created_by = '".$userid."'");
$entity_staff = runloopQuery("select * from entity_staff where created_by = '".$userid."'");
$entity_location = runloopQuery("select * from entity_location where created_by = '".$userid."'");

$el_arr = [];
for($el = 0;$el < count($entity_location);$el++)
{
	
	$el_arr[$entity_location[$el]['entity_id']][] = $entity_location[$el]['ID'].'-'.$entity_location[$el]['location']; 
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
						<div class="kt-portlet__head row">
							<div class="kt-portlet__head-label col-lg-3 col-md-4 col-sm-4 col-xs-12">
								<h3 class="kt-portlet__head-title">Staff List</h3>
							</div>
							<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
								<!-- <a href="add_super_admin.php"><button type="button" class="btn  btn btn-primary  mt-2" style="float:right" data-toggle="modal" data-target="#exampleModalScrollable">Add Super Admin</button></a> -->   
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

								<button type="button" class="btn  btn btn-primary  mt-2" style="float:right" data-toggle="modal" data-target="#exampleModalScrollable">Add Staff</button>


							</div>
							<div class="kt-portlet kt-portlet--mobile">


								<div class="kt-portlet__body">
									<div class="kt-portlet kt-portlet--mobile"> 
										<div class="kt-portlet__head">
											<div class="kt-portlet__head-label">
									
											</div>
										</div>     
										<div class="kt-portlet__body">
										<?php if(!empty($_GET['success'])){?>
										<div class="alert alert-success">
											Staff Added Succussfully
										</div>
									<?php } ?>
											<div class="table table-responsive">

												<!--begin: Datatable -->
												<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
													<thead>
														<tr>
															<th>S.No</th>
															<th>First Name</th>
															<th>Last Name</th>
															<th>Designation</th>
															<th>Mobile Number</th>
															<th>Email</th>
															<th>Action</th>
														</tr>
													</thead>
													<tbody>
													<?php for($i=0;$i<count($entity_staff);$i++){ ?>
														<tr>
															<td><?= $i+1; ?></td>
															<td><?= $entity_staff[$i]['first_name']; ?></td>
															<td><?= $entity_staff[$i]['last_name']; ?></td>
															<td><?= $entity_staff[$i]['designation']; ?></td>
															<td><?= $entity_staff[$i]['mobile_number']; ?></td>
															<td><?= $entity_staff[$i]['email']; ?></td>
															<td><a href="edit-entity-staff.php?staff_id=<?= $entity_staff[$i]['ID']; ?>"> Edit</a></td>
														</tr>
													<?php } ?>
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

				<!-- Modal -->


				<!-- Modal -->
<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable  w-100 modal-lg"  role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalScrollableTitle">Add Staff</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="" enctype="multipart/form-data" id="addentityform" autocomplete="off">
					<div class="row">
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">First Name</label>
							<input class="form-control" name="first_name" type="text" placeholder="Enter First Name" id="first_name" required>
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Last Name</label>
							<input class="form-control" name="last_name" type="text" placeholder="Enter Last Name" id="last_name" >
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Business Entity</label>
							<select class="form-control" name="business_entity" id="business_entity" onchange="changelocation(this.value)" required>
								<option value="">Select</option>
								<?php for($i=0;$i<count($entities);$i++){ ?>
								<option value="<?= $entities[$i]['ID']; ?>"><?= $entities[$i]['entity_name']; ?></opiton>
								<?php } ?>
							</select>
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Designation</label>
							<input type="text" class="form-control" name="designation" id="designation" placeholder="Enter Designation" >
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Reporting Manager</label>
							<input type="text" class="form-control" name="reporting_manager" id="reporting_manager" placeholder="Enter Reporting Manager" >
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Reporting Manager Mobile Number</label>
							<input type="text" class="form-control" name="reporting_mobile_number" id="reporting_mobile_number" placeholder="Enter Reporting Manager Mobile Number" >
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Reporting Manager Designation</label>
							<input type="text" class="form-control" name="reporting_designation" id="reporting_designation" placeholder="Enter Reporting Manager Mobile Number" >
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Mobile Number</label>
							<input type="text" class="form-control" name="mobile_number" id="mobile_number" placeholder="Enter Mobile Number" required>
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Alternative Mobile Number</label>
							<input type="text" class="form-control" name="alt_mobile_number" id="alt_mobile_number" placeholder="Enter Alternative Mobile Number" >
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Whatsapp Number</label>
							<input type="text" class="form-control" name="whatsapp_number" id="whatsapp_number" placeholder="Enter Whatsapp Number" >
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Email</label>
							<input type="text" class="form-control" name="email" id="email" placeholder="Enter Email" required>
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Emp Code</label>
							<input type="text" class="form-control" name="emp_code" id="emp_code" placeholder="Enter Emp Code" required>
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Preferral Work Type</label>
							<select type="text" class="form-control" name="preferral_work_type" id="preferral_work_type" required>
								<option value="">Select</option>
								<option value="physical">Physical</option>
								<option value="whatsapp">Whatsapp</option>
								<option value="mail">Mail</option>
								<option value="portal">Portal</option>
							</select>
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Profile Pic</label>
							<input type="file" class="form-control" name="profile_pic" id="profile_pic" >
						</div>						
					</div>
					
					<table id="tblAddRow" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Location</th>
					    <th>Pin Code</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<tr>
					<td>
						<select  id="location"  name="location[]" class="form-control location">
								</select>
						</td>
					<td>
						<input type="text" name="pincode[]" class="form-control">
						</td>
					</tr>
				</tbody>
			</table>
				</form>
			</div>
			<div class="modal-footer">
			<button id="btnAddRow" class="btn btn-success" type="button">Add Row</button>
				<button type="submit" name="submit" value="submit" id="addentitysubmitid" class="btn btn-primary">Add Staff</button>
			</div>
		</div>
	</div>
</div>
<script>


$("#addentitysubmitid").click(function(){
        	var user_input_value;
        	var err_value = 0
        	$('#addentityform').find('input,select,select2').each(function(){
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
        		$("#addentitysubmitid").hide();
        		$("#addentityform").submit();	
        	}

        });

		$('#tblAddRow tbody tr')
    .find('td')
    //.append('<input type="button" value="Delete" class="del"/>')
    .parent() //traversing to 'tr' Element
    .append('<td><a href="#" class="delrow"><i class="fa fa-trash border-red text-red"></i></a></td>');


// Add row the table
$('#btnAddRow').on('click', function() {
    var lastRow = $('#tblAddRow tbody tr:last').html();
    //alert(lastRow);
    $('#tblAddRow tbody').append('<tr>' + lastRow + '</tr>');
    $('#tblAddRow tbody tr:last input').val('');
});
// Delete row on click in the table
$('#tblAddRow').on('click', 'tr a', function(e) {
    var lenRow = $('#tblAddRow tbody tr').length;
    e.preventDefault();
    if (lenRow == 1 || lenRow <= 1) {
        alert("Can't remove all row!");
    } else {
        $(this).parents('tr').remove();
    }
});


function changelocation(entity_id){
	var el_arr_json = '<?= json_encode($el_arr); ?>';
	var el_arr = JSON.parse(el_arr_json);
	if(typeof(el_arr[entity_id]) != "undefined" && el_arr[entity_id] !== null) {
    	var locations = el_arr[entity_id];
		
		
		$(".location").html('');
		$(".location").append('<option value="">Please Select</option>');
		for(var l=0;l<locations.length;l++){
			var loc = locations[l].split("-");
			$(".location").append('<option value="'+loc[0]+'">'+loc[1]+'</option>');
		}
	}
	else{
		$(".location").html('');
		$(".location").html('<option value="">Please Select</option>');
	}
	

}
</script>
</body>

<!-- end::Body -->
</html>