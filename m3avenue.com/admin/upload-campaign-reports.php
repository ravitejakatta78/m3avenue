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
$campainid = !empty($_GET['campaign']) ? $_GET['campaign'] : '';
$campaignsheet = !empty($_GET['campaignsheet']) ? $_GET['campaignsheet'] : '';
if(empty($userid)){

	header("Location: campaigns-list.php");

}
$compaindetails = runQuery("select * from campaigns where ID = '".$campainid."'");
$compainsheetdetails = runQuery("select * from campaigns_excell where ID = '".$campaignsheet."'");
if(empty($compaindetails)){ 
	header("Location: campaigns-list.php");

} 
if(!empty($_GET['delete'])){
	$sql = "DELETE FROM campaigns_users WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: upload-campaign-excell.php?campaign=".$_GET['campaign']."&dsuccess=success");
   
} else {
    echo "Error deleting record: " . $conn->error;
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
			 
						<!-- begin:: Content -->
	<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
                          
		    <div class="kt-portlet kt-portlet--mobile"> 
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-label"> 
							<?php if(!empty($compainsheetdetails['ID'])){?>
							<h3 class="kt-portlet__head-title"><?php echo $compaindetails['title'];?> With sheet name <?php echo $compainsheetdetails['excellname'];?></h3>
							<?php }else{ ?>
							<h3 class="kt-portlet__head-title">Report for <?php echo $compaindetails['title'];?></h3>
							<?php }?>
							<?php if(!empty($compainsheetdetails)){?>
								<a href="csv-campaign-report.php?campaign=<?php echo $compaindetails["ID"];?>&sheet=<?php echo $compainsheetdetails["ID"] ?: 0;?>" class="btn btn-primary" >Download</a>
							<?php }else{ ?>
								<a href="csv-campaign-report.php?campaign=<?php echo $compaindetails["ID"];?>" class="btn btn-primary" >Download</a>
							<?php }?>
							</div>
						</div>     
		<div class="kt-portlet__body">  
						<!--begin: Datatable -->
			<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>S.No</th> 
                            <th>Executive</th>    
                            <th>Full Name,Mobile</th>    
                            <th>Call duration</th>  
                            <th>Call status</th>  
                            <th>Call message</th>  
                            <th>Called on</th>  
                            <th>Reg date</th>  
                            <th>Action</th>  
						</tr> 
							</thead>
							<tbody>
								  <?php
								   if(!empty($compainsheetdetails['ID'])){
							$sql = runloopQuery("SELECT cu.*,e.name as ename FROM campaigns_users cu,executive e where cu.executive_id = e.ID and cu.campaign_id = '".$compaindetails['ID']."' and cu.campaignexcell_id = '".$compainsheetdetails['ID']."' order by cu.ID desc");
								   }else{
							$sql = runloopQuery("SELECT cu.*,e.name as ename FROM campaigns_users cu,executive e where cu.executive_id = e.ID and cu.campaign_id = '".$compaindetails['ID']."'  order by cu.ID desc");
								   }
							   $x=1;  foreach($sql as $row)
									{
							?>
							<tr>
							<td><?php echo  $x;?></td>  
							<td><?php echo $row["ename"];?> 
							<td><?php echo $row["name"];?> 
							<br/><?php echo $row["mobile"];?></td>   
							<td><?php echo $row["callduration"];?> Sec</td> 
							<td><?php echo feedbackstatus($row["callstatus"]);?></td> 
							<td><?php echo $row["callmessage"];?></td> 
							<td><?php echo reg_date($row["duration"]);?></td> 
							<td><?php echo reg_date($row["reg_date"]);?></td>
							<td><a onclick="return confirm('Are you sure want to delete??');"  href="?campaign=<?php echo $compaindetails['ID']; ?>&delete=<?php echo $row["ID"];?>">Delete</a></td>
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