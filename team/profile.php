<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');
$userid = current_employeeid();
if(empty($userid)){
	header("Location:index.php");
	}

$message = '';
$usedetails = runQuery("select * from employee where ID = '".$userid."'");
$lead_det = runQuery("select * from employee where unique_id = '".$usedetails['leader']."'");
if(!empty($_POST['submit'])){
          if(!empty($_POST['fname'])){
			if(!empty($_POST['lname'])){
				if(!empty($_POST['mobile'])){
					if(!empty($_POST['pannum'])){
						if(!empty($_POST['marital_status'])){
					         	if(!empty($_POST['gender'])){
							        if(!empty($_POST['occuption'])){
							        if(!empty($_POST['income'])){
							        if(!empty($_POST['emergency_contact'])){
							 

			$m3avenuearray = $m3avenuewherearray = array(); 
$m3avenuewherearray['ID']=$userid;
$m3avenuearray['fname']=mysqli_real_escape_string($conn,$_POST['fname']);
$m3avenuearray['lname']=mysqli_real_escape_string($conn,$_POST['lname']);
$m3avenuearray['dob']=mysqli_real_escape_string($conn,$_POST['dob']);
$m3avenuearray['mobile']=mysqli_real_escape_string($conn,$_POST['mobile']);
$m3avenuearray['pannum']=mysqli_real_escape_string($conn,$_POST['pannum']);
$m3avenuearray['marital_status']=mysqli_real_escape_string($conn,$_POST['marital_status']);
$m3avenuearray['gender']=mysqli_real_escape_string($conn,$_POST['gender']);
$m3avenuearray['occuption']=mysqli_real_escape_string($conn,$_POST['occuption']);
$m3avenuearray['income']=mysqli_real_escape_string($conn,$_POST['income']);
$m3avenuearray['emergency_contact']=mysqli_real_escape_string($conn,$_POST['emergency_contact']);

	if(!empty($_FILES["profilepic"]['name'])){
  if (!file_exists('employeeimages')) {	
		mkdir('employeeimages', 0777, true);	
		}
	    $target_dir = 'employeeimages/';									
		$file = $_FILES["profilepic"]['name'];

		$imageFileType = strtolower(pathinfo($file,PATHINFO_EXTENSION));								

		$file = $usedetails['ID'].'.'.$imageFileType;				
		$target_file = $target_dir . strtolower($file);		

		$uploadOk = 1;

		if($imageFileType != "jpg" && $imageFileType != "png"  && $imageFileType != "jpeg"&& $imageFileType != "gif" )

			{
		$message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
		$uploadOk = 0;						
		}
	    if ($uploadOk == 0) {			
		$message .= "Sorry, your file was not uploaded.";		
		} else {
			if (move_uploaded_file($_FILES["profilepic"]["tmp_name"], $target_file)){				
				$m3avenuearray['profilepic'] = strtolower($file);						
			} else {
				$message .= "Sorry, There Was an Error Uploading Your File.";			
				}
			}
	}
			$result= updateQuery($m3avenuearray,'employee',$m3avenuewherearray);
			
 
if(!$result){

 
header("Location:profile.php?success=success");
}else{
		$message .=$result;
}

		}else{
		$message .="Emergency Contact field is empty";
	}
	}else{
		$message .="Income field is empty";
	}
	}else{
		$message .="Occuption field is empty";
	}
	}else{
		$message .="Gender field is empty";
	}	
	}else{
		$message .="marital status field is empty";
	}
	}else{
		$message .="Pancard Number  field is empty";
	}
	
	}else{
		$message .="Mobile  field is empty";
	}
	
	}else{
		$message .="Last Name field is empty";
	}
	
	}else{
		$message .="First Name field is empty";
	}
	
}

