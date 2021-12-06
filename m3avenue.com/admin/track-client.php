<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');


 
$userid = current_adminid(); 

if(empty($userid)){

	header("Location: index.php");

}

$message = '';
//if(!empty($_POST['submit'])){
	if(!empty($_POST['clientname'])){
	   $pagerarray  = array();
	   $empid = mysqli_real_escape_string($conn,$_POST['employee_id']);
			$employeeid = runQuery("select * from employee where unique_id = '".$empid."'");
			if(!empty($employeeid['ID'])){ 
                $pagerarray['employee_id'] = $employeeid['ID']; 
                $pagerarray['clientname'] = mysqli_real_escape_string($conn,$_POST['clientname']);
                $pagerarray['regdate'] = mysqli_real_escape_string($conn,$_POST['regdate']);
                $pagerarray['mobile'] = mysqli_real_escape_string($conn,$_POST['mobile']);
                $pagerarray['servicetype'] = mysqli_real_escape_string($conn,$_POST['servicetype']);
                $pagerarray['bankname'] = mysqli_real_escape_string($conn,$_POST['bankname']);
                $pagerarray['loanamount'] = mysqli_real_escape_string($conn,$_POST['loanamount']);
                $pagerarray['companyname'] = mysqli_real_escape_string($conn,$_POST['companyname']);
                $pagerarray['pointstype'] = mysqli_real_escape_string($conn,$_POST['pointstype']);
                $pagerarray['location'] = mysqli_real_escape_string($conn,$_POST['location']);
                $pagerarray['status'] = 'Pending';
				$pagerarray['mod_date'] = date('Y-m-d H:i:s A');
	            $result = insertQuery($pagerarray,'clients');
				if(!$result){
					emp_monthly_points_calculation(['empid' => $employeeid['ID']]);
					header("Location: track-client.php?success=success");
                    } 
				}else{ 
					$message .="Invalid employee id";
				}
		 	}
		 	//else{
			//	$message .="  client Name  Field is Empty";
			//}

//} 

