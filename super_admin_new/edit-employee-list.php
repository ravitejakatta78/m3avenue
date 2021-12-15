<?php
session_start();
error_reporting(E_ALL); 
include('../functions.php');
$userid = current_adminid(); 
if(empty($userid)){
	header("Location: login.php");
} 
$message = '';
$user_unique_id = employee_details($userid,'unique_id');
$get_roles_array = [3,4,5];
$tree = employeehirerachy($user_unique_id,$get_roles_array);
//echo "<pre>";print_r($tree);exit;
$empString = !empty($tree) ? implode("','",array_column($tree,'ID')) : '';

$employeedetails = runQuery("select * from employee where ID = '".$_GET['edit']."'");
$message = '';
if(!empty($_POST['submit'])){
    if (!empty($_POST['fname'])) {
        $pagerarray  = array();
        $pagewererarray['ID'] = $employeedetails['ID']; 
        if ($_POST['role_id']=='4') {
            $pagerarray['leader'] = mysqli_real_escape_string($conn,$_POST['leader']);
        }
		$joining_date= mysqli_real_escape_string($conn,$_POST['join_date']);
        $pagerarray['role_id'] = mysqli_real_escape_string($conn,$_POST['role_id']);
        $pagerarray['fname'] = mysqli_real_escape_string($conn,$_POST['fname']);
        $pagerarray['lname'] = mysqli_real_escape_string($conn,$_POST['lname']);  
        $pagerarray['bankdetails'] = mysqli_real_escape_string($conn,$_POST['bankdetails']);
        $pagerarray['accntnum'] = mysqli_real_escape_string($conn,$_POST['accntnum']);
        $pagerarray['ifsccode'] = mysqli_real_escape_string($conn,$_POST['ifsccode']);
        $pagerarray['address'] = mysqli_real_escape_string($conn,$_POST['address']);
        $pagerarray['income'] = mysqli_real_escape_string($conn,$_POST['income']);
        $pagerarray['pannum'] = mysqli_real_escape_string($conn,$_POST['pannum']);
		$pagerarray['email'] = mysqli_real_escape_string($conn,$_POST['email']);
		$pagerarray['personal_email'] = mysqli_real_escape_string($conn,$_POST['personal_email']);
		$pagerarray['alternate_mobile_number'] = mysqli_real_escape_string($conn,$_POST['alternate_mobile_number']);
		$pagerarray['whatsapp_number'] = mysqli_real_escape_string($conn,$_POST['whatsapp_number']);
		
		
		$pagerarray['landmark'] = mysqli_real_escape_string($conn,$_POST['landmark']);
		$pagerarray['city'] = mysqli_real_escape_string($conn,$_POST['city']);
		$pagerarray['state'] = mysqli_real_escape_string($conn,$_POST['state']);
		$pagerarray['pincode'] = mysqli_real_escape_string($conn,$_POST['pincode']);
		$pagerarray['payment_type'] = mysqli_real_escape_string($conn,$_POST['payment']);
		$pagerarray['joining_date'] =date("d-m-Y", strtotime($joining_date));
		$pagerarray['designation'] = mysqli_real_escape_string($conn,$_POST['designation']);
		$pagerarray['location'] = mysqli_real_escape_string($conn,$_POST['location']);
	    $result = updateQuery($pagerarray,'employee',$pagewererarray);
		if(!$result){
			header("Location: employee_list.php?usuccess=success");
        }
	    
	}
	else {
        $message .=" First Name Field is Empty";
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
	   
	header("Location: employee_list.php?success=success");
	   
	} else {
	    echo "Error deleting record: " . $conn->error;
	}
}


$roles = roles();


