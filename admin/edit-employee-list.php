<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');
 
$userid = current_adminid(); 

if(empty($userid)){

	header("Location: index.php");

}
$employeedetails = runQuery("select * from employee where ID = '".$_GET['edit']."'");
$super_admin_details= runQuery("select * from tbl_super_admin_details where super_admin_id = '".$_GET['edit']."'");
$super_admin_module_details= runloopQuery("select * from tbl_super_admin_subscription_module where super_admin_id = '".$_GET['edit']."'");

//print_r($super_admin_details); echo '<br>';
//print_r($super_admin_module_details); exit;
$message = '';
if(!empty($_POST['submit'])){
			if(!empty($_POST['fname'])){
	   $pagerarray  = array();

		  if (!file_exists('empimage')) {	
		mkdir('empimage', 0777, true);	
		}
	    $target_dir = 'empimage/';									

			 if(!empty($_FILES["panimg"]['name'])){
		$file = $_FILES["panimg"]['name'];				
		$target_file = $target_dir . strtolower($file);		

		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);								

		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" )

			{
		$message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
		$uploadOk = 0;						
		}
	    if ($uploadOk == 0) {			
		$message .= "Sorry, your file was not uploaded.";		
		} else {
			if (move_uploaded_file($_FILES["panimg"]["tmp_name"], $target_file)){				
				$pagerarray['panimg'] = strtolower($file);						
				} else {
					$message .= "Sorry, There Was an Error Uploading Your File.";			
					}
				}
				}
			 if(!empty($_FILES["adhaarimg"]['name'])){
		$file = $_FILES["adhaarimg"]['name'];				
		$target_file = $target_dir . strtolower($file);		

		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);								

		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" )

			{
		$message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
		$uploadOk = 0;						
		}
	    if ($uploadOk == 0) {			
		$message .= "Sorry, your file was not uploaded.";		
		} else {
			if (move_uploaded_file($_FILES["adhaarimg"]["tmp_name"], $target_file)){				
				$pagerarray['adhaarimg'] = strtolower($file);						
				} else {
					$message .= "Sorry, There Was an Error Uploading Your File.";			
					}
				} 
				} 
				$pagewererarray['ID'] = $employeedetails['ID']; 
                $pagerarray['leader'] = mysqli_real_escape_string($conn,$_POST['leader']);
                $pagerarray['role_id'] = mysqli_real_escape_string($conn,$_POST['role_id']);
                $pagerarray['fname'] = mysqli_real_escape_string($conn,$_POST['fname']);
                $pagerarray['lname'] = mysqli_real_escape_string($conn,$_POST['lname']);  
                $pagerarray['bankdetails'] = mysqli_real_escape_string($conn,$_POST['bankdetails']);
                $pagerarray['accntnum'] = mysqli_real_escape_string($conn,$_POST['accntnum']);
                $pagerarray['ifsccode'] = mysqli_real_escape_string($conn,$_POST['ifsccode']);
                $pagerarray['address'] = mysqli_real_escape_string($conn,$_POST['address']);
                $pagerarray['income'] = mysqli_real_escape_string($conn,$_POST['income']);
                $pagerarray['pannum'] = mysqli_real_escape_string($conn,$_POST['pannum']);
                
	            $result = updateQuery($pagerarray,'employee',$pagewererarray);
				if(!$result){
					header("Location: employe-list.php?success=success");
                    }
	    
		 	}else{

		$message .=" First Name Field is Empty";

	}

}