if(!empty($_GET['delete'])){
	$sql = "DELETE FROM clients WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: track-client.php?success=success");
   
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
								<div class="col-lg-8 col-md-6 col-sm-6 col-xs-12">
								<h3 class="kt-portlet__head-title">Track Client</h3></div>
								<a href="csv-loanenquires.php?table=clients" class="btn btn-primary" >Download</a>
								
							</div>
							<div class="col-lg-4 col-sm-6 col-md-6 col-xs-12">
                    	<button type="button" class="btn  btn btn-primary  mt-2" style="float:right" data-toggle="modal" data-target="#exampleModalScrollable">Add  Track Client</button>    

                    </div>
						</div>
						<div class="kt-portlet__body"> 
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
                                           				
					 </div>
					 </div> 
				<div class="kt-portlet kt-portlet--mobile">
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<h3 class="kt-portlet__head-title">Client Data</h3>
							</div>
						</div>
                                
					<div class="kt-portlet__body">
				<form method="get" action="">
					<div class="form-group row">
				
						<label for="example-text-input" class="col-2 col-form-label">Employee</label>
                        <div class="col-4">
                            <input class="form-control" type="text" placeholder="Employee id"  value="<?php echo !empty($_GET['employeeid']) ? $_GET['employeeid']: '';?>" name="employeeid" >
                        </div>
                        <label for="example-text-input" class="col-2 col-form-label">Client mobile</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="clientmobile" value="<?php echo !empty($_GET['clientmobile']) ? $_GET['clientmobile']: '';?>" placeholder="client mobile" >
                        </div>
                    </div>
					 <div class="form-group row">
                       <div class="col-4">
                             <button type="submit"  class="btn btn-brand btn-elevate btn-pill">Submit</button>
						</div>
                    </div> 
					</form>	 
							</div>
						</div> 
					<div class="col-12 row">
						 <?php
						   	if(isset($_GET["page"])){
										$page_number = filter_var($_GET["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);		
										if(!is_numeric($page_number)){
											die('Invalid page number!');
										}			
										}else{	
										$page_number = 1;	
										}
										$item_per_page      = 20;	
									$page_position = (($page_number-1) * $item_per_page);
									
						 if(!empty($_GET['employeeid'])){
							 $empid = employee_id($_GET['employeeid']);
							$sql = runloopQuery("SELECT * FROM clients where employee_id = '".$empid."' order by ID desc  LIMIT $page_position, $item_per_page");
							$totalordersarray = runQuery("SELECT count(*) as count FROM clients where employee_id = '".$empid."' order by ID desc")['count'];	
							
						 }else if(!empty($_GET['clientmobile'])){ 
							 $empid = $_GET['clientmobile'];
							$sql = runloopQuery("SELECT * FROM clients where mobile = '".$empid."' order by ID desc  LIMIT $page_position, $item_per_page");
							$totalordersarray = runQuery("SELECT count(*) as count FROM clients where mobile = '".$empid."' order by ID desc")['count'];	
						 }else{
							$sql = runloopQuery("SELECT * FROM clients order by ID desc  LIMIT $page_position, $item_per_page");
							$totalordersarray = runQuery("SELECT count(*) as count FROM clients order by ID desc")['count'];	
						 }
					   $x=1;  foreach($sql as $row)
							{
					?>
					<div class="col-4">
				<div class="kt-portlet kt-portlet--mobile">
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<h3 class="kt-portlet__head-title"><?php echo employee_details($row["employee_id"],'fname');?>(<?php echo employee_details($row["employee_id"],'unique_id');?>)</h3>
							</div>
							<div class="pull-right">
							<a class="btn btn-danger" onclick="return confirm('Are you sure want to delete??');"  href="?delete=<?php echo $row["ID"];?>">Delete</a>
							</div>
						</div>
                                
					<div class="kt-portlet__body">
					   <div class="row form-group">
							<div class="col-6">
								<h6>Name: </h6>
								<?php echo $row["clientname"];?>
							</div>
							<div class="col-6">
								<h6>Reg Date: </h6>
								<?php echo $row["regdate"];?>
							</div>
					   </div>
					   <div class="row form-group">
							<div class="col-6">
								<h6>Mobile: </h6>
								<?php echo $row["mobile"];?>
							</div>
							<div class="col-6">
								<h6>Service Type: </h6>
								<?php echo $row["servicetype"];?>
							</div>
					   </div>
					   <div class="row form-group">
							<div class="col-6">
								<h6>Company name: </h6>
								<?php echo $row["companyname"];?>
							</div>
							<div class="col-6">
								<h6>Points: </h6>
								<?php echo points($row["loanamount"],$row["pointstype"]);?>(<?php echo pointset_details($row["pointstype"],'title');?>)
							</div>
					   </div>
					   <div class="row form-group">
							<div class="col-6">
								<h6>Location: </h6>
								<?php echo $row["location"];?>
							</div>
							<div class="col-6">
								<h6>Bank name: </h6>
								<?php echo $row["bankname"];?>
							</div>
					   </div>
					   <div class="row form-group">
							<div class="col-12">
									<h6>Status: </h6>
																	
									<select class="form-control" id="status" name="status"  data-clientid="<?php echo $row["ID"];?>">
										<option value="">--Select--</option>
										<option value="Pending" <?php if($row['status']==''||$row['status']=='Pending'){ echo 'selected';}?>>Pending</option>
										<option value="Approved" <?php if($row['status']=='Approved'){ echo 'selected';}?>>Approved</option>
										<option value="Rejected" <?php if($row['status']=='Reject'){ echo 'selected';}?>>Reject</option>
									</select>
							</div>

							<div class="col-12 mt-5">

							<button class="changestatus btn btn-brand btn-elevate btn-pill" data-clientid="<?php echo $row["ID"];?>">Submit</button>									
							</div> 
					   </div>
</div>
									<!--end: Datatable -->
							</div>
						</div>
						
							<?php }?>
						</div>
						  <div class="row form-group">
							<div class="col-6">
					 	<?php echo m3admin_nav($page_number,$totalordersarray,$item_per_page);?> 
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
		 <!-- Modal -->
<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog  modal-md modal-dialog-scrollable"   role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalScrollableTitle">Add Track Client</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="" enctype="multipart/form-data" id="addclientform" autocomplete="off">
        	
  			<div class="row">
    			<div class="col">
    				<label class="font-weight-bold">Employee Id</label>
      				<input class="form-control" type="text" name="employee_id" value="<?php echo !empty($_POST['mobile']) ? $_POST['mobile']: '';?>" placeholder="Enter Employee Id" >
    			</div>
    			<div class="col">
    				<label class="font-weight-bold">Client Name</label>
      				<input class="form-control" type="text" id="clientname" required name="clientname"  value="<?php echo !empty($_POST['clientname']) ? $_POST['clientname']: '';?>" placeholder="Enter Client Name" >
    			</div>
  			</div>
  			<div class="row mt-2">
    			<div class="col">
    				<label class="font-weight-bold">Reg Date</label>
					<input class="form-control" type="date" name="regdate" value="<?php echo !empty($_POST['regdate']) ? $_POST['regdate']: '';?>" placeholder="DD/MM/YYYY" >    			</div>
    			<div class="col">
    				<label class="font-weight-bold">Mobile Number</label>
      				<input class="form-control" type="number" name="mobile" value="<?php echo !empty($_POST['mobile']) ? $_POST['mobile']: '';?>" placeholder="Enter Mobile Number" >
    			</div>
  			</div>
  			<div class="row mt-2">
    			<div class="col">
    				<label class="font-weight-bold">Service type</label>
      				<input class="form-control" type="text" name="servicetype" value="<?php echo !empty($_POST['servicetype']) ? $_POST['servicetype']: '';?>" placeholder="Enter Service type" >
    			</div>
    			<div class="col">
    				<label class="font-weight-bold">Loan/Invested Amount</label>
      				<input class="form-control" type="text" placeholder="Loan/Invested Amount"  value="<?php echo !empty($_POST['loanamount']) ? $_POST['loanamount']: '';?>" name="loanamount" >
    			</div>
  			</div>
  			 <div class="row mt-2">
    			<div class="col">
    				<label class="font-weight-bold">Company name</label>
      				<input class="form-control" type="text" name="companyname" value="<?php echo !empty($_POST['companyname']) ? $_POST['companyname']: '';?>" placeholder="Enter Invested Type" >
    			</div>
    			<div class="col">
    				<label class="font-weight-bold">Bank name</label>
      				<input class="form-control" type="text" name="bankname" value="<?php echo !empty($_POST['bankname']) ? $_POST['bankname']: '';?>" placeholder="Enter Bank name" >
    			</div>
  			</div>
  			<div class="row mt-2">
    			<div class="col">
    				<label class="font-weight-bold">Points type</label>
      				<select class="form-control" required id="pointstype" name="pointstype" >
								<option value="">--select--</option>
								<?php $pointsetarray = runloopQuery("select * from pointset order by ID desc");
									foreach($pointsetarray as $pointset){
								?>
								<option value="<?php echo $pointset['ID'];?>"><?php echo $pointset['title'];?></option>
									<?php }?>
							</select>
    			</div>
    			<div class="col">
    				<label class="font-weight-bold">Location</label>
      				<input class="form-control" type="text" name="location" value="<?php echo !empty($_POST['location']) ? $_POST['location']: '';?>" placeholder="Enter location" >
    			</div>
  			</div>
					
		</form>

      </div>
        <div class="modal-footer">
		  <button type="submit" name="submit" value="submit" class="btn btn-primary" id="addclientsubmitid"> Add Track Client</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>
 
		
	<?php include("footerscripts.php");?>
	 
		
		  <?php if(!empty($_GET['view'])){?>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
<!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
          <form method="post" action="">
		<?php $m3avenue = runQuery("select * from clients where ID = ".$_GET['view']."");?>

		   <div class="form-group">
		    <select class="form-control" name="status">
			 <option value="">Select Status</option>
			 <option value="Accept">Accept</option>
			 <option value="Reject">Reject</option>
			</select>
		   </div>
		   <div class="form-group">
		    <button type="submit" class="btn btn-success" name="submit" value="<?php echo $m3avenue['ID'];?>">Submit</button>
		   </div>
		  </form>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>

</div>
<script>$("#myModal").modal('show');</script>

  <?php } ?>
  <script>	
	 $(document).on('change','.itemready',function(){	
	 var checked = $(this).val();					
	 $.ajax({				
	 url : 'ajax/trackclientstatus.php',		
	 
	 type: "POST",		
	 data: {			
	 checked:checked			
	 },				
	 success: function(res){	
	 /* slience is golden */	
	 }					
	 });			
	 });
	  $('.changestatus').on('click',function(){ 
		  
	 var checked = $(this).data('clientid');					
	 var changestatus = $("#status").val();

	 $.ajax({				
	 url : 'ajax/trackclientchangestatus.php',		
	 
	 type: "POST",		
	 data: {			
	 checked:checked,			
	 changestatus:changestatus			
	 },				
	 success: function(res){	
	 /* slience is golden */	
	 }					
	 });			
	 });


	  $("#addclientsubmitid").click(function(){
		var user_input_value;
		var err_value = 0
		$('#addclientform').find('input,select,select2').each(function(){
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
			$("#addclientsubmitid").hide();
			$("#addclientform").submit();	
		}
		
	});

	 </script>

	
	</body>

	<!-- end::Body -->
</html>