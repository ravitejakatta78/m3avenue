<?php 
session_start(); 

include('../functions.php');



$userid = current_adminid(); 

if(empty($userid)){

	header("Location: index.php");

}

$message = '';
$sql = [];
if(!empty($_POST['submit'])){
	if(!empty($_POST['pincode'])){
		$sqlpincode = runloopQuery("SELECT * FROM bankexcel_details  
		where pincode= '".$_POST['pincode']."' ") ;
		//echo "<pre>";print_r($sqlpincode);exit;
		$sqlstring = '';
		if(!empty($sqlpincode)){
		    $banckexcel_id_arr = array_column($sqlpincode,'banckexcel_id');
		    $banckexcel_id_string = implode("','",$banckexcel_id_arr);
		        $sqlstring .= "select * from bankexcel_details where banckexcel_id in  ('".$banckexcel_id_string."') ";
	            if(!empty($_POST['company_name'])) {
			        $sqlstring .= " and company_name like '%".$_POST['company_name']."%' ";
		        }
		        
		        	if(!empty($_POST['salary'])) {
			            $sqlstring .= " and min_salary >= '".$_POST['salary']."' ";
		            }
		            $sqlstring .= "  order by ID desc";
		    
		    //echo $sqlstring;exit;
		    		$sql = runloopQuery($sqlstring);

		 }
		
		
		
	//	if(!empty($_POST['salary'])) {
	//		$sqlstring .= " and min_salary <= '".$_POST['salary']."' ";
		//}
		
													  }
													  else{
														$sql = []; 
													  }
}


if(!empty($_GET['delete'])){
	$sql = "DELETE FROM campaigns WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: campaigns-list.php?success=success");
   
} else {
    echo "Error deleting record: " . $conn->error;
}
} 
?>
<!DOCTYPE html>

<html lang="en"> 
	<head> 
		<meta charset="utf-8" />
		<title>M3  | Dashboard</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />

		<?php include('headerscripts.php');?>
	</head>
 
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
					    	<h3 class="kt-portlet__head-title">Search Banks</h3>
					    </div>
			    	</div>
                    <div class="kt-portlet__body">
						   <?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Campaigns Added Succussfully
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
		 <form  method="post" action="" enctype="multipart/form-data" autocomplete="off" > 
						<div class="row">
						<div class="form-group col-md-6">
						    
                        <label for="example-text-input" class="col-12 col-form-label">Enter Pincode</label>
                        <div class="col-md-12">
                            <input class="form-control" name="pincode" value="<?= $_POST['pincode'] ?? '' ?>" type="text" placeholder="Enter Pincode" id="pincode">
                        </div>  
						</div>  
						<div class="form-group col-md-6">
						    
                        <label for="example-text-input" class="col-12 col-form-label">Enter Company</label>
                        <div class="col-md-12">
                            <input class="form-control" name="company_name" value="<?= $_POST['company_name'] ?? '' ?>" id="company_name"  type="text" placeholder="Enter Company Name"   >
                        </div>  
						</div>   
						<div class="form-group col-md-6">
                        <label for="example-text-input" class="col-12 col-form-label">Salary</label>
                        <div class="col-md-12">
                            <input class="form-control" name="salary" id="salary" value="<?= $_POST['salary'] ?? '' ?>" type="text" placeholder="Enter Salary"  >
                        </div> 
                    </div>
					
						</div> 
						
					 <div class="form-group row"> 
                    
                       <div class="col-3">
                             <button type="submit" name="submit" value="submit" class="btn btn-brand btn-elevate btn-pill">Bank Search</button>
						</div>
                    </div>  
					</form>
					</form>
					 
						</div>
						</div>
                          
		    <div class="kt-portlet kt-portlet--mobile"> 
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<div class="col-10">
								<h3 class="kt-portlet__head-title">Banks List</h3>
								</div> 
							</div>
						</div>     
		<div class="kt-portlet__body">
		<div class="table table-responsive">

						<!--begin: Datatable -->
			<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>S.No</th>
							<th>Pincode</th>
							<th>Company Name</th>
                            <th>Category</th>  
                            <th>Bank Name</th>  
                            <th>Min Salary</th>
                            
						</tr> 
							</thead>
								<tbody>
								  <?php

   $x=1;  foreach($sql as $row)
	{
	?>
<tr>
	<td><?= $x; ?></td>
	<td><?= @$_POST['pincode'] ?? $row['pincode'] ; ?></td>
	<td><?= $row['company_name']; ?></td>
	<td><?= $row['category']; ?></td>
	<td><?= $row['bank_name']; ?></td>
	<td><?= $row['min_salary']; ?></td>

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
	</body>

	<!-- end::Body -->
</html>