if(!empty($_POST['super_admin_details_submit']))
{
	$subscription_duration=$_POST['subscription_duration'];
	$no_of_managers=$_POST['no_of_managers'];
	$no_of_executives=$_POST['no_of_executives'];
	$super_admin_id=$_POST['super_admin_id'];

	
	$expiry_date = date('Y-m-d', strtotime("+$subscription_duration months", strtotime(date('Y-m-d'))));
	$form_array=array(); $modules_array=array();
	$selected_modules=array();
	$selected_modules=$_POST['selected_modules'];
	$form_array['super_admin_id']=mysqli_real_escape_string($conn,$super_admin_id);
	$form_array['subscription_date']=mysqli_real_escape_string($conn,date('Y-m-d'));
	$form_array['subscription_duration']=mysqli_real_escape_string($conn,$subscription_duration);
	$form_array['expiry_date']=mysqli_real_escape_string($conn,$expiry_date);
	$form_array['no_of_managers']=mysqli_real_escape_string($conn,$no_of_managers);
	$form_array['no_of_executives']=mysqli_real_escape_string($conn,$no_of_executives);


	$super_admin_details= runQuery("select * from tbl_super_admin_details where super_admin_id = '".$super_admin_id."'");
	$super_admin_module_details= runloopQuery("select * from tbl_super_admin_subscription_module where super_admin_id = '".$super_admin_id."'");
 
	if(count($super_admin_details)==0)
	{
	 	$result=insertQuery($form_array,'tbl_super_admin_details');
	}else
	{
		 $previous_date=$_POST['subscription_date'];
		 $expiry_date = date('Y-m-d', strtotime("+$subscription_duration months", strtotime($previous_date)));
		 $pagerarray['expiry_date']=mysqli_real_escape_string($conn,$expiry_date);
		 $pagerarray['subscription_date']=mysqli_real_escape_string($conn,$previous_date);
		 $pagerarray['subscription_duration']=mysqli_real_escape_string($conn,$subscription_duration);
		 $pagerarray['no_of_managers']=mysqli_real_escape_string($conn,$no_of_managers);
		 $pagerarray['no_of_executives']=mysqli_real_escape_string($conn,$no_of_executives);
		 $pagewererarray['super_admin_id'] = $_GET['edit']; 
		 $result = updateQuery($pagerarray,'tbl_super_admin_details',$pagewererarray);

	}
	if(count($super_admin_module_details)==0){
		foreach($selected_modules as $key=>$val)
		{
			$modules_array['super_admin_id']=mysqli_real_escape_string($conn,$super_admin_id);
			$modules_array['module_id']=mysqli_real_escape_string($conn,$val);
			$modules_array['status']=mysqli_real_escape_string($conn,1);
			$result=insertQuery($modules_array,'tbl_super_admin_subscription_module');
		}
   	}
	else
   	{
		$pagerarray=array(); 
		$pagewererarray=array();  
		$registered_modules=array();
		$pagerarray['status']=mysqli_real_escape_string($conn,0);
		$pagewererarray['super_admin_id'] = $super_admin_id;
		updateQuery($pagerarray,'tbl_super_admin_subscription_module',$pagewererarray);
		foreach($super_admin_module_details as $key=>$val) 
		{			
			$registered_modules[]=$val['module_id'];
 		}
		 
		 foreach($selected_modules as $module)
		 {
			 
			 if(in_array($module,$registered_modules))
			 {	
				$pagerarray['status']=1;
				$pagewererarray['super_admin_id'] = $super_admin_id;
				$pagewererarray['module_id'] = $module; 
				$result = updateQuery($pagerarray,'tbl_super_admin_subscription_module',$pagewererarray);
			 }
			 else
			 {
				$modules_array['status']=mysqli_real_escape_string($conn,1);
				$modules_array['super_admin_id']=mysqli_real_escape_string($conn,$super_admin_id);
				$modules_array['module_id']=mysqli_real_escape_string($conn,$module);
				$result=insertQuery($modules_array,'tbl_super_admin_subscription_module');
			 }
		 }
	}
}

if(!empty($_POST['password-submit'])){
					if(!empty($_POST['new_pass'])){
						if(!empty($_POST['confirm_pass'])){
							if($_POST['new_pass']===$_POST['confirm_pass']){
								$newepass = mysqli_real_escape_string($conn,$_POST['new_pass']);
								$confirmepass = mysqli_real_escape_string($conn,$_POST['confirm_pass']);
								$id = $employeedetails['ID'];
									$newpass = password_hash($newepass,PASSWORD_DEFAULT);
									$insert_sql = "update employee set password = '".$newpass."' where ID = $id";
									if($conn->query($insert_sql)===TRUE){ 
										header("location: employe-list.php?psuccess=Password Changed successfully");
										}else{
											$message .=$conn->error;
										}
									}else{
									$message .="Password mistach";
									}
								}else{
								$message .="please enter the confirm password";	
								}
							}else{	
							$message .="please enter the new password";	
							}	
			}
			

