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
	$pagerarray['first_name'] = mysqli_real_escape_string($conn,$_POST['first_name']);
	$pagerarray['last_name'] = mysqli_real_escape_string($conn,$_POST['last_name']);
	$pagerarray['entity_id'] = mysqli_real_escape_string($conn,$_POST['business_entity']);
	$pagerarray['designation'] = mysqli_real_escape_string($conn,$_POST['designation']);
	$pagerarray['reporting_manager'] = mysqli_real_escape_string($conn,$_POST['reporting_manager']);
	$pagerarray['reporting_mobile_number'] = mysqli_real_escape_string($conn,$_POST['reporting_mobile_number']);
	$pagerarray['reporting_designation'] = mysqli_real_escape_string($conn,$_POST['reporting_designation']);
	$pagerarray['location'] = mysqli_real_escape_string($conn,$_POST['location']);
	$pagerarray['whatsapp_number'] = mysqli_real_escape_string($conn,$_POST['whatsapp_number']);
	$pagerarray['mobile_number'] = mysqli_real_escape_string($conn,$_POST['mobile_number']);
	$pagerarray['alt_mobile_number'] = mysqli_real_escape_string($conn,$_POST['alt_mobile_number']);
	$pagerarray['email'] = mysqli_real_escape_string($conn,$_POST['email']);
	$pagerarray['emp_code'] = mysqli_real_escape_string($conn,$_POST['emp_code']);
	$pagerarray['preferral_work_type'] = mysqli_real_escape_string($conn,$_POST['preferral_work_type']);
	$pagewherearray['ID'] = $_POST['entity_id'];
	updateQuery($pagerarray,'entity_staff',$pagewherearray);
	if(!empty($_POST['pincode'])){
		$pincodearray = [];
		$pincodes = $_POST['pincode'];
		$locations = $_POST['locationupdate'];

		for($p=0;$p<count($pincodes);$p++){
				$pincodewhere['ID'] = $_POST['pincodeid'][$p];
				$pincodearray['pincode'] = $_POST['pincode'][$p];
				$pincodearray['location_id'] =  $locations[$p];
				updateQuery($pincodearray,'entity_staff_pincode',$pincodewhere);
			
		}

	}

}
else{
	$message .=" First Name Field is Empty";
}	

if(!empty($_POST['newpincode'])){
	$pincodearray = [];
	$pincodes = $_POST['newpincode'];
	$locations = $_POST['location'];
	for($p=0;$p<count($pincodes);$p++){
		$pincodearray['staff_id'] =  $_GET['staff_id'];
		$pincodearray['pincode'] =  $pincodes[$p];
		$pincodearray['location_id'] =  $locations[$p];
		$res = insertQuery($pincodearray,'entity_staff_pincode');
		
	}

}

$staff = runQuery("select * from entity_staff where id='".$_GET['staff_id']."'");

