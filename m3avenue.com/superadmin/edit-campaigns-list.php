<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');
 
$userid = current_adminid(); 

if(empty($userid)){

	header("Location: index.php");

}
$campaignsdetails = runQuery("select * from campaigns where ID = '".$_GET['edit']."'");
$message = '';

$message = '';
if(!empty($_POST['submit'])){
			if(!empty($_POST['title'])){
	   $pagerarray  = array();
  
				$pagewererarray['ID'] = $campaignsdetails['ID'];  
                $pagerarray['title'] = mysqli_real_escape_string($conn,$_POST['title']); 
                $pagerarray['user_id'] = $userid; 
                $pagerarray['manager_id'] = !empty($_POST['manager']) ? implode(',',$_POST['manager']) : ''; 
                $pagerarray['service'] = mysqli_real_escape_string($conn,$_POST['servicetype']); 
                $pagerarray['status'] = mysqli_real_escape_string($conn,$_POST['status']); 
                $pagerarray['timeframe'] = mysqli_real_escape_string($conn,$_POST['timeframe']);  
                $pagerarray['feedbackoptions'] = !empty($_POST['feedbackoptions']) ? implode(',',$_POST['feedbackoptions']) : '';
	            $result = updateQuery($pagerarray,'campaigns',$pagewererarray);
				if(!$result){
					header("Location: campaigns-list.php?success=success");
                    }
	    
		 	}else{

		$message .="Campaigns title is Empty";

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
			<div class="alert alert-light alert-elevate" role="alert">
			    <div class="kt-portlet kt-portlet--mobile">
			        
                    <div class="kt-portlet__head">
				    	<div class="kt-portlet__head-label">
					    	<h3 class="kt-portlet__head-title">Edit Campaigns</h3>
					    </div>
			    	</div>
                    <div class="kt-portlet__body">
						   <?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Campaigns list Added Succussfully
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
						<label for="example-text-input" class="col-12 col-form-label">Enter Title</label>
                        <div class="col-md-12"> 			
                            <input class="form-control" name="title" type="text" placeholder="Enter Title"  value="<?php echo $campaignsdetails['title']; ?>" >
                        </div> 
						</div>
						<div class="form-group col-md-6">
                        <label for="example-text-input" class="col-12 col-form-label">Time frame (seconds)</label>
                        <div class="col-md-12"> 			
                            <input class="form-control" name="timeframe" type="text" placeholder="Enter Time frame" value="<?php echo $campaignsdetails['timeframe']; ?>"  >
                        </div>  
						</div>   
						<div class="form-group col-md-6">
                        <label for="example-text-input" class="col-12 col-form-label">Service type</label>
                        <div class="col-md-12"> 		
                            <input class="form-control" name="servicetype" type="text" placeholder="Enter Service type" value="<?php echo $campaignsdetails['service']; ?>" >
                        </div> 
                    </div>   
						<div class="form-group col-md-6">
                        <label for="example-text-input" class="col-12 col-form-label">Manager</label>
                        <div class="col-md-12"> 			
                            <select class="form-control select2" name="manager[]" multiple >
							<option value="">--Select Manager--</option>
							<?php 
							$campaignsarray = explode(',',$campaignsdetails['manager_id']);
							$campaignsmanagers = runloopQuery("select * from manager order by ID desc");
								foreach($campaignsmanagers as $campaigns){
							?>
							<option value="<?php echo $campaigns['ID'];?>" <?php if(in_array($campaigns['ID'],$campaignsarray)){ echo 'selected';}?>><?php echo $campaigns['name'];?></option>
								<?php }?>
							</select>
                        </div> 
						</div>
					 <?php $currentfeebacks = explode(',',$campaignsdetails['feedbackoptions']);?>
						<div class="form-group col-md-6">
                        <label for="example-text-input" class="col-md-12 col-form-label">Feedback list</label>
                        <div class="col-md-12"> 			
                            <select class="form-control select2" name="feedbackoptions[]" multiple >
							<option value="">--Select Feedback--</option>
							<?php $campaignsmanagers = runloopQuery("select * from feedbackoptions order by ID asc");
								foreach($campaignsmanagers as $campaigns){ ?>
									<option value="<?php echo $campaigns['ID'];?>" <?php if(in_array($campaigns['ID'],$currentfeebacks)){ echo 'selected';}?>><?php echo $campaigns['title'];?></option>
								<?php }?>
							</select>
                        </div> 
						</div> 
						
						<div class="form-group col-md-6">
						<label for="example-text-input" class="col-12 col-form-label">Status</label>
                        <div class="col-md-12"> 			
							<select class="form-control" name="status">
								<option value="">--Select--</option> 
								<option value="0" <?php if($campaignsdetails['status']=='0'){ echo 'selected';}?>>Pending</option>
								<option value="1" <?php if($campaignsdetails['status']=='1'){ echo 'selected';}?>>Active</option>
								<option value="2" <?php if($campaignsdetails['status']=='2'){ echo 'selected';}?>>Inactive</option>
							</select>
                        </div>
						</div>	 
					  
						</div> 
					
					 <div class="form-group row">  
                        <div class="col-md-6"> 			
                             <button type="submit" name="submit" value="submit" class="btn btn-brand btn-elevate btn-pill">Update Campaigns</button>
						</div>
                    </div>  
					</form>
						 					
                         		
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
	
	<?php include("footerscripts.php");?>
	</body>

	<!-- end::Body -->
</html>