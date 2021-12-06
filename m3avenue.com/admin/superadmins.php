<?php

session_start();

error_reporting(E_ALL);


include('../functions.php');



$userid = current_adminid(); 

if(empty($userid)){

	header("Location: index.php");

}

$message = '';




if(!empty($_GET['delete'])){
	$sql = "DELETE FROM employee WHERE ID=".$_GET['delete']."";

	if ($conn->query($sql) === TRUE) {

		header("Location: employe-list.php?success=success");

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
						<div class="kt-portlet__head row">
							<div class="kt-portlet__head-label col-lg-3 col-md-4 col-sm-4 col-xs-12">
								<h3 class="kt-portlet__head-title">SuperAdmins</h3>
							</div>
							<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
								<a href="add_super_admin.php"><button type="button" class="btn  btn btn-primary  mt-2" style="float:right">Add Super Admin</button></a>   
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



							</div>
							<div class="kt-portlet kt-portlet--mobile">


								<div class="kt-portlet__body">
									<?php if(!empty($_GET['success'])){?>
										<div class="alert alert-success">
											SuperAdmin Added Successfully.
										</div>
									<?php } ?>
									<?php if(!empty($_GET['osuccess'])){?>
										<div class="alert alert-success">
											Offer Letter Generated Successfully
										</div>
									<?php } ?>
									<?php if(!empty($_GET['usuccess'])){?>
										<div class="alert alert-success">
											Super Admin Updated Successfully
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
															<th>Offer Letter</th>
															<th>Action</th>
															<th>Dailer Status</th>
														</tr>

													</thead>
													<tbody>
														<?php
															$sql = runloopQuery("SELECT * FROM employee where role_id = 2  order by ID desc");
														
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
																<td>	<?php if(file_exists($path)){
																	echo "Generated";

																	?>

																<?php } else { ?>
																	<a class="btn btn-info" href="../offerletter/offerletter.php?id=<?php echo $row['ID']?>">Generate</a>
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
													<label class="font-weight-bold">House No</label>
													<input type="text" class="form-control" placeholder="Enter House No" id="house_no" name="house_no" required>
												</div>
											</div>
											<div class="row mt-2">
												<div class="col">
													<label class="font-weight-bold">Street</label>
													<input type="text" class="form-control" placeholder="Enter street" id="street" name="street" required>
												</div>
												<div class="col">
													<label class="font-weight-bold">City</label>
													<input type="text" class="form-control" placeholder="Enter City" id="city" name="city" required>
												</div>
											</div>
											<div class="row mt-2">
												<div class="col">
													<label class="font-weight-bold">District</label>
													<input type="text" class="form-control" placeholder="Enter District" name="district" required id="district">
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


    </script>
</div>
</div>
</div>

</body>

<!-- end::Body -->
</html>