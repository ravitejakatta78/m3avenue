<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');

$userid = current_adminid(); 
if(empty($userid)){
	header("Location: index.php");
}
$message = '';
if(!empty($_POST['invesmentsubmit'])){
	   $pagerarray  = array();  
                $pagerwherearray['ID'] = mysqli_real_escape_string($conn,$_POST['invesmentsubmit']);
                $pagerarray['payin'] = mysqli_real_escape_string($conn,$_POST['payin']); 
                $pagerarray['growth'] = mysqli_real_escape_string($conn,$_POST['growth']); 
                $pagerarray['status'] = mysqli_real_escape_string($conn,$_POST['status']); 
	            $result = updateQuery($pagerarray,'user_investment',$pagerwherearray);
				if(!$result){
					header("Location: track-user-investment.php?success=success");
                    } 
} 

if(!empty($_GET['delete'])){
	$sql = "DELETE FROM clients WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: track-user-investment.php?success=success");
   
} else {
    echo "Error deleting record: " . $conn->error;
}
}
?>
<!DOCTYPE html>

<html lang="en">
 
	<head>
 
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
		 
				<div class="kt-portlet kt-portlet--mobile">
					<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<h3 class="kt-portlet__head-title">Track Client</h3>
							</div>
						</div>
						<br>
						     <?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Track Client details Added Succussfully
								</div>
								<?php } ?>
								<?php if(!empty($message)){?>
								<div class="alert alert-danger">
								<?=$message?>
								</div>
								<?php } ?>
					  
                                
		<div class="kt-portlet__body">
				<form method="get" action="">
					<div class="form-group row">
				
						<label for="example-text-input" class="col-2 col-form-label">User</label>
                        <div class="col-4">
                            <input class="form-control" type="text" placeholder="User id"  value="<?php echo !empty($_GET['userid']) ? $_GET['userid']: '';?>" name="userid" id="example-text-input">
                        </div>
                    </div>
					 <div class="form-group row">
                       <div class="col-4">
                             <button type="submit"  class="btn btn-brand btn-elevate btn-pill">Submit</button>
							 <a href="track-user-investment.php" class="btn btn-primary btn-elevate btn-pill" >Clear</a>
						</div>
                    </div> 
					</form>	
						<!--begin: Datatable -->
			<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>S.No</th>
                            <th>Client Name</th>
							<th>Reg Date</th>
							<th>mobile</th>
                            <th>Invested Type</th>
							<th>Loan/Invested Amount</th>
							<th>PayIn/Payout</th>
							<th>growth</th>
							<th>Status</th>
							<th>View</th>
						</tr>
					</thead>
					<tbody>
						 <?php
						 if(!empty($_GET['userid'])){
							 $empid = user_id($_GET['userid']);
							$sql = runloopQuery("SELECT * FROM user_investment where user_id = '".$empid."' order by ID desc");
						 }else{
							$sql = runloopQuery("SELECT * FROM user_investment order by ID desc");
						 }
					   $x=1;  foreach($sql as $row)
							{
					?>
<tr>
<td><?php echo  $x;?></td>
<td><?php echo user_details($row["user_id"],'fname');?>(<?php echo user_details($row["user_id"],'unique_id');?>)</td>
<td><?php echo date('d/m/Y',strtotime($row["applieddate"]));?></td>
<td><?php echo user_details($row["user_id"],'mobile');?></td>
<td><?php echo $row["investedtype"];?></td>
<td><?php echo $row["loanamount"];?></td>
<td><?php echo $row["payin"];?></td>
<td><?php echo $row["growth"];?></td>
<td><?php echo $row["status"]==1 ? 'Active' : 'Pending';?></td>
<td><a href="?invesmentview=<?php echo $row["ID"];?>">View</a></td>
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