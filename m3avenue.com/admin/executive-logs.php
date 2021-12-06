<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_adminid(); 

if(empty($userid)){

	header("Location: index.php");

}

$executive = $_GET['executive']; 
$campaign = !empty($_GET['campaign']) ? $_GET['campaign'] : ''; 

if(empty($executive)){

	header("Location: executive-list.php");

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
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-label"> 
									<div class="col-12">
										<h3 class="kt-portlet__head-title">Executive Logs</h3>
									</div> 
										<a href="executive-list.php" class="btn btn-primary pull-right" >Executive List</a> 
								<a href="csv-executive-logs.php?executive=<?php echo $executive; ?>&campaign=<?php echo $campaign; ?>" class="btn btn-primary pull-right" >Download</a> 
							</div>
						</div>     
		<div class="kt-portlet__body">
		<form method="get" action="" >
		<div class="row from-group">
		<div class="col-4">
				<select class="form-control" name="campaign" >
				<option value="">-Select Campaign--</option>
				<?php $campaignsarray = runloopQuery("select c.ID,c.title from campaign_logs l, campaigns c where l.campaign_id = c.ID and l.executive_id = '".$executive."' group by l.campaign_id order by l.campaign_id asc");
					foreach($campaignsarray as $campaigns){ ?>
						<option value="<?php echo $campaigns['ID'];?>" <?php if(!empty($_GET['campaign'])&&$_GET['campaign']==$campaigns['ID']){ echo 'selected';}?>><?php echo $campaigns['title'];?></option>
				<?php	} 	?>
				</select>
			
			</div> 
		<div class="col-4">
			<input type="submit" class="btn btn-success" Value="Submit" />
			<input type="hidden" name="executive" Value="<?php echo $executive;?>" />
			</div>  
			</div>
		</form>
		<div class="table table-responsive"> 
						<!--begin: Datatable -->
			<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>S.No</th>
							<?php if(empty($campaign)){?>
							<th>Campaign</th>
							<?php }?>
                            <th>Log Date</th>  
                            <th>Action</th>  
                           <!-- <th>Delete</th>
							<th>Status:</th>-->
						</tr>
						
							</thead>
									<tbody>
									  <?php
									  if(!empty($_GET['campaign'])){
					  $sql = runloopQuery("select l.* from campaign_logs l, campaigns c where l.campaign_id = c.ID and l.executive_id = '".$executive."'  and l.campaign_id ='".$campaign."' order by l.ID asc");
										   
									  }else{
										
					  $sql = runloopQuery("select l.* from campaign_logs l, campaigns c where l.campaign_id = c.ID and l.executive_id = '".$executive."'  order by l.ID asc");
										   
									  }

	   $x=1;  foreach($sql as $row)
			{
	?>
	<tr>
	<td><?php echo  $x;?></td>
							<?php if(empty($campaign)){?>
	<td><?php echo campaign_details($row["campaign_id"],'title');?></td>
							<?php }?>
	<td><?php echo reg_date($row["logtime"]);?></td>
	<td><?php echo $row["action"];?></td> 
	<!--<td><a href="edit-executive-list.php?edit=<?php echo $row["ID"];?>">Edit</a> | <a onclick="return confirm('Are you sure want to delete??');"  href="?delete=<?php echo $row["ID"];?>">Delete</a></td>-->
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
 
		
	<?php include("footerscripts.php");?>
	<script>
			function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
	</script>
	</body>

	<!-- end::Body -->
</html>