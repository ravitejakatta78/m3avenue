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

$employeedetails = runQuery("select * from employee where ID = '".$_GET['edit']."'");
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
                if($_POST['role_id']=='4')
                {
                	$pagerarray['leader'] = mysqli_real_escape_string($conn,$_POST['leader']);
                }
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
					header("Location: employee_list.php?usuccess=success");
                    }
	    
		 	}else{

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


$managers = runloopQuery("SELECT unique_id,concat(fname,' ',lname) leadername FROM employee where leader='".$user_unique_id."' and role_id = 3 order by ID desc");


if($employeedetails['role_id'] == '4'){
	$labeltext = 'Manager';
	$leader_primary = $managers;
}
else {
    $labeltext = '';
    $leader_primary = [];
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
									<?php foreach($roles as $roleid => $rolename) { if ($roleid != '0' && $roleid != '1' && $roleid != '2') {?> 
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
										if($employeedetails['unique_id'] != $leader_primary[$i]['unique_id']) {
										?>

									<option value="<?= $leader_primary[$i]['unique_id'] ; ?>" <?php if($employeedetails['leader'] == $leader_primary[$i]['unique_id']) { ?> selected <?php } ?>>
									<?= $leader_primary[$i]['leadername'] ; ?></option>
									<?php } } ?>
								</select>							
							</div>
							</div><br>
						<div class="form-group row">
						    
                        <label for="example-text-input" class="col-3 col-form-label">Enter FirstName</label>
                        <div class="col-3">
                            <input class="form-control" name="fname" type="text" placeholder="Enter FirstName"  value="<?php echo $employeedetails['fname']; ?>" required>
                        </div>
						<label for="example-text-input" class="col-3 col-form-label">Enter LastName</label>
                        <div class="col-3">
                            <input class="form-control" name="lname" type="text" placeholder="Enter LastName"  value="<?php echo $employeedetails['lname']; ?>" required>
                        </div>
						</div><br>
						
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
					<br>
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
					
					 <div class="form-group row">
                        <label for="example-text-input" class="col-3 col-form-label">Address</label>
                        <div class="col-12">
						<div class="form-group">
							<textarea class="form-control"  name="address" id="exampleTextarea" rows="4" required><?php echo $employeedetails['address']; ?></textarea>
						</div>

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
                            <input class="form-control" name="confirm_pass" type="password" placeholder="Enter Leader employee id" >
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
		if(role_id == '4') {
			$("#rolebaseid").show();
			$("#leader").show();
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
</html>