<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');
$userid = current_userid();
if(empty($userid)){
	header("Location:profile.php");
	}
	$message = '';
$usedetails = runQuery("select * from user where ID = '".$userid."'");
if(empty($userid)){
	header("Location: ../index.php");
}
if(!empty($_POST['submit'])){
          if(!empty($_POST['fname'])){
				if(!empty($_POST['mobile'])){
					if(!empty($_POST['pannum'])){
						if(!empty($_POST['marital_status'])){
					         	if(!empty($_POST['gender'])){
							        if(!empty($_POST['occuption'])){
							        if(!empty($_POST['income'])){
							        if(!empty($_POST['emergency_contact'])){
							 

			$m3avenuearray = array();
		
		
$m3avenuewherearray['ID']=$userid;
$m3avenuearray['fname']=mysqli_real_escape_string($conn,$_POST['fname']);
$m3avenuearray['lname']=mysqli_real_escape_string($conn,$_POST['lname']);
$m3avenuearray['dob']=mysqli_real_escape_string($conn,$_POST['dob']);
$m3avenuearray['email']=mysqli_real_escape_string($conn,$_POST['email']);
$m3avenuearray['mobile']=mysqli_real_escape_string($conn,$_POST['mobile']);
$m3avenuearray['pannum']=mysqli_real_escape_string($conn,$_POST['pannum']);
$m3avenuearray['marital_status']=mysqli_real_escape_string($conn,$_POST['marital_status']);
$m3avenuearray['gender']=mysqli_real_escape_string($conn,$_POST['gender']);
$m3avenuearray['occuption']=mysqli_real_escape_string($conn,$_POST['occuption']);
$m3avenuearray['income']=mysqli_real_escape_string($conn,$_POST['income']);
$m3avenuearray['emergency_contact']=mysqli_real_escape_string($conn,$_POST['emergency_contact']);

			$result= updateQuery($m3avenuearray,'user',$m3avenuewherearray);
			
 
if(!$result){

 
header("Location:profile.php?success=success");
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
		$message .="First Name field is empty";
	}
	
}

if(!empty($_POST['password-submit'])){
	if(!empty($_POST['new_pass'])){
		if(!empty($_POST['confirm_pass'])){
			if($_POST['new_pass']===$_POST['confirm_pass']){
				$newepass = mysqli_real_escape_string($conn,$_POST['new_pass']);
				$confirmepass = mysqli_real_escape_string($conn,$_POST['confirm_pass']);
				$id = $_POST['id'];
					$newpass = password_hash($newepass,PASSWORD_DEFAULT);
					$insert_sql = "update user set password = '".$newpass."' where ID = $userid";
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
    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- toast CSS -->
    <link href="../plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- morris CSS -->
    <link href="../plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="css/colors/blue-dark.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
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
                                        <a href="javascript:void(0)"><img src="img/m3-logo.png" class="thumb-lg img-circle" alt="img"></a>
                                        <h4 class="text-black"> <?php echo ucwords($usedetails['fname'].' '.$usedetails['lname']);?></h4>
                                        <h5 class="text-black">User Id : <?php echo $usedetails['unique_id'];?></h5> </div>
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
                    </div>
                    <div class="col-md-8 col-xs-12">
                        <div class="white-box">
                          <? if(!empty($_GET['success'])){?>

								<div class="alert alert-success">

								Profile Details Added Successfully

								</div>

								<? }?>

								<? if(!empty($message)){?>

								<div class="alert alert-danger">

								<?=$message;?>

								</div>

								<? }?>

				  <form  method="post" action="" enctype="multipart/form-data">
								 
								  <div class="row">
								  <div class="form-group">
									<label class="col-md-12">First Name (As Per Pancard)</label>
									<div class="col-md-12">
										<input type="text" name="fname" value="<?php echo !empty($usedetails['fname']) ? $usedetails['fname'] : ''; ?>" class="form-control form-control-line"> </div>
								</div>
								  <div class="form-group">
									<label class="col-md-12">Last Name (As Per Pancard)</label>
									<div class="col-md-12">
										<input type="text" name="lname" value="<?php echo !empty($usedetails['lname']) ? $usedetails['lname'] : ''; ?>" class="form-control form-control-line"> </div>
								</div>
								<div class="form-group">
									<label for="example-email" class="col-md-12">Date Of  Birth</label>
									<div class="col-md-12">
										<input type="date"  class="form-control form-control-line" name="dob" id="example-email"> </div>
								</div>
								
								<div class="form-group">
									<label for="example-email" class="col-md-12">Email</label>
									<div class="col-md-12">
										<input type="text"  class="form-control form-control-line" name="email" value="<?php echo !empty($usedetails['email']) ? $usedetails['email'] : ''; ?>" readonly id="example-email"> </div>
								</div>
								<div class="form-group">
									<label for="example-email" class="col-md-12">Mobile Number</label>
									<div class="col-md-12">
										<input type="text"  class="form-control form-control-line" name="mobile" value="<?php echo !empty($usedetails['mobile']) ? $usedetails['mobile'] : ''; ?>" id="example-email"> </div>
								</div>
								
								<div class="form-group">
									<label for="example-email" class="col-md-12">Pan Number</label>
									<div class="col-md-12">
										<input type="text" value="<?php echo !empty($usedetails['pannum']) ? $usedetails['pannum'] : ''; ?>" class="form-control form-control-line" name="pannum" id="example-email"> </div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-12">Marital Status</label>
									<div class="col-sm-12">
										<select class="form-control form-control-line" name="marital_status">
											<option value="Married" <?php if($usedetails['marital_status']=='Married'){ echo 'selected';}?>>Married</option>
											<option value="Un-Married" <?php if($usedetails['marital_status']=='Un-Married'){ echo 'selected';}?>>Un-Married</option>
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-12">Gender</label>
									<div class="col-sm-12">
										<select class="form-control form-control-line" name="gender">
											<option value="Male" <?php if($usedetails['gender']=='Male'){ echo 'selected';}?>>Male</option>
											<option value="Female" <?php if($usedetails['gender']=='Female'){ echo 'selected';}?>>Female</option>
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-6">Occupation</label>
									<div class="col-sm-6">
										<select class="form-control form-control-line" name="occuption">
											<option value="Business" <?php if($usedetails['occuption']=='Business'){ echo 'selected';}?>>Business</option>
											<option value="Salaried" <?php if($usedetails['occuption']=='Self Salaried'){ echo 'selected';}?>>Salaried</option>
											<option value="Self Employied" <?php if($usedetails['occuption']=='Salaried'){ echo 'selected';}?> >Self Employied</option>
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-6">Income Range Rs.</label>
									<div class="col-sm-6">
										<input type="text" name="income" value="<?php echo !empty($usedetails['income']) ? $usedetails['income'] : ''; ?>" class="form-control form-control-line">
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-md-12">Emergency Contact</label>
									<div class="col-md-12">
										<input type="text" name="emergency_contact"  value="<?php echo !empty($usedetails['emergency_contact']) ? $usedetails['emergency_contact'] : ''; ?>" class="form-control form-control-line"> </div>
								</div>
								
							   <!-- <div class="form-group">
									<label class="col-md-12">Change Password</label>
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
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
<?php include('footer.php');?>			
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/custom.min.js"></script>
</body>

</html>
