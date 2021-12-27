<?php

session_start();

error_reporting(E_ALL);


include('../functions.php');

if(!isset($_GET['filter_by_role'])){
	$filter_by_role='';
}else{
	$filter_by_role=$_GET['filter_by_role'];
}

$userid = current_adminid(); 

if(empty($userid)){

	header("Location: index.php");

}

$message = '';

if(!empty($_POST['fname']))
{
	$pagerarray  = array();

	if (!file_exists('empimage')) {	
		mkdir('empimage', 0777, true);	
	}
	$target_dir = 'empimage/';									

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
				//adhaarimg
	if (!file_exists('empimage')) {	
		mkdir('empimage', 0777, true);	
	}
	$target_dir = 'empimage/';									

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
	$uniqueusers = (int)runQuery("select max(ID) as id from employee order by ID desc")['id'];
	$newuniquid = $uniqueusers+1;

	if($_POST['role_id'] == 2)
	{
		$unique_prefix = strtoupper(substr($_POST['fname'],0,2));
	}
	else{
		$sqlleader  = runQuery("select * from employee where unique_id = '".$_POST['leader']."' limit 1");
		$unique_prefix = strtoupper(substr($sqlleader['unique_id'],0,2));
	}

	$address=mysqli_real_escape_string($conn,$_POST['address']);
	$landmark=mysqli_real_escape_string($conn,$_POST['landmark']);
	$city=mysqli_real_escape_string($conn,$_POST['city']);
	$state=mysqli_real_escape_string($conn,$_POST['state']);
	$pincode=mysqli_real_escape_string($conn,$_POST['pincode']);

	$joining_date= mysqli_real_escape_string($conn,$_POST['join_date']);
	$pagerarray['role_id'] = mysqli_real_escape_string($conn,$_POST['role_id']) ?? 2;
	$pagerarray['leader'] = mysqli_real_escape_string($conn,$_POST['leader']);
	$pagerarray['fname'] = mysqli_real_escape_string($conn,$_POST['fname']);
	$pagerarray['lname'] = mysqli_real_escape_string($conn,$_POST['lname']);
	$pagerarray['unique_id'] = $unique_prefix.sprintf('%05d',$newuniquid);
	$pagerarray['email'] = mysqli_real_escape_string($conn,$_POST['email']);
	$pagerarray['mobile'] = mysqli_real_escape_string($conn,$_POST['mobile']);
	$pagerarray['password'] = password_hash(trim($_POST['password']),PASSWORD_DEFAULT);
	$pagerarray['bankdetails'] = mysqli_real_escape_string($conn,$_POST['bankdetails']);
	$pagerarray['accntnum'] = mysqli_real_escape_string($conn,$_POST['accntnum']);
	$pagerarray['ifsccode'] = mysqli_real_escape_string($conn,$_POST['ifsccode']);
	$pagerarray['address'] = $address;
	$pagerarray['landmark'] = $landmark;
	$pagerarray['city'] = $city;
	$pagerarray['state'] = $state;
	$pagerarray['pincode'] = $pincode;
	$pagerarray['income'] = mysqli_real_escape_string($conn,$_POST['income']);
	$pagerarray['pannum'] = mysqli_real_escape_string($conn,$_POST['pannum']);
	$pagerarray['payment_type'] = mysqli_real_escape_string($conn,$_POST['payment']);
	$pagerarray['joining_date'] =date("d-m-Y", strtotime($joining_date));
	$pagerarray['designation'] = mysqli_real_escape_string($conn,$_POST['designation']);
	$pagerarray['location'] = mysqli_real_escape_string($conn,$_POST['location']);
	$pagerarray['status'] = '0';
	$result = insertQuery($pagerarray,'employee');
	if(!$result){
		header("Location: employe-list.php?success=success");
	}

	} 
	else{

			//$message .=" First Name Field is Empty";

	}


if(!empty($_POST['dailerstatusid'])){
	$sql = "update  employee dialer_status = ".$_POST['dailerstatusid']."  WHERE ID=".$_POST['id']."";
	echo $sql;exit; 
	if ($conn->query($sql) === TRUE) {

		header("Location: employe-list.php?success=success");

	} else {
		echo "Error deleting record: " . $conn->error;
	}

}

if(!empty($_GET['delete'])){
	$sql = "DELETE FROM employee WHERE ID=".$_GET['delete']."";

	if ($conn->query($sql) === TRUE) {

		header("Location: employe-list.php?success=success");

	} else {
		echo "Error deleting record: " . $conn->error;
	}
}
$roles = roles();

