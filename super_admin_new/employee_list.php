<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_adminid(); 
$user_unique_id = employee_details($userid,'unique_id');
$get_roles_array = [3,4,5];
$tree = employeehirerachy($user_unique_id,$get_roles_array);
//echo "<pre>";print_r($tree);exit;
$empString = !empty($tree) ? implode("','",array_column($tree,'ID')) : '';
if(empty($userid)){

	header("Location: index.php");
}

$message = '';
if(!empty($_POST['fname'])){
	
	$mobile_number = mysqli_real_escape_string($conn,$_POST['mobile']);
	$prev_mobile_det = runQuery("select * from employee where mobile = '".$mobile_number."'");

	$subscription_details = runQuery("select * from tbl_super_admin_details where super_admin_id = '".$userid."'");

	$count_managers=runQuery("select count(*) as count from employee where status=1 and role_id=3 and  leader = '".$user_unique_id."'");
	$count_executives=runQuery("select count(*) as count from employee where status=1 and role_id=4 and leader = '".$user_unique_id."'");

	if($_POST['role_id']=='3' && !empty($count_managers['count']) && $count_managers['count']>=$subscription_details['no_of_active_managers'])
	{
		$message .= "You have already reached Maximum Number of Active Managers Count";
	}
	else if($_POST['role_id']=='4' && !empty($count_executives['count']) && $count_executives['count']>=$subscription_details['no_of_active_executives'])
	{
		$message .= "You have already reached Maximum Number of Active Executives Count";
	}
	else if(!empty($prev_mobile_det))
	{
		$message .= "Mobile Number Already Taken.";
	}
	else{
		$pagerarray  = array();
		$docsarray=array();

		$uniqueusers = (int)runQuery("select max(ID) as id from employee order by ID desc")['id'];
		$newuniquid = $uniqueusers+1;

		$joining_date= mysqli_real_escape_string($conn,$_POST['join_date']);
		$pagerarray['role_id'] = mysqli_real_escape_string($conn,$_POST['role_id']) ?? 2;
		$pagerarray['leader']= ($_POST['role_id'] == '4') ? ($_POST['leader'] ??  $user_unique_id) : $user_unique_id;
		$pagerarray['fname'] = mysqli_real_escape_string($conn,$_POST['fname']);
		$pagerarray['lname'] = mysqli_real_escape_string($conn,$_POST['lname']);
		$pagerarray['unique_id'] = 'M3'.sprintf('%05d',$newuniquid);
		$pagerarray['email'] = mysqli_real_escape_string($conn,$_POST['employee_email']);
		$pagerarray['personal_email'] = mysqli_real_escape_string($conn,$_POST['personal_email']);
		$pagerarray['mobile'] = mysqli_real_escape_string($conn,$_POST['mobile']);
		$pagerarray['whatsapp_number'] = mysqli_real_escape_string($conn,$_POST['whatsapp_number']);
		$pagerarray['alternate_mobile_number'] = mysqli_real_escape_string($conn,$_POST['alternate_mobile_number']);
		$pagerarray['password'] = password_hash(trim($_POST['password']),PASSWORD_DEFAULT);
		$pagerarray['bankdetails'] = mysqli_real_escape_string($conn,$_POST['bankdetails']);
		$pagerarray['accntnum'] = mysqli_real_escape_string($conn,$_POST['accntnum']);
		$pagerarray['ifsccode'] = mysqli_real_escape_string($conn,$_POST['ifsccode']);
		$pagerarray['address'] = mysqli_real_escape_string($conn,$_POST['address']);
		$pagerarray['landmark'] = mysqli_real_escape_string($conn,$_POST['landmark']);
		$pagerarray['city'] = mysqli_real_escape_string($conn,$_POST['city']);
		$pagerarray['state'] = mysqli_real_escape_string($conn,$_POST['state']);
		$pagerarray['pincode'] = mysqli_real_escape_string($conn,$_POST['pincode']);
		$pagerarray['income'] = mysqli_real_escape_string($conn,$_POST['income']);
		$pagerarray['pannum'] = mysqli_real_escape_string($conn,$_POST['pannum']);
		$pagerarray['payment_type'] = mysqli_real_escape_string($conn,$_POST['payment']);
		$pagerarray['joining_date'] =date("d-m-Y", strtotime($joining_date));
		$pagerarray['designation'] = mysqli_real_escape_string($conn,$_POST['designation']);
		$pagerarray['location'] = mysqli_real_escape_string($conn,$_POST['location']);
		$pagerarray['status'] = '1';

		$new_employee_id = insertIDQuery($pagerarray,'employee');

		if($new_employee_id)
		{
			upload_employee_documents($_FILES['uploadedfile'],$_POST['uploadedfilename'],$new_employee_id);
			header("Location: employee_list.php?success=success");
		}

	}

}


