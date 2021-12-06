<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_adminid();
 

if(empty($userid)){

	header("Location: index.php");

}

if(!empty($_GET['delete'])){
	$sql = "DELETE FROM investment WHERE id=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: investment.php?success=success");
   
} else {
    echo "Error deleting record: " . $conn->error;
}
}

?>
<!DOCTYPE html>

<html lang="en">

	<!-- begin::Head -->
	<head>
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
                
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<div class="col-10">
								<h3 class="kt-portlet__head-title">Investment Enquirys</h3>
								</div>
								<a href="csv-loanenquires.php?table=investment" class="btn btn-primary" >Download</a>
								
							</div>
						</div>
                                
		<div class="kt-portlet__body">

						<!--begin: Datatable -->
			<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>S.No</th>

                            <th>Name</th>
							<th>Phone No</th>
							<th>Email</th>
							<th>Location</th>
							<th>Invest Amount</th>
							<th>Intersted In</th> 
							<th>Reg date</th> 
							<th>Delete</th> 
						</tr>
							</thead>
								<tbody>
									 <?php
$sql = runloopQuery("SELECT * FROM investment order by ID desc");

   $x=1;  foreach($sql as $row)
		{
?>
<tr>
<td><?php echo  $x;?></td>
<td><?php echo $row["name"];?></td>
<td><?php echo $row["phone"];?></td>
<td><?php echo $row["email"];?></td>
<td><?php echo $row["location"];?></td>
<td><?php echo $row["investment"];?></td>
<td><?php echo $row["interestedin"];?></td>
<td><?php echo reg_date($row["reg_date"]);?></td>

<td><a onclick="return confirm('Are you sure want to delete??');"  href="?delete=<?php echo $row["ID"];?>">Delete</a></td>
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