$managers = runloopQuery("SELECT unique_id,concat(fname,' ',lname) leadername,role_id FROM employee where leader='".$user_unique_id."' and role_id = 3 order by ID desc");
$superAdminAndManagers = runloopQuery("SELECT unique_id,concat(fname,' ',lname) leadername,role_id FROM employee 
where ID in  ('".$empString."','".$userid."')  and role_id not in ('4')");

if($employeedetails['role_id'] == '4'){
	$labeltext = 'Manager';
	$leader_primary = $managers;
}
else {
    $labeltext = 'SuperAdmin & Managers';
    $leader_primary = $superAdminAndManagers;
}

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

				<div class="row">

				<div class="col-12">
					<div class="card">

						<div class="card-body">



				<form  method="post" action="" enctype="multipart/form-data" autocomplete="off" >


						<div class="form-group row">
						    
							<label for="example-text-input"  class="col-3 col-form-label">Role</label>
							<div class="col-3">
	                            <select class="form-control"  name="role_id" id="role_id" onchange="rolebasedisplay()">
									<option value="">Select</option>
									<?php foreach($roles as $roleid => $rolename) { if ($roleid != '0' && $roleid != '1' && $roleid != '2' && $roleid != '5' && $roleid != '6') {?> 
	        									<option value="<?php echo $roleid; ?>" <?php if($employeedetails['role_id'] == $roleid) { echo 'selected'; } ?>><?php echo $rolename; ?></option>
	        								<?php } } ?>
								</select>
							</div>
							<label for="example-text-input" id="rolebaselabel" class="col-3 col-form-label"><?= @$labeltext ?? ''; ?></label>
							<?php if(count($leader_primary) == 0) $displayleadprimary = "none"; else $displayleadprimary = "block"; ?>
							<div class="col-3" id="rolebaseid" >
							<select class="form-control"  name="leader" id="leader" style="display:<?php echo $displayleadprimary; ?>">
									<option value="">Select</option>
									<?php for($i=0; $i < count($leader_primary); $i++) { 
										//if($employeedetails['unique_id'] != $leader_primary[$i]['unique_id']) {
										?>

									<option value="<?= $leader_primary[$i]['unique_id'] ; ?>" <?php if($employeedetails['leader'] == $leader_primary[$i]['unique_id']) { ?> selected <?php } ?>>
									<?= $leader_primary[$i]['leadername']. '-'.$roles[$leader_primary[$i]['role_id']] ; ?></option>
									<?php //} 
								} ?>
								</select>							
							</div>
							</div><br>
						<div class="form-group row">
						    
                        <label for="example-text-input" class="col-3 col-form-label">First Name</label>
                        <div class="col-3">
                            <input class="form-control" name="fname" id="fname" type="text" placeholder="Enter First Name"  value="<?php echo $employeedetails['fname']; ?>" required>
                        </div>
						<label for="example-text-input" class="col-3 col-form-label">Last Name</label>
                        <div class="col-3">
                            <input class="form-control" name="lname" id="lname" type="text" placeholder="Enter Last Name"  value="<?php echo $employeedetails['lname']; ?>" required>
                        </div>
						</div><br>
						
					<div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Offical Email</label>
                        <div class="col-3">
                            <input class="form-control" type="text" name="email" placeholder="Enter Offical Email" id="email"   value="<?php echo $employeedetails['email']; ?>" readonly  >
                        </div>
						<label for="example-text-input" class="col-3 col-form-label">Personal Email</label>
                        <div class="col-3">
                            <input class="form-control" type="text" name="personal_email" placeholder="Enter Personal Email" id="personal_email" name="personal_email"   value="<?php echo $employeedetails['personal_email']; ?>" readonly  >
                        </div>

                    </div>	 
					<br>
					<div class="form-group row ">
        							<label class="font-weight-bold">Mobile Numbers</label>
        							<div class="col">
        									<input type="text" class="form-control form-control-line"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" placeholder="Mobile No"  name="mobile" value="<?php echo $employeedetails['mobile']; ?>"  required maxlength="10" pattern="\d{10}" id="mobile" >
        							</div>
        							<div class="col">
        									<input type="text" class="form-control form-control-line"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" placeholder="Enter Whatsapp Number"  name="whatsapp_number" value="<?php echo $employeedetails['whatsapp_number']; ?>"  required maxlength="10" pattern="\d{10}" id="mobile" >
        							</div>
        							<div class="col">
										<input type="text" class="form-control form-control-line"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" placeholder="Enter Alternate Mobile Number"  name="alternate_mobile_number" value="<?php echo $employeedetails['alternate_mobile_number']; ?>"  required maxlength="10" pattern="\d{10}" id="mobile" >
        							</div>
        			</div><br>
					<div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Address</label>
                        <div class="col-12">
						<div class="form-group">
							<textarea class="form-control"  name="address" id="exampleTextarea" rows="4" required><?php echo $employeedetails['address']; ?></textarea>
						</div>

                        </div>
                    </div><br>
					<div class="row mt-2">
        							<div class="col">
        									<label class="font-weight-bold">Landmark</label>
        									<input type="text" class="form-control" placeholder="Enter Landmark" id="landmark" name="landmark" value="<?= $employeedetails['landmark'] ?>" required>
        							</div>
        							<div class="col">
        									<label class="font-weight-bold">City</label>
        									<input type="text" class="form-control" placeholder="Enter City" id="city" name="city" value="<?= $employeedetails['city'] ?>" required>
        							</div>
        						</div><br>
    							<div class="row mt-2">
    								<div class="col">
    									<label class="font-weight-bold">State</label>
    									<input type="text" class="form-control" placeholder="Enter State" name="state" required value="<?= $employeedetails['state'] ?>" id="state">
    								</div>
    								<div class="col">
    									<label class="font-weight-bold">Pin code</label>
    									<input type="text" class="form-control" placeholder="Enter pincode" id="pincode" value="<?= $employeedetails['pincode'] ?>" maxlength="6" name="pincode" required>
    								</div>
    							</div><br>
    							<div class="row mt-2"> 
    								<div class="col">
    									<label for="example-text-input" class="font-weight-bold">Joining Date</label>
    									<input class="form-control" type="date" name="join_date" id="join_date" value="<?php echo date('Y-m-d'); ?>" required/>
    								</div>   
    								<div class="col"> 
    									<label for="example-text-input" class="font-weight-bold">Payment Type</label>
    									<select class="form-select form-control" aria-label="Default select example" name='payment' id="payment" onchange="return paymentType();" required>
    										<option value="">Select</option>
    										<option value="review_pay" <?php if($employeedetails['payment_type'] == 'review_pay') { echo 'selected'; }  ?>>Review Pay</option>
    										<option value="variable_pay" <?php if($employeedetails['payment_type'] == 'variable_pay') { echo 'selected'; } ?>>Variable Pay</option>
    										<option value="royalty_pay" <?php if($employeedetails['payment_type'] == 'royalty_pay') { echo 'selected'; } ?>>Royalty Pay</option>
    									</select>
    								</div>
    							</div><br>
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
                    <br>
					
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
						<br>
						<div class="row mt-2">
        								<div class="col"> 
        									<label for="example-text-input" class="font-weight-bold">Designation</label>
        									<select class="form-select form-control" aria-label="Default select example" name="designation" required id="designation">
        										<option value="">Select</option>
        										<option value="Telecaller" <?php if($employeedetails['designation'] == 'Telecaller') { echo 'selected'; }  ?>>Telecaller</option>
        										<option value="Business Development Associate" <?php if($employeedetails['designation'] == 'Business Development Associate') { echo 'selected'; }  ?>>Business Development Associate</option>
        										<option value="Digital Marketing Executive" <?php if($employeedetails['designation'] == 'Digital Marketing Executive') { echo 'selected'; }  ?>>Digital Marketing Executive</option>
        										<option value="HR Manager" <?php if($employeedetails['designation'] == 'HR Manager') { echo 'selected'; }  ?>>HR Manager</option>
        										<option value="Team Leader" <?php if($employeedetails['designation'] == 'Team Leader') { echo 'selected'; }  ?>>Team Leader</option>
        									</select>
        								</div>
        								<div class="col"> 
        									<label for="example-text-input" class="font-weight-bold">Work Location</label>
        									<input class="form-control" type="text" name="location"  value="<?= $employeedetails['location']; ?>"    placeholder="Enter work location" id="location" required>
        								</div> 
        							</div>

                    <br> 
                    <div class="form-group row">
                       <div class="col-3">
                             <button type="submit" name="submit" value="submit" class="btn btn-primary btn-elevate btn-pill">Update Employee</button>
						</div>
                    </div> 
                    <br> 
					</form>
						
						
					<form  method="post" action="" enctype="multipart/form-data" autocomplete="off" >
						<div class="form-group row">
						    
                        <label for="example-text-input" class="col-3 col-form-label">Password</label>
                        <div class="col-3">
                            <input class="form-control" name="new_pass" type="password" placeholder="Enter Password" >
                        </div>
						</div>
						<br>
					 
						<div class="form-group row">
						    
                        <label for="example-text-input" class="col-3 col-form-label">Confirm password</label>
                        <div class="col-3">
                            <input class="form-control" name="confirm_pass" type="password" placeholder="Enter Confirm Password" >
                        </div>
						</div>
						<br>
					 
					 <div class="form-group row"> 
                    
                       <div class="col-3">
                             <button type="submit" name="password-submit" value="submit" class="btn btn-primary btn-elevate btn-pill">Update Password</button>
						</div>
                    </div>  
					</form>
			 						
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

    <script>

	function rolebasedisplay()
	{
		$("#rolebaselabel").html('');
		var unique_id = '<?= $employeedetails['unique_id'] ; ?>';

		var role_id = $("#role_id").val();
		var rolesJson = '<?= json_encode(roles()); ?>'
        var roles = JSON.parse(rolesJson);
		if(role_id == '4') {
			$("#rolebaseid").show();
			$("#leader").show();
			var leaders = '<?= json_encode($managers) ; ?>';
			var label = 'Managers';
		}
		else if(role_id == '3') {
			$("#rolebaseid").show();
			$("#leader").show();
			var leaders = '<?= json_encode($superAdminAndManagers) ; ?>';
			var label = 'SuperAdmin & Managers';
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
			$("#leader").append(`<option value = "${leaderarray[i]['unique_id']}">${leaderarray[i]['leadername']} - ${roles[leaderarray[i]['role_id']]}</option>`)
			}
		}
	}
	</script>

</body>
</html>