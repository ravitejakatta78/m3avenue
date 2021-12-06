<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_adminid();
 

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
								<h3 class="kt-portlet__head-title">Employee report</h3>
							</div>
						</div>
                                
		<div class="kt-portlet__body">
					<form method="get" action="">
					<div class="form-group row">
				
						<label for="example-text-input" class="col-2 col-form-label">Employee</label>
                        <div class="col-4">
                            <input class="form-control" type="text" placeholder="Employee id"  value="<?php echo !empty($_GET['employeeid']) ? $_GET['employeeid']: '';?>" name="employeeid" id="example-text-input" required>
                        </div>
						<label for="example-text-input" class="col-2 col-form-label">Date</label>
                        <div class="col-4">
                            <input class="form-control" type="date" placeholder="Employee id"  value="<?php echo !empty($_GET['employeedate']) ? $_GET['employeedate']: '';?>" name="employeedate" id="example-text-input" required>
                        </div>
                       <div class="col-4">
                             <button type="submit"  class="btn btn-brand btn-elevate btn-pill">Submit</button>
						</div> 
                        
                    </div> 
					</form>	
					<?php 
									  if(!empty($_GET['employeeid'])){
										  
							 $empid = employee_id($_GET['employeeid']);
							  if(!empty($empid)){
										  ?>
						<!--begin: Datatable -->
						
			<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr> 
                            <th>Employee ID</th>
                            <th>Clients</th>
							<th>Invested Amount</th>
                            <th>Converted</th>
							<th>Approved Amount</th>
						</tr>
							</thead>
								<tbody>
									 <?php
							 $empfromdate = date('Y-m-d',strtotime($_GET['employeedate']));
							$row = runQuery("SELECT * FROM employee where ID = '".$empid."' order by ID desc");
							
			$totalclients = runQuery("select count(*) as count from clients where employee_id = '".$row['ID']."' and reg_date>='".$empfromdate." 00:00:00' and reg_date <='".$empfromdate." 23:59:59' ");
			$totalinvestedamount = runQuery("select sum(loanamount) as count from clients where employee_id = '".$row['ID']."'  and reg_date>='".$empfromdate." 00:00:00' and reg_date <='".$empfromdate." 23:59:59' ");
			$totalconvertedclients = runQuery("select count(*) as count from clients where employee_id = '".$row['ID']."' and status = 'Active'  and reg_date>='".$empfromdate." 00:00:00' and reg_date <='".$empfromdate." 23:59:59'  ");
			$totalconvertedamount = runQuery("select sum(loanamount) as count from clients where employee_id = '".$row['ID']."' and status = 'Active'  and reg_date>='".$empfromdate." 00:00:00' and reg_date <='".$empfromdate." 23:59:59' ");
	?>
	<tr> 
	<td><?php echo $row["fname"];?>(<?php echo $row["unique_id"];?>)</td>
	<td><?php echo $totalclients["count"];?></td>
	<td><?php echo $totalinvestedamount["count"];?></td>
	<td><?php echo $totalconvertedclients["count"];?></td>
	<td><?php echo $totalconvertedamount["count"];?></td>
	</tr> 				
							</tbody>
						</table>
									  <?php }else{ ?>
									  
									  <p>Employee not found</p>
									  <?php }?>
									  <?php }?>
									<!--end: Datatable -->
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