if(!empty($_GET['delete'])){
	$sql = "DELETE FROM employee WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: employe-list.php?success=sasuccess");
   
} else {
    echo "Error deleting record: " . $conn->error;
}
}


$roles = roles();

$superadmins_managers = runloopQuery("SELECT unique_id,concat(fname,' ',lname,'-',(case when role_id = 2 then 'Super Admin' else 'Operations Manager' end)) leadername  FROM employee where role_id in (2,5)  order by ID desc");
$salesmanagers = runloopQuery("SELECT unique_id,concat(fname,' ',lname) leadername FROM employee where role_id = 3 order by ID desc");
$superadmins = runloopQuery("SELECT unique_id,concat(fname,' ',lname) leadername FROM employee where role_id = 2 order by ID desc");
$opsmanagers = runloopQuery("SELECT unique_id,concat(fname,' ',lname) leadername FROM employee where role_id = 5 order by ID desc");


if($employeedetails['role_id'] == '3') {
	$labeltext = 'Super Admin & Managers';
	$leader_primary = $superadmins_managers;
} else if($employeedetails['role_id'] == '4'){
	$labeltext = 'Sales Manager';
	$leader_primary = $salesmanagers;
}
else if($employeedetails['role_id'] == '5'){
	$labeltext = 'Super Admin';
	$leader_primary = $superadmins;
}
else if($employeedetails['role_id'] == '6'){
	$labeltext = 'Operations Managers';
	$leader_primary = $opsmanagers;
}
else {
    $labeltext = '';
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
					    	<h3 class="kt-portlet__head-title">Edit Employee</h3>
					    </div>
			    	</div>
                    <div class="kt-portlet__body">
						   <?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Employee list Added Succussfully
								</div>
								<?php } ?>
								<?php if(!empty($message)){?>
								<div class="alert alert-danger">
								<?=$message?>
								</div>
								<?php } ?>
		 <form  method="post" action="" enctype="multipart/form-data" autocomplete="off" >


						<div class="form-group row">
						    
							<label for="example-text-input"  class="col-3 col-form-label">Role</label>
							<div class="col-3">
	                            <select class="form-control"  name="role_id" id="role_id" onchange="rolebasedisplay()">
									<option value="">Select</option>
									<?php foreach($roles as $roleid => $rolename) { if ($roleid != '1') {?> 
										<option value="<?= $roleid; ?>" <?php if($employeedetails['role_id'] == $roleid) { ?> selected <?php } ?>><?= $rolename; ?>
										</option> 
									<?php } } ?>
								</select>
							</div>
							<label for="example-text-input" id="rolebaselabel" class="col-3 col-form-label"><?= @$labeltext ?? ''; ?></label>
							<div class="col-3" id="rolebaseid" style="display:none">
								<select class="form-control"  name="leader" id="leader">
										<option value="">Select</option>
										<?php for($i=0; $i < count($leader_primary); $i++) { 
											if($employeedetails['unique_id'] != $leader_primary[$i]['unique_id']) {
											?>

										<option value="<?= $leader_primary[$i]['unique_id'] ; ?>" <?php if($employeedetails['leader'] == $leader_primary[$i]['unique_id']) { ?> selected <?php } ?>>
										<?= $leader_primary[$i]['leadername'] ; ?></option>
										<?php } } ?>
								</select>					
							</div>
							</div>
						<div class="form-group row">
						    
                        <label for="example-text-input" class="col-3 col-form-label">Enter FirstName</label>
                        <div class="col-3">
                            <input class="form-control" name="fname" type="text" placeholder="Enter FirstName"  value="<?php echo $employeedetails['fname']; ?>" required>
                        </div>
						<label for="example-text-input" class="col-3 col-form-label">Enter LastName</label>
                        <div class="col-3">
                            <input class="form-control" name="lname" type="text" placeholder="Enter LastName"  value="<?php echo $employeedetails['lname']; ?>" required>
                        </div>
						</div>
						
					<div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Enter Email</label>
                        <div class="col-3">
                            <input class="form-control" type="text" name="email" placeholder="Enter employee email" id="example-text-input"   value="<?php echo $employeedetails['email']; ?>" readonly  >
                        </div>
						<label for="example-text-input" class="col-3 col-form-label">Enter Mobile Number</label>
                        <div class="col-3">
                            <input class="form-control" type="text" name="mobile" placeholder="Enter mobile number"  value="<?php echo $employeedetails['mobile']; ?>" readonly >
                        </div>
                    </div>	 
					
					<div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Enter Bank Details</label>
                        <div class="col-3">
                            <input class="form-control" type="text" name="bankdetails"   value="<?php echo $employeedetails['bankdetails']; ?>" placeholder="Enter Bank Name" id="example-text-input">
                        </div>
                        <div class="col-3">
                            <input class="form-control" type="text" name="accntnum"  value="<?php echo $employeedetails['accntnum']; ?>" placeholder="Enter Account Number" id="example-text-input">
                        </div>
						<div class="col-3">
                            <input class="form-control" type="text" name="ifsccode"  value="<?php echo $employeedetails['ifsccode']; ?>" placeholder="Enter IFSC Code" id="example-text-input">
                        </div>
                    </div>
					
					<div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Upload PAN Card</label>
                        <div class="col-3">
							<div class="custom-file">
                                <input type="file" class="custom-file-input" name="panimg" id="customFile">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
						</div>
						
						<label for="example-text-input" class="col-3 col-form-label">Upload ADHAAR Card</label>
                        <div class="col-3">
							<div class="custom-file">
                                <input type="file" class="custom-file-input" name="adhaarimg" id="customFile">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
						</div>
                    </div>

					<div class="form-group row">
						    
                    
                        <label for="example-text-input" class="col-3 col-form-label">CTC</label>
                        <div class="col-3">
                            <input class="form-control" name="income" type="text" placeholder="Enter CTC"  value="<?php echo $employeedetails['income']; ?>" required>
                        </div>
                        
                        
                        <label for="example-text-input" class="col-3 col-form-label">PAN Number</label>
                        <div class="col-3">
                            <input class="form-control" name="pannum" type="text" placeholder="Enter PAN Number"  value="<?php echo $employeedetails['pannum']; ?>" required>
                        </div>
                        
                        
						</div>
					
					 <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Address</label>
                        <div class="col-11">
						<div class="form-group">
							<textarea class="form-control" name="address" id="exampleTextarea" rows="3" required><?php echo $employeedetails['address']; ?></textarea>
						</div>

                        </div>
                    
                       <div class="col-3">
                             <button type="submit" name="submit" value="submit" class="btn btn-brand btn-elevate btn-pill">Update Employee</button>
						</div>
                    </div>  
					</form>
						
						
						<form  method="post" action="" enctype="multipart/form-data" autocomplete="off" >
						<div class="form-group row">
						    
                        <label for="example-text-input" class="col-3 col-form-label">Password</label>
                        <div class="col-3">
                            <input class="form-control" name="new_pass" type="password" placeholder="Enter Password" >
                        </div>
						</div>
					 
						<div class="form-group row">
						    
                        <label for="example-text-input" class="col-3 col-form-label">Confirm password</label>
                        <div class="col-3">
                            <input class="form-control" name="confirm_pass" type="password" placeholder="Enter Leader employee id" >
                        </div>
						</div>
					 
					 <div class="form-group row"> 
                    
                       <div class="col-3">
                             <button type="submit" name="password-submit" value="submit" class="btn btn-brand btn-elevate btn-pill">Update Password</button>
						</div>
                    </div>  
					</form>

					<?php $modules = runloopQuery("SELECT * FROM tbl_modules  order by ID desc"); 
						//print_r($modules);
						 ?>

					<?php if($employeedetails['role_id'] ==2) { ?>							
					<form  method="post" id="super_admin_details_form" action="" enctype="multipart/form-data" autocomplete="off" >
					<input class="form-control" min="0" value="<?php echo $_GET['edit']; ?>" required name="super_admin_id" type="hidden" placeholder="" >
						<div class="form-group row">
							
							<label for="example-text-input" class="col-3 col-form-label">Subscription Duration (months)</label>
							<div class="col-3">
								<input class="form-control" value="<?php if(count($super_admin_details)>0) echo $super_admin_details['subscription_duration'] ?>"  min="0" required name="subscription_duration" type="number" placeholder="" >
							</div>
							<label for="example-text-input" class="col-3 col-form-label">No of Active Managers</label>
							<div class="col-3">
								<input class="form-control" value="<?php if(count($super_admin_details)>0) echo $super_admin_details['no_of_managers'] ?>" name="no_of_managers" required min="0" type="number" placeholder="" >
							</div>
						</div>
						<div class="form-group row">
							<label for="example-text-input" class="col-3 col-form-label">No of Active Executives</label>
							<div class="col-3">
								<input class="form-control" value="<?php if(count($super_admin_details)>0) echo $super_admin_details['no_of_executives'] ?>" name="no_of_executives" required min="0" type="number" placeholder="" >
							</div>				
							<label for="example-text-input" class="col-3 col-form-label">Subscription Modules</label>
							<div class="col-3">

							<?php $selected_modules=array();
								$active_super_admin_module_details= runloopQuery("select * from tbl_super_admin_subscription_module where status=1 and  super_admin_id = '".$_GET['edit']."'");

								   foreach($active_super_admin_module_details as $key=>$val) { 
										$selected_modules[]=$val['module_id'];
							 } ?>

							<?php foreach($modules as $key=>$val){ ?>										
								<input <?php if(count($selected_modules)>0 && in_array($val['ID'],$selected_modules)) echo 'checked'; ?> value="<?php echo $val['ID']?>"  name="selected_modules[]" id="<?php echo $val['module_name'].'_'.$val['ID'] ; ?>" type="checkbox"> 
								<label for="<?php echo $val['module_name'].'_'.$val['ID'] ; ?>"> <?php echo $val['module_name'] ?></label> &nbsp;
							<?php } ?>

							<input type="hidden" value="<?php echo  count($super_admin_details)>0?'1' : '0' ?>" name="super_admin_details" />

							<input type="hidden" value="<?php echo  count($super_admin_module_details)>0?'1' : '0' ?>" name="super_admin_module_details" />

							<input type="hidden" value="<?php if(count($super_admin_details)>0) echo $super_admin_details['subscription_date'] ?>" name="subscription_date" />




							</div>			
						</div>
						<div class="form-group row">                    
							<div class="col-3">
									<button type="submit" name="super_admin_details_submit" value="submit" class="btn btn-brand btn-elevate btn-pill">Update Super admin details</button>
							</div>
						</div> 
					</form>

					<?php } ?>
					
                         		
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
			//$("#kt_table_1").datatable();
			$(function() {
				$('#super_admin_details_form').submit(function(e)
					{
						var cbx_group = $("input:checkbox[name='selected_modules[]']:checked");
						console.log('checked_modules'+cbx_group.length);
						if(cbx_group.length<=0){
							e.preventDefault();
							alert("You need to select at least one module");
							return false;
						}
				});
			});

			// $(document).ready(function() 
			// {
				
			// 		$cbx_group = $("input:checkbox[name='selected_modules[]']");
			// 		//$cbx_group = $("input:checkbox[id^='option-']"); // name is not always helpful ;)

			// 		$cbx_group.prop('required', true);
			// 		if($cbx_group.is(":checked")){
			// 		$cbx_group.prop('required', false);
			// 	}
			// });
			

	function rolebasedisplay()
	{
		$("#rolebaselabel").html('');
		var unique_id = '<?= $employeedetails['unique_id'] ; ?>';

		var role_id = $("#role_id").val();
		if(role_id == '3') {
			$("#rolebaseid").show();
			var leaders = '<?= json_encode($superadmins) ; ?>';
			var label = 'Super Admin & Managers';
		}
		else if(role_id == '4') {
			$("#rolebaseid").show();
			var leaders = '<?= json_encode($managers) ; ?>';
			var label = 'Managers';
		}
		else {
			$("#rolebaseid").hide();
			return false;
		}
		$("#rolebaselabel").html(label);
		var leaderarray =JSON.parse(leaders);
		$("#leader").html('');
		$("#leader").append(`<option value = ''>Select</option>`);
		for(var i=0;i<leaderarray.length; i++) { 
			if(unique_id != leaderarray[i]['unique_id']) {
			$("#leader").append(`<option value = "${leaderarray[i]['unique_id']}">${leaderarray[i]['leadername']}</option>`)
			}
		}
	}
	</script>

	</body>

	<!-- end::Body -->
</html>