$staff_pincodes = runloopQuery("select * from entity_staff_pincode where staff_id='".$_GET['staff_id']."'");
$entities = runloopQuery("select * from entity");
$entity_location = runloopQuery("select * from entity_location");
$el_arr = [];
for($el = 0;$el < count($entity_location);$el++)
{
	$el_arr[$entity_location[$el]['entity_id']][] = $entity_location[$el]['ID'].'-'.$entity_location[$el]['location']; 
}
$locationname_id = $el_arr[$staff['entity_id']];
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

		<!--begin::Fonts -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
			WebFont.load({
				google: {
					"families": ["Poppins:300,400,500,600,700"]
				},
				active: function() {
					sessionStorage.fonts = true;
				}
			});
		</script>

		<!--end::Fonts -->

		<!--begin::Page Vendors Styles(used by this page) -->
		<link href="./assets/vendors/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />

		<!--end::Page Vendors Styles -->

		<!--begin:: Global Mandatory Vendors -->
		<link href="./assets/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" type="text/css" />

		<!--end:: Global Mandatory Vendors -->

		<!--begin:: Global Optional Vendors -->
		<link href="./assets/vendors/general/tether/dist/css/tether.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/nouislider/distribute/nouislider.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/owl.carousel/dist/assets/owl.carousel.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/owl.carousel/dist/assets/owl.theme.default.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/dropzone/dist/dropzone.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/summernote/dist/summernote.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/bootstrap-markdown/css/bootstrap-markdown.min.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/animate.css/animate.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/morris.js/morris.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/sweetalert2/dist/sweetalert2.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/socicon/css/socicon.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/custom/vendors/line-awesome/css/line-awesome.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/custom/vendors/flaticon/flaticon.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/custom/vendors/flaticon2/flaticon.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />

		<!--end:: Global Optional Vendors -->

		<!--begin::Global Theme Styles(used by all pages) -->
		<link href="./assets/css/demo1/style.bundle.css" rel="stylesheet" type="text/css" />

		<!--end::Global Theme Styles -->

		<!--begin::Layout Skins(used by all pages) -->
		<link href="./assets/css/demo1/skins/header/base/light.css" rel="stylesheet" type="text/css" />
		<link href="./assets/css/demo1/skins/header/menu/light.css" rel="stylesheet" type="text/css" />
		<link href="./assets/css/demo1/skins/brand/navy.css" rel="stylesheet" type="text/css" />
		<link href="./assets/css/demo1/skins/aside/navy.css" rel="stylesheet" type="text/css" />

		<!--end::Layout Skins -->
		<link rel="shortcut icon" href="./assets/media/logos/favicon.ico" />
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
			<div class="alert alert-light alert-elevate" role="alert">
			    <div class="kt-portlet kt-portlet--mobile">
			        
                    <div class="kt-portlet__head">
				    	<div class="kt-portlet__head-label">
					    	<h3 class="kt-portlet__head-title">Edit Staff</h3>
					    </div>
			    	</div>
                    <div class="kt-portlet__body">
						   <?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Staff list Updated Succussfully
								</div>
								<?php } ?>

								<form method="post" action="" enctype="multipart/form-data" id="addentityform" autocomplete="off">
					<div class="row">
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">First Name</label>
							<input class="form-control" name="first_name" type="text" placeholder="Enter First Name" id="first_name" value="<?= $staff['first_name']; ?>" required>
							<input class="form-control" name="entity_id" type="hidden"  id="entity_id" value="<?= $staff['ID']; ?>" required>

						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Last Name</label>
							<input class="form-control" name="last_name" type="text" placeholder="Enter Last Name" id="last_name" value="<?= $staff['last_name']; ?>" >
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Business Entity</label>
							<select class="form-control" name="business_entity" id="business_entity" onchange="changelocation(this.value)" required>
								<option value="">Select</option>
								<?php for($i=0;$i<count($entities);$i++){ ?>
								<option value="<?= $entities[$i]['ID']; ?>" <?php if($entities[$i]['ID'] == $staff['entity_id']){ echo 'selected'; }  ?>><?= $entities[$i]['entity_name']; ?></opiton>
								<?php } ?>
							</select>
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Designation</label>
							<input type="text" class="form-control" name="designation" id="designation" value="<?= $staff['designation']; ?>" placeholder="Enter Designation" >
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Reporting Manager</label>
							<input type="text" class="form-control" name="reporting_manager" id="reporting_manager" value="<?= $staff['reporting_manager']; ?>" placeholder="Enter Reporting Manager" required>
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Reporting Manager Mobile Number</label>
							<input type="text" class="form-control" name="reporting_mobile_number" id="reporting_mobile_number" value="<?= $staff['reporting_mobile_number']; ?>" placeholder="Enter Reporting Manager Mobile Number" required>
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Reporting Manager Designation</label>
							<input type="text" class="form-control" name="reporting_designation" id="reporting_designation" value="<?= $staff['reporting_designation']; ?>" placeholder="Enter Reporting Manager Mobile Number" required>
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Mobile Number</label>
							<input type="text" class="form-control" name="mobile_number" id="mobile_number" value="<?= $staff['mobile_number']; ?>" placeholder="Enter Mobile Number" required>
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Alternative Mobile Number</label>
							<input type="text" class="form-control" name="alt_mobile_number" id="alt_mobile_number" value="<?= $staff['alt_mobile_number']; ?>" placeholder="Enter Alternative Mobile Number" >
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Whatsapp Number</label>
							<input type="text" class="form-control" name="whatsapp_number" id="whatsapp_number" value="<?= $staff['whatsapp_number']; ?>" placeholder="Enter Whatsapp Number" >
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Location</label>
							<input type="text" class="form-control" name="location" id="location" value="<?= $staff['location']; ?>" placeholder="Enter Whatsapp Number" required>
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Email</label>
							<input type="text" class="form-control" name="email" id="email" placeholder="Enter Email" value="<?= $staff['email']; ?>" required>
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Emp Code</label>
							<input type="text" class="form-control" name="emp_code" id="emp_code" value="<?= $staff['emp_code']; ?>" placeholder="Enter Emp Code" required>
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Preferral Work Type</label>
							<select type="text" class="form-control" name="preferral_work_type" id="preferral_work_type" required>
								<option value="">Select</option>
								<option value="physical" <?php if($staff['preferral_work_type'] == 'physical'){ echo 'selected'; }  ?>>Physical</option>
								<option value="whatsapp" <?php if($staff['preferral_work_type'] == 'whatsapp'){ echo 'selected'; }  ?>>Whatsapp</option>
								<option value="mail" <?php if($staff['preferral_work_type'] == 'mail'){ echo 'selected'; }  ?>>Mail</option>
								<option value="mail" <?php if($staff['preferral_work_type'] == 'portal'){ echo 'selected'; }  ?>>Portal</option>

							</select>
						</div>
						<!-- <div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Profile Pic</label>
							<input type="file" class="form-control" name="profile_pic" id="profile_pic" required>
						</div> -->						
					</div>
					
					<table  class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Location</th>
							<th>Pin Code</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						for($p=0;$p<count($staff_pincodes);$p++) {
					
							?>
						<tr>
							<td>
								<select name="locationupdate[]" class="form-control">
									<option value="">Select</option>
									<?php 
									
										for($l=0;$l<count($locationname_id);$l++) {
										$loc = explode('-',$locationname_id[$l]);
										?>
										<option value="<?= $loc[0]; ?>" <?php if($staff_pincodes[$p]['location_id'] == $loc[0]) echo 'selected'; ?>><?= $loc[1]; ?></option>	
									<?php }?>
								</select>
							</td>
							<td>
							<input type="text" value="<?= $staff_pincodes[$p]['pincode']; ?>" name="pincode[]" id="alpincode" class="form-control">
							<input type="hidden" value="<?= $staff_pincodes[$p]['ID']; ?>" name="pincodeid[]" >

						</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				</form>
					
				<div class="col-3">
                             <button type="submit" name="submit" value="submit" id="addentitysubmitid" class="btn btn-brand btn-elevate btn-pill">Update Staff</button>
						</div>	
			</div>
			<form method="post" action="" id="pincodesubmit">
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
						<input type="text" name="newpincode[]" class="form-control">
						</td>
					</tr>
				</tbody>
			</table>
			</form>
			<div >
			<button id="btnAddRow" class="btn btn-success" type="button">Add Row</button>
				<button type="submit" name="submit" value="submit" id="addpincodesubmitid" class="btn btn-primary">Add Pincode</button>
			</div>
			</div>
			
		 
		
		
		</div>
				<!-- end:: Content -->
	</div>
</div>
	    
	    <!-- begin:: Footer -->
			    <div class="kt-footer kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop">
						<div class="kt-footer__copyright">
							2019&nbsp;&copy;&nbsp;<a href="#" target="_blank" class="kt-link">m3avenue</a>
						</div>
						<div class="kt-footer__menu">
							<a href="http://keenthemes.com/keen" target="_blank" class="kt-footer__menu-link kt-link">About</a>
							<a href="http://keenthemes.com/keen" target="_blank" class="kt-footer__menu-link kt-link">Team</a>
							<a href="http://keenthemes.com/keen" target="_blank" class="kt-footer__menu-link kt-link">Contact</a>
						</div>
				</div>
		<!-- end:: Footer -->

		<!-- begin:: Scrolltop -->
		<div id="kt_scrolltop" class="kt-scrolltop">
			<i class="la la-arrow-up"></i>
		</div>

		<!-- end:: Scrolltop -->
		<!-- begin::Global Config(global config for global JS sciprts) -->
		<script>
			var KTAppOptions = {
				"colors": {
					"state": {
						"brand": "#5d78ff",
						"metal": "#c4c5d6",
						"light": "#ffffff",
						"accent": "#00c5dc",
						"primary": "#5867dd",
						"success": "#34bfa3",
						"info": "#36a3f7",
						"warning": "#ffb822",
						"danger": "#fd3995",
						"focus": "#9816f4"
					},
					"base": {
						"label": [
							"#c5cbe3",
							"#a1a8c3",
							"#3d4465",
							"#3e4466"
						],
						"shape": [
							"#f0f3ff",
							"#d9dffa",
							"#afb4d4",
							"#646c9a"
						]
					}
				}
			};
		</script>

		<!-- end::Global Config -->

		<!--begin:: Global Mandatory Vendors -->
		<script src="./assets/vendors/general/jquery/dist/jquery.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/popper.js/dist/umd/popper.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/js-cookie/src/js.cookie.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/moment/min/moment.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/tooltip.js/dist/umd/tooltip.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/sticky-js/dist/sticky.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/wnumb/wNumb.js" type="text/javascript"></script>

		<!--end:: Global Mandatory Vendors -->

		<!--begin:: Global Optional Vendors -->
		<script src="./assets/vendors/general/jquery-form/dist/jquery.form.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/custom/js/vendors/bootstrap-datepicker.init.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/custom/js/vendors/bootstrap-timepicker.init.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/bootstrap-maxlength/src/bootstrap-maxlength.js" type="text/javascript"></script>
		<script src="./assets/vendors/custom/vendors/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/typeahead.js/dist/typeahead.bundle.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/handlebars/dist/handlebars.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/inputmask/dist/jquery.inputmask.bundle.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/inputmask/dist/inputmask/inputmask.date.extensions.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/inputmask/dist/inputmask/inputmask.numeric.extensions.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/nouislider/distribute/nouislider.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/owl.carousel/dist/owl.carousel.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/autosize/dist/autosize.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/clipboard/dist/clipboard.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/dropzone/dist/dropzone.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/summernote/dist/summernote.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/markdown/lib/markdown.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/bootstrap-markdown/js/bootstrap-markdown.js" type="text/javascript"></script>
		<script src="./assets/vendors/custom/js/vendors/bootstrap-markdown.init.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/jquery-validation/dist/jquery.validate.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/jquery-validation/dist/additional-methods.js" type="text/javascript"></script>
		<script src="./assets/vendors/custom/js/vendors/jquery-validation.init.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/raphael/raphael.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/morris.js/morris.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/chart.js/dist/Chart.bundle.js" type="text/javascript"></script>
		<script src="./assets/vendors/custom/vendors/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/custom/vendors/jquery-idletimer/idle-timer.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/waypoints/lib/jquery.waypoints.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/counterup/jquery.counterup.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/es6-promise-polyfill/promise.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/sweetalert2/dist/sweetalert2.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/custom/js/vendors/sweetalert2.init.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/jquery.repeater/src/lib.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/jquery.repeater/src/jquery.input.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/jquery.repeater/src/repeater.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/dompurify/dist/purify.js" type="text/javascript"></script>

		<!--end:: Global Optional Vendors -->

		<!--begin::Global Theme Bundle(used by all pages) -->
		<script src="./assets/js/demo1/scripts.bundle.js" type="text/javascript"></script>

		<!--end::Global Theme Bundle -->

		<!--begin::Page Vendors(used by this page) -->
		<script src="./assets/vendors/custom/fullcalendar/fullcalendar.bundle.js" type="text/javascript"></script>

		<!--end::Page Vendors -->

		<!--begin::Page Scripts(used by this page) -->
		<script src="./assets/js/demo1/pages/dashboard.js" type="text/javascript"></script>

		<!--end::Page Scripts -->
<script>
$( document ).ready(function() {
    changelocation('<?= $staff['entity_id']; ?>');
});

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
        	$('#addentityform').find('input[type=file]').each(function(){

        		if($('#'+this.id)[0].files.length === 0){
        			err_value = err_value + 1;
        			//alert("Please Upload File");
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


$("#addpincodesubmitid").click(function(){
	$("#pincodesubmit").submit();
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