if(!empty($_POST['dailerstatusid'])){
	$sql = "update  employee set dialer_status = ".$_POST['dailerstatusid']."  WHERE ID=".$_POST['id']."";
	if ($conn->query($sql) === TRUE) {

		header("Location: employee_list.php?ssuccess=success");

	} else {
		echo "Error deleting record: " . $conn->error;
	}

}


if(!empty($_GET['delete'])){
	$sql = "DELETE FROM employee WHERE ID=".$_GET['delete']."";

	if ($conn->query($sql) === TRUE) {

		header("Location: employee_list.php?dsuccess=success");

	} else {
		echo "Error deleting record: " . $conn->error;
	}
}


if(!empty($_POST['uploaddata']))
{
	$upload_employee_id = $_POST['upload_employee_id'];
    upload_employee_documents($_FILES['uploadedfile'],$_POST['uploadedfilename'],$upload_employee_id);
	header("Location:employee_list.php?usuccess=success");
}

function upload_employee_documents($filearray,$filenamearray,$emp_id){
	echo "sad";
	if(!empty(array_filter($filearray['name']))) {

		foreach ($filearray['tmp_name'] as $key => $value) {
			
			$file_tmpname = $filearray['tmp_name'][$key];
			$file_name = $filearray['name'][$key];
			$file_size = $filearray['size'][$key];
			$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);		
			$newname = date('YmdHis',time()).mt_rand().'.'.$file_ext;
			$path = '../../sp_ace_docs/empdocs/'.$emp_id.'/';
			if (!is_dir($path)) {
				mkdir($path, 0777, true);
			}

			move_uploaded_file($file_tmpname,$path.'/'.$newname);
			$docsarray['file_name'] = $filenamearray[$key];
			$docsarray['doc_name'] = $newname;
			$docsarray['employee_id'] = $emp_id;
			$docsarray['reg_date'] = date('Y-m-d');
			$docsarray['created_on'] = date('Y-m-d H:i:s A');
			$result = insertQuery($docsarray,'employee_documents');
		}
	}

}


$roles = roles();