if(!empty($_POST['password-submit'])){
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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../plugins/images/favicon.png">
    <title>M3 Dashboard</title>
   
       <?php include('headerscripts.php');?>
</head>

<body>
    <!-- Preloader -->
   
    <div id="wrapper">
        <!-- Navigation -->
       <?php include('header.php');?>
        <!-- Left navbar-header end -->
		
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Profile page</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li class="active">Profile page</li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
                <!-- .row -->
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <div class="white-box">
						
                            <div class="user-bg">
                                <div class="overlay-box">
                                    <div class="user-content">
                                        <a href="javascript:void(0)"><img src="<?php echo !empty($usedetails['profilepic']) ? EMPLOYEE_IMAGE.$usedetails['profilepic'] : 'img/m3-logo.png' ;?>" class="thumb-lg img-circle" alt="img"></a>
                                        <h4 class="text-black"> <?php echo ucwords($usedetails['fname'].' '.$usedetails['lname']);?></h4>
                                        <h5 class="text-black">User Id : <?php echo $usedetails['unique_id'];?></h5> 
									<h5 class="text-black"><span>Reporting Manager: </span>  <?php echo $lead_det['fname'].' '.$lead_det['lname'];?></h5>
                                        </div>
                                </div>
                            </div>
                            <div class="user-btm-box">
                                <div class="col-md-12 col-sm-6 text-center">
									<h5 class="text-black"><span>Call: </span>  <?php echo $usedetails['mobile'];?></h5>
								</div>

								<div class="col-md-12 col-sm-6 text-center">
									<h5 class="text-black"><span>mail: </span>  <?php echo $usedetails['email'];?></h5>
								</div>		
								
                        </div>
						</div>
                        <div class="white-box">
                            <div class="user-btm-box">
							<? if(!empty($_GET['password'])){?>

								<div class="alert alert-success">

								Password Updated Successfully

								</div>

								<? }?>

							<h3>Change Password</h3>
                           <form  method="post" action="" enctype="multipart/form-data">
							 
									<div class="col-md-12">
								  <div class="form-group">
										<label>Password</label>
										<input type="password" name="new_pass"  class="form-control form-control-line"> 
										</div> 
								  <div class="form-group">
									<label >Confirm password</label> 
										<input type="password" name="confirm_pass" class="form-control form-control-line"> 
									</div>
								  <div class="form-group"> 
										<button name="password-submit" value="submit" class="btn btn-success">Submit</button>
									</div>
									</div>
									</form>
							
							</div>
                        </div> 
                        <div class="white-box">
                            <div class="user-btm-box">
							<h3>Points</h3>
								<?php $monthly_points = emp_points($usedetails['ID']); ?>
									<div class="col-md-12">
								  <div class="form-group">
										<label>Target Points : <?php echo (int)($usedetails['income'] / (12 * 25)) ; ?></label>
								</div>
								<div class="form-group">
										<label>Earned Points : <?= $monthly_points['approvedclientspoints'] ?? 0; ?></label>
								</div>
								<div class="form-group">
										<label>Pending Points : <?= $monthly_points['pendingclientspoints'] ?? 0; ?></label>
								</div>
								<div class="form-group">
										<label>Canceled Points : <?= $monthly_points['rejectclientspoints'] ?? 0; ?></label>
								</div>
									</div>

							</div>
                        </div> 
                    </div>
                    <div class="col-md-8 col-xs-12">
                        <div class="white-box">
                            <? if(!empty($_GET['success'])){?>

								<div class="alert alert-success">

								Profile Details Updated Successfully

								</div>

								<? }?>

								<? if(!empty($message)){?>

								<div class="alert alert-danger">

								<?=$message;?>

								</div>

								<? }?>
							<div class="row">
								<form  method="post" action="" enctype="multipart/form-data">
							 
									<div class="col-md-12">
								  <div class="form-group">
										<label>First Name (As Per Pancard)</label>
										<input type="text" name="fname" value="<?php echo !empty($usedetails['fname']) ? $usedetails['fname'] : ''; ?>" class="form-control form-control-line" readonly> 
										</div> 
								  <div class="form-group">
									<label >Last Name (As Per Pancard)</label> 
										<input type="text" name="lname" value="<?php echo !empty($usedetails['lname']) ? $usedetails['lname'] : ''; ?>" class="form-control form-control-line" readonly> 
									</div>
								<div class="form-group">
									<label >Date Of  Birth</label> 
										<input type="date"  class="form-control form-control-line" name="dob" id="example-email"  value="<?php echo !empty($usedetails['dob']) ? $usedetails['dob'] : ''; ?>" readonly> 
								</div>
								
								<div class="form-group">
									<label >Mobile Number</label> 
										<input type="text"  class="form-control form-control-line" name="mobile" value="<?php echo !empty($usedetails['mobile']) ? $usedetails['mobile'] : ''; ?>" id="example-email"   onkeypress="return isNumberKey(event)" placeholder="Mobile No"   required maxlength="10" pattern="\d{10}"   readonly> 
								</div>
								<div class="form-group">
									<label >Email</label> 
										<input type="text"  class="form-control form-control-line" name="email" value="<?php echo !empty($usedetails['email']) ? $usedetails['email'] : ''; ?>" readonly id="example-email" > 
								</div>
								<div class="form-group">
									<label >Pan Number</label> 
										<input type="text" value="<?php echo !empty($usedetails['pannum']) ? $usedetails['pannum'] : ''; ?>" class="form-control form-control-line" name="pannum" id="example-email" readonly> 
								</div>
								
								<div class="form-group">
									<label >Marital Status</label> 
										<select class="form-control form-control-line" name="marital_status" readonly>
											<option value="Married" <?php if($usedetails['marital_status']=='Married'){ echo 'selected';}?>>Married</option>
											<option value="Un-Married" <?php if($usedetails['marital_status']=='Un-Married'){ echo 'selected';}?>>Un-Married</option>
										</select> 
								</div>
								
								<div class="form-group">
									<label >Gender</label> 
										<select class="form-control form-control-line" name="gender" readonly>
											<option value="Male" <?php if($usedetails['gender']=='Male'){ echo 'selected';}?>>Male</option>
											<option value="Female" <?php if($usedetails['gender']=='Female'){ echo 'selected';}?>>Female</option>
										</select> 
								</div>
								
								<div class="form-group">
									<label >Occupation</label> 
										<select class="form-control form-control-line" name="occuption" readonly>
											<option value="Business" <?php if($usedetails['occuption']=='Business'){ echo 'selected';}?>>Business</option>
											<option value="Salaried" <?php if($usedetails['occuption']=='Self Salaried'){ echo 'selected';}?>>Salaried</option>
											<option value="Self Employied" <?php if($usedetails['occuption']=='Salaried'){ echo 'selected';}?> >Self Employied</option>
										</select> 
								</div>
								
								<div class="form-group">
									<label >CTC</label> 
										<input type="text" name="income" value="<?php echo !empty($usedetails['income']) ? $usedetails['income'] : ''; ?>" class="form-control form-control-line" readonly>
									</div> 
								
								<div class="form-group">
									<label >Emergency Contact</label> 
										<input type="text" name="emergency_contact"  value="<?php echo !empty($usedetails['emergency_contact']) ? $usedetails['emergency_contact'] : ''; ?>" class="form-control form-control-line">
								</div>
								<div class="form-group">
									<label >Profile Picture</label> 
										<input type="file" name="profilepic" >
								</div>
								
							   <!-- <div class="form-group">
									<label >Change Password</label>
									<div class="col-md-6">
										<input type="password" placeholder="Enter password" class="form-control form-control-line">
									</div>
									<div class="col-md-6">
										<input type="password" placeholder="Re Enter password" class="form-control form-control-line">
									</div>
								</div>-->
								
								<div class="form-group">
									<div class="col-sm-6">
										<button class="btn btn-success" name="submit" value="submit">Update Profile</button>
									</div>
									<div class="col-sm-6">
										<button class="btn btn-success">Discard Changes</button>
									</div>
									
								</div>
								</div>
							</form>
                        </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
<?php include('footer.php');?>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
   
   <?php include("footerscripts.php");?>
</body>

</html>
