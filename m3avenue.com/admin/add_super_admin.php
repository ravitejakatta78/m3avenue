<?php

session_start();

error_reporting(E_ALL);


include('../functions.php');

$modules = runloopQuery("SELECT * FROM tbl_modules  order by ID desc");

if(!empty($_POST['owner_name']))
{
	$pagerarray  = array();
	$pagerarray_info  = array();
	if (!file_exists('empimage')) {	
		mkdir('empimage', 0777, true);	
	}
	$target_dir = 'empimage/';

	$file = $_FILES["company_logo"]['name'];				
	$target_file = $target_dir . strtolower($file);	

	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);	

	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif")

	{
		$message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
		$uploadOk = 0;						
	}
	if ($uploadOk == 0) {			
		$message .= "Sorry, your file was not uploaded.";		
	} else {
		if (move_uploaded_file($_FILES["company_logo"]["tmp_name"], $target_file)){				
			$pagerarray_info['company_logo'] = strtolower($file);						
		} else {
			$message .= "Sorry, There Was an Error Uploading Your File.";			
		}
	}

	
	$unique_prefix = strtoupper(substr($_POST['owner_name'],0,2));

	$uniqueusers = (int)runQuery("select max(ID) as id from employee order by ID desc")['id'];
	$newuniquid = $uniqueusers+1;

	$pagerarray['fname'] = mysqli_real_escape_string($conn,$_POST['owner_name']);
	$pagerarray['email'] = mysqli_real_escape_string($conn,$_POST['email']);
	$pagerarray['mobile'] = mysqli_real_escape_string($conn,$_POST['mobile_number']);
	$pagerarray['joining_date'] =date("d-m-Y");
	$pagerarray['unique_id'] = $unique_prefix.sprintf('%05d',$newuniquid);
	$pagerarray['status'] = '0';
	$pagerarray['role_id'] = 2;
	$pagerarray['password'] = password_hash(trim($_POST['password']),PASSWORD_DEFAULT);	


	// $pagerarray_info['owner_name']=mysqli_real_escape_string($conn,$_POST['owner_name']);
	// $pagerarray_info['mobile_number']=mysqli_real_escape_string($conn,$_POST['mobile_number']);
	$pagerarray_info['facebook_url']=mysqli_real_escape_string($conn,$_POST['facebook_url']);
	$pagerarray_info['youtube_url']=mysqli_real_escape_string($conn,$_POST['youtube_url']);
	$pagerarray_info['instagram_url']=mysqli_real_escape_string($conn,$_POST['instagram_url']);
	$pagerarray_info['linkedin_url']=mysqli_real_escape_string($conn,$_POST['linkedin_url']);
	

	$pagerarray_info['company_type']=mysqli_real_escape_string($conn,$_POST['company_type']);
	$pagerarray_info['company_name']=mysqli_real_escape_string($conn,$_POST['company_name']);
	$pagerarray_info['website']=mysqli_real_escape_string($conn,$_POST['website']);
	$pagerarray_info['address']=mysqli_real_escape_string($conn,$_POST['address']);
	$pagerarray_info['contact_email']=mysqli_real_escape_string($conn,$_POST['contact_email']);
	$pagerarray_info['contact_mobile_number']=mysqli_real_escape_string($conn,$_POST['contact_mobile_number']);


	$pagerarray_info['subscription_date']=date("Y-m-d");
	$pagerarray_info['subscription_duration']=mysqli_real_escape_string($conn,$_POST['subscription_duration']);
	$pagerarray_info['no_of_active_managers']=mysqli_real_escape_string($conn,$_POST['no_of_active_managers']);
	$pagerarray_info['no_of_active_executives']=mysqli_real_escape_string($conn,$_POST['no_of_active_executives']);
	$selected_modules=implode(",",$_POST['selected_modules']);
	$pagerarray_info['selected_modules']=mysqli_real_escape_string($conn,$selected_modules);


	$result_id = insertIDQuery($pagerarray,'employee');

	$pagerarray_info['super_admin_id']=mysqli_real_escape_string($conn,$result_id);

	$result = insertQuery($pagerarray_info,'tbl_super_admin_details ');

	if(!$result || !$result_id)
	{
		header("Location: superadmins.php?success=success");
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
						<div class="kt-portlet__head row">
							

							<div class="kt-portlet kt-portlet--mobile">
								<div class="kt-portlet__head">
										<div class="kt-portlet__head-label">
											<div class="col-12">
											<h3 class="kt-portlet__head-title">Add Super Admin</h3>
											</div> 
										</div>
									</div>
									<br>

							<div class="kt-portlet__body">
							<form method="post" action="" enctype="multipart/form-data" id="addempform" autocomplete="off">


									<div class="row mt-2">

										<div class="col">
											<label class="font-weight-bold">Owner Name</label>
											<input class="form-control"required name="owner_name" type="text">
										</div>
										<div class="col">
											<label class="font-weight-bold">Mobile Number</label>
											<input class="form-control" maxlength="10" required name="mobile_number" type="text">
										</div>
									</div>
									<div class="row mt-2">
										<div class="col">
											<label class="font-weight-bold">Email Id</label>
											<input class="form-control"required name="email" type="text">
										</div>
										<div class="col">
											<label class="font-weight-bold">Password</label>
											<input class="form-control"required name="password" type="password">
										</div>
									</div>
									<div class="row mt-2">
										<div class="col">
											<label class="font-weight-bold">Company Type</label>
											<input class="form-control"required name="company_type" type="text">
										</div>
										<div class="col">
											<label class="font-weight-bold">Company Name</label>
											<input class="form-control"required name="company_name" type="text">
										</div>
									</div>
									<div class="row mt-2">
										<div class="col">
											<label class="font-weight-bold">Company Logo</label>
											<input class="form-control" required name="company_logo" type="file">
										</div>
										<div class="col">
											<label class="font-weight-bold">Social Media</label>
											<input class="form-control"  name="social_media_urls" type="text">
										</div>
									</div>
									<br>

									<h5>Social Media:</h5>
											
									<div class="row mt-2">

										<div class="col">
											
											<label class="font-weight-bold">Facbook</label>
											<input class="form-control"  min="0" required name="facebook_url" type="text" placeholder="" >

										</div>

										<div class="col">
											<label  class="font-weight-bold">Instagram</label>
												<input class="form-control" value="" name="instagram_url" required min="0" type="text" placeholder="" >
										</div>
										
									</div>
									<div class="row mt-2">

										<div class="col">
											
											<label class="font-weight-bold">YouTube</label>
											<input class="form-control"  min="0" required name="youtube_url" type="text" placeholder="" >

										</div>

										<div class="col">
											<label  class="font-weight-bold">LinkedIn</label>
												<input class="form-control" value="" name="linkedin_url" required min="0" type="text" placeholder="" >
										</div>
										
									</div>
									<br>

									

									<h5>Subscription:</h5>
											
									<div class="row mt-2">

										<div class="col">
											
											<label class="font-weight-bold">Subscription Duration (months)</label>
											<input class="form-control"  min="0" required name="subscription_duration" type="number" placeholder="" >

										</div>

										<div class="col">
											<label  class="font-weight-bold">No Of Active Managers</label>
												<input class="form-control" value="" name="no_of_active_managers" required min="0" type="number" placeholder="" >
										</div>
										
									</div>

									<div class="row mt-2">
										<div class="col-6">
											<label  class="font-weight-bold">No Of Active Executives</label>
												<input class="form-control" value="" name="no_of_active_executives" required min="0" type="number" placeholder="" >
										</div>
									</div>

									<br>
									<h5>Contact Us:</h5>

									<div class="row mt-2">

										<div class="col">
											<label class="font-weight-bold">Website</label>
											<input class="form-control"required name="website" type="text">
										</div>
										<div class="col">
											<label class="font-weight-bold">Address</label>
											<input class="form-control" required name="address" type="text">
										</div>
									</div>

									<div class="row mt-2">

										<div class="col">
											<label class="font-weight-bold">Contact Email</label>
											<input class="form-control"required name="contact_email" type="text">
										</div>
										<div class="col">
											<label class="font-weight-bold">Contact Mobile Number</label>
											<input class="form-control" maxlength="10" required name="contact_mobile_number" type="text">
										</div>
									</div>
									<br>
									<h5>Features to be selected:</h5>		

									<div class="col">
										<?php foreach($modules as $key=>$val){ ?>										
											<input value="<?php echo $val['ID']?>"  name="selected_modules[]" id="<?php echo $val['module_name'].'_'.$val['ID'] ; ?>" type="checkbox"> 
											<label for="<?php echo $val['module_name'].'_'.$val['ID'] ; ?>"> <?php echo $val['module_name'] ?></label> &nbsp;
										<?php } ?>
									</div>
									<br>

									<div class="form-group row">                    
										<div class="col-3">
												<button type="submit" name="super_admin_details_submit" value="submit" class="btn btn-brand btn-elevate btn-pill">Submit</button>
										</div>
									</div>
									
										</div>
									</form>

									<!-- end:: Wrapper -->
								</div>

								<!-- end:: Page -->

							</div>

							</div>

							<!-- end:: Root -->


							<?php include("footerscripts.php");?>

							<!-- Modal -->

							<script>

								$(function() {
									$('#addempform').submit(function(e)
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



								function rolebasedisplay()
								{
									$("#rolebaselabel").html('');

									var role_id = $("#role_id").val();
									if(role_id == '3') {
										$("#rolebaseid").show();
										var leaders = '<?= json_encode($superadmins) ; ?>';
										var label = 'Super Admin';
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
										$("#leader").append(`<option value = "${leaderarray[i]['unique_id']}">${leaderarray[i]['leadername']}</option>`)
									}
								}
								function changedialerstatus(name,id){

									$.ajax({
										type: 'post',
										url: 'ajax/ajax.php',
										data: {
											conditionname: name,
											tableid: id
										},
										success: function(response) { 
										}
									});

								}

								function paymentType()
								{
									var   payment  = $("#payment").val();
									var   income  = $("#income").val();
									if(payment=='royalty_pay'){
				$('#income').attr('readonly', true); // mark it as read only
				$('#income').css('background-color' , '#DEDEDE');
				$("#income").css('border-color', '#e4e7ea');
				$('#income').prop('required',false);
			}else{
				$('#income').attr('readonly', false);
				$("#income").css('border-color', 'red');
				$('#income').prop('required',true);
				$('#income').css('background-color' , '');
			}
		}

		$("#addempsubmitid").click(function(){
			var user_input_value;
			var err_value = 0
			$('#addempform').find('input,select,select2').each(function(){
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
			$('#addempform').find('input[type=file]').each(function(){

				if($('#'+this.id)[0].files.length === 0){
					err_value = err_value + 1;
					alert("Please Upload File");
				//$("#"+this.id).css('border','1px solid red','padding','2px');
			}
			else{
				var fileInput = document.getElementById(this.id);
				var filePath = fileInput.value;
				// Allowing file type
				var allowedExtensions = 
				/(\.jpg|\.jpeg|\.png|\.gif)$/i;
				if (!allowedExtensions.exec(filePath)) {
					err_value = err_value + 1;
					alert('Invalid file type');
					$("#"+this.id).css('border','1px solid red','padding','2px');
					fileInput.value = '';
					
				}
				else{
					$("#"+this.id).css('border','1px solid black','padding','2px');
				}

			}
		});	
			if(err_value == 0)
			{
				$("#addempsubmitid").hide();
				$("#addempform").submit();	
			}

		});

		$("#filter_by_role").change(function(){
			$("#filterForm").submit();
		});
	</script>
</body>

<!-- end::Body -->
</html>