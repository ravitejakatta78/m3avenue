<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_adminid();
$user_unique_id = employee_details($userid,'unique_id');
$tree = employeehirerachy($user_unique_id);
$empString = !empty($tree) ? implode("','",array_column($tree,'ID')) : ''; 

if(empty($userid)){

	header("Location: index.php");

}

?>
<!DOCTYPE html>

<html lang="en">

	<!-- begin::Head -->
	<head>

		<!--begin::Base Path (base relative path for assets of this page) -->
		

		<!--end::Base Path -->
		<meta charset="utf-8" />
		<title>M3 | Dashboard</title>
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
		 <div class="kt-portlet kt-portlet--mobile">
                
							  
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<h3 class="kt-portlet__head-title">Track Team</h3>
							</div>
						</div> 
		<div class="kt-portlet__body">
	<form method="get" action="">
					<div class="form-group row">
				
						<label for="example-text-input" class="col-2 col-form-label">Employee</label>
                        <div class="col-4">
                            <input class="form-control" type="text" placeholder="Employee id"  value="<?php echo !empty($_GET['employeeid']) ? $_GET['employeeid']: '';?>" name="employeeid" id="example-text-input">
                        </div>
                        <label for="example-text-input" class="col-2 col-form-label">Employee mobile</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="employeemobile" value="<?php echo !empty($_GET['employeemobile']) ? $_GET['employeemobile']: '';?>" placeholder="client mobile" id="example-text-input">
                        </div>
                    </div>
					 <div class="form-group row">
                       <div class="col-4">
                             <button type="submit"  class="btn btn-brand btn-elevate btn-pill">Submit</button>
                             <a href="track-team.php" class="btn btn-brand btn-elevate btn-pill">Clear</a>
                             <a href="track-team-download.php" class="btn btn-brand btn-elevate btn-pill">Download</a>
						</div>
                    </div> 
					</form>	
						<!--begin: Datatable -->
			<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>S.No</th>
                            <th>Leader ID</th>
                            <th>Employee ID</th>
                            <th>Mobile</th>
                            <th>Clients</th>
							<th>Invested Amount</th>
							<th>Points</th>
                            <th>Converted</th>
                            <th>Converted amount</th>
							<th>Points</th>
						</tr>
							</thead>
								<tbody>
									 <?php
									 
									  if(!empty($_GET['employeeid'])){
							 $empid = employee_id($_GET['employeeid']);
							$sql = runloopQuery("SELECT * FROM employee where ID = '".$empid."' order by ID desc");
						 }else if(!empty($_GET['employeemobile'])){ 
							 $empid = $_GET['employeemobile'];
							$sql = runloopQuery("SELECT * FROM employee where mobile = '".$empid."' order by ID desc");
						 }else{
							$sql = runloopQuery("SELECT * FROM employee where ID in ('".$empString."')order by ID desc");
						 }
						  

   $x=1;  foreach($sql as $row)
		{
			$totalclients = runQuery("select count(*) as count from clients where employee_id = '".$row['ID']."'");
			$totalinvestedamount = runQuery("select sum(loanamount) as count from clients where employee_id = '".$row['ID']."'");
			
			$totalinvestedamountarray = runloopQuery("select loanamount,pointstype from clients where employee_id = '".$row['ID']."'");
			$totalinvespoints = 0;
			foreach($totalinvestedamountarray as $totalinvested){
			$totalinvespoints += points($totalinvested["loanamount"],$totalinvested["pointstype"]);
			}
			$totalconvertedclients = runQuery("select count(*) as count from clients where employee_id = '".$row['ID']."' and status = 'Active'");
			$totalconvertedamountarray = runloopQuery("select loanamount,pointstype from clients where employee_id = '".$row['ID']."' and status = 'Active'");
			$totalconvertedamount = runQuery("select sum(loanamount) as count from clients where employee_id = '".$row['ID']."' and status = 'Active'");
				$totalconetpoints = 0;
			foreach($totalconvertedamountarray as $totalconverted){
			$totalconetpoints += points($totalconverted["loanamount"],$totalconverted["pointstype"]);
			}
			
	?>
	<tr>
	<td><?php echo  $x;?></td>
	<td><?php echo $row["leader"];?></td>
	<td><?php echo $row["fname"];?>(<?php echo $row["unique_id"];?>)</td>
	<td><?php echo $row["mobile"];?></td>
	<td><?php echo $totalclients["count"];?></td>
	<td><?php echo $totalinvestedamount["count"];?></td>
	<td><?php echo $totalinvespoints;?></td>
	<td><?php echo $totalconvertedclients["count"];?></td>
	<td><?php echo $totalconvertedamount["count"];?></td>
	<td><?php echo $totalconetpoints;?></td>
	</tr>
<?php
     $x++; }
?>					
							</tbody>
						</table>

									<!--end: Datatable -->
							</div>
						</div> 
						<!-- end:: Content -->
			</div>  

						<!-- end:: Content -->
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