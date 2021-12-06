<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_adminid(); 
$usedetails = runQuery("select * from employee where ID = '".$userid."'");
if(empty($userid)){

	header("Location: index.php");

}

$message = '';

	if(!empty($_FILES["profilepic"]['name'])){
  if (!file_exists('../executiveimage')) {	
		mkdir('../executiveimage', 0777, true);	
		}
	    $target_dir = '../executiveimage/';									
		$file = $_FILES["profilepic"]['name'];

		$imageFileType = strtolower(pathinfo($file,PATHINFO_EXTENSION));								

		$file = $usedetails['ID'].'.'.$imageFileType;				
		$target_file = $target_dir . strtolower($file);		

		$uploadOk = 1;

		if($imageFileType != "jpg" && $imageFileType != "png"  && $imageFileType != "jpeg" && $imageFileType != "gif" )

			{
		$message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
		$uploadOk = 0;						
		}
	    if ($uploadOk == 0) {			
		$message .= "Sorry, your file was not uploaded.";		
		} else {
			if (move_uploaded_file($_FILES["profilepic"]["tmp_name"], $target_file)){				
				$m3avenuearray['profilepic'] = strtolower($file);
				$m3avenuewherearray['ID'] = $usedetails['ID'];
				$result= updateQuery($m3avenuearray,'employee',$m3avenuewherearray);
				$message .= "Profile Pic Update Successfully.";	
			} else {
				$message .= "Sorry, There Was an Error Uploading Your File.";			
				}
			}
	}
			
if(!empty($_POST['password-submit']) && !empty($_POST['pwdupdate'])  ){
	if(!empty($_POST['new_pass'])){
		if(!empty($_POST['confirm_pass'])){
			if($_POST['new_pass']===$_POST['confirm_pass']){
				$newepass = mysqli_real_escape_string($conn,$_POST['new_pass']);
				$confirmepass = mysqli_real_escape_string($conn,$_POST['confirm_pass']); 
					$newpass = password_hash($newepass,PASSWORD_DEFAULT);
					$insert_sql = "update employee set password = '".$newpass."' where ID = $userid";
					if($conn->query($insert_sql)===TRUE){ 
						header("location: profile.php?password=Password Changed successfully");
						}else{
							$loginmessage .=$conn->error;
						}
					}else{
					$loginmessage .="Password mistach";
					}
				}else{
				$loginmessage .="please enter the confirm password";	
				}
			}else{	
			$loginmessage .="please enter the new password";	
			}	
		}

?>
<!DOCTYPE html>

<html lang="en">
 
	<head>
 
		<!--end::Base Path -->
		<meta charset="utf-8" />
		<title>M3  | Profile</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />

		<?php include('headerscripts.php');?>
		<style>

.user-bg .overlay-box {
    
   
    text-align: center;
}
.user-btm-box{padding:40px 0 10px;clear:both;overflow:hidden}
.backgrndbox {
	background: #e8e8e8;
    opacity: .9;
}
.img-circle{width:100px;margin-right:10px}
</style>	
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
								<h3 class="kt-portlet__head-title">Profile</h3>
								</div> 
							</div>
						</div>
						<br>
 
		<div class="kt-portlet__body">
						<!--begin: Datatable -->
						<div class="row">
							

						<div class="col-md-4 col-xs-12">
                        <div class="white-box">
                            <div class="user-bg">
                                <div class="overlay-box">
                                    <div class="user-content ">
                                        <a href="javascript:void(0)"><img src="<?php echo !empty($usedetails['profilepic']) ? EMPLOYEE_IMAGE.$usedetails['profilepic'] : 'img/m3-logo.png' ;?>" class="thumb-lg img-circle" alt="img"></a>
                                        <h4 class="text-black"> <?php echo ucwords($usedetails['fname'].' '.$usedetails['lname']);?></h4>
                                        <h5 class="text-black">User Id : <?php echo $usedetails['unique_id'];?></h5> 

                                        </div>
										<div class="col-md-12 col-sm-6 text-left mt-5">
									<h5 class="text-black"><span>Call: </span>  <?php echo $usedetails['mobile'];?></h5>
								</div>

								<div class="col-md-12 col-sm-6 text-left">
									<h5 class="text-black"><span>mail: </span>  <?php echo $usedetails['email'];?></h5>
								</div>	
                                </div>
                            </div>

						</div>
							<div class="white-box">
                            <div class="user-btm-box">
						<?php if(!empty($_GET['password'])){ ?>
						<div class="alert alert-success">
						Password Updated Successfully
						</div>
						<?php } ?>
						<h3>Change Password</h3>
							<form  method="post" action="" >
									<div class="col-md-12">
									<div class="form-group">
											<label>Password</label>
											<input type="password" name="new_pass"  class="form-control form-control-line"> 
											</div> 
									<div class="form-group">
										<label >Confirm password</label> 
											<input type="password" name="confirm_pass" class="form-control form-control-line">
											<input type="hidden" name="pwdupdate" value="1"> 
										</div>
									<div class="form-group"> 
											<button name="password-submit" value="submit" class="btn btn-success">Submit</button>
										</div>
									</div>
								</form>
							</div>
							</div>
							</div>

							<div class="col-md-8">
							<h3>Profile</h3>
							<form  method="post" action="" enctype="multipart/form-data">
									<div class="col-md-12">
										<div class="form-group">
										<div class="custom-file">
										<input type="file" class="custom-file-input" name="profilepic" id="customFileLang" lang="es">
										<label class="custom-file-label" for="customFileLang">Select </label>
										</div> 
									</div> 

									<div class="form-group"> 
											<button name="password-submit" value="submit" class="btn btn-success">Submit</button>
										</div>
									</div>
								</form>
							</div>
						</div>
						<!-- end row -->
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
	  


  <script>	
	

	 </script>
	</body>

	<!-- end::Body -->
</html>