$superadmins_managers = runloopQuery("SELECT unique_id,concat(fname,' ',lname,'-',(case when role_id = 2 then 'Super Admin' else 'Operations Manager' end)) leadername  FROM employee where role_id in (2,5)  order by ID desc");
$salesmanagers = runloopQuery("SELECT unique_id,concat(fname,' ',lname) leadername FROM employee where role_id = 3 order by ID desc");
$superadmins = runloopQuery("SELECT unique_id,concat(fname,' ',lname) leadername FROM employee where role_id = 2 order by ID desc");
$opsmanagers = runloopQuery("SELECT unique_id,concat(fname,' ',lname) leadername FROM employee where role_id = 5 order by ID desc");

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
							<div class="kt-portlet__head-label col-lg-3 col-md-4 col-sm-4 col-xs-12">
								<h3 class="kt-portlet__head-title">Employee List</h3>
							</div>
							<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
								<!-- <a href="add_super_admin.php"><button type="button" class="btn  btn btn-primary  mt-2" style="float:right" data-toggle="modal" data-target="#exampleModalScrollable">Add Super Admin</button></a> -->   
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

								<button type="button" class="btn  btn btn-primary  mt-2" style="float:right" data-toggle="modal" data-target="#exampleModalScrollable">Add Employee</button>


							</div>
							<div class="kt-portlet kt-portlet--mobile">


								<div class="kt-portlet__body">
									<?php if(!empty($_GET['success'])){?>
										<div class="alert alert-success">
											Employe-list Added Succussfully
										</div>
									<?php } ?>
									<?php if(!empty($_GET['osuccess'])){?>
										<div class="alert alert-success">
											Offer Letter Generated Succussfully
										</div>
									<?php } ?>
									<?php if(!empty($_GET['sasuccess'])){?>
										<div class="alert alert-success">
											Super Admin Details Added/Updated Successfully.
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

									<div class="kt-portlet kt-portlet--mobile"> 
										<div class="kt-portlet__head">
											<div class="kt-portlet__head-label">
												<form method="get" action="" id="filterForm">
													<div class="form-group row">


														<div class="col-7">
															<select class="form-control"  name="filter_by_role" id="filter_by_role" >
																<option value="">Filter By Role</option>
																<?php 
																foreach($roles as $roleid => $rolename) { if ($roleid != '1') {?> 
																	<option value="<?= $roleid; ?>" <?php if($filter_by_role!=''){$selectedRole=$filter_by_role; if($selectedRole==$roleid){ echo "selected";} } ?> ><?= $rolename; ?></option> 
																<?php } } ?>
															</select>
														</div>

														<div class="col-3 ml-3" style="display: flex;  justify-content: center;" >

															<a href="download-csv.php?table=employee&filter_by_role=<?php echo $filter_by_role;?>" class="btn btn-primary pull-right" >Download</a> 
														</div>

													</div>

												</form> 

											</div>
										</div>     
										<div class="kt-portlet__body">
											<div class="table table-responsive">

												<!--begin: Datatable -->
												<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
													<thead>
														<tr>
															<th>S.No</th>
															<th>EMP Id & Name</th>
															<th>Role</th>
															<th>Reporting Manager</th>
															<th>Mobile number</th>
															<th>Bank details</th>
															<th>Pan Card</th>
															<th>Adhaar Card</th>
															<th>Address</th>
															<th>Reg date</th>
															<th>Status</th>
															<th>Offer Letter</th>
															<th>Action</th>
															<th>Dailer Status</th>
														</tr>

													</thead>
													<tbody>
														<?php
														if($filter_by_role!=''){
															$sql = runloopQuery("SELECT * FROM employee where role_id = '".$filter_by_role."'  order by ID desc");
														}else{
															$sql = runloopQuery("SELECT * FROM employee order by ID desc");
														}
														$x=1;  foreach($sql as $row)
														{
															$path = '../offerletter/Offer_letters/'.$row["unique_id"].'_offerletter.pdf';
															$leader=$row["leader"]; 

															?>
															<tr>
																<td><?php echo  $x;?></td>
																<td><?php echo $row["unique_id"];?><br/><?php echo $row["fname"];?> <?php echo $row["lname"];?><br/><?php echo $row["email"];?></td>

																<td><?php echo $row['role_id'] ? roles($row['role_id']) : 'No Role Assigned'; ?></td>
																<?php  if(!empty($leader)) { 
																	$leader_data= runQuery("select * from employee where unique_id = '".$leader."' limit 1"); ?>
																<?php } ?>
																<?php if($leader!=''){ 	?>
																	<td><?php echo $leader_data["unique_id"];?><br/><?php echo $leader_data["fname"];?> <?php echo $leader_data["lname"];?><br/></td>
																<?php } else { ?>
																	<td></td>
																<?php } ?>
																<td><?php echo $row["mobile"];?></td>
																<td><?php echo $row["bankdetails"];?>
																<br/><?php echo $row["accntnum"];?>
																<br/><?php echo $row["ifsccode"];?></td>
																<td><?php $panimgpath="empimage/".$row["panimg"];if(file_exists($panimgpath)){?><a href="empimage/<?php echo $row["panimg"];?>" class="html5lightbox" >View</a><?php }else{ echo "File not found";}?></td>
																<td><?php $aadharpath="empimage/".$row["adhaarimg"];if(file_exists($aadharpath)){?><a href="empimage/<?php echo $row["adhaarimg"];?>" class="html5lightbox" >View</a><?php } else{ echo "File not found";}?></td> 
																<td><?php echo $row["address"];?></td>
																<td><?php echo reg_date($row["reg_date"]);?></td>
																<td><?php echo ($row["status"]  == 1 ? 'Active' : 'Inactive');?></td>
																<td>	<?php if(file_exists($path)){
																	echo "Generated";

																	?>

																<?php } else { ?>
																	<a class="btn btn-info" href="../offerletter/offerletter.php?id=<?php echo $row['ID']?>&path=admin">Generate</a>
																<?php } ?>
																<br>
																<?php echo "Requested By:".lead_details($row["leader"],'fname'); 
																if(file_exists($path)){ ?>
																	<a class="btn btn-info" target="_blank" href="../offerletter/Offer_letters/<?php echo $row["unique_id"]?>_offerletter.pdf">Download</a> 
																<?php }?>
															</td>
															<td>
																<?php if($row["role_id"]==2) { ?>
																	<a href="edit_super_admin.php?edit=<?php echo $row["ID"];?>">Edit</a>

																<?php } else { ?>
																<a href="edit-employee-list.php?edit=<?php echo $row["ID"];?>">Edit</a> <?php } ?> | <a onclick="return confirm('Are you sure want to delete??');"  href="?delete=<?php echo $row["ID"];?>">Delete</a>

															</td>
															</td>
															<td>
																<label class="switch">
																	<input type="checkbox" id="dailerstatusid"  <?php if($row['dialer_status']=='1'){ echo 'checked';}?> onChange="changedialerstatus('dailerstatus',<?php echo $row['ID'];?>);">
																	<span class="slider round"></span>
																</label>


															</td>
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

				<!-- Modal -->


				<!-- Modal -->
				<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
					<div class="modal-dialog modal-dialog-scrollable h-75 w-100 modal-lg"  role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalScrollableTitle">Add Employee</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form method="post" action="" enctype="multipart/form-data" id="addempform" autocomplete="off">
									<div class="row">
										<div class="col-6">    
											<label for="example-text-input" class="font-weight-bold">Role</label>
											<select class="form-control"  name="role_id" id="role_id" onchange="rolebasedisplay()" required>
												<option value="">Select</option>
												<?php foreach($roles as $roleid => $rolename) { if ($roleid != '1') {?> 
													<option value="<?= $roleid; ?>"><?= $rolename; ?></option> 
												<?php } } ?>
											</select>
										</div>
										<div class="col-6" id="rolebaseid" style="display:none">
											<label for="example-text-input" id="rolebaselabel" class="font-weight-bold"></label>
											<!-- <div class="col-4"  > -->

												<select class="form-control"  name="leader" id="leader">

												</select>

												<!-- </div> -->
											</div>

										</div>
										<div class="row mt-2">
											<div class="col">
												<label class="font-weight-bold">Enter First Name</label>
												<input class="form-control" name="fname" type="text" placeholder="Enter First Name" id="fname" required>
											</div>
											<div class="col">
												<label class="font-weight-bold">Enter Last Name</label>
												<input class="form-control" name="lname" type="text" placeholder="Enter Last Name" id="lname" required>
											</div>
										</div>
										<div class="row mt-2">
											<div class="col">
												<label class="font-weight-bold">Enter Email</label>
												<input class="form-control" type="text" name="email" placeholder="Enter employee email" id="email" required>    			</div>
												<div class="col">
													<label class="font-weight-bold">Enter Mobile Number</label>
													<input type="text" class="form-control form-control-line"  onkeypress="return isNumberKey(event)" placeholder="Mobile No"  name="mobile" value=""  required maxlength="10" pattern="\d{10}" id="mobile" >
												</div>
											</div>
											<div class="row mt-2">
												<div class="col">
													<label class="font-weight-bold">Password</label>
													<input class="form-control" type="password" name="password" placeholder="Enter Password" id="password" required>
												</div>
												<div class="col">
													<label class="font-weight-bold">Address</label>
													<input type="text" class="form-control" placeholder="Enter House No and Street name" id="house_no" name="address" required>
												</div>
											</div>
											<div class="row mt-2">
												<div class="col">
													<label class="font-weight-bold">Landmark</label>
													<input type="text" class="form-control" placeholder="Enter Landmark" id="landmark" name="landmark" required>
												</div>
												<div class="col">
													<label class="font-weight-bold">City</label>
													<input type="text" class="form-control" placeholder="Enter City" id="city" name="city" required>
												</div>
											</div>
											<div class="row mt-2">
												<div class="col">
													<label class="font-weight-bold">State</label>
													<input type="text" class="form-control" placeholder="Enter State" name="state" required id="state">
												</div>
												<div class="col">
													<label class="font-weight-bold">Pin code</label>
													<input type="text" class="form-control" placeholder="Enter pincode" id="pincode" name="pincode" required>
												</div>
											</div>
											<div class="row mt-2"> 
												<div class="col">
													<label for="example-text-input" class="font-weight-bold">Joining Date</label>
													<input class="form-control" type="date" name="join_date" id="join_date" value="<?php echo date('Y-m-d'); ?>" required/>
												</div>   
												<div class="col"> 
													<label for="example-text-input" class="font-weight-bold">Payment Type</label>
													<select class="form-select form-control" aria-label="Default select example" name='payment' id="payment" onchange="return paymentType();" required>
														<option value="">Select</option>
														<option value="review_pay">Review Pay</option>
														<option value="variable_pay">Variable Pay</option>
														<option value="royalty_pay">Royalty Pay</option>
													</select>
												</div>
											</div>
											<div class="row mt-2"> 
												<div class="col">	
													<label for="example-text-input" class="font-weight-bold">CTC</label>
													<input type="text" name="income" id="income" placeholder="Enter CTC"  class="form-control" required >
												</div>   						
												<div class="col"> 
													<label for="example-text-input" class="font-weight-bold">PAN Number</label>
													<input class="form-control" type="text" name="pannum" placeholder="Enter PAN Number" id="pannum" required>
												</div>   
											</div>
											<div class="row mt-2"> 
												<div class="col">
													<label for="example-text-input" class="font-weight-bold">Enter Bank Details</label>
													<div class="form-group row">

														<div class="col-md-4">
															<input class="form-control" type="text" name="bankdetails" placeholder="Enter Bank Name" id="bankdetails">
														</div>
														<div class="col-md-4">
															<input class="form-control" type="text" name="accntnum" placeholder="Enter Account Number" id="accntnum">
														</div>
														<div class="col-md-4">
															<input class="form-control" type="text" name="ifsccode" placeholder="Enter IFSC Code" id="ifsccode">
														</div>
													</div>
												</div>
											</div>
											<div class="row mt-2">
												<div class="col"> 
													<label for="example-text-input" class="font-weight-bold">Designation</label>
													<select class="form-select form-control" aria-label="Default select example" name="designation" required id="designation">
														<option value="">Select</option>
														<option value="Telecaller">Telecaller</option>
														<option value="Business Development Associate">Business Development Associate</option>
														<option value="Digital Marketing Executive">Digital Marketing Executive</option>
														<option value="HR Manager">HR Manager</option>
														<option value="Team Leader">Team Leader</option>
													</select>
												</div>
												<div class="col"> 
													<label for="example-text-input" class="font-weight-bold">Location</label>
													<input class="form-control" type="text" name="location"     placeholder="Enter location" id="location" required>
												</div> 
											</div>			
											<div class="row mt-2">
												<div class="col"> 
													<label for="example-text-input" class="font-weight-bold">Upload PAN Card</label>
													<div class="custom-file">
														<input type="file" class="custom-file-input" name="panimg" id="panimg"><label class="custom-file-label" for="customFile">Choose file</label>
													</div>
												</div>
												<div class="col"> 
													<label for="example-text-input" class="font-weight-bold" >Upload ADHAAR Card</label>
													<div class="custom-file">
														<input type="file" class="custom-file-input" name="adhaarimg" id="adhaarimg">
														<label class="custom-file-label" for="customFile">Choose file</label>
													</div>
												</div>
											</div>
										</form>

									</div>
									<div class="modal-footer">
										<button type="submit" name="submit" value="submit" id="addempsubmitid" class="btn btn-primary">Add Employee</button>
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
						</div>
						<script>
							function rolebasedisplay()
							{
								$("#rolebaselabel").html('');

								var role_id = $("#role_id").val();
								if(role_id == '3') {
									$("#rolebaseid").show();
									var leaders = '<?= json_encode($superadmins_managers) ; ?>';
									var label = 'Super Admin & Managers';
								}
								else if(role_id == '4') {
									$("#rolebaseid").show();
									var leaders = '<?= json_encode($salesmanagers) ; ?>';
									var label = 'Sales Managers';
								}
								else if(role_id == '5'){
									$("#rolebaseid").show();
									var leaders = '<?= json_encode($superadmins) ; ?>';
									var label = 'Super Admin';
								}
								else if(role_id == '6'){
									$("#rolebaseid").show();
									var leaders = '<?= json_encode($opsmanagers) ; ?>';
									var label = 'Operations Managers';
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