$superadmins = runloopQuery("SELECT unique_id,concat(fname,' ',lname) leadername FROM employee where ID =  '".$userid."'");
$managers = runloopQuery("SELECT unique_id,concat(fname,' ',lname) leadername FROM employee where role_id = 3 
and leader = '".$user_unique_id."' order by ID desc");
$manager_deisgnations = runloopQuery("SELECT * FROM tbl_designations where role_under = 3  order by ID desc");
$executive_deisgnations = runloopQuery("SELECT * FROM tbl_designations where role_under = 4  order by ID desc");




?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<meta name="robots" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template" />
	<meta property="og:title" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template" />
	<meta property="og:description" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template" />
	<meta property="og:image" content="https:/fillow.dexignlab.com/xhtml/social-image.png" />
	<meta name="format-detection" content="telephone=no">
	
	<!-- PAGE TITLE HERE -->
	<title>Fillow Saas Admin Dashboard</title>
	
	
	<?php include('header_scripts.php');?>

	
</head>
<body>

    <!--*******************
        Preloader start
        ********************-->
        <div id="preloader">
        	<div class="lds-ripple">
        		<div></div>
        		<div></div>
        	</div>
        </div>
    <!--*******************
        Preloader end
        ********************-->

    <!--**********************************
        Main wrapper start
        ***********************************-->
        <div id="main-wrapper">


        	<?php include('header.php');?>


		<!--**********************************
            Content body start
            ***********************************-->
            <div class="content-body">
            	<!-- row -->
            	<div class="container-fluid">

			<!-- <div class="row page-titles">
				<ol class="breadcrumb">
					<li class="breadcrumb-item active"><a href="javascript:void(0)">EmployeesList</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0)">Datatable(emo</a></li>
				</ol>
			</div> -->
			<!-- row -->


			<div class="row">

				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h4 class="page-title" style="color:#886CC0">Executive list</h4>

							<button type="button" class="btn btn-primary" style="float:right" 
							data-bs-toggle="modal" data-bs-target=".add_employee_form"data-toggle="modal" data-target="#exampleModalScrollable">Add Employee</button> 

							<a href="download-csv.php?table=employee&filter_by_role="><button type="button" class="btn btn-primary" style="float:right" >Download</button></a> 
						</div>
						<div class="card-body">

							<?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
									Employee Added Successfully
								</div>
							<?php } ?>
							<?php if(!empty($_GET['osuccess'])){?>
								<div class="alert alert-success">
									Offer Letter Generated Successfully
								</div>
							<?php } ?>
							<?php if(!empty($_GET['usuccess'])){?>
								<div class="alert alert-success">
									Employee Updated Successfully
								</div>
							<?php } ?>
							<?php if(!empty($_GET['dsuccess'])){?>
								<div class="alert alert-success">
									Employee Deleted Successfully
								</div>
							<?php } ?>
							<?php if(!empty($_GET['ssuccess'])){?>
								<div class="alert alert-success">
									Employee Status Updated Successfully
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

							<div class="table table-responsive">

								<!--begin: Datatable -->
								<table id="example3" style="min-width: 845px" class="table table-bordered table-hover table-checkable display">
									<thead>
										<tr>
											<th>S.No</th>
											<th>Employee</th>
											<th>Contact Info</th>
											<th>Reporting Manager</th>
					 						<th>Role</th>
											<th>Documents</th>
											<th>Reg date</th>
											<th>Offer Letter</th>
											<th>Action</th>
											<th>Status</th>
										</tr>
										
									</thead>
									<tbody>
										<?php

										$sql = runloopQuery("SELECT * FROM employee where ID in ('".$empString."')  order by ID desc");

										$x=1;  foreach($sql as $row)
										{
											$employee_documents = runloopQuery("SELECT * FROM employee_documents where employee_id ='".$row["ID"]."' order by ID desc");
											$path = '../offerletter/Offer_letters/'.$row["unique_id"].'_offerletter.pdf';
											$lead_details = lead_details($row["leader"]);
											?>
											<tr>
												<td><?php echo  $x;?></td>
												<td><?php echo $row["fname"];?> <?php echo $row["lname"];?>
												<br/><?php echo $row["email"];?>
												<br/><?php echo $row["unique_id"];?></td>
												<td><?php echo $row["mobile"];?>
												<br/><?php echo $row["whatsapp_number"];?>
												<br/><?php echo $row["alternate_mobile_number"];?></td>
												<td><?php echo $lead_details['fname'].' '.$lead_details['lname']
												.'<br>'.$lead_details['unique_id'];?></td>
												<td><?php echo $row['role_id'] ? roles($row['role_id']) : 'No Role Assigned'; ?></td>
												<td>
													<?php if(count($employee_documents)>0){ ?>
														<button type="button" class="btn btn-primary" onclick="preview_doc(<?php echo $row['ID'];?>)">
														  Preview 
														</button>
													<?php  } ?>
												     <button type="button" class="btn btn-success" onclick="upload_doc(<?php echo $row['ID'];?>)">
														Upload
													</button> 
													
												</td>

												
												<td><?php echo date('d-M-Y h:i A',strtotime($row["reg_date"]));?></td>
												<td>	<?php if(file_exists($path)){ ?>
													<a class="btn btn-info" target="_blank" href="../offerletter/Offer_letters/<?php echo $row["unique_id"]?>_offerletter.pdf"><i class="fa fa-download" title="Download" aria-hidden="true"></i></a> 
													
												<?php } else { ?>
													<a class="btn btn-info" href="../offerletter/offerletter.php?id=<?php echo $row['ID']?>"><i class="fa fa-file" aria-hidden="true" title="Generate"></i></a>
												<?php } ?>
												<br>
												<?php echo "Requested By:".lead_details($row["leader"],'fname'); ?>
											</td>
											<td><a href="edit-employee-list.php?edit=<?php echo $row["ID"];?>">Edit</a> | <a onclick="return confirm('Are you sure want to delete??');"  href="?delete=<?php echo $row["ID"];?>">Delete</a></td>
											<td>
											<!--	<label class="switch">
													<input type="checkbox" id="dailerstatusid"  <?php if($row['dialer_status']=='1'){ echo 'checked';}?> onChange="changedialerstatus('dailerstatus',<?php echo $row['ID'];?>);">
													<span class="slider round"></span>
												</label> -->

												<label class="switch">
													<input type="checkbox" id="empstatusid"  <?php if($row['status']=='1'){ echo 'checked';}?> onChange="changeemployeestatus('empstatus',<?php echo $row['ID'];?>);">
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
		</div>
	</div>
        <!--**********************************
            Content body end
            ***********************************-->


        </div>
    <!--**********************************
        Main wrapper end
        ***********************************-->

        <?php include('footer_scripts.php');?>


        <div class="modal fade add_employee_form" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        	<div class="modal-dialog modal-dialog-scrollable h-75 w-100 modal-lg"  role="document">
        		<div class="modal-content">
        			<div class="modal-header">
        				<h5 class="modal-title" id="exampleModalScrollableTitle">Add Employee</h5>
        				<button type="button" class="btn-close" data-bs-dismiss="modal">
                                                    </button>
        			</div>
        			<div class="modal-body">
        				<form method="post" action="" enctype="multipart/form-data" id="addempform" autocomplete="off">
        					<div class="row">
        						<div class="col-6">    
        							<label for="example-text-input" class="font-weight-bold">Role</label>
        							<select class="form-control"  name="role_id" id="role_id" onchange="rolebasedisplay()" required>
        								<option value="">Select</option>
        								<?php foreach($roles as $roleid => $rolename) { if ($roleid != '0' && $roleid != '1' && $roleid != '2') {?> 
        									<option value="<?php echo $roleid; ?>"><?php echo $rolename; ?></option>
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
        								<label class="font-weight-bold">First Name</label>
        								<input class="form-control" name="fname" type="text" placeholder="Enter First Name" id="fname" required>
        							</div>
        							<div class="col">
        								<label class="font-weight-bold">Last Name</label>
        								<input class="form-control" name="lname" type="text" placeholder="Enter Last Name" id="lname" required>
        							</div>
        						</div>
        						<div class="row mt-2">
        							<div class="col">
        								<label class="font-weight-bold">Offical Email</label>
        								<input class="form-control" type="text" name="employee_email" placeholder="Enter employee email" id="employee_email" required>  
        							</div>
        							<div class="col">
        								<label class="font-weight-bold">Personal Email</label>
        								<input class="form-control" type="text" name="personal_email" placeholder="Enter personal email" id="personal_email" required>  
        							</div>
        						</div>
        						<div class="row mt-2">
        							<label class="font-weight-bold">Mobile Numbers</label>
        							<div class="col">
        									<input type="text" class="form-control form-control-line"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" placeholder="Mobile No"  name="mobile" value=""  required maxlength="10" pattern="\d{10}" id="mobile" >
        							</div>
        							<div class="col">
        									<input type="text" class="form-control form-control-line"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" placeholder="Enter Whatsapp Number"  name="whatsapp_number" value=""  required maxlength="10" pattern="\d{10}" id="mobile" >
        							</div>
        							<div class="col">
        									
        									<input type="text" class="form-control form-control-line"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" placeholder="Enter Alternate Mobile Number"  name="alternate_mobile_number" value=""  required maxlength="10" pattern="\d{10}" id="mobile" >
        							</div>
        						</div>
        						<div class="row mt-2">
        							<div class="col">
        									<label class="font-weight-bold">Password</label>
        									<input class="form-control" type="password" name="password" placeholder="Enter Password" id="password" required>
        							</div>
        							<div class="col">
        									<label class="font-weight-bold">Confirm Password</label>
        									<input class="form-control" type="password" name="confirm_password" placeholder="Enter Confirm Password" id="confirm_password" required>
        							</div>
        						</div>
        						<div class="row mt-2">
        							<div class="col">
        									<label class="font-weight-bold">Address</label>
        									<textarea class="form-control" placeholder="Enter House No and Street name" id="house_no" name="address" required></textarea>
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
    									<input type="text" class="form-control" placeholder="Enter pincode" id="pincode" maxlength="6" name="pincode" required>
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
        									<input type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" name="income" id="income" placeholder="Enter CTC"  class="form-control" required >
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
        											<input class="form-control" type="text" name="ifsccode" maxlength="11" placeholder="Enter IFSC Code" id="ifsccode">
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
        									<label for="example-text-input" class="font-weight-bold">Work Location</label>
        									<input class="form-control" type="text" name="location"     placeholder="Enter work location" id="location" required>
        								</div> 
        							</div>			
									<table id="tblAddRow" class="table table-bordered table-striped">
						<thead>
							<tr>
							    <th>File Name</th>
								<th>File</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							    <td>
								<input type="text" name="uploadedfilename[]" class="form-control">
								</td>
								<td>
								<input type="file" name="uploadedfile[]" class="form-control">
								</td>
							</tr>
						</tbody>
					</table>

					<div class="modal-footer">
							<button id="btnAddRow" class="btn btn-success" type="button" >Add Row</button>
					</div>
        						</form>

        					</div>
        					<div class="modal-footer">
        						<button type="submit" name="submit" value="submit" id="addempsubmitid" class="btn btn-primary">Add Employee</button>
        						<button type="button" class="btn btn-default" data-bs-dismiss="modal" >Close</button>
        					</div>
        				</div>
        			</div>
        </div>
        <div id="myModal2" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		       <div class="modal-header">
		        <h4 class="modal-title">Upload Documents</h4>
		        <button type="button" class="btn-close" data-bs-dismiss="modal">
				                                                    </button>
		      </div>
		      <div class="modal-body">
				<form method="post" action="" id="upload-data-form-id" enctype="multipart/form-data">
				<input type="hidden"  id="upload_employee_id" name="upload_employee_id">
				<input type="hidden"  id="uploaddata" name="uploaddata" value="1">
				
				<table id="tblAddRowMore" class="table table-bordered table-striped">
						<thead>
							<tr>
							    <th>File Name</th>
								<th>File</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							    <td>
								<input type="text" name="uploadedfilename[]" class="form-control">
								</td>
								<td>
								<input type="file" name="uploadedfile[]" class="form-control">
								</td>
							</tr>
						</tbody>
					</table>

					<div class="modal-footer">
							<button id="btnAddRowMore" class="btn btn-success" type="button">Add Row</button>
							<input type="submit" class="btn btn-success" value="Save">

						</div>			
				</form>
		      </div>
		    </div>

		  </div>
		</div>


<div id="myModal3" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Uploaded Documents</h4>
		<input type='button' id='download' value='Download'>
        <button type="button" class="btn-close" data-bs-dismiss="modal">
		                                                    </button>
      </div>
   
      <div class="modal-body" id="docbody">
        
	  </div>
	  <div class="modal-footer">
      
        <button type="button" class="btn btn-default " data-bs-dismiss="modal">Close</button>
      </div>
    </div>
	</div>
	
 </div>




        		<script>
        			function rolebasedisplay()
        			{
        				$("#rolebaselabel").html('');

        				var role_id = $("#role_id").val();


        				if(role_id == '4') 
        				{
        					$("#rolebaseid").show();
        					var leaders = '<?= json_encode($managers) ; ?>';
        					var designations = '<?= json_encode($executive_deisgnations) ; ?>';
        					var label = 'Managers';
        				}
        				else if(role_id == '3')
        				{
        					$("#rolebaseid").hide();
        					var designations = '<?= json_encode($manager_deisgnations) ; ?>';
        					console.log(designations);
        				}
        				else 
        				{
        					$("#designation").html('');
        					$("#designation").append(`<option value = ''>Select</option>`);
        					$("#rolebaseid").hide();
        					return false;
        				}
        				$("#rolebaselabel").html(label);

        				var designations_array=JSON.parse(designations);
        				$("#designation").html('');
        				$("#designation").append(`<option value = ''>Select</option>`);

        				for(var i=0;i<designations_array.length; i++) { 
        					$("#designation").append(`<option value = "${designations_array[i]['designation_name']}">${designations_array[i]['designation_name']}</option>`)
        				}
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

        			function changeemployeestatus(name,id)
        			{

        				$.ajax({
        					type: 'post',
        					url: 'ajax/commonajax.php',
        					data: {
        						action: 'employeechangestatus',
        						empid: id
        					},
        					success: function(response) 
        					{
        						var res=JSON.parse(response);
        						alert(res.message);
        						location.reload();

        					}
        				});

        			}

        			function paymentType(){
        				var   payment  = $("#payment").val();
        				var   income  = $("#income").val();
        				// if(payment=='royalty_pay'){
			         //        $('#income').attr('readonly', 'true'); // mark it as read only
			         //        $('#income').css('background-color' , '#DEDEDE');
			         //        $("#income").css('border-color', '#e4e7ea');
			         //        $('#income').prop('required',false);
			         //    }else{
			         //    	$('#income').attr('readonly', false);
			         //    	$("#income").css('border-color', 'red');
			         //    	$('#income').prop('required',true);
			         //    	$('#income').css('background-color' , '');
            	
            // }
            
        }

        $("#addempsubmitid").click(function(){
        	var user_input_value;
        	var err_value = 0
        	$('#addempform').find('input,select,textarea').each(function(){
        		if($(this).prop('required'))
        		{
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

        	if($('#password').val() != $('#confirm_password').val())
        	{
        		err_value = err_value + 1;
        		$('#password').css('border-color', 'red');
        		$('#confirm_password').css('border-color', 'red');
        		alert("Passwords Not Matched");
        	}

        	

        	if(err_value == 0)
        	{
        		$("#addempsubmitid").hide();
        		$("#addempform").submit();	
        	}
        	
        });

        function upload_doc(employee_id)
		{
			$("#upload_employee_id").val(employee_id);
			$("#myModal2").modal('show');

		}

		$("#myModal2").on("hidden.bs.modal",function(){
		window.location.reload();
});

		$('#tblAddRow,#tblAddRowMore tbody tr').find('td').parent() 
    		.append('<td><a href="#" class="delrow"><i class="fa fa-trash border-red text-red"></i></a></td>');


		// Add row the table
		$('#btnAddRow,#btnAddRowMore').on('click', function() {
		    var lastRow = $('#tblAddRow tbody tr:last').html();
		    //alert(lastRow);
		    $('#tblAddRow,#tblAddRowMore tbody').append('<tr>' + lastRow + '</tr>');
		    $('#tblAddRow,#tblAddRowMore tbody tr:last input').val('');
		});
		// Delete row on click in the table
		$('#tblAddRow').on('click', 'tr a', function(e) {
		    var lenRow = $('#tblAddRow tbody tr').length;
		    e.preventDefault();
		    if (lenRow == 1 || lenRow <= 1) {
		        alert("Can't remove all row!");
		    } else {
		        $(this).parents('tr').remove();
		    }
		});

		$('#tblAddRowMore').on('click', 'tr a', function(e) {
		    var lenRow = $('#tblAddRowMore tbody tr').length;
		    e.preventDefault();
		    if (lenRow == 1 || lenRow <= 1) {
		        alert("Can't remove all row!");
		    } else {
		        $(this).parents('tr').remove();
		    }
		});



function preview_doc(employee_id){
	$("#myModal3").modal('show');
	
	$("#employee_id").val(employee_id);
    $.ajax({				
	 url : '_ajaxemployeedocs.php',		
	 
	 type: "POST",		
	 data: {			
		employee_id:employee_id			
	 },				
	 success: function(res){	
		var result = JSON.parse(res);
        
        
		$("#docbody").html('');
		$("#docbody").append('<div class="row"><div class="col">');
		$("#docbody").append(`<table width="100%" id="doctable"><tr><th class="tablestyle">S.No</th><th class="tablestyle">File Name</th><th class="tablestyle">File</th></tr>`);
        for(i=0; i < result.length ; i++) {
			$("#doctable").append(`<tr><td class="tablestyle">${(i+1)}</td><td class="tablestyle">${result[i]['file_name']}</td><td class="tablestyle"><a target="_blank" href="../../sp_ace_docs/empdocs/${employee_id}/${result[i]['doc_name']}">
			<img src="../../sp_ace_docs/empdocs/${employee_id}/${result[i]['doc_name']}"
			 class="borders" style="display: inline-block;width: 100px;height: 100px;margin: 6px;"></a></td></tr>`);

            
        }
		$("table#doctable tr:last").after('</table>');
        $("#docbody").append('</div></div>');
        }					
	 });

}

$(document).ready(function(){
 $('#download').click(function(){
   $.ajax({
     url: '../ajax/commonajax.php',
     type: 'post',
	 data : {action:'empzipdownload',emp_id:187},
     success: function(response){
       window.location = response;
     }
   });
 });
});

    </script>


